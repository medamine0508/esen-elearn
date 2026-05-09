<?php
/**
 * Contrôleur d'Authentification
 * Gestion de la connexion, inscription et déconnexion
 */

require_once ROOT_PATH . 'core/Controller.php';
require_once ROOT_PATH . 'models/UtilisateurModel.php';

class AuthController extends Controller {
    
    private UtilisateurModel $model;
    
    public function __construct() {
        $this->model = new UtilisateurModel();
    }
    
    /**
     * Afficher le formulaire de connexion
     */
    public function showLogin(): void {
        // Rediriger si déjà connecté
        if (isset($_SESSION['user'])) {
            $this->redirectByRole($_SESSION['user']['role']);
        }
        
        $this->render('public/login', ['title' => 'Connexion - ESEN E-Learn'], 'auth');
    }
    
    /**
     * Traiter le formulaire de connexion
     */
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/showLogin');
        }
        
        // Validation CSRF (token simple)
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->flash('error', 'Requête invalide. Veuillez réessayer.');
            $this->redirect('auth/showLogin');
        }
        
        $email = $this->input('email');
        $motDePasse = $_POST['mot_de_passe'] ?? ''; // Ne pas échapper le mot de passe
        
        // Validation des champs
        $erreurs = [];
        if (empty($email)) $erreurs[] = 'L\'email est obligatoire';
        if (empty($motDePasse)) $erreurs[] = 'Le mot de passe est obligatoire';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = 'Email invalide';
        
        if (!empty($erreurs)) {
            $_SESSION['flash']['error'] = implode('<br>', $erreurs);
            $this->redirect('auth/showLogin');
        }
        
        // Authentification
        $user = $this->model->authentifier($email, $motDePasse);
        
        if ($user) {
            // Créer la session utilisateur
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'     => $user['id_user'],
                'nom'    => $user['nom'],
                'prenom' => $user['prenom'],
                'email'  => $user['email'],
                'role'   => $user['role'],
            ];
            
            $this->flash('success', 'Bienvenue, ' . $user['prenom'] . ' !');
            $this->redirectByRole($user['role']);
        } else {
            $this->flash('error', 'Email ou mot de passe incorrect.');
            $this->redirect('auth/showLogin');
        }
    }
    
    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegister(): void {
        if (isset($_SESSION['user'])) {
            $this->redirectByRole($_SESSION['user']['role']);
        }
        
        $this->render('public/register', ['title' => 'Inscription - ESEN E-Learn'], 'auth');
    }
    
    /**
     * Traiter le formulaire d'inscription étudiant
     */
    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/showRegister');
        }
        
        $nom       = $this->input('nom');
        $prenom    = $this->input('prenom');
        $email     = $this->input('email');
        $motDePasse  = $_POST['mot_de_passe'] ?? '';
        $confirmation = $_POST['confirmation'] ?? '';
        
        // Validation complète
        $erreurs = [];
        if (empty($nom))    $erreurs[] = 'Le nom est obligatoire';
        if (empty($prenom)) $erreurs[] = 'Le prénom est obligatoire';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = 'Email invalide';
        if (strlen($motDePasse) < 6) $erreurs[] = 'Le mot de passe doit contenir au moins 6 caractères';
        if ($motDePasse !== $confirmation) $erreurs[] = 'Les mots de passe ne correspondent pas';
        
        // Vérifier l'unicité de l'email
        if ($this->model->emailExiste($email)) {
            $erreurs[] = 'Cet email est déjà utilisé';
        }
        
        if (!empty($erreurs)) {
            $_SESSION['flash']['error'] = implode('<br>', $erreurs);
            $_SESSION['form_data'] = compact('nom', 'prenom', 'email');
            $this->redirect('auth/showRegister');
        }
        
        // Créer le compte
        $idUser = $this->model->creerCompte([
            'nom'         => $nom,
            'prenom'      => $prenom,
            'email'       => $email,
            'mot_de_passe' => $motDePasse,
            'role'        => 'etudiant',
        ]);
        
        if ($idUser) {
            $this->flash('success', 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.');
            $this->redirect('auth/showLogin');
        } else {
            $this->flash('error', 'Une erreur est survenue. Veuillez réessayer.');
            $this->redirect('auth/showRegister');
        }
    }
    
    /**
     * Déconnecter l'utilisateur
     */
    public function logout(): void {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit();
    }
    
    /**
     * Rediriger selon le rôle de l'utilisateur
     * 
     * @param string $role Rôle de l'utilisateur
     */
    private function redirectByRole(string $role): void {
        match($role) {
            'admin'      => $this->redirect('admin/dashboard'),
            'professeur' => $this->redirect('etudiant/dashboard'),
            default      => $this->redirect('etudiant/dashboard'),
        };
    }
}
