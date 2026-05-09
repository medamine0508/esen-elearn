<?php
/**
 * Contrôleur Cours – Liste, recherche, détail
 */

require_once ROOT_PATH . 'core/Controller.php';
require_once ROOT_PATH . 'models/CoursModel.php';

class CoursController extends Controller {

    private CoursModel $model;

    public function __construct() {
        $this->model = new CoursModel();
    }

    /** Catalogue complet avec recherche multicritères */
    public function liste(): void {
        $terme     = $this->input('q', '');
        $categorie = (int) $this->input('categorie', 0);
        $niveau    = $this->input('niveau', '');

        $cours = $this->model->rechercher($terme, $categorie, $niveau);

        $this->render('public/catalogue', [
            'title'     => 'Catalogue des cours – ESEN E-Learn',
            'cours'     => $cours,
            'terme'     => $terme,
            'categorie' => $categorie,
            'niveau'    => $niveau,
        ]);
    }

    /** Fiche détail d'un cours */
    public function detail(int $id): void {
        $cours = $this->model->findCompletById($id);
        if (!$cours) {
            $this->flash('error', 'Cours introuvable.');
            $this->redirect('cours/liste');
        }

        $inscrit = false;
        if (isset($_SESSION['user'])) {
            require_once ROOT_PATH . 'models/InscriptionModel.php';
            $inscr   = new InscriptionModel();
            $inscrit = $inscr->estInscrit($_SESSION['user']['id'], $id);
        }

        $this->render('public/detail_cours', [
            'title'  => $cours['titre'] . ' – ESEN E-Learn',
            'cours'  => $cours,
            'inscrit' => $inscrit,
        ]);
    }
}
