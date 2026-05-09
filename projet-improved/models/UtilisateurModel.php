<?php
/**
 * Modèle Utilisateur
 * Gestion des comptes utilisateurs (admin, professeur, étudiant)
 */

require_once ROOT_PATH . 'core/Model.php';

class UtilisateurModel extends Model {
    
    protected string $table = 'utilisateurs';
    protected string $primaryKey = 'id_user';
    
    /**
     * Authentifier un utilisateur par email et mot de passe
     * 
     * @param string $email Email de l'utilisateur
     * @param string $motDePasse Mot de passe en clair
     * @return array|false Données utilisateur ou false
     */
    public function authentifier(string $email, string $motDePasse): array|false {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email AND actif = TRUE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($motDePasse, $user['mot_de_passe'])) {
            // Mettre à jour la date de dernière connexion
            $this->update($user['id_user'], ['derniere_connexion' => date('Y-m-d H:i:s')]);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Créer un nouveau compte utilisateur avec hachage du mot de passe
     * 
     * @param array $data Données du nouvel utilisateur
     * @return int ID du nouvel utilisateur créé
     */
    public function creerCompte(array $data): int {
        // Hacher le mot de passe avec bcrypt
        $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_BCRYPT);
        return $this->create($data);
    }
    
    /**
     * Vérifier si un email est déjà utilisé
     * 
     * @param string $email Email à vérifier
     * @param int|null $excludeId Exclure un utilisateur (pour la mise à jour)
     * @return bool True si l'email existe déjà
     */
    public function emailExiste(string $email, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email";
        $params = [':email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id_user != :id";
            $params[':id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }
    
    /**
     * Récupérer tous les utilisateurs par rôle
     * 
     * @param string $role Rôle ('etudiant', 'professeur', 'admin')
     * @return array Liste des utilisateurs
     */
    public function findByRole(string $role): array {
        $sql = "SELECT id_user, nom, prenom, email, date_inscription, actif, derniere_connexion 
                FROM utilisateurs 
                WHERE role = :role 
                ORDER BY nom, prenom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':role' => $role]);
        return $stmt->fetchAll();
    }
    
    /**
     * Statistiques des utilisateurs pour le tableau de bord admin
     * 
     * @return array Statistiques
     */
    public function getStatistiques(): array {
        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN role = 'etudiant' THEN 1 ELSE 0 END) AS etudiants,
                    SUM(CASE WHEN role = 'professeur' THEN 1 ELSE 0 END) AS professeurs,
                    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) AS admins,
                    SUM(CASE WHEN actif = 1 THEN 1 ELSE 0 END) AS actifs
                FROM utilisateurs";
        return $this->db->query($sql)->fetch();
    }
    
    /**
     * Rechercher des utilisateurs (multicritères)
     * 
     * @param string $terme Terme de recherche
     * @param string $role Filtrer par rôle
     * @return array Résultats de la recherche
     */
    public function rechercher(string $terme, string $role = ''): array {
        $sql = "SELECT id_user, nom, prenom, email, role, actif, date_inscription 
                FROM utilisateurs
                WHERE (nom LIKE :terme OR prenom LIKE :terme OR email LIKE :terme)";
        $params = [':terme' => "%{$terme}%"];
        
        if ($role) {
            $sql .= " AND role = :role";
            $params[':role'] = $role;
        }
        
        $sql .= " ORDER BY nom, prenom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Changer le mot de passe d'un utilisateur
     * 
     * @param int $idUser ID de l'utilisateur
     * @param string $nouveauMdp Nouveau mot de passe en clair
     * @return bool Succès
     */
    public function changerMotDePasse(int $idUser, string $nouveauMdp): bool {
        $hash = password_hash($nouveauMdp, PASSWORD_BCRYPT);
        return $this->update($idUser, ['mot_de_passe' => $hash]);
    }
}
