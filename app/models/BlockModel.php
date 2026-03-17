<?php

use BaseModel;
// /kwetu_con/app/models/BlockModel.php

require_once app_path('core/BaseModel.php');

class BlockModel extends BaseModel {
    protected $table = 'blocks';
    protected $primaryKey = 'id';
    protected $fillable = ['blocker_id', 'blocked_id', 'reason'];
    
    /**
     * Bloquer un utilisateur
     */
    public function blockUser($blockerId, $blockedId, $reason = null) {
        // Vérifier si déjà bloqué
        if ($this->isBlocked($blockerId, $blockedId)) {
            return ['error' => 'Cet utilisateur est déjà bloqué'];
        }
        
        // Ne pas pouvoir se bloquer soi-même
        if ($blockerId == $blockedId) {
            return ['error' => 'Vous ne pouvez pas vous bloquer vous-même'];
        }
        
        return $this->create([
            'blocker_id' => $blockerId,
            'blocked_id' => $blockedId,
            'reason' => $reason
        ]);
    }
    
    /**
     * Débloquer un utilisateur
     */
    public function unblockUser($blockerId, $blockedId) {
        $stmt = $this->pdo->prepare("
            DELETE FROM blocks 
            WHERE blocker_id = ? AND blocked_id = ?
        ");
        return $stmt->execute([$blockerId, $blockedId]);
    }
    
    /**
     * Vérifier si un utilisateur est bloqué
     */
    public function isBlocked($blockerId, $blockedId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM blocks 
            WHERE blocker_id = ? AND blocked_id = ?
        ");
        $stmt->execute([$blockerId, $blockedId]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Vérifier le blocage mutuel
     */
    public function areMutuallyBlocked($userId1, $userId2) {
        return $this->isBlocked($userId1, $userId2) || 
               $this->isBlocked($userId2, $userId1);
    }
    
    /**
     * Obtenir la liste des utilisateurs bloqués
     */
    public function getBlockedUsers($userId) {
        $stmt = $this->pdo->prepare("
            SELECT b.*, u.email, p.first_name, p.last_name, p.profile_pic
            FROM blocks b
            JOIN users u ON b.blocked_id = u.id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE b.blocker_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir les utilisateurs qui m'ont bloqué
     */
    public function getBlockedBy($userId) {
        $stmt = $this->pdo->prepare("
            SELECT b.*, u.email, p.first_name, p.last_name
            FROM blocks b
            JOIN users u ON b.blocker_id = u.id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE b.blocked_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Compter les blocages pour un utilisateur
     */
    public function countBlocks($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                (SELECT COUNT(*) FROM blocks WHERE blocker_id = ?) as blocking,
                (SELECT COUNT(*) FROM blocks WHERE blocked_id = ?) as blocked_by
        ");
        $stmt->execute([$userId, $userId]);
        
        return $stmt->fetch();
    }
    
    /**
     * Obtenir les statistiques des blocages
     */
    public function getStats() {
        $stats = [];
        
        // Total blocages
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM blocks");
        $stats['total'] = $stmt->fetchColumn();
        
        // Blocages par jour (7 derniers jours)
        $stmt = $this->pdo->query("
            SELECT DATE(created_at) as date, COUNT(*) as count
            FROM blocks
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ");
        $stats['daily'] = $stmt->fetchAll();
        
        // Top bloqueurs
        $stmt = $this->pdo->query("
            SELECT blocker_id, COUNT(*) as count
            FROM blocks
            GROUP BY blocker_id
            ORDER BY count DESC
            LIMIT 10
        ");
        $stats['top_blockers'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    /**
     * Nettoyer les anciens blocages (optionnel)
     */
    public function cleanupOldBlocks($days = 365) {
        $stmt = $this->pdo->prepare("
            DELETE FROM blocks 
            WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        return $stmt->execute([$days]);
    }
}