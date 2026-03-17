<?php

// use BaseModel;
// use ConversationModel;
// use ProfileModel;
// /kwetu_con/app/models/MessageModel.php

require_once app_path('core/BaseModel.php');

class MessageModel extends BaseModel {
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $fillable = [
        'conversation_id', 'sender_id', 'message', 'is_read', 'read_at', 'sender_deleted'
    ];
    
    /**
     * Envoyer un message
     */
    public function sendMessage($conversationId, $senderId, $message) {
        // Vérifier le blocage
        if ($this->isBlocked($conversationId, $senderId)) {
            return ['error' => 'Vous ne pouvez pas envoyer de message à cet utilisateur'];
        }
        
        $data = [
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'message' => $message,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->create($data);
        
        if ($result) {
            // Mettre à jour la conversation
            $conversationModel = new ConversationModel();
            $conversationModel->updateLastMessage($conversationId, $message);
            
            // Créer une notification
            $this->createNotification($conversationId, $senderId, $message);
        }
        
        return $result;
    }
    
    /**
     * Vérifier si l'utilisateur est bloqué
     */
    private function isBlocked($conversationId, $senderId) {
        $conversationModel = new ConversationModel();
        $conversation = $conversationModel->find($conversationId);
        
        if (!$conversation) {
            return true;
        }
        
        $otherUserId = ($conversation['user1_id'] == $senderId) 
            ? $conversation['user2_id'] 
            : $conversation['user1_id'];
        
        $blockModel = new BlockModel();
        return $blockModel->isBlocked($senderId, $otherUserId) || 
               $blockModel->isBlocked($otherUserId, $senderId);
    }
    
    /**
     * Créer une notification pour le message
     */
    private function createNotification($conversationId, $senderId, $message) {
        $conversationModel = new ConversationModel();
        $conversation = $conversationModel->find($conversationId);
        
        if (!$conversation) {
            return;
        }
        
        $receiverId = ($conversation['user1_id'] == $senderId) 
            ? $conversation['user2_id'] 
            : $conversation['user1_id'];
        
        $notificationModel = new NotificationModel();
        $notificationModel->create([
            'user_id' => $receiverId,
            'type' => 'message',
            'content' => 'Nouveau message de ' . $this->getSenderName($senderId),
            'related_id' => $conversationId
        ]);
    }
    
    /**
     * Obtenir le nom de l'expéditeur
     */
    private function getSenderName($userId) {
        $profileModel = new ProfileModel();
        $profile = $profileModel->findBy('user_id', $userId, 1);
        
        return $profile ? $profile['first_name'] : 'Utilisateur';
    }
    
    /**
     * Obtenir les messages d'une conversation
     */
    public function getConversationMessages($conversationId, $userId, $limit = 50, $offset = 0) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, 
                   p.first_name, p.last_name, p.profile_pic
            FROM messages m
            LEFT JOIN profiles p ON m.sender_id = p.user_id
            WHERE m.conversation_id = ?
              AND (m.sender_id = ? OR m.sender_deleted = 0)
            ORDER BY m.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$conversationId, $userId, $limit, $offset]);
        
        $messages = $stmt->fetchAll();
        
        // Marquer comme lus
        $this->markAsRead($conversationId, $userId);
        
        return array_reverse($messages); // Retourner dans l'ordre chronologique
    }
    
    /**
     * Marquer les messages comme lus
     */
    public function markAsRead($conversationId, $userId) {
        $stmt = $this->pdo->prepare("
            UPDATE messages 
            SET is_read = 1, read_at = NOW()
            WHERE conversation_id = ? 
              AND sender_id != ? 
              AND is_read = 0
        ");
        return $stmt->execute([$conversationId, $userId]);
    }
    
    /**
     * Compter les messages non lus
     */
    public function countUnread($userId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as count
            FROM messages m
            JOIN conversations c ON m.conversation_id = c.id
            WHERE (c.user1_id = ? OR c.user2_id = ?)
              AND m.sender_id != ?
              AND m.is_read = 0
        ");
        $stmt->execute([$userId, $userId, $userId]);
        $result = $stmt->fetch();
        
        return $result['count'];
    }
    
    /**
     * Supprimer un message (soft delete pour l'expéditeur)
     */
    public function deleteMessage($messageId, $userId) {
        $message = $this->find($messageId);
        
        if (!$message || $message['sender_id'] != $userId) {
            return false;
        }
        
        return $this->update($messageId, ['sender_deleted' => 1]);
    }
    
    /**
     * Rechercher dans les messages
     */
    public function searchMessages($userId, $query) {
        $searchTerm = '%' . $this->escapeLike($query) . '%';
        
        $stmt = $this->pdo->prepare("
            SELECT m.*, c.*,
                   CASE 
                       WHEN c.user1_id = ? THEN u2.id
                       ELSE u1.id
                   END as other_user_id
            FROM messages m
            JOIN conversations c ON m.conversation_id = c.id
            JOIN users u1 ON c.user1_id = u1.id
            JOIN users u2 ON c.user2_id = u2.id
            WHERE (c.user1_id = ? OR c.user2_id = ?)
              AND m.message LIKE ?
            ORDER BY m.created_at DESC
            LIMIT 50
        ");
        $stmt->execute([$userId, $userId, $userId, $searchTerm]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir les dernières conversations avec dernier message
     */
    public function getLastMessages($userId, $limit = 10) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, c.*,
                   CASE 
                       WHEN c.user1_id = ? THEN p2.first_name
                       ELSE p1.first_name
                   END as sender_name
            FROM messages m
            JOIN conversations c ON m.conversation_id = c.id
            LEFT JOIN profiles p1 ON c.user1_id = p1.user_id
            LEFT JOIN profiles p2 ON c.user2_id = p2.user_id
            WHERE (c.user1_id = ? OR c.user2_id = ?)
            ORDER BY m.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $userId, $userId, $limit]);
        
        return $stmt->fetchAll();
    }
}