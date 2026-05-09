> ESENE-Learn c'est une Plateforme d'apprentissage en ligne développée
> 
> 1. Ouvrir phpMyAdmin
> 2. Cliquer sur "Importer":importer le fichier esen_elern.sql
> 3. Cliquer sur "Exécuter"
> -Architecture
> projet-improved/
> ├── config/
> │   ├── config.php          # Configuration générale (URL, constantes)
> │   └── database.php        # Connexion PDO (pattern Singleton)
> ├── core/
> │   ├── Controller.php      # Contrôleur de base
> │   └── Model.php           # Modèle de base
> ├── controllers/
> │   ├── AuthController.php      # Connexion / Inscription / Déconnexion
> │   ├── AdminController.php     # Gestion admin
> │   ├── CoursController.php     # Catalogue et détail des cours
> │   ├── EtudiantController.php  # Espace étudiant
> │   └── PublicController.php    # Pages publiques
> ├── models/
> │   ├── UtilisateurModel.php    # Gestion des utilisateurs
> │   ├── CoursModel.php          # Gestion des cours
> │   └── InscriptionModel.php    # Gestion des inscriptions
> ├── views/
> │   ├── admin/              # Vues administration
> │   ├── etudiant/           # Vues espace étudiant
> │   ├── public/             # Vues publiques
> │   └── shared/             # Layouts partagés
> ├── public/
> │   ├── css/                # Feuilles de style
> │   ├── js/                 # Scripts JavaScript
> │   └── images/             # Images des cours
> ├── sql/                    # Scripts SQL détaillés
> ├── index.php               # Point d'entrée unique
> ├── .htaccess               # Réécriture d'URL
> └── esen_elearn.sql         # Base de données
