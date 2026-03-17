<?php

use BaseModel;
use NotificationModel;
use ProfileModel;
// /kwetu_con/app/models/VisitModel.php

require_once app_path('core/BaseModel.php');

class VisitModel extends BaseModel {
    protected $table = 'user_visits';
    protected $primaryKey = 'id';
    protected $fillable = ['visitor_id', 'visited_id', 'visited_at'];
    
    /**
     * Enregistrer une visite
     */
    public function recordVisit($visitorId, $visitedId) {
        // Ne pas enregistrer les visites sur soi-même
        if ($visitorId == $visitedId) {
            return false;
        }
        
        // Vérifier si une visite récente existe (moins de 1 heure)
        $stmt = $this->pdo->prepare("
            SELECT id FROM user_visits
            WHERE visitor_id = ? AND visited_id = ?
              AND visited_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ");
        $stmt->execute([$visitorId, $visitedId]);
        
        if ($stmt->fetch()) {
            return true; // Déjà visité récemment
        }
        
        // Enregistrer la visite
        $result = $this->create([
            'visitor_id' => $visitorId,
            'visited_id' => $visitedId,
            'visited_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            // Incrémenter le compteur de vues du profil
            $profileModel = new ProfileModel();
            $profile = $profileModel->findBy('user_id', $visitedId, 1);
            if ($profile) {
                $profileModel->incrementViews($profile['id']);
            }
            
            // Créer une notification
            $notificationModel = new NotificationModel();
            $notificationModel->notifyVisit($visitorId, $visitedId);
        }
        
        return $result;
    }
    
    /**
     * Obtenir les visiteurs d'un profil
     */
    public function getVisitors($userId, $limit = 20, $offset = 0) {
        $stmt = $this->pdo->prepare("
            SELECT v.*, u.email, p.first_name, p.last_name, p.profile_pic,
                   p.city, p.occupation,
                   (SELECT COUNT(*) FROM likes WHERE liker_id = v.visitor_id AND liked_id = ?) as is_liked
            FROM user_visits v
            JOIN users u ON v.visitor_id = u.id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE v.visited_id = ?
            ORDER BY v.visited_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$userId, $userId, $limit, $offset]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir les profils visités
     */
    public function getVisited($userId, $limit = 20, $offset = 0) {
        $stmt = $this->pdo->prepare("
            SELECT v.*, u.email, p.first_name, p.last_name, p.profile_pic,
                   p.city, p.occupation
            FROM user_visits v
            JOIN users u ON v.visited_id = u.id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE v.visitor_id = ?
            ORDER BY v.visited_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$userId, $limit, $offset]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Compter les visiteurs uniques
     */
    public function countUniqueVisitors($userId, $days = 30) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(DISTINCT visitor_id) as count
            FROM user_visits
            WHERE visited_id = ?
              AND visited_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        $stmt->execute([$userId, $days]);
        
        return $stmt->fetchColumn();
    }
    
    /**
     * Obtenir les statistiques des visites
     */
    public function getVisitStats($userId) {
        $stats = [];
        
        // Total visites
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM user_visits WHERE visited_id = ?
        ");
        $stmt->execute([$userId]);
        $stats['total'] = $stmt->fetchColumn();
        
        // Visites par jour (7 derniers jours)
        $stmt = $this->pdo->prepare("
            SELECT DATE(visited_at) as date, COUNT(*) as count
            FROM user_visits
            WHERE visited_id = ?
              AND visited_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(visited_at)
            ORDER BY date DESC
        ");
        $stmt->execute([$userId]);
        $stats['daily'] = $stmt->fetchAll();
        
        // Top visiteurs
        $stmt = $this->pdo->prepare("
            SELECT visitor_id, COUNT(*) as count,
                   p.first_name, p.last_name
            FROM user_visits v
            LEFT JOIN profiles p ON v.visitor_id = p.user_id
            WHERE v.visited_id = ?
            GROUP BY visitor_id
            ORDER BY count DESC
            LIMIT 10
        ");
        $stmt->execute([$userId]);
        $stats['top_visitors'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    /**
     * Nettoyer les anciennes visites
     */
    public function cleanupOldVisits($days = 90) {
        $stmt = $this->pdo->prepare("
            DELETE FROM user_visits 
            WHERE visited_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        return $stmt->execute([$days]);
    }
}