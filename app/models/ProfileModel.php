<?php

// use BaseModel;
// /kwetu_con/app/models/ProfileModel.php

require_once app_path('core/BaseModel.php');

class ProfileModel extends BaseModel {
    protected $table = 'profiles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'birth_date', 'gender',
        'looking_for', 'bio', 'city', 'country', 'latitude', 'longitude',
        'profile_pic', 'occupation', 'education', 'interests', 'height',
        'relationship_status', 'has_children', 'wants_children', 'religion',
        'smoking', 'drinking', 'languages', 'verified', 'last_active', 'views_count'
    ];
    
    /**
     * Créer un profil pour un utilisateur
     */
    public function createProfile($userId, $data) {
        // Vérifier si le profil existe déjà
        if ($this->findBy('user_id', $userId, 1)) {
            return ['error' => 'Un profil existe déjà pour cet utilisateur'];
        }
        
        $data['user_id'] = $userId;
        $data['last_active'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }
    
    /**
     * Mettre à jour le profil
     */
    public function updateProfile($userId, $data) {
        $profile = $this->findBy('user_id', $userId, 1);
        
        if (!$profile) {
            return $this->createProfile($userId, $data);
        }
        
        return $this->update($profile['id'], $data);
    }
    
    /**
     * Obtenir le profil complet avec photos
     */
    public function getFullProfile($userId) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   u.email, u.status, u.created_at as member_since,
                   (SELECT COUNT(*) FROM photos WHERE user_id = ?) as photos_count
            FROM profiles p
            JOIN users u ON p.user_id = u.id
            WHERE p.user_id = ?
        ");
        $stmt->execute([$userId, $userId]);
        
        return $stmt->fetch();
    }
    
    /**
     * Obtenir les profils à découvrir (non bloqués, actifs, etc.)
     */
    public function getDiscoverProfiles($userId, $filters = [], $limit = 20, $offset = 0) {
        $sql = "
            SELECT p.*, u.last_login,
                   (SELECT COUNT(*) FROM photos WHERE user_id = p.user_id) as photos_count,
                   (SELECT COUNT(*) FROM likes WHERE liker_id = ? AND liked_id = p.user_id) as is_liked,
                   (SELECT COUNT(*) FROM blocks WHERE blocker_id = ? AND blocked_id = p.user_id) as is_blocked
            FROM profiles p
            JOIN users u ON p.user_id = u.id
            WHERE u.id != ? 
              AND u.status = 'active'
              AND u.id NOT IN (
                  SELECT blocked_id FROM blocks WHERE blocker_id = ?
              )
              AND u.id NOT IN (
                  SELECT blocker_id FROM blocks WHERE blocked_id = ?
              )
        ";
        
        $params = [$userId, $userId, $userId, $userId, $userId];
        
        // Appliquer les filtres
        if (!empty($filters['gender']) && $filters['gender'] !== 'all') {
            $sql .= " AND p.gender = ?";
            $params[] = $filters['gender'];
        }
        
        if (!empty($filters['looking_for']) && $filters['looking_for'] !== 'all') {
            $sql .= " AND p.looking_for IN (?, 'all')";
            $params[] = $filters['looking_for'];
        }
        
        if (!empty($filters['min_age'])) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, p.birth_date, CURDATE()) >= ?";
            $params[] = $filters['min_age'];
        }
        
        if (!empty($filters['max_age'])) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, p.birth_date, CURDATE()) <= ?";
            $params[] = $filters['max_age'];
        }
        
        if (!empty($filters['city'])) {
            $sql .= " AND p.city LIKE ?";
            $params[] = '%' . $filters['city'] . '%';
        }
        
        if (!empty($filters['country'])) {
            $sql .= " AND p.country = ?";
            $params[] = $filters['country'];
        }
        
        if (!empty($filters['verified'])) {
            $sql .= " AND p.verified = 1";
        }
        
        if (!empty($filters['has_photo'])) {
            $sql .= " AND p.profile_pic IS NOT NULL";
        }
        
        if (!empty($filters['online'])) {
            $sql .= " AND u.last_login >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
        }
        
        // Tri
        $sql .= " ORDER BY u.last_login DESC, p.views_count DESC";
        
        // Pagination
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir les profils à proximité (par distance)
     */
    public function getNearbyProfiles($userId, $latitude, $longitude, $radius = 50, $limit = 20) {
        $sql = "
            SELECT p.*, u.last_login,
                   (6371 * acos(cos(radians(?)) * cos(radians(p.latitude)) * 
                    cos(radians(p.longitude) - radians(?)) + 
                    sin(radians(?)) * sin(radians(p.latitude)))) AS distance
            FROM profiles p
            JOIN users u ON p.user_id = u.id
            WHERE u.id != ? 
              AND u.status = 'active'
              AND p.latitude IS NOT NULL 
              AND p.longitude IS NOT NULL
              AND u.id NOT IN (SELECT blocked_id FROM blocks WHERE blocker_id = ?)
            HAVING distance <= ?
            ORDER BY distance
            LIMIT ?
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$latitude, $longitude, $latitude, $userId, $userId, $radius, $limit]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews($profileId) {
        $stmt = $this->pdo->prepare("
            UPDATE profiles 
            SET views_count = views_count + 1 
            WHERE id = ?
        ");
        return $stmt->execute([$profileId]);
    }
    
    /**
     * Mettre à jour la dernière activité
     */
    public function updateLastActive($userId) {
        $stmt = $this->pdo->prepare("
            UPDATE profiles 
            SET last_active = NOW() 
            WHERE user_id = ?
        ");
        return $stmt->execute([$userId]);
    }
    
    /**
     * Rechercher des profils
     */
    public function searchProfiles($query, $limit = 20) {
        $searchTerm = '%' . $this->escapeLike($query) . '%';
        
        $stmt = $this->pdo->prepare("
            SELECT p.*, u.last_login
            FROM profiles p
            JOIN users u ON p.user_id = u.id
            WHERE u.status = 'active'
              AND (p.first_name LIKE ? 
                   OR p.last_name LIKE ? 
                   OR p.bio LIKE ? 
                   OR p.city LIKE ? 
                   OR p.occupation LIKE ?)
            ORDER BY p.views_count DESC
            LIMIT ?
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir les statistiques des profils
     */
    public function getStats() {
        $stats = [];
        
        // Total profils
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM profiles");
        $stats['total'] = $stmt->fetchColumn();
        
        // Profils vérifiés
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM profiles WHERE verified = 1");
        $stats['verified'] = $stmt->fetchColumn();
        
        // Profils avec photo
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM profiles WHERE profile_pic IS NOT NULL");
        $stats['with_photo'] = $stmt->fetchColumn();
        
        // Répartition par genre
        $stmt = $this->pdo->query("
            SELECT gender, COUNT(*) as count 
            FROM profiles 
            GROUP BY gender
        ");
        $stats['by_gender'] = $stmt->fetchAll();
        
        // Âge moyen
        $stmt = $this->pdo->query("
            SELECT AVG(TIMESTAMPDIFF(YEAR, birth_date, CURDATE())) as avg_age 
            FROM profiles
        ");
        $stats['avg_age'] = round($stmt->fetchColumn());
        
        return $stats;
    }
}