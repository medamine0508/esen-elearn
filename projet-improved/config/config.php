3<?php
/**
 * Configuration de la Base de Données
 * ESEN E-Learn - Plateforme Pédagogique
 * Utilisation de PDO pour la connexion
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'esen_elearn');
define('DB_USER', 'root');
define('DB_PASS', '');  
define('DB_CHARSET', 'utf8mb4');

// URL de base de l'application
define('BASE_URL', 'http://localhost/projet-improved/');
define('APP_NAME', 'ESEN E-Learn');
define('APP_VERSION', '1.0.0');

// Chemins de l'application
// ROOT_PATH is defined in index.php
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('MODELS_PATH', ROOT_PATH . 'models/');
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers/');

// Configuration de session
define('SESSION_LIFETIME', 3600); // 1 heure
define('SESSION_NAME', 'esen_session');

// Paramètres de pagination
define('ITEMS_PER_PAGE', 10);
