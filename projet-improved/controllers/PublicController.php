<?php
/**
 * Contrôleur Public
 * Page d'accueil, catalogue cours, recherche pour internautes
 */

require_once ROOT_PATH . 'core/Controller.php';
require_once ROOT_PATH . 'models/CoursModel.php';
require_once ROOT_PATH . 'models/UtilisateurModel.php';
require_once ROOT_PATH . 'models/InscriptionModel.php';

class PublicController extends Controller {
    
    private CoursModel $coursModel;
    private UtilisateurModel $userModel;
    private InscriptionModel $inscriptionModel;
    
    public function __construct() {
        $this->coursModel       = new CoursModel();
        $this->userModel        = new UtilisateurModel();
        $this->inscriptionModel = new InscriptionModel();
    }
    
    /**
     * Page d'accueil
     */
    public function index(): void {
        $cours      = $this->coursModel->findAllComplets();
        $statsUsers = $this->userModel->getStatistiques();
        $statsCours = $this->coursModel->getStatistiques();
        $statsInscr = $this->inscriptionModel->getStatistiquesGlobales();
        
        $this->render('public/accueil', [
            'title'       => 'ESEN E-Learn – Plateforme Pédagogique',
            'cours'       => array_slice($cours, 0, 6),
            'statsUsers'  => $statsUsers,
            'statsCours'  => $statsCours,
            'statsInscr'  => $statsInscr,
        ]);
    }
    
    /**
     * Page non trouvée
     */
    public function notFound(): void {
        http_response_code(404);
        $this->render('shared/404', ['title' => 'Page introuvable']);
    }
}
