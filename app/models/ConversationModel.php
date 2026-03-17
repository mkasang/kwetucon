<?php

use BaseModel;
// /kwetu_con/app/models/ConversationModel.php

require_once app_path('core/BaseModel.php');

class ConversationModel extends BaseModel {
    protected $table = 'conversations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user1_id', 'user2_id', 'last_message', 'last_message_time',
        'user1_deleted', 'user2_deleted'
    ];
    
    /**
     * Obtenir ou créer une conversation entre deux utilisateurs
     */
    public function getOrCreateConversation($user1Id, $user2Id) {
        // S'assurer que user1_id est le plus petit pour la cohérence
        if ($user1Id > $user2Id) {
            $temp = $user1Id;
            $user1Id = $user2Id;
            $user2Id = $temp;
        }
        
        // Chercher une conversation existante
        $stmt = $this->pdo->prepare("
            SELECT * FROM conversations 
            WHERE (user1_id = ? AND user2_id = ?)
               OR (user1_id = ? AND user2_id = ?)
        ");
        $stmt->execute([$user1Id, $user2Id, $user2Id, $user1Id]);
        $conversation = $stmt->fetch();
        
        if ($conversation) {
            // Réactiver si supprimé
            if ($conversation['user1_id'] == $user1Id && $conversation['user1_deleted']) {
                $this->update($conversation['id'], ['user1_deleted' => 0]);
            }
            if ($conversation['user2_id'] == $user1Id && $conversation['user2_deleted']) {
                $this->update($conversation['id'], ['user2_deleted' => 0]);
            }
            
            return $conversation;
        }
        
        // Créer une nouvelle conversation
        return $this->create([
            'user1_id' => $user1Id,
            'user2_id' => $user2Id,
            'last_message_time' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Obtenir les conversations d'un utilisateur
     */
    public function getUserConversations($userId) {
        $sql = "
            SELECT c.*,
                   CASE 
                       WHEN c.user1_id = ? THEN u2.id
                       ELSE u1.id
                   END as other_user_id,
                   CASE 
                       WHEN c.user1_id = ? THEN p2.first_name
                       ELSE p1.first_name
                   END as other_first_name,
                   CASE 
                       WHEN c.user1_id = ? THEN p2.last_name
                       ELSE p1.last_name
                   END as other_last_name,
                   CASE 
                       WHEN c.user1_id = ? THEN p2.profile_pic
                       ELSE p1.profile_pic
                   END as other_profile_pic,
                   (SELECT COUNT(*) FROM messages 
                    WHERE conversation_id = c.id 
                      AND sender_id != ? 
                      AND is_read = 0) as unread_count
            FROM conversations c
            JOIN users u1 ON c.user1_id = u1.id
            JOIN users u2 ON c.user2_id = u2.id
            LEFT JOIN profiles p1 ON u1.id = p1.user_id
            LEFT JOIN profiles p2 ON u2.id = p2.user_id
            WHERE (c.user1_id = ? AND c.user1_deleted = 0)
               OR (c.user2_id = ? AND c.user2_deleted = 0)
            ORDER BY c.last_message_time DESC
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $userId, $userId, $userId, $userId, $userId, $userId]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir une conversation avec les détails
     */
    public function getConversationWithDetails($conversationId, $userId) {
        $stmt = $this->pdo->prepare("
            SELECT c.*,
                   CASE 
                       WHEN c.user1_id = ? THEN u2.id
                       ELSE u1.id
                   END as other_user_id,
                   CASE 
                       WHEN c.user1_id = ? THEN p2.first_name
                       ELSE p1.first_name
                   END as other_first_name,
                   CASE 
                       WHEN c.user1_id = ? THEN p2.last_name
                       ELSE p1.last_name
                   END as other_last_name,
                   CASE 
                       WHEN c.user1_id = ? THEN p2.profile_pic
                       ELSE p1.profile_pic
                   END as other_profile_pic,
                   CASE 
                       WHEN c.user1_id = ? THEN p2.last_active
                       ELSE p1.last_active
                   END as other_last_active
            FROM conversations c
            JOIN users u1 ON c.user1_id = u1.id
            JOIN users u2 ON c.user2_id = u2.id
            LEFT JOIN profiles p1 ON u1.id = p1.user_id
            LEFT JOIN profiles p2 ON u2.id = p2.user_id
            WHERE c.id = ?
              AND ((c.user1_id = ? AND c.user1_deleted = 0)
                OR (c.user2_id = ? AND c.user2_deleted = 0))
        ");
        $stmt->execute([$userId, $userId, $userId, $userId, $userId, $conversationId, $userId, $userId]);
        
        return $stmt->fetch();
    }
    
    /**
     * Mettre à jour le dernier message
     */
    public function updateLastMessage($conversationId, $message) {
        return $this->update($conversationId, [
            'last_message' => substr($message, 0, 100),
            'last_message_time' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Marquer une conversation comme supprimée pour un utilisateur
     */
    public function markAsDeleted($conversationId, $userId) {
        $conversation = $this->find($conversationId);
        
        if (!$conversation) {
            return false;
        }
        
        if ($conversation['user1_id'] == $userId) {
            return $this->update($conversationId, ['user1_deleted' => 1]);
        } elseif ($conversation['user2_id'] == $userId) {
            return $this->update($conversationId, ['user2_deleted' => 1]);
        }
        
        return false;
    }
    
    /**
     * Compter les conversations non lues
     */
    public function countUnreadConversations($userId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(DISTINCT c.id) as count
            FROM conversations c
            JOIN messages m ON c.id = m.conversation_id
            WHERE (c.user1_id = ? OR c.user2_id = ?)
              AND m.sender_id != ?
              AND m.is_read = 0
        ");
        $stmt->execute([$userId, $userId, $userId]);
        $result = $stmt->fetch();
        
        return $result['count'];
    }
    
    /**
     * Nettoyer les conversations vides (sans messages)
     */
    public function cleanupEmptyConversations() {
        $stmt = $this->pdo->query("
            DELETE c FROM conversations c
            LEFT JOIN messages m ON c.id = m.conversation_id
            WHERE m.id IS NULL
              AND c.last_message_time < DATE_SUB(NOW(), INTERVAL 1 DAY)
        ");
        
        return $stmt->rowCount();
    }
}