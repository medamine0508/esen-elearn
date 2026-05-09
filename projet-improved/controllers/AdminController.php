<?php
/**
 * Contrôleur Administrateur – ESEN E-Learn
 * CORRECTIONS :
 *  - ajouterCours() et modifierCours() ajoutés
 *  - toggleActifCours() ajouté
 *  - InscriptionModel chargé dans rapports()
 */

require_once ROOT_PATH . 'core/Controller.php';
require_once ROOT_PATH . 'models/UtilisateurModel.php';
require_once ROOT_PATH . 'models/CoursModel.php';
require_once ROOT_PATH . 'models/InscriptionModel.php';

class AdminController extends Controller {

    private UtilisateurModel  $utilisateurModel;
    private CoursModel        $coursModel;
    private InscriptionModel  $inscriptionModel;

    public function __construct() {
        $this->utilisateurModel  = new UtilisateurModel();
        $this->coursModel        = new CoursModel();
        $this->inscriptionModel  = new InscriptionModel();
    }

    // -------------------------------------------------------
    // Tableau de bord
    // -------------------------------------------------------

    public function dashboard(): void {
        $this->requireAdmin();

        $statsUsers = $this->utilisateurModel->getStatistiques();
        $statsCours = $this->coursModel->getStatistiques();
        $topCours   = $this->coursModel->findAllComplets();
        $rapportCat = $this->coursModel->rapportParCategorie();
        $statsInscr = $this->inscriptionModel->getStatistiquesGlobales();

        $this->render('admin/dashboard', [
            'title'      => 'Tableau de Bord – Admin',
            'statsUsers' => $statsUsers,
            'statsCours' => $statsCours,
            'statsInscr' => $statsInscr,
            'topCours'   => array_slice($topCours, 0, 5),
            'rapportCat' => $rapportCat,
        ], 'admin');
    }

    // -------------------------------------------------------
    // Gestion des utilisateurs
    // -------------------------------------------------------

    public function utilisateurs(): void {
        $this->requireAdmin();

        $role  = $this->input('role', '');
        $terme = $this->input('q', '');
        $page  = max(1, (int) $this->input('page', 1));

        if ($terme) {
            $users = $this->utilisateurModel->rechercher($terme, $role);
        } elseif ($role) {
            $users = $this->utilisateurModel->findByRole($role);
        } else {
            $users = $this->utilisateurModel->findAll('nom');
        }

        $this->render('admin/utilisateurs', [
            'title' => 'Gestion des Utilisateurs',
            'users' => $users,
            'role'  => $role,
            'terme' => $terme,
            'page'  => $page,
        ], 'admin');
    }

    public function ajouterUtilisateur(): void {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom'          => $this->input('nom'),
                'prenom'       => $this->input('prenom'),
                'email'        => $this->input('email'),
                'mot_de_passe' => $_POST['mot_de_passe'],
                'role'         => $this->input('role', 'etudiant'),
                'actif'        => 1,
            ];

            if ($this->utilisateurModel->emailExiste($data['email'])) {
                $this->flash('error', 'Cet email est déjà utilisé.');
            } else {
                $id = $this->utilisateurModel->creerCompte($data);
                if ($id) {
                    $this->flash('success', 'Utilisateur créé avec succès (ID: ' . $id . ')');
                    $this->redirect('admin/utilisateurs');
                }
            }
        }

        $this->render('admin/ajouter_utilisateur', [
            'title' => 'Ajouter un Utilisateur',
        ], 'admin');
    }

    public function modifierUtilisateur(int $id): void {
        $this->requireAdmin();

        $user = $this->utilisateurModel->findById($id);
        if (!$user) {
            $this->flash('error', 'Utilisateur introuvable.');
            $this->redirect('admin/utilisateurs');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom'    => $this->input('nom'),
                'prenom' => $this->input('prenom'),
                'email'  => $this->input('email'),
                'role'   => $this->input('role'),
                'actif'  => $this->input('actif', 1),
            ];

            if (!empty($_POST['nouveau_mdp'])) {
                $this->utilisateurModel->changerMotDePasse($id, $_POST['nouveau_mdp']);
            }

            if ($this->utilisateurModel->update($id, $data)) {
                $this->flash('success', 'Utilisateur mis à jour avec succès.');
                $this->redirect('admin/utilisateurs');
            }
        }

        $this->render('admin/modifier_utilisateur', [
            'title' => 'Modifier l\'Utilisateur',
            'user'  => $user,
        ], 'admin');
    }

    public function supprimerUtilisateur(int $id): void {
        $this->requireAdmin();

        if ($id === $_SESSION['user']['id']) {
            $this->flash('error', 'Vous ne pouvez pas désactiver votre propre compte.');
            $this->redirect('admin/utilisateurs');
        }

        if ($this->utilisateurModel->update($id, ['actif' => 0])) {
            $this->flash('success', 'Compte désactivé avec succès.');
        } else {
            $this->flash('error', 'Erreur lors de la désactivation.');
        }

        $this->redirect('admin/utilisateurs');
    }

    // -------------------------------------------------------
    // Gestion des cours
    // -------------------------------------------------------

    public function cours(): void {
        $this->requireAdmin();

        $cours = $this->coursModel->findAllComplets();

        $this->render('admin/cours', [
            'title' => 'Gestion des Cours',
            'cours' => $cours,
        ], 'admin');
    }

    /**
     * Activer / désactiver un cours (toggle)
     * CORRECTION : méthode absente dans la version originale
     */
    public function toggleActifCours(int $id): void {
        $this->requireAdmin();

        $c = $this->coursModel->findById($id);
        if (!$c) {
            $this->flash('error', 'Cours introuvable.');
        } else {
            $newState = $c['actif'] ? 0 : 1;
            $this->coursModel->update($id, ['actif' => $newState]);
            $label = $newState ? 'activé' : 'désactivé';
            $this->flash('success', "Cours {$label} avec succès.");
        }

        $this->redirect('admin/cours');
    }

    // -------------------------------------------------------
    // Rapports et statistiques
    // -------------------------------------------------------

    public function rapports(): void {
        $this->requireAdmin();

        $statsUsers = $this->utilisateurModel->getStatistiques();
        $statsCours = $this->coursModel->getStatistiques();
        $statsInscr = $this->inscriptionModel->getStatistiquesGlobales();
        $rapportCat = $this->coursModel->rapportParCategorie();

        $this->render('admin/rapports', [
            'title'      => 'Rapports et Statistiques',
            'statsUsers' => $statsUsers,
            'statsCours' => $statsCours,
            'statsInscr' => $statsInscr,
            'rapportCat' => $rapportCat,
        ], 'admin');
    }
}
