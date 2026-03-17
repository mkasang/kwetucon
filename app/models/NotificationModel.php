<?php

// use BaseModel;
// use ProfileModel;
// use UserModel;
// /kwetu_con/app/models/NotificationModel.php

require_once app_path('core/BaseModel.php');

class NotificationModel extends BaseModel {
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'type', 'content', 'is_read', 'related_id'
    ];
    
    /**
     * Créer une notification
     */
    public function createNotification($userId, $type, $content, $relatedId = null) {
        return $this->create([
            'user_id' => $userId,
            'type' => $type,
            'content' => $content,
            'related_id' => $relatedId,
            'is_read' => 0
        ]);
    }
    
    /**
     * Obtenir les notifications d'un utilisateur
     */
    public function getUserNotifications($userId, $limit = 20, $offset = 0) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$userId, $limit, $offset]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir les notifications non lues
     */
    public function getUnreadNotifications($userId, $limit = 10) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM notifications
            WHERE user_id = ? AND is_read = 0
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Compter les notifications non lues
     */
    public function countUnread($userId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM notifications
            WHERE user_id = ? AND is_read = 0
        ");
        $stmt->execute([$userId]);
        
        return $stmt->fetchColumn();
    }
    
    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($notificationId, $userId) {
        $stmt = $this->pdo->prepare("
            UPDATE notifications 
            SET is_read = 1 
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$notificationId, $userId]);
    }
    
    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead($userId) {
        $stmt = $this->pdo->prepare("
            UPDATE notifications 
            SET is_read = 1 
            WHERE user_id = ? AND is_read = 0
        ");
        return $stmt->execute([$userId]);
    }
    
    /**
     * Supprimer les anciennes notifications
     */
    public function deleteOldNotifications($days = 30) {
        $stmt = $this->pdo->prepare("
            DELETE FROM notifications 
            WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
              AND is_read = 1
        ");
        return $stmt->execute([$days]);
    }
    
    /**
     * Créer une notification pour un like
     */
    public function notifyLike($likerId, $likedId) {
        $profileModel = new ProfileModel();
        $liker = $profileModel->findBy('user_id', $likerId, 1);
        
        $content = $liker 
            ? $liker['first_name'] . ' a aimé votre profil'
            : 'Quelqu\'un a aimé votre profil';
        
        return $this->createNotification($likedId, 'like', $content, $likerId);
    }
    
    /**
     * Créer une notification pour une visite
     */
    public function notifyVisit($visitorId, $visitedId) {
        $profileModel = new ProfileModel();
        $visitor = $profileModel->findBy('user_id', $visitorId, 1);
        
        $content = $visitor 
            ? $visitor['first_name'] . ' a visité votre profil'
            : 'Quelqu\'un a visité votre profil';
        
        return $this->createNotification($visitedId, 'visit', $content, $visitorId);
    }
    
    /**
     * Créer une notification système
     */
    public function notifySystem($userId, $message) {
        return $this->createNotification($userId, 'system', $message);
    }
    
    /**
     * Envoyer une notification à tous les utilisateurs (admin)
     */
    public function broadcastToAll($message, $userModel = null) {
        if (!$userModel) {
            $userModel = new UserModel();
        }
        
        $users = $userModel->findBy('status', 'active');
        $count = 0;
        
        foreach ($users as $user) {
            if ($this->createNotification($user['id'], 'system', $message)) {
                $count++;
            }
        }
        
        return $count;
    }
}