<?php
/**
 * Classe Model - Classe de base pour tous les modèles
 * Implémente les opérations CRUD génériques avec PDO
 */

require_once ROOT_PATH . 'config/database.php';

abstract class Model {
    
    /** @var PDO Instance de connexion */
    protected PDO $db;
    
    /** @var string Nom de la table */
    protected string $table;
    
    /** @var string Clé primaire */
    protected string $primaryKey = 'id';
    
    /**
     * Constructeur - récupère l'instance PDO
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Récupérer tous les enregistrements
     * 
     * @param string $orderBy Colonne de tri
     * @param string $order ASC ou DESC
     * @return array Tableau de résultats
     */
    public function findAll(string $orderBy = '', string $order = 'ASC'): array {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy} {$order}";
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Trouver un enregistrement par son ID
     * 
     * @param int $id Identifiant
     * @return array|false Données ou false si non trouvé
     */
    public function findById(int $id): array|false {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Insérer un enregistrement
     * 
     * @param array $data Données à insérer
     * @return int ID du dernier enregistrement inséré
     */
    public function create(array $data): int {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        // Lier les valeurs avec les bons types
        foreach ($data as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue(":$key", $value, $type);
        }
        
        $stmt->execute();
        return (int) $this->db->lastInsertId();
    }
    
    /**
     * Mettre à jour un enregistrement
     * 
     * @param int $id Identifiant
     * @param array $data Données à mettre à jour
     * @return bool Succès de l'opération
     */
    public function update(int $id, array $data): bool {
        $sets = array_map(fn($col) => "{$col} = :{$col}", array_keys($data));
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . 
               " WHERE {$this->primaryKey} = :id";
        
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }
    
    /**
     * Supprimer un enregistrement (soft delete si colonne 'actif' existe)
     * 
     * @param int $id Identifiant
     * @return bool Succès de l'opération
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Compter le nombre total d'enregistrements
     * 
     * @param string $where Condition WHERE optionnelle
     * @param array $params Paramètres de la condition
     * @return int Nombre d'enregistrements
     */
    public function count(string $where = '', array $params = []): int {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if ($where) $sql .= " WHERE {$where}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * Pagination des résultats
     * 
     * @param int $page Numéro de page (commence à 1)
     * @param int $limit Éléments par page
     * @param string $where Condition WHERE
     * @param array $params Paramètres
     * @return array Résultats paginés
     */
    public function paginate(int $page = 1, int $limit = ITEMS_PER_PAGE, 
                             string $where = '', array $params = []): array {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM {$this->table}";
        if ($where) $sql .= " WHERE {$where}";
        $sql .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
