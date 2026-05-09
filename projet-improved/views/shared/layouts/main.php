<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'ESEN E-Learn') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation principale -->
    <header class="main-header">
        <nav class="navbar">
            <div class="container nav-container">
                <a href="<?= BASE_URL ?>" class="logo">
                    ESEN<span>E-Learn</span>
                </a>
                <ul class="nav-menu">
                    <li><a href="<?= BASE_URL ?>">Accueil</a></li>
                    <li><a href="<?= BASE_URL ?>?ctrl=cours&action=liste">Cours</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <li><a href="<?= BASE_URL ?>?ctrl=admin&action=dashboard">Admin</a></li>
                        <?php else: ?>
                            <li><a href="<?= BASE_URL ?>?ctrl=etudiant&action=dashboard">Mon espace</a></li>
                        <?php endif; ?>
                        <li>
                            <span class="user-name">
                                <i class="fas fa-user-circle"></i>
                                <?= htmlspecialchars($_SESSION['user']['prenom']) ?>
                            </span>
                        </li>
                        <li><a href="<?= BASE_URL ?>?ctrl=auth&action=logout" class="btn-logout">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="<?= BASE_URL ?>?ctrl=auth&action=showLogin" class="btn-login">Connexion</a></li>
                        <li><a href="<?= BASE_URL ?>?ctrl=auth&action=showRegister" class="btn-register">S'inscrire</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Messages flash -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash-messages">
            <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
                <div class="alert alert-<?= $type ?>">
                    <i class="fas <?= $type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
                    <?= $msg ?>
                    <button class="close-alert" onclick="this.parentElement.remove()">×</button>
                </div>
            <?php endforeach; unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <!-- Contenu principal -->
    <main class="main-content">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4>ESEN E-Learn</h4>
                    <p>Plateforme éducative interactive pour les étudiants de l'ESEN</p>
                </div>
                <div>
                    <h4>Contact</h4>
                    <p><i class="fas fa-envelope"></i> group1-2-E-BUS@esen-elearn.tn</p>
                    <p><i class="fas fa-phone"></i> +216 56 014 469</p>
                </div>
                <div>
                    <h4>Équipe</h4>
                    <p>Ghazouani M.A. | Ouertani M.A.</p>
                    <p>Gabsi M.A. | Rouabah F.</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 ESEN E-Learn – Université de La Manouba | L2 E-BUS G1/G2</p>
            </div>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>public/js/app.js"></script>
</body>
</html>
