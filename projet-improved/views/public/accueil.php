<?php /* Vue : Page d'accueil publique – ESEN E-Learn */ ?>

<!-- Hero Section -->
<section class="hero">
  <div class="container hero-container">
    <div class="hero-text">
      <h2>Apprenez à votre rythme avec <span class="highlight">ESEN E-Learn</span></h2>
      <p>Plateforme éducative interactive avec cours, exercices et quiz pour les étudiants de l'ESEN – Université de La Manouba</p>
      <div class="hero-buttons">
        <a href="<?= BASE_URL ?>?ctrl=cours&action=liste" class="btn btn-primary">
          <i class="fas fa-book-open"></i> Voir les cours
        </a>
        <?php if (!isset($_SESSION['user'])): ?>
          <a href="<?= BASE_URL ?>?ctrl=auth&action=showRegister" class="btn btn-secondary" style="color:#fff;border-color:rgba(255,255,255,0.5)">
            <i class="fas fa-user-plus"></i> S'inscrire
          </a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>?ctrl=etudiant&action=dashboard" class="btn btn-secondary" style="color:#fff;border-color:rgba(255,255,255,0.5)">
            <i class="fas fa-tachometer-alt"></i> Mon espace
          </a>
        <?php endif; ?>
      </div>
    </div>
    <div class="hero-image">
      <img src="<?= BASE_URL ?>public/images/hero-bg.jpg" alt="ESEN E-Learn – Apprentissage en ligne">
    </div>
  </div>
</section>

<!-- Statistiques animées -->
<section class="stats">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-item">
        <h3 data-target="<?= $statsCours['cours_actifs'] ?? 4 ?>">0</h3>
        <p>Cours disponibles</p>
      </div>
      <div class="stat-item">
        <h3 data-target="<?= $statsUsers['etudiants'] ?? 0 ?>">0</h3>
        <p>Étudiants actifs</p>
      </div>
      <div class="stat-item">
        <h3 data-target="<?= $statsInscr['total'] ?? 0 ?>">0</h3>
        <p>Inscriptions</p>
      </div> 
      <div class="stat-item">
        <h3 data-target="<?= $statsUsers['professeurs'] ?? 0 ?>">0</h3>
        <p>Professeurs</p>
      </div>
    </div>
  </div>
</section>

<!-- Catalogue des cours -->
<section class="section">
  <div class="container">
    <h2 class="section-title">Cours Disponibles</h2>

    <!-- Filtres dynamiques basés sur les catégories réelles -->
    <div class="filtres">
      <button class="filtre-btn active" data-filtre="tout">Tous</button>
      <button class="filtre-btn" data-filtre="informatique">Informatique</button>
      <button class="filtre-btn" data-filtre="ebusiness">E-Business</button>
      <button class="filtre-btn" data-filtre="management">Management</button>
      <button class="filtre-btn" data-filtre="langues">Langues</button>
    </div>

    <!-- Grille des cours -->
    <div class="cours-grid">
      <?php if (empty($cours)): ?>
        <p style="grid-column:1/-1;text-align:center;color:#6c757d">Aucun cours disponible pour le moment.</p>
      <?php else: ?>
        <?php foreach ($cours as $c): ?>
          <?php
            /* CORRECTION : mapping catégorie → data-filtre aligné sur les vraies catégories DB */
            $categNorm = strtolower(preg_replace('/\s+/', '', $c['nom_categorie']));
            if (strpos($categNorm, 'informatique') !== false)  $datacat = 'informatique';
            elseif (strpos($categNorm, 'ebusiness') !== false ||
                    strpos($categNorm, 'e-business') !== false) $datacat = 'ebusiness';
            elseif (strpos($categNorm, 'management') !== false) $datacat = 'management';
            elseif (strpos($categNorm, 'langues') !== false ||
                    strpos($categNorm, 'langue') !== false)     $datacat = 'langues';
            else                                                 $datacat = 'informatique';

            /* Image – jamais NULL */
            $imageSrc = BASE_URL . 'public/images/' . htmlspecialchars(!empty($c['image']) ? $c['image'] : 'cours1.jpg');
          ?>
          <div class="cours-card" data-categorie="<?= $datacat ?>">
            <div class="cours-image">
              <img src="<?= $imageSrc ?>"
                   alt="<?= htmlspecialchars($c['titre']) ?>"
                   onerror="this.src='<?= BASE_URL ?>public/images/cours1.jpg'">
              <span class="cours-categorie" style="background:<?= htmlspecialchars($c['couleur'] ?? '#333') ?>">
                <?= htmlspecialchars($c['nom_categorie']) ?>
              </span>
            </div>
            <div class="cours-content">
              <h3><?= htmlspecialchars($c['titre']) ?></h3>
              <p><?= htmlspecialchars(substr($c['description'] ?? '', 0, 100)) ?>…</p>
              <div class="cours-info">
                <span><i class="fas fa-list"></i> <?= (int)$c['nb_lecons'] ?> leçons</span>
                <span><i class="fas fa-clock"></i> <?= $c['duree_heures'] ?>h</span>
                <span><i class="fas fa-signal"></i> <?= ucfirst($c['niveau']) ?></span>
                <span><i class="fas fa-users"></i> <?= (int)$c['nb_inscrits'] ?></span>
              </div>
              <p style="font-size:0.83rem;color:#6c757d;margin-bottom:12px">
                <i class="fas fa-chalkboard-teacher"></i> <?= htmlspecialchars($c['nom_professeur']) ?>
              </p>
              <?php if (isset($_SESSION['user'])): ?>
                <a href="<?= BASE_URL ?>?ctrl=cours&action=detail&id=<?= $c['id_cours'] ?>" class="btn-cours">
                  <i class="fas fa-eye"></i> Voir le cours
                </a>
              <?php else: ?>
                <a href="<?= BASE_URL ?>?ctrl=auth&action=showLogin" class="btn-cours">
                  <i class="fas fa-sign-in-alt"></i> Connexion pour s'inscrire
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <?php if (count($cours) >= 4): ?>
    <div style="text-align:center;margin-top:40px">
      <a href="<?= BASE_URL ?>?ctrl=cours&action=liste" class="btn btn-primary">
        <i class="fas fa-arrow-right"></i> Voir tous les cours
      </a>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- Section CTA inscription -->
<?php if (!isset($_SESSION['user'])): ?>
<section style="background:linear-gradient(135deg,#1B3A6B,#2563A8);padding:64px 0;text-align:center;color:#fff">
  <div class="container">
    <h2 style="font-size:2rem;margin-bottom:16px">Rejoignez ESEN E-Learn aujourd'hui</h2>
    <p style="opacity:0.85;margin-bottom:32px;font-size:1.1rem">Accédez à tous les cours, quiz et ressources pédagogiques de l'ESEN</p>
    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
      <a href="<?= BASE_URL ?>?ctrl=auth&action=showRegister" class="btn btn-primary" style="background:#fff;color:#1B3A6B">
        <i class="fas fa-user-plus"></i> Créer un compte gratuit
      </a>
      <a href="<?= BASE_URL ?>?ctrl=auth&action=showLogin" class="btn btn-secondary" style="border-color:rgba(255,255,255,0.5);color:#fff">
        <i class="fas fa-sign-in-alt"></i> Se connecter
      </a>
    </div>
  </div>
</section>
<?php endif; ?>
