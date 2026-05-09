<?php
/**
 * Contrôleur Étudiant
 * Tableau de bord, cours, quiz pour les étudiants
 */

require_once ROOT_PATH . 'core/Controller.php';
require_once ROOT_PATH . 'models/CoursModel.php';
require_once ROOT_PATH . 'models/InscriptionModel.php';

class EtudiantController extends Controller {
    
    private CoursModel $coursModel;
    private InscriptionModel $inscriptionModel;
    
    public function __construct() {
        $this->coursModel      = new CoursModel();
        $this->inscriptionModel = new InscriptionModel();
    }
    
    /**
     * Tableau de bord de l'étudiant
     */
    public function dashboard(): void {
        $this->requireAuth();
        $idEtudiant = $_SESSION['user']['id'];
        
        $mesCours  = $this->coursModel->findByEtudiant($idEtudiant);
        $stats     = $this->inscriptionModel->getStatsByEtudiant($idEtudiant);
        
        $this->render('etudiant/dashboard', [
            'title'    => 'Mon Espace Étudiant',
            'mesCours' => $mesCours,
            'stats'    => $stats,
        ]);
    }
    
    /**
     * S'inscrire à un cours
     */
    public function inscrireCours(): void {
        $this->requireAuth();
        $idCours    = (int) $this->input('id_cours', 0);
        $idEtudiant = $_SESSION['user']['id'];
        
        if (!$idCours) {
            $this->flash('error', 'Cours invalide.');
            $this->redirect('cours/liste');
        }
        
        if ($this->inscriptionModel->inscrire($idEtudiant, $idCours)) {
            $this->flash('success', 'Inscription réussie ! Bonne étude.');
        } else {
            $this->flash('error', 'Vous êtes déjà inscrit à ce cours.');
        }
        
        $this->redirect('etudiant/dashboard');
    }
}
