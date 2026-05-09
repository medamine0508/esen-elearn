<?php /* Vue : Gestion des Cours (Admin) */ ?>

<div class="container" style="padding:40px 20px">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:32px;flex-wrap:wrap;gap:16px">
    <h1 style="color:var(--primary);font-size:1.5rem"><i class="fas fa-book"></i> Gestion des Cours</h1>
    <span style="color:var(--gray);font-size:0.92rem"><?= count($cours) ?> cours au total</span>
  </div>

  <?php if (isset($_SESSION['flash'])): ?>
    <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
      <div class="alert alert-<?= $type ?>" style="margin-bottom:20px">
        <i class="fas fa-<?= $type==='success'?'check-circle':'exclamation-circle' ?>"></i> <?= $msg ?>
        <button class="close-alert" onclick="this.parentElement.remove()">×</button>
      </div>
    <?php endforeach; unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <!-- Barre de recherche live -->
  <div style="margin-bottom:20px">
    <input id="liveSearch" type="text" placeholder="Rechercher un cours…"
           style="width:100%;max-width:360px;padding:10px 14px;border:2px solid var(--border);border-radius:var(--radius);font-size:0.95rem">
  </div>

  <div class="card">
    <div class="card-header">
      <h3><i class="fas fa-list"></i> Liste des cours</h3>
    </div>
    <div style="overflow-x:auto">
      <?php if (empty($cours)): ?>
        <p style="padding:40px;text-align:center;color:var(--gray)">Aucun cours disponible.</p>
      <?php else: ?>
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Aperçu</th>
              <th>Titre</th>
              <th>Catégorie</th>
              <th>Niveau</th>
              <th>Professeur</th>
              <th>Durée</th>
              <th>Inscrits</th>
              <th>Statut</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cours as $c): ?>
            <tr>
              <td><?= $c['id_cours'] ?></td>
              <td>
                <img src="<?= BASE_URL ?>public/images/<?= htmlspecialchars(!empty($c['image']) ? $c['image'] : 'cours1.jpg') ?>"
                     alt="<?= htmlspecialchars($c['titre']) ?>"
                     style="width:60px;height:40px;object-fit:cover;border-radius:6px;display:block"
                     onerror="this.src='<?= BASE_URL ?>public/images/cours1.jpg'">
              </td>
              <td>
                <strong><?= htmlspecialchars($c['titre']) ?></strong>
                <br><small style="color:var(--gray)"><?= $c['nb_lecons'] ?> leçons</small>
              </td>
              <td>
                <span style="background:<?= htmlspecialchars($c['couleur'] ?? '#333') ?>;color:#fff;padding:3px 10px;border-radius:20px;font-size:0.78rem">
                  <i class="<?= htmlspecialchars($c['icone'] ?? 'fas fa-book') ?>"></i>
                  <?= htmlspecialchars($c['nom_categorie']) ?>
                </span>
              </td>
              <td><?= ucfirst($c['niveau']) ?></td>
              <td><?= htmlspecialchars($c['nom_professeur']) ?></td>
              <td><?= $c['duree_heures'] ?>h</td>
              <td><i class="fas fa-users"></i> <?= $c['nb_inscrits'] ?></td>
              <td>
                <span style="background:<?= $c['actif']?'#d4edda':'#f8d7da' ?>;color:<?= $c['actif']?'#155724':'#721c24' ?>;padding:3px 10px;border-radius:20px;font-size:0.8rem">
                  <?= $c['actif'] ? 'Actif' : 'Inactif' ?>
                </span>
              </td>
              <td>
                <!-- CORRECTION : bouton toggle actif/inactif ajouté -->
                <a href="<?= BASE_URL ?>?ctrl=admin&action=toggleActifCours&id=<?= $c['id_cours'] ?>"
                   class="btn btn-sm"
                   style="font-size:0.78rem;padding:4px 10px;background:<?= $c['actif']?'#f39c12':'#27ae60' ?>;color:#fff;border-radius:4px;text-decoration:none"
                   data-confirm="<?= $c['actif'] ? 'Désactiver ce cours ?' : 'Activer ce cours ?' ?>">
                  <i class="fas fa-<?= $c['actif'] ? 'eye-slash' : 'eye' ?>"></i>
                  <?= $c['actif'] ? 'Désactiver' : 'Activer' ?>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>
