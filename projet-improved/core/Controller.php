<?php
/**
 * Classe Controller - Classe de base pour tous les contrôleurs
 * Implémente le rendu des vues et la gestion des redirections
 */

abstract class Controller {
    
    /**
     * Rendu d'une vue avec ses données
     */
    protected function render(string $view, array $data = [], string $layout = 'main'): void {
        extract($data);
        
        $viewFile = VIEWS_PATH . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("Vue introuvable : {$viewFile}");
        }
        
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        
        $layoutFile = VIEWS_PATH . 'shared/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
    
    /**
     * Redirection via paramètres GET (compatible avec le routeur ?ctrl=&action=)
     * Supporte deux formats :
     *   - 'ctrl/action'     → ?ctrl=ctrl&action=action
     *   - '?ctrl=...'       → URL brute passée telle quelle
     */
    protected function redirect(string $url): void {
        // Si l'URL contient déjà des paramètres GET, l'utiliser directement
        if (strpos($url, '?') !== false || strpos($url, 'http') === 0) {
            header('Location: ' . BASE_URL . ltrim($url, '/'));
            exit();
        }
        
        // Format 'ctrl/action' → convertir en paramètres GET
        $parts = explode('/', $url);
        $ctrl   = $parts[0] ?? 'public';
        $action = $parts[1] ?? 'index';
        $id     = $parts[2] ?? null;
        
        $query = BASE_URL . '?ctrl=' . urlencode($ctrl) . '&action=' . urlencode($action);
        if ($id !== null) {
            $query .= '&id=' . urlencode($id);
        }
        
        header('Location: ' . $query);
        exit();
    }
    
    /**
     * Retourner une réponse JSON
     */
    protected function json(mixed $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    protected function requireAuth(): void {
        if (!isset($_SESSION['user'])) {
            $this->redirect('auth/showLogin');
        }
    }
    
    protected function requireAdmin(): void {
        $this->requireAuth();
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('public/notFound');
        }
    }
    
    protected function requireProfOrAdmin(): void {
        $this->requireAuth();
        if (!in_array($_SESSION['user']['role'], ['admin', 'professeur'])) {
            $this->redirect('public/notFound');
        }
    }
    
    protected function input(string $key, mixed $default = null): mixed {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;
        return is_string($value) ? htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8') : $value;
    }
    
    protected function flash(string $type, string $message): void {
        $_SESSION['flash'][$type] = $message;
    }
}
