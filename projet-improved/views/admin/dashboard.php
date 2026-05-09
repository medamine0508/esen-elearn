<?php /* Vue : Tableau de bord Administrateur */ ?>

<!-- Statistiques globales -->
<div class="stats-grid">
    <div class="stat-card stat-blue">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h3><?= $statsUsers['total'] ?? 0 ?></h3>
            <p>Utilisateurs total</p>
            <small><?= $statsUsers['etudiants'] ?? 0 ?> étudiants | <?= $statsUsers['professeurs'] ?? 0 ?> professeurs</small>
        </div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-icon"><i class="fas fa-book-open"></i></div>
        <div class="stat-info">
            <h3><?= $statsCours['cours_actifs'] ?? 0 ?></h3>
            <p>Cours actifs</p>
            <small>Durée moy. <?= round($statsCours['duree_moyenne'] ?? 0) ?> h</small>
        </div>
    </div>
    <div class="stat-card stat-orange">
        <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
        <div class="stat-info">
            <h3><?= $statsUsers['actifs'] ?? 0 ?></h3>
            <p>Comptes actifs</p>
        </div>
    </div>
    <div class="stat-card stat-purple">
        <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
        <div class="stat-info">
            <h3><?= $statsCours['total_cours'] ?? 0 ?></h3>
            <p>Cours total</p>
            <small><?= $statsCours['debutant'] ?? 0 ?> deb. | <?= $statsCours['intermediaire'] ?? 0 ?> int. | <?= $statsCours['avance'] ?? 0 ?> avancé</small>
        </div>
    </div>
</div>

<div class="dashboard-row">
    <!-- Top cours -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3><i class="fas fa-fire"></i> Cours les plus inscrits</h3>
            <a href="?ctrl=admin&action=cours" class="btn-sm">Voir tout</a>
        </div>
        <table class="data-table">
            <thead>
                <tr><th>Cours</th><th>Catégorie</th><th>Niveau</th><th>Inscrits</th></tr>
            </thead>
            <tbody>
                <?php foreach ($topCours as $cours): ?>
                <tr>
                    <td><?= htmlspecialchars($cours['titre']) ?></td>
                    <td>
                        <span class="badge" style="background:<?= htmlspecialchars($cours['couleur']) ?>20; color:<?= htmlspecialchars($cours['couleur']) ?>">
                            <?= htmlspecialchars($cours['nom_categorie']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($cours['niveau']) ?></td>
                    <td><strong><?= $cours['nb_inscrits'] ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Rapport par catégorie -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3><i class="fas fa-layer-group"></i> Cours par catégorie</h3>
        </div>
        <div class="category-report">
            <?php foreach ($rapportCat as $cat): ?>
            <div class="category-item">
                <div class="category-icon" style="background:<?= htmlspecialchars($cat['couleur']) ?>20">
                    <i class="<?= htmlspecialchars($cat['icone'] ?? 'fas fa-folder') ?>" style="color:<?= htmlspecialchars($cat['couleur']) ?>"></i>
                </div>
                <div class="category-info">
                    <span><?= htmlspecialchars($cat['nom_categorie']) ?></span>
                    <small><?= $cat['nb_cours'] ?> cours – <?= $cat['nb_inscriptions'] ?> inscriptions</small>
                </div>
                <div class="category-bar">
                    <?php $pct = $cat['nb_cours'] > 0 ? min(100, $cat['nb_inscriptions'] * 5) : 0; ?>
                    <div class="bar-fill" style="width:<?= $pct ?>%; background:<?= htmlspecialchars($cat['couleur']) ?>"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="quick-actions">
    <h3>Actions rapides</h3>
    <div class="action-buttons">
        <a href="?ctrl=admin&action=ajouterUtilisateur" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Ajouter utilisateur
        </a>
        <a href="?ctrl=admin&action=cours" class="btn btn-secondary">
            <i class="fas fa-plus-circle"></i> Gérer les cours
        </a>
        <a href="?ctrl=admin&action=rapports" class="btn btn-info">
            <i class="fas fa-file-pdf"></i> Générer rapport
        </a>
    </div>
</div>
