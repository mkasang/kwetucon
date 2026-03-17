<?php

// use BaseModel;
// /kwetu_con/app/models/AdsModel.php

require_once app_path('core/BaseModel.php');

class AdsModel extends BaseModel {
    protected $table = 'ads';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title', 'description', 'image_url', 'target_url',
        'position', 'type', 'views_count', 'clicks_count',
        'max_views', 'max_clicks', 'start_date', 'end_date', 'status'
    ];
    
    /**
     * Obtenir les publicités actives pour une position
     */
    public function getActiveAds($position, $limit = 5) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM ads
            WHERE position = ?
              AND status = 'active'
              AND start_date <= CURDATE()
              AND end_date >= CURDATE()
              AND (max_views IS NULL OR views_count < max_views)
              AND (max_clicks IS NULL OR clicks_count < max_clicks)
            ORDER BY RAND()
            LIMIT ?
        ");
        $stmt->execute([$position, $limit]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Enregistrer une vue
     */
    public function trackView($adId) {
        $stmt = $this->pdo->prepare("
            UPDATE ads 
            SET views_count = views_count + 1 
            WHERE id = ?
        ");
        return $stmt->execute([$adId]);
    }
    
    /**
     * Enregistrer un clic
     */
    public function trackClick($adId) {
        $stmt = $this->pdo->prepare("
            UPDATE ads 
            SET clicks_count = clicks_count + 1 
            WHERE id = ?
        ");
        return $stmt->execute([$adId]);
    }
    
    /**
     * Créer une nouvelle publicité
     */
    public function createAd($data) {
        // Validation des dates
        if (strtotime($data['end_date']) < strtotime($data['start_date'])) {
            return ['error' => 'La date de fin doit être postérieure à la date de début'];
        }
        
        return $this->create($data);
    }
    
    /**
     * Mettre à jour le statut des publicités expirées
     */
    public function updateExpiredStatus() {
        $stmt = $this->pdo->prepare("
            UPDATE ads 
            SET status = 'expired' 
            WHERE status = 'active' 
              AND end_date < CURDATE()
        ");
        return $stmt->execute();
    }
    
    /**
     * Obtenir les statistiques des publicités
     */
    public function getStats() {
        $stats = [];
        
        // Total actives
        $stmt = $this->pdo->query("
            SELECT COUNT(*) FROM ads 
            WHERE status = 'active' 
              AND start_date <= CURDATE() 
              AND end_date >= CURDATE()
        ");
        $stats['active'] = $stmt->fetchColumn();
        
        // Total vues
        $stmt = $this->pdo->query("SELECT SUM(views_count) FROM ads");
        $stats['total_views'] = $stmt->fetchColumn() ?: 0;
        
        // Total clics
        $stmt = $this->pdo->query("SELECT SUM(clicks_count) FROM ads");
        $stats['total_clicks'] = $stmt->fetchColumn() ?: 0;
        
        // Taux de clic moyen
        if ($stats['total_views'] > 0) {
            $stats['ctr'] = round(($stats['total_clicks'] / $stats['total_views']) * 100, 2);
        } else {
            $stats['ctr'] = 0;
        }
        
        // Performance par position
        $stmt = $this->pdo->query("
            SELECT position, 
                   SUM(views_count) as views, 
                   SUM(clicks_count) as clicks,
                   ROUND((SUM(clicks_count) / SUM(views_count)) * 100, 2) as ctr
            FROM ads
            GROUP BY position
        ");
        $stats['by_position'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    /**
     * Dupliquer une publicité
     */
    public function duplicateAd($adId) {
        $ad = $this->find($adId);
        
        if (!$ad) {
            return false;
        }
        
        unset($ad['id']);
        $ad['title'] = $ad['title'] . ' (copie)';
        $ad['views_count'] = 0;
        $ad['clicks_count'] = 0;
        $ad['status'] = 'paused';
        $ad['created_at'] = date('Y-m-d H:i:s');
        
        return $this->create($ad);
    }
    
    /**
     * Obtenir les publicités les plus performantes
     */
    public function getTopPerforming($limit = 10) {
        $stmt = $this->pdo->prepare("
            SELECT *,
                   CASE 
                       WHEN views_count > 0 
                       THEN ROUND((clicks_count / views_count) * 100, 2)
                       ELSE 0
                   END as ctr
            FROM ads
            WHERE views_count > 0
            ORDER BY clicks_count DESC, ctr DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Planifier une publicité pour plus tard
     */
    public function scheduleAd($adId, $startDate) {
        return $this->update($adId, [
            'start_date' => $startDate,
            'status' => 'paused' // Sera activé automatiquement à la date
        ]);
    }
    
    /**
     * Activer/Désactiver une publicité
     */
    public function toggleStatus($adId) {
        $ad = $this->find($adId);
        
        if (!$ad) {
            return false;
        }
        
        $newStatus = $ad['status'] === 'active' ? 'paused' : 'active';
        
        return $this->update($adId, ['status' => $newStatus]);
    }
}