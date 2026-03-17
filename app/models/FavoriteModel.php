<?php

use BaseModel;
// /kwetu_con/app/models/FavoriteModel.php

require_once app_path('core/BaseModel.php');

class FavoriteModel extends BaseModel {
    protected $table = 'favorites';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'favorite_id'];
    
    /**
     * Ajouter aux favoris
     */
    public function addFavorite($userId, $favoriteId) {
        // Vérifier si déjà en favoris
        if ($this->isFavorite($userId, $favoriteId)) {
            return ['error' => 'Déjà dans vos favoris'];
        }
        
        // Ne pas pouvoir s'ajouter soi-même
        if ($userId == $favoriteId) {
            return ['error' => 'Vous ne pouvez pas vous ajouter vous-même'];
        }
        
        return $this->create([
            'user_id' => $userId,
            'favorite_id' => $favoriteId
        ]);
    }
    
    /**
     * Retirer des favoris
     */
    public function removeFavorite($userId, $favoriteId) {
        $stmt = $this->pdo->prepare("
            DELETE FROM favorites 
            WHERE user_id = ? AND favorite_id = ?
        ");
        return $stmt->execute([$userId, $favoriteId]);
    }
    
    /**
     * Vérifier si un utilisateur est en favoris
     */
    public function isFavorite($userId, $favoriteId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM favorites 
            WHERE user_id = ? AND favorite_id = ?
        ");
        $stmt->execute([$userId, $favoriteId]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Obtenir la liste des favoris
     */
    public function getFavorites($userId) {
        $stmt = $this->pdo->prepare("
            SELECT f.*, u.email, p.first_name, p.last_name, p.profile_pic,
                   p.city, p.occupation,
                   (SELECT COUNT(*) FROM likes WHERE liker_id = ? AND liked_id = f.favorite_id) as is_liked
            FROM favorites f
            JOIN users u ON f.favorite_id = u.id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE f.user_id = ?
            ORDER BY f.created_at DESC
        ");
        $stmt->execute([$userId, $userId]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Compter les favoris
     */
    public function countFavorites($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                (SELECT COUNT(*) FROM favorites WHERE user_id = ?) as given,
                (SELECT COUNT(*) FROM favorites WHERE favorite_id = ?) as received
        ");
        $stmt->execute([$userId, $userId]);
        
        return $stmt->fetch();
    }
    
    /**
     * Vérifier si c'est réciproque
     */
    public function isMutual($userId1, $userId2) {
        return $this->isFavorite($userId1, $userId2) && 
               $this->isFavorite($userId2, $userId1);
    }
    
    /**
     * Obtenir les suggestions basées sur les favoris
     */
    public function getSuggestions($userId, $limit = 10) {
        // Trouver les utilisateurs favoris des personnes que j'aime
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT f2.favorite_id, 
                   COUNT(*) as common,
                   p.first_name, p.last_name, p.profile_pic
            FROM favorites f1
            JOIN favorites f2 ON f1.favorite_id = f2.user_id
            LEFT JOIN profiles p ON f2.favorite_id = p.user_id
            WHERE f1.user_id = ?
              AND f2.favorite_id != ?
              AND f2.favorite_id NOT IN (
                  SELECT favorite_id FROM favorites WHERE user_id = ?
              )
            GROUP BY f2.favorite_id
            ORDER BY common DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $userId, $userId, $limit]);
        
        return $stmt->fetchAll();
    }
}