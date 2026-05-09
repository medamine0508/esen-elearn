<?php /* Vue : Détail d'un cours */ ?>

<div class="container" style="padding:40px 20px">

  <!-- En-tête du cours -->
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:40px;align-items:start;margin-bottom:40px" class="detail-grid">
    <div>
      <span style="background:<?= htmlspecialchars($cours['couleur'] ?? '#333') ?>;color:#fff;padding:4px 14px;border-radius:20px;font-size:0.85rem;display:inline-block;margin-bottom:16px">
        <?= htmlspecialchars($cours['nom_categorie']) ?>
      </span>
      <h1 style="font-size:2rem;color:var(--primary);margin-bottom:12px"><?= htmlspecialchars($cours['titre']) ?></h1>
      <p style="color:var(--gray);margin-bottom:20px;font-size:1.05rem"><?= htmlspecialchars($cours['description'] ?? '') ?></p>

      <div style="display:flex;gap:20px;flex-wrap:wrap;font-size:0.92rem;color:var(--gray)">
        <span><i class="fas fa-chalkboard-teacher"></i> <?= htmlspecialchars($cours['nom_professeur']) ?></span>
        <span><i class="fas fa-list"></i> <?= $cours['nb_lecons'] ?> leçons</span>
        <span><i class="fas fa-clock"></i> <?= $cours['duree_heures'] ?>h</span>
        <span><i class="fas fa-signal"></i> <?= ucfirst($cours['niveau']) ?></span>
        <span><i class="fas fa-users"></i> <?= $cours['nb_inscrits'] ?> inscrits</span>
      </div>
    </div>

    <!-- Carte d'inscription -->
    <div class="card">
      <img src="<?= BASE_URL ?>public/images/<?= htmlspecialchars($cours['image'] ?? 'cours1.jpg') ?>"
           alt="<?= htmlspecialchars($cours['titre']) ?>"
           style="width:100%;height:200px;object-fit:cover"
           onerror="this.src='<?= BASE_URL ?>public/images/cours1.jpg'">
      <div style="padding:20px">
        <?php if (isset($_SESSION['user'])): ?>
          <?php if ($inscrit): ?>
            <div class="btn btn-success btn-full" style="cursor:default;justify-content:center">
              <i class="fas fa-check-circle"></i> Vous êtes inscrit
            </div>
            <a href="<?= BASE_URL ?>?ctrl=etudiant&action=dashboard"
               class="btn btn-secondary btn-full" style="margin-top:10px;justify-content:center">
              <i class="fas fa-play"></i> Aller à mon espace
            </a>
          <?php else: ?>
            <a href="<?= BASE_URL ?>?ctrl=etudiant&action=inscrireCours&id_cours=<?= $cours['id_cours'] ?>"
               class="btn btn-primary btn-full" style="justify-content:center">
              <i class="fas fa-plus-circle"></i> S'inscrire à ce cours
            </a>
          <?php endif; ?>
        <?php else: ?>
          <a href="<?= BASE_URL ?>?ctrl=auth&action=showLogin"
             class="btn btn-primary btn-full" style="justify-content:center">
            <i class="fas fa-sign-in-alt"></i> Se connecter pour s'inscrire
          </a>
          <a href="<?= BASE_URL ?>?ctrl=auth&action=showRegister"
             class="btn btn-secondary btn-full" style="margin-top:10px;justify-content:center">
            <i class="fas fa-user-plus"></i> Créer un compte gratuit
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <a href="<?= BASE_URL ?>?ctrl=cours&action=liste" style="color:var(--primary-lt);font-size:0.92rem">
    <i class="fas fa-arrow-left"></i> Retour au catalogue
  </a>
</div>

<style>
@media(max-width:768px){
  .detail-grid { grid-template-columns:1fr !important; }
}
</style>
