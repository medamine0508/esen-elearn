<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin – ESEN E-Learn') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>ESEN <span>Admin</span></h2>
                <p><?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?></p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?= strpos($_SERVER['QUERY_STRING']??'','dashboard')!==false?'active':'' ?>">
                        <a href="<?= BASE_URL ?>?ctrl=admin&action=dashboard">
                            <i class="fas fa-tachometer-alt"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="<?= strpos($_SERVER['QUERY_STRING']??'','utilisateurs')!==false?'active':'' ?>">
                        <a href="<?= BASE_URL ?>?ctrl=admin&action=utilisateurs">
                            <i class="fas fa-users"></i> Utilisateurs
                        </a>
                    </li>
                    <li class="<?= strpos($_SERVER['QUERY_STRING']??'','cours')!==false?'active':'' ?>">
                        <a href="<?= BASE_URL ?>?ctrl=admin&action=cours">
                            <i class="fas fa-book"></i> Cours
                        </a>
                    </li>
                    <li class="<?= strpos($_SERVER['QUERY_STRING']??'','rapports')!==false?'active':'' ?>">
                        <a href="<?= BASE_URL ?>?ctrl=admin&action=rapports">
                            <i class="fas fa-chart-bar"></i> Rapports
                        </a>
                    </li>
                    <li class="separator"></li>
                    <li>
                        <a href="<?= BASE_URL ?>" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Voir le site
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>?ctrl=auth&action=logout" class="logout-link">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Contenu principal admin -->
        <div class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button id="sidebarToggle" class="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title"><?= htmlspecialchars($title ?? '') ?></h1>
                </div>
                <div class="topbar-right">
                    <?php if (isset($_SESSION['flash'])): ?>
                        <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
                            <div class="alert alert-<?= $type ?> alert-sm">
                                <?= $msg ?>
                            </div>
                        <?php endforeach; unset($_SESSION['flash']); ?>
                    <?php endif; ?>
                    <span class="admin-user">
                        <i class="fas fa-user-shield"></i>
                        <?= htmlspecialchars($_SESSION['user']['email']) ?>
                    </span>
                </div>
            </header>

            <div class="admin-content">
                <?= $content ?>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>public/js/app.js"></script>
    <script src="<?= BASE_URL ?>public/js/admin.js"></script>
</body>
</html>
