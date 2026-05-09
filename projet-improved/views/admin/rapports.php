<?php /* Vue : Rapports et Statistiques (Admin) */ ?>

<div class="container" style="padding:40px 20px">
  <h1 style="color:var(--primary);font-size:1.5rem;margin-bottom:32px">
    <i class="fas fa-chart-bar"></i> Rapports &amp; Statistiques
  </h1>

  <!-- Statistiques globales -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:40px">
    <div class="stat-card stat-blue">
      <div class="stat-icon"><i class="fas fa-users"></i></div>
      <div class="stat-info"><h3><?= $statsUsers['total'] ?? 0 ?></h3><p>Utilisateurs total</p></div>
    </div>
    <div class="stat-card stat-green">
      <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
      <div class="stat-info"><h3><?= $statsUsers['etudiants'] ?? 0 ?></h3><p>Étudiants</p></div>
    </div>
    <div class="stat-card stat-orange">
      <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
      <div class="stat-info"><h3><?= $statsUsers['professeurs'] ?? 0 ?></h3><p>Professeurs</p></div>
    </div>
    <div class="stat-card stat-purple">
      <div class="stat-icon"><i class="fas fa-book-open"></i></div>
      <div class="stat-info"><h3><?= $statsCours['cours_actifs'] ?? 0 ?></h3><p>Cours actifs</p></div>
    </div>
  </div>

  <!-- Rapport par catégorie -->
  <div class="card" style="margin-bottom:32px">
    <div class="card-header"><h3><i class="fas fa-tags"></i> Rapport par catégorie</h3></div>
    <div style="overflow-x:auto">
      <?php if (empty($rapportCat)): ?>
        <p style="padding:32px;text-align:center;color:var(--gray)">Aucune donnée disponible.</p>
      <?php else: ?>
        <table class="data-table">
          <thead>
            <tr>
              <th>Catégorie</th>
              <th>Cours</th>
              <th>Inscriptions</th>
              <th>Progression moy.</th>
              <th>Barre</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rapportCat as $cat): ?>
            <tr>
              <td>
                <span style="background:<?= htmlspecialchars($cat['couleur'] ?? '#333') ?>;color:#fff;padding:3px 12px;border-radius:20px;font-size:0.82rem">
                  <?= htmlspecialchars($cat['nom_categorie']) ?>
                </span>
              </td>
              <td><?= $cat['nb_cours'] ?></td>
              <td><?= $cat['nb_inscriptions'] ?></td>
              <td><?= round($cat['progression_moyenne'] ?? 0) ?>%</td>
              <td style="min-width:120px">
                <div class="progress-wrap">
                  <div class="progress-bar" data-width="<?= round($cat['progression_moyenne'] ?? 0) ?>"
                       style="width:<?= round($cat['progression_moyenne'] ?? 0) ?>%;background:<?= htmlspecialchars($cat['couleur'] ?? 'var(--primary)') ?>"></div>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

  <!-- Résumé cours -->
  <div class="card">
    <div class="card-header"><h3><i class="fas fa-chart-pie"></i> Répartition par niveau</h3></div>
    <div style="padding:24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px">
      <div style="text-align:center;padding:20px;background:var(--accent);border-radius:var(--radius)">
        <h3 style="font-size:2rem;color:var(--primary)"><?= $statsCours['debutant'] ?? 0 ?></h3>
        <p style="color:var(--gray)">Débutant</p>
      </div>
      <div style="text-align:center;padding:20px;background:#fff3e0;border-radius:var(--radius)">
        <h3 style="font-size:2rem;color:var(--warning)"><?= $statsCours['intermediaire'] ?? 0 ?></h3>
        <p style="color:var(--gray)">Intermédiaire</p>
      </div>
      <div style="text-align:center;padding:20px;background:#fce4ec;border-radius:var(--radius)">
        <h3 style="font-size:2rem;color:var(--danger)"><?= $statsCours['avance'] ?? 0 ?></h3>
        <p style="color:var(--gray)">Avancé</p>
      </div>
      <div style="text-align:center;padding:20px;background:#e8f5e9;border-radius:var(--radius)">
        <h3 style="font-size:2rem;color:var(--success)"><?= round($statsCours['duree_moyenne'] ?? 0) ?>h</h3>
        <p style="color:var(--gray)">Durée moyenne</p>
      </div>
    </div>
  </div>
</div>
