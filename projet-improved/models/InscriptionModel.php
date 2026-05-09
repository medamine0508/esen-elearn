<?php
/**
 * Modèle Inscription
 * Gestion des inscriptions étudiants aux cours
 */

require_once ROOT_PATH . 'core/Model.php';

class InscriptionModel extends Model {
    
    protected string $table = 'inscriptions';
    protected string $primaryKey = 'id_inscription';
    
    /**
     * Vérifier si un étudiant est inscrit à un cours
     */
    public function estInscrit(int $idEtudiant, int $idCours): bool {
        $sql = "SELECT COUNT(*) FROM inscriptions WHERE id_etudiant = :e AND id_cours = :c";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':e' => $idEtudiant, ':c' => $idCours]);
        return (int) $stmt->fetchColumn() > 0;
    }
    
    /**
     * Inscrire un étudiant à un cours
     */
    public function inscrire(int $idEtudiant, int $idCours): bool {
        if ($this->estInscrit($idEtudiant, $idCours)) return false;
        return (bool) $this->create([
            'id_etudiant' => $idEtudiant,
            'id_cours'    => $idCours,
            'statut'      => 'en_cours',
            'progression' => 0,
        ]);
    }
    
    /**
     * Mettre à jour la progression
     */
    public function mettreAJourProgression(int $idEtudiant, int $idCours, int $progression): bool {
        $sql = "UPDATE inscriptions SET progression = :p WHERE id_etudiant = :e AND id_cours = :c";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':p' => $progression, ':e' => $idEtudiant, ':c' => $idCours]);
    }
    
    /**
     * Statistiques inscriptions pour un étudiant
     */
    public function getStatsByEtudiant(int $idEtudiant): array {
        $sql = "SELECT
                    COUNT(*) AS total,
                    SUM(CASE WHEN statut='termine' THEN 1 ELSE 0 END) AS termines,
                    SUM(CASE WHEN statut='en_cours' THEN 1 ELSE 0 END) AS en_cours,
                    AVG(progression) AS progression_moyenne
                FROM inscriptions WHERE id_etudiant = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $idEtudiant]);
        return $stmt->fetch();
    }
    
    /**
     * Statistiques globales pour l'admin
     */
    public function getStatistiquesGlobales(): array {
        $sql = "SELECT COUNT(*) AS total,
                    SUM(CASE WHEN statut='en_cours' THEN 1 ELSE 0 END) AS en_cours,
                    SUM(CASE WHEN statut='termine' THEN 1 ELSE 0 END) AS termines,
                    AVG(progression) AS progression_moyenne
                FROM inscriptions";
        return $this->db->query($sql)->fetch();
    }
}
