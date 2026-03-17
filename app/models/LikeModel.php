<?php

use BaseModel;
use NotificationModel;
use ProfileModel;
// /kwetu_con/app/models/LikeModel.php

require_once app_path('core/BaseModel.php');

class LikeModel extends BaseModel {
    protected $table = 'likes';
    protected $primaryKey = 'id';
    protected $fillable = ['liker_id', 'liked_id'];
    
    /**
     * Ajouter un like
     */
    public function addLike($likerId, $likedId) {
        // Vérifier si déjà liké
        if ($this->isLiked($likerId, $likedId)) {
            return ['error' => 'Vous aimez déjà ce profil'];
        }
        
        // Ne pas pouvoir se liker soi-même
        if ($likerId == $likedId) {
            return ['error' => 'Vous ne pouvez pas vous aimer vous-même'];
        }
        
        $result = $this->create([
            'liker_id' => $likerId,
            'liked_id' => $likedId
        ]);
        
        if ($result) {
            // Créer une notification
            $notificationModel = new NotificationModel();
            $notificationModel->notifyLike($likerId, $likedId);
            
            // Vérifier si c'est un match (like mutuel)
            if ($this->isMutual($likerId, $likedId)) {
                $this->createMatch($likerId, $likedId);
            }
        }
        
        return $result;
    }
    
    /**
     * Retirer un like
     */
    public function removeLike($likerId, $likedId) {
        $stmt = $this->pdo->prepare("
            DELETE FROM likes 
            WHERE liker_id = ? AND liked_id = ?
        ");
        return $stmt->execute([$likerId, $likedId]);
    }
    
    /**
     * Vérifier si un utilisateur est liké
     */
    public function isLiked($likerId, $likedId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM likes 
            WHERE liker_id = ? AND liked_id = ?
        ");
        $stmt->execute([$likerId, $likedId]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Vérifier si c'est mutuel (match)
     */
    public function isMutual($userId1, $userId2) {
        return $this->isLiked($userId1, $userId2) && 
               $this->isLiked($userId2, $userId1);
    }
    
    /**
     * Créer un match (notification spéciale)
     */
    private function createMatch($userId1, $userId2) {
        $notificationModel = new NotificationModel();
        
        // Notification pour l'utilisateur 1
        $profile2 = (new ProfileModel())->findBy('user_id', $userId2, 1);
        $content2 = $profile2 
            ? 'C\'est un match ! Vous avez aimé ' . $profile2['first_name'] . ' et il/elle vous aime aussi !'
            : 'C\'est un match ! Vous vous êtes mutuellement likés !';
        $notificationModel->createNotification($userId1, 'match', $content2, $userId2);
        
        // Notification pour l'utilisateur 2
        $profile1 = (new ProfileModel())->findBy('user_id', $userId1, 1);
        $content1 = $profile1 
            ? 'C\'est un match ! Vous avez aimé ' . $profile1['first_name'] . ' et il/elle vous aime aussi !'
            : 'C\'est un match ! Vous vous êtes mutuellement likés !';
        $notificationModel->createNotification($userId2, 'match', $content1, $userId1);
        
        return true;
    }
    
    /**
     * Obtenir les utilisateurs qui m'ont liké
     */
    public function getLikers($userId) {
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.email, p.first_name, p.last_name, p.profile_pic,
                   (SELECT COUNT(*) FROM likes WHERE liker_id = ? AND liked_id = l.liker_id) as is_match
            FROM likes l
            JOIN users u ON l.liker_id = u.id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE l.liked_id = ?
            ORDER BY l.created_at DESC
        ");
        $stmt->execute([$userId, $userId]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir les utilisateurs que j'ai likés
     */
    public function getLiked($userId) {
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.email, p.first_name, p.last_name, p.profile_pic,
                   (SELECT COUNT(*) FROM likes WHERE liker_id = ? AND liked_id = l.liked_id) as is_match
            FROM likes l
            JOIN users u ON l.liked_id = u.id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE l.liker_id = ?
            ORDER BY l.created_at DESC
        ");
        $stmt->execute([$userId, $userId]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir les matches (likes mutuels)
     */
    public function getMatches($userId) {
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT 
                   CASE 
                       WHEN l1.liker_id = ? THEN l1.liked_id
                       ELSE l1.liker_id
                   END as matched_user_id,
                   p.first_name, p.last_name, p.profile_pic,
                   l1.created_at as matched_at
            FROM likes l1
            JOIN likes l2 ON 
                (l1.liker_id = l2.liked_id AND l1.liked_id = l2.liker_id)
            LEFT JOIN profiles p ON 
                (CASE 
                    WHEN l1.liker_id = ? THEN l1.liked_id
                    ELSE l1.liker_id
                 END) = p.user_id
            WHERE (l1.liker_id = ? OR l1.liked_id = ?)
              AND l1.liker_id != l1.liked_id
            ORDER BY l1.created_at DESC
        ");
        $stmt->execute([$userId, $userId, $userId, $userId]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Compter les likes pour un utilisateur
     */
    public function countLikes($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                (SELECT COUNT(*) FROM likes WHERE liked_id = ?) as received,
                (SELECT COUNT(*) FROM likes WHERE liker_id = ?) as given,
                (SELECT COUNT(*) FROM (
                    SELECT l1.liker_id, l1.liked_id
                    FROM likes l1
                    JOIN likes l2 ON l1.liker_id = l2.liked_id AND l1.liked_id = l2.liker_id
                    WHERE l1.liker_id = ? OR l1.liked_id = ?
                ) as matches) as matches
        ");
        $stmt->execute([$userId, $userId, $userId, $userId]);
        
        return $stmt->fetch();
    }
    
    /**
     * Obtenir les statistiques des likes
     */
    public function getStats() {
        $stats = [];
        
        // Total likes
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM likes");
        $stats['total'] = $stmt->fetchColumn();
        
        // Likes par jour (7 derniers jours)
        $stmt = $this->pdo->query("
            SELECT DATE(created_at) as date, COUNT(*) as count
            FROM likes
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ");
        $stats['daily'] = $stmt->fetchAll();
        
        // Utilisateurs les plus likés
        $stmt = $this->pdo->query("
            SELECT liked_id, COUNT(*) as count
            FROM likes
            GROUP BY liked_id
            ORDER BY count DESC
            LIMIT 10
        ");
        $stats['top_liked'] = $stmt->fetchAll();
        
        return $stats;
    }
}