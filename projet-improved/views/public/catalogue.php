<?php /* Vue : Catalogue des cours */ ?>

<section class="section">
  <div class="container">
    <h2 class="section-title">Catalogue des Cours</h2>

    <!-- Formulaire de recherche -->
    <form method="GET" action="<?= BASE_URL ?>" class="search-bar" style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:32px;justify-content:center">
      <input type="hidden" name="ctrl"   value="cours">
      <input type="hidden" name="action" value="liste">
      <input type="text" name="q" value="<?= htmlspecialchars($terme) ?>"
             placeholder="Rechercher un cours..." class="search-input" style="flex:1;min-width:220px;padding:10px 16px;border:2px solid var(--border);border-radius:var(--radius);font-size:0.95rem">
      <select name="niveau" class="filter-select" style="padding:10px 14px;border:2px solid var(--border);border-radius:var(--radius)">
        <option value="">Tous les niveaux</option>
        <option value="debutant"      <?= $niveau==='debutant'?'selected':'' ?>>Débutant</option>
        <option value="intermediaire" <?= $niveau==='intermediaire'?'selected':'' ?>>Intermédiaire</option>
        <option value="avance"        <?= $niveau==='avance'?'selected':'' ?>>Avancé</option>
      </select>
      <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Rechercher</button>
      <?php if ($terme || $niveau || $categorie): ?>
        <a href="<?= BASE_URL ?>?ctrl=cours&action=liste" class="btn btn-secondary">Réinitialiser</a>
      <?php endif; ?>
    </form>

    <!-- Résultats -->
    <?php if (empty($cours)): ?>
      <div style="text-align:center;padding:64px;color:var(--gray)">
        <i class="fas fa-search" style="font-size:3rem;margin-bottom:16px;display:block;opacity:0.3"></i>
        <p>Aucun cours ne correspond à votre recherche.</p>
        <a href="<?= BASE_URL ?>?ctrl=cours&action=liste" class="btn btn-primary" style="margin-top:16px">
          <i class="fas fa-list"></i> Voir tous les cours
        </a>
      </div>
    <?php else: ?>
      <p style="color:var(--gray);margin-bottom:24px;text-align:center"><?= count($cours) ?> cours trouvé(s)</p>
      <div class="cours-grid">
        <?php foreach ($cours as $c): ?>
          <div class="cours-card">
            <div class="cours-image">
              <img src="<?= BASE_URL ?>public/images/<?= htmlspecialchars($c['image'] ?? 'cours1.jpg') ?>"
                   alt="<?= htmlspecialchars($c['titre']) ?>"
                   onerror="this.src='<?= BASE_URL ?>public/images/cours1.jpg'">
              <span class="cours-categorie" style="background:<?= htmlspecialchars($c['couleur'] ?? '#333') ?>">
                <?= htmlspecialchars($c['nom_categorie']) ?>
              </span>
            </div>
            <div class="cours-content">
              <h3><?= htmlspecialchars($c['titre']) ?></h3>
              <p><?= htmlspecialchars(substr($c['description'] ?? '', 0, 110)) ?>...</p>
              <div class="cours-info">
                <span><i class="fas fa-list"></i> <?= $c['nb_lecons'] ?> leçons</span>
                <span><i class="fas fa-clock"></i> <?= $c['duree_heures'] ?>h</span>
                <span><i class="fas fa-signal"></i> <?= ucfirst($c['niveau']) ?></span>
                <span><i class="fas fa-users"></i> <?= $c['nb_inscrits'] ?></span>
              </div>
              <p style="font-size:0.83rem;color:var(--gray);margin-bottom:12px">
                <i class="fas fa-chalkboard-teacher"></i> <?= htmlspecialchars($c['nom_professeur']) ?>
              </p>
              <?php if (isset($_SESSION['user'])): ?>
                <a href="<?= BASE_URL ?>?ctrl=etudiant&action=inscrireCours&id_cours=<?= $c['id_cours'] ?>"
                   class="btn-cours">
                  <i class="fas fa-plus-circle"></i> S'inscrire
                </a>
              <?php else: ?>
                <a href="<?= BASE_URL ?>?ctrl=auth&action=showLogin" class="btn-cours">
                  <i class="fas fa-sign-in-alt"></i> Connexion pour s'inscrire
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
