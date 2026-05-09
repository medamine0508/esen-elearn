<?php
/**
 * Layout Auth – utilisé pour les pages de connexion et inscription
 * Ce layout ne contient pas la navbar ni le footer (page épurée)
 */
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'ESEN E-Learn') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="auth-body">
    <?= $content ?>
    <script src="<?= BASE_URL ?>public/js/app.js"></script>
</body>
</html>
