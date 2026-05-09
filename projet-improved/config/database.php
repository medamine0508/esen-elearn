<?php
/**
 * Classe Database - Connexion PDO (Singleton)
 * Gestion centralisée de la connexion à MySQL
 * Pattern Singleton pour éviter les connexions multiples
 */

require_once __DIR__ . '/config.php';

class Database {
    
    /** @var PDO Instance unique de PDO */
    private static ?PDO $instance = null;
    
    /**
     * Constructeur privé - empêche l'instanciation directe
     */
    private function __construct() {}
    
    /**
     * Retourne l'instance unique de PDO (Singleton)
     * 
     * @return PDO Instance de connexion PDO
     * @throws PDOException En cas d'échec de connexion
     */
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                DB_HOST,
                DB_NAME,
                DB_CHARSET
            );
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"
            ];
            
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // En production, ne pas afficher les détails de l'erreur
                error_log('Erreur de connexion BD: ' . $e->getMessage());
                throw new PDOException('Impossible de se connecter à la base de données.');
            }
        }
        
        return self::$instance;
    }
    
    /**
     * Empêche le clonage de l'instance
     */
    private function __clone() {}
}
