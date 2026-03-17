<?php

use AuthHelper;
use BaseModel;
// /kwetu_con/app/models/UserModel.php

require_once app_path('core/BaseModel.php');

class UserModel extends BaseModel {
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'email', 
        'password', 
        'role', 
        'status', 
        'last_login'
    ];
    protected $hidden = ['password'];
    
    /**
     * Créer un nouvel utilisateur
     */
    public function createUser($data) {
        // Vérifier si l'email existe déjà
        if ($this->emailExists($data['email'])) {
            return ['error' => 'Cet email est déjà utilisé'];
        }
        
        // Hacher le mot de passe
        $data['password'] = AuthHelper::hashPassword($data['password']);
        
        // Définir les valeurs par défaut
        $data['role'] = $data['role'] ?? 'user';
        $data['status'] = $data['status'] ?? 'pending';
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }
    
    /**
     * Vérifier si l'email existe
     */
    public function emailExists($email) {
        $result = $this->findBy('email', $email, 1);
        return !empty($result);
    }
    
    /**
     * Authentifier un utilisateur
     */
    public function authenticate($email, $password) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM {$this->table} 
            WHERE email = ? AND status = 'active'
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && AuthHelper::verifyPassword($password, $user['password'])) {
            // Mettre à jour la dernière connexion
            $this->update($user['id'], [
                'last_login' => date('Y-m-d H:i:s')
            ]);
            
            // Ne pas retourner le mot de passe
            unset($user['password']);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Obtenir les utilisateurs avec leurs profils
     */
    public function getWithProfiles($status = null, $role = null) {
        $sql = "
            SELECT u.*, p.* 
            FROM users u 
            LEFT JOIN profiles p ON u.id = p.user_id 
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($status) {
            $sql .= " AND u.status = ?";
            $params[] = $status;
        }
        
        if ($role) {
            $sql .= " AND u.role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY u.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $this->hideFields($stmt->fetchAll());
    }
    
    /**
     * Obtenir un utilisateur avec son profil
     */
    public function getWithProfile($userId) {
        $stmt = $this->pdo->prepare("
            SELECT u.*, p.* 
            FROM users u 
            LEFT JOIN profiles p ON u.id = p.user_id 
            WHERE u.id = ?
        ");
        $stmt->execute([$userId]);
        
        $result = $stmt->fetch();
        return $result ? $this->hideFields($result) : null;
    }
    
    /**
     * Activer/Désactiver un utilisateur
     */
    public function setStatus($userId, $status) {
        $allowedStatus = ['active', 'blocked', 'pending'];
        if (!in_array($status, $allowedStatus)) {
            return false;
        }
        
        return $this->update($userId, ['status' => $status]);
    }
    
    /**
     * Changer le rôle d'un utilisateur
     */
    public function setRole($userId, $role) {
        $allowedRoles = ['user', 'admin'];
        if (!in_array($role, $allowedRoles)) {
            return false;
        }
        
        return $this->update($userId, ['role' => $role]);
    }
    
    /**
     * Compter les utilisateurs par statut
     */
    public function countByStatus() {
        $stmt = $this->pdo->query("
            SELECT status, COUNT(*) as count 
            FROM users 
            GROUP BY status
        ");
        
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[$row['status']] = $row['count'];
        }
        
        return $result;
    }
    
    /**
     * Obtenir les statistiques des inscriptions par mois
     */
    public function getRegistrationStats($months = 6) {
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as count
            FROM users
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month DESC
        ");
        $stmt->execute([$months]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Rechercher des utilisateurs
     */
    public function search($query, $limit = 20) {
        $searchTerm = '%' . $this->escapeLike($query) . '%';
        
        $stmt = $this->pdo->prepare("
            SELECT u.*, p.first_name, p.last_name, p.profile_pic
            FROM users u
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE u.email LIKE ? 
               OR p.first_name LIKE ? 
               OR p.last_name LIKE ?
            LIMIT ?
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $limit]);
        
        return $this->hideFields($stmt->fetchAll());
    }
    
    /**
     * Supprimer un utilisateur et toutes ses données associées
     */
    public function deleteWithRelations($userId) {
        try {
            $this->beginTransaction();
            
            // Les suppressions en cascade devraient gérer la plupart des relations
            // Mais on peut ajouter des nettoyages supplémentaires si nécessaire
            
            // Supprimer l'utilisateur
            $result = $this->delete($userId);
            
            $this->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtenir les utilisateurs en ligne
     */
    public function getOnlineUsers($minutes = 15) {
        $stmt = $this->pdo->prepare("
            SELECT u.*, p.first_name, p.last_name, p.profile_pic
            FROM users u
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE u.last_login >= DATE_SUB(NOW(), INTERVAL ? MINUTE)
            AND u.status = 'active'
            ORDER BY u.last_login DESC
        ");
        $stmt->execute([$minutes]);
        
        return $this->hideFields($stmt->fetchAll());
    }
}