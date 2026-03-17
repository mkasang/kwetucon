<?php

use BaseModel;
use BlockModel;
use UserModel;
// /kwetu_con/app/models/ReportModel.php

require_once app_path('core/BaseModel.php');

class ReportModel extends BaseModel {
    protected $table = 'reports';
    protected $primaryKey = 'id';
    protected $fillable = [
        'reporter_id', 'reported_id', 'reason', 'description',
        'status', 'admin_notes', 'reviewed_by', 'reviewed_at'
    ];
    
    /**
     * Signaler un utilisateur
     */
    public function reportUser($reporterId, $reportedId, $reason, $description = null) {
        // Vérifier si déjà signalé en attente
        $existing = $this->findByMultiple([
            'reporter_id' => $reporterId,
            'reported_id' => $reportedId,
            'status' => 'pending'
        ], 1);
        
        if ($existing) {
            return ['error' => 'Vous avez déjà signalé cet utilisateur'];
        }
        
        return $this->create([
            'reporter_id' => $reporterId,
            'reported_id' => $reportedId,
            'reason' => $reason,
            'description' => $description,
            'status' => 'pending'
        ]);
    }
    
    /**
     * Trouver par plusieurs critères
     */
    private function findByMultiple($conditions, $limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $where = [];
        $params = [];
        
        foreach ($conditions as $field => $value) {
            $where[] = "{$field} = ?";
            $params[] = $value;
        }
        
        $sql .= implode(' AND ', $where);
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $limit === 1 ? $stmt->fetch() : $stmt->fetchAll();
    }
    
    /**
     * Obtenir tous les signalements avec détails
     */
    public function getAllReports($status = null) {
        $sql = "
            SELECT r.*,
                   rep.email as reporter_email, rep.first_name as reporter_first_name, rep.last_name as reporter_last_name,
                   red.email as reported_email, red.first_name as reported_first_name, red.last_name as reported_last_name,
                   adm.email as reviewed_by_email
            FROM reports r
            JOIN users rep ON r.reporter_id = rep.id
            JOIN users red ON r.reported_id = red.id
            LEFT JOIN users adm ON r.reviewed_by = adm.id
            LEFT JOIN profiles prep ON rep.id = prep.user_id
            LEFT JOIN profiles pred ON red.id = pred.user_id
        ";
        
        $params = [];
        
        if ($status) {
            $sql .= " WHERE r.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY r.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir les signalements en attente
     */
    public function getPendingReports() {
        return $this->getAllReports('pending');
    }
    
    /**
     * Traiter un signalement
     */
    public function processReport($reportId, $adminId, $action, $notes = null) {
        $report = $this->find($reportId);
        
        if (!$report) {
            return false;
        }
        
        $data = [
            'status' => $action === 'resolve' ? 'resolved' : 'dismissed',
            'admin_notes' => $notes,
            'reviewed_by' => $adminId,
            'reviewed_at' => date('Y-m-d H:i:s')
        ];
        
        // Si l'action est de bloquer l'utilisateur signalé
        if ($action === 'block') {
            $blockModel = new BlockModel();
            $blockModel->blockUser($adminId, $report['reported_id'], 'Signalement approuvé');
            
            // Désactiver le compte
            $userModel = new UserModel();
            $userModel->setStatus($report['reported_id'], 'blocked');
            
            $data['status'] = 'resolved';
        }
        
        return $this->update($reportId, $data);
    }
    
    /**
     * Obtenir les statistiques des signalements
     */
    public function getStats() {
        $stats = [];
        
        // Total par statut
        $stmt = $this->pdo->query("
            SELECT status, COUNT(*) as count
            FROM reports
            GROUP BY status
        ");
        
        while ($row = $stmt->fetch()) {
            $stats[$row['status']] = $row['count'];
        }
        
        // Total par raison
        $stmt = $this->pdo->query("
            SELECT reason, COUNT(*) as count
            FROM reports
            GROUP BY reason
        ");
        $stats['by_reason'] = $stmt->fetchAll();
        
        // Utilisateurs les plus signalés
        $stmt = $this->pdo->query("
            SELECT reported_id, COUNT(*) as count
            FROM reports
            GROUP BY reported_id
            ORDER BY count DESC
            LIMIT 10
        ");
        $stats['top_reported'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    /**
     * Vérifier si un utilisateur a été signalé plusieurs fois
     */
    public function getUserReportCount($userId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM reports
            WHERE reported_id = ? AND status != 'dismissed'
        ");
        $stmt->execute([$userId]);
        
        return $stmt->fetchColumn();
    }
    
    /**
     * Nettoyer les anciens signalements résolus
     */
    public function cleanupResolved($days = 90) {
        $stmt = $this->pdo->prepare("
            DELETE FROM reports 
            WHERE status IN ('resolved', 'dismissed')
              AND reviewed_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        return $stmt->execute([$days]);
    }
}