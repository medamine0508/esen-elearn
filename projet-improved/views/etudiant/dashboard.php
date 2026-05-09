<?php /* Vue : Tableau de bord Étudiant */ ?>

<div class="container" style="padding:40px 20px">
  <h1 style="color:#1B3A6B;margin-bottom:8px">
    Bonjour, <?= htmlspecialchars($_SESSION['user']['prenom']) ?> 👋
  </h1>
  <p style="color:#6c757d;margin-bottom:32px">Voici votre tableau de bord pédagogique</p>

  <!-- Stats personnelles -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px;margin-bottom:40px">
    <div class="stat-card stat-blue">
      <div class="stat-icon"><i class="fas fa-book-open"></i></div>
      <div class="stat-info">
        <h3><?= $stats['total'] ?? 0 ?></h3>
        <p>Cours inscrits</p>
      </div>
    </div>
    <div class="stat-card stat-green">
      <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
      <div class="stat-info">
        <h3><?= $stats['termines'] ?? 0 ?></h3>
        <p>Cours terminés</p>
      </div>
    </div>
    <div class="stat-card stat-orange">
      <div class="stat-icon"><i class="fas fa-spinner"></i></div>
      <div class="stat-info">
        <h3><?= $stats['en_cours'] ?? 0 ?></h3>
        <p>En cours</p>
      </div>
    </div>
    <div class="stat-card stat-purple">
      <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
      <div class="stat-info">
        <h3><?= round($stats['progression_moyenne'] ?? 0) ?>%</h3>
        <p>Progression moy.</p>
      </div>
    </div>
  </div>

  <!-- Mes cours -->
  <div class="card">
    <div class="card-header">
      <h3><i class="fas fa-graduation-cap"></i> Mes Cours</h3>
      <a href="<?= BASE_URL ?>?ctrl=cours&action=liste" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Découvrir d'autres cours
      </a>
    </div>
    <div style="padding:20px">
      <?php if (empty($mesCours)): ?>
        <div style="text-align:center;padding:48px;color:#6c757d">
          <i class="fas fa-book" style="font-size:3rem;margin-bottom:16px;display:block;opacity:0.3"></i>
          <p>Vous n'êtes inscrit à aucun cours pour le moment.</p>
          <a href="<?= BASE_URL ?>?ctrl=cours&action=liste" class="btn btn-primary" style="margin-top:16px">
            <i class="fas fa-search"></i> Parcourir le catalogue
          </a>
        </div>
      <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px">
          <?php foreach ($mesCours as $c): ?>
            <div style="border:1px solid #dee2e6;border-radius:12px;overflow:hidden;transition:box-shadow .2s" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.12)'" onmouseout="this.style.boxShadow='none'">
              <div style="position:relative;height:140px;overflow:hidden">
                <img src="<?= BASE_URL ?>public/images/<?= htmlspecialchars($c['image'] ?? 'cours1.jpg') ?>"
                     style="width:100%;height:100%;object-fit:cover"
                     onerror="this.src='<?= BASE_URL ?>public/images/cours1.jpg'">
                <span style="position:absolute;top:10px;left:10px;background:<?= htmlspecialchars($c['couleur'] ?? '#333') ?>;color:#fff;padding:3px 10px;border-radius:20px;font-size:0.78rem">
                  <?= htmlspecialchars($c['nom_categorie']) ?>
                </span>
                <span style="position:absolute;top:10px;right:10px;background:<?= $c['statut']==='termine'?'#198754':($c['statut']==='en_cours'?'#fd7e14':'#6c757d') ?>;color:#fff;padding:3px 10px;border-radius:20px;font-size:0.78rem">
                  <?= $c['statut']==='termine'?'✓ Terminé':($c['statut']==='en_cours'?'En cours':'Abandonné') ?>
                </span>
              </div>
              <div style="padding:16px">
                <h4 style="font-size:0.95rem;color:#1B3A6B;margin-bottom:8px"><?= htmlspecialchars($c['titre']) ?></h4>
                <p style="font-size:0.82rem;color:#6c757d;margin-bottom:12px">
                  <i class="fas fa-signal"></i> <?= ucfirst($c['niveau']) ?>
                  &nbsp;|&nbsp;
                  <i class="fas fa-puzzle-piece"></i> <?= $c['nb_quiz'] ?? 0 ?> quiz
                </p>

                <!-- Barre de progression -->
                <div style="margin-bottom:12px">
                  <div style="display:flex;justify-content:space-between;font-size:0.82rem;color:#6c757d;margin-bottom:4px">
                    <span>Progression</span>
                    <strong><?= $c['progression'] ?>%</strong>
                  </div>
                  <div class="progress-wrap">
                    <div class="progress-bar" data-width="<?= $c['progression'] ?>" style="width:0%"></div>
                  </div>
                </div>

                <?php if ($c['statut'] !== 'termine'): ?>
                  <a href="<?= BASE_URL ?>?ctrl=cours&action=detail&id=<?= $c['id_cours'] ?>" class="btn btn-primary" style="width:100%;text-align:center;display:block;padding:8px">
                    <i class="fas fa-play"></i> Continuer
                  </a>
                <?php else: ?>
                  <div class="btn btn-success" style="width:100%;text-align:center;display:block;padding:8px;cursor:default">
                    <i class="fas fa-trophy"></i> Cours terminé !
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
