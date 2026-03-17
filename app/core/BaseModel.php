<?php
// /kwetu_con/app/core/BaseModel.php

/**
 * Base Model pour KWETU CON
 * Gère les connexions PDO et les opérations CRUD de base
 */

class BaseModel {
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = ['password'];
    
    public function __construct() {
        $this->connect();
    }
    
    /**
     * Établir la connexion à la base de données
     */
    private function connect() {
        $configFile = config_path('database.php');
        
        if (!file_exists($configFile)) {
            die("Erreur: Fichier de configuration database.php non trouvé. Veuillez exécuter init.php d'abord.");
        }
        
        $config = require $configFile;
        
        try {
            $this->pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
                $config['user'],
                $config['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die("Erreur de connexion à la base de données. Vérifiez votre fichier de configuration.");
        }
    }
    
    /**
     * Obtenir tous les enregistrements
     */
    public function all($orderBy = null, $direction = 'ASC') {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy} {$direction}";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        return $this->hideFields($stmt->fetchAll());
    }
    
    /**
     * Trouver par ID
     */
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        
        $result = $stmt->fetch();
        return $result ? $this->hideFields($result) : null;
    }
    
    /**
     * Trouver par une colonne spécifique
     */
    public function findBy($column, $value, $limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$value]);
        
        $results = $stmt->fetchAll();
        return $limit === 1 ? ($results[0] ?? null) : $this->hideFields($results);
    }
    
    /**
     * Créer un nouvel enregistrement
     */
    public function create($data) {
        // Filtrer les données selon $fillable
        $data = $this->filterFillable($data);
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        if ($stmt->execute()) {
            $id = $this->pdo->lastInsertId();
            return $this->find($id);
        }
        
        return null;
    }
    
    /**
     * Mettre à jour un enregistrement
     */
    public function update($id, $data) {
        // Filtrer les données selon $fillable
        $data = $this->filterFillable($data);
        
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = :{$column}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':id', $id);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        if ($stmt->execute()) {
            return $this->find($id);
        }
        
        return null;
    }
    
    /**
     * Supprimer un enregistrement
     */
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Compter les enregistrements
     */
    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = "{$column} = :{$column}";
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($conditions as $column => $value) {
            $stmt->bindValue(":{$column}", $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['total'];
    }
    
    /**
     * Pagination
     */
    public function paginate($page = 1, $perPage = 20, $conditions = [], $orderBy = 'created_at', $direction = 'DESC') {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = "{$column} = :{$column}";
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        $sql .= " ORDER BY {$orderBy} {$direction} LIMIT :offset, :perPage";
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($conditions as $column => $value) {
            $stmt->bindValue(":{$column}", $value);
        }
        
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $total = $this->count($conditions);
        $data = $this->hideFields($stmt->fetchAll());
        
        return [
            'data' => $data,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Exécuter une requête personnalisée
     */
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $this->hideFields($stmt->fetchAll());
    }
    
    /**
     * Exécuter une requête et retourner une seule ligne
     */
    public function queryOne($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return $result ? $this->hideFields($result) : null;
    }
    
    /**
     * Commencer une transaction
     */
    public function beginTransaction() {
        $this->pdo->beginTransaction();
    }
    
    /**
     * Valider une transaction
     */
    public function commit() {
        $this->pdo->commit();
    }
    
    /**
     * Annuler une transaction
     */
    public function rollback() {
        $this->pdo->rollBack();
    }
    
    /**
     * Filtrer les données selon $fillable
     */
    protected function filterFillable($data) {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * Cacher les champs sensibles
     */
    protected function hideFields($data) {
        if (empty($this->hidden) || empty($data)) {
            return $data;
        }
        
        // Si c'est un tableau de résultats
        if (isset($data[0]) && is_array($data[0])) {
            foreach ($data as &$item) {
                foreach ($this->hidden as $field) {
                    unset($item[$field]);
                }
            }
        } 
        // Si c'est un seul résultat
        elseif (is_array($data)) {
            foreach ($this->hidden as $field) {
                unset($data[$field]);
            }
        }
        
        return $data;
    }
    
    /**
     * Échapper les valeurs pour LIKE
     */
    protected function escapeLike($value) {
        return str_replace(['%', '_'], ['\%', '\_'], $value);
    }
    
    /**
     * Obtenir la dernière erreur
     */
    public function getLastError() {
        return $this->pdo->errorInfo();
    }
}