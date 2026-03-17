<?php

use BaseModel;
// /kwetu_con/app/models/PhotoModel.php

require_once app_path('core/BaseModel.php');

class PhotoModel extends BaseModel {
    protected $table = 'photos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'profile_id', 'photo_path', 'is_primary', 'sort_order'
    ];
    
    /**
     * Ajouter une photo
     */
    public function addPhoto($userId, $profileId, $photoPath) {
        // Vérifier le nombre de photos
        $count = $this->count(['user_id' => $userId]);
        
        if ($count >= 10) { // Maximum 10 photos
            return ['error' => 'Nombre maximum de photos atteint (10)'];
        }
        
        // Si c'est la première photo, la définir comme principale
        $isPrimary = ($count === 0);
        
        $data = [
            'user_id' => $userId,
            'profile_id' => $profileId,
            'photo_path' => $photoPath,
            'is_primary' => $isPrimary,
            'sort_order' => $count
        ];
        
        return $this->create($data);
    }
    
    /**
     * Définir une photo comme principale
     */
    public function setPrimary($photoId, $userId) {
        try {
            $this->beginTransaction();
            
            // Enlever le statut principal de toutes les photos de l'utilisateur
            $stmt = $this->pdo->prepare("
                UPDATE photos 
                SET is_primary = 0 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            
            // Définir la nouvelle photo principale
            $result = $this->update($photoId, ['is_primary' => 1]);
            
            // Mettre à jour le profile_pic dans la table profiles
            $photo = $this->find($photoId);
            if ($photo) {
                $stmt = $this->pdo->prepare("
                    UPDATE profiles 
                    SET profile_pic = ? 
                    WHERE user_id = ?
                ");
                $stmt->execute([$photo['photo_path'], $userId]);
            }
            
            $this->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("Erreur lors du changement de photo principale: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtenir toutes les photos d'un utilisateur
     */
    public function getUserPhotos($userId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM photos 
            WHERE user_id = ? 
            ORDER BY is_primary DESC, sort_order ASC
        ");
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir la photo principale
     */
    public function getPrimaryPhoto($userId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM photos 
            WHERE user_id = ? AND is_primary = 1
        ");
        $stmt->execute([$userId]);
        
        return $stmt->fetch();
    }
    
    /**
     * Réorganiser les photos
     */
    public function reorderPhotos($userId, $order) {
        // $order est un tableau d'IDs dans l'ordre souhaité
        try {
            $this->beginTransaction();
            
            foreach ($order as $index => $photoId) {
                $stmt = $this->pdo->prepare("
                    UPDATE photos 
                    SET sort_order = ? 
                    WHERE id = ? AND user_id = ?
                ");
                $stmt->execute([$index, $photoId, $userId]);
            }
            
            $this->commit();
            return true;
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("Erreur lors de la réorganisation: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Supprimer une photo
     */
    public function deletePhoto($photoId, $userId) {
        try {
            $this->beginTransaction();
            
            $photo = $this->find($photoId);
            
            if (!$photo || $photo['user_id'] != $userId) {
                return false;
            }
            
            // Vérifier si c'était la photo principale
            $wasPrimary = $photo['is_primary'];
            
            // Supprimer le fichier physique
            $filePath = public_path('assets/images/uploads/' . $photo['photo_path']);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Supprimer de la base de données
            $result = $this->delete($photoId);
            
            if ($wasPrimary) {
                // Définir une nouvelle photo principale si disponible
                $stmt = $this->pdo->prepare("
                    SELECT * FROM photos 
                    WHERE user_id = ? 
                    ORDER BY sort_order ASC 
                    LIMIT 1
                ");
                $stmt->execute([$userId]);
                $newPrimary = $stmt->fetch();
                
                if ($newPrimary) {
                    $this->setPrimary($newPrimary['id'], $userId);
                } else {
                    // Plus de photos, mettre profile_pic à NULL
                    $stmt = $this->pdo->prepare("
                        UPDATE profiles 
                        SET profile_pic = NULL 
                        WHERE user_id = ?
                    ");
                    $stmt->execute([$userId]);
                }
            }
            
            $this->commit();
            return true;
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("Erreur lors de la suppression: " . $e->getMessage());
            return false;
        }
    }
}