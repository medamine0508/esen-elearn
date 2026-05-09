<?php /* Vue : Inscription – contenu injecté dans le layout auth */ ?>

<div class="auth-container" style="max-width:520px">
  <div class="auth-card">
    <div class="auth-header">
      <h1>ESEN<span>E-Learn</span></h1>
      <p>Créez votre compte étudiant</p>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
      <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
        <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
      <?php endforeach; unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>?ctrl=auth&action=register" class="auth-form" id="formInscription">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label><i class="fas fa-user"></i> Nom</label>
          <input type="text" name="nom" required placeholder="Votre nom"
                 value="<?= htmlspecialchars($_SESSION['form_data']['nom'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label><i class="fas fa-user"></i> Prénom</label>
          <input type="text" name="prenom" required placeholder="Votre prénom"
                 value="<?= htmlspecialchars($_SESSION['form_data']['prenom'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label><i class="fas fa-envelope"></i> Email</label>
        <input type="email" name="email" required placeholder="votre@email.tn"
               value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label><i class="fas fa-lock"></i> Mot de passe</label>
        <div class="input-password">
          <input type="password" id="mot_de_passe" name="mot_de_passe" required
                 placeholder="Minimum 6 caractères">
          <button type="button" class="toggle-password" onclick="togglePwd(this)">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>

      <div class="form-group">
        <label><i class="fas fa-lock"></i> Confirmer le mot de passe</label>
        <div class="input-password">
          <input type="password" id="confirmation" name="confirmation" required
                 placeholder="Répétez le mot de passe">
          <button type="button" class="toggle-password" onclick="togglePwd(this)">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-full">
        <i class="fas fa-user-plus"></i> Créer mon compte
      </button>
    </form>

    <div class="auth-footer">
      <p>Déjà inscrit ? <a href="<?= BASE_URL ?>?ctrl=auth&action=showLogin">Se connecter</a></p>
      <p><a href="<?= BASE_URL ?>">← Retour à l'accueil</a></p>
    </div>
  </div>
</div>
<?php unset($_SESSION['form_data']); ?>

<script>
function togglePwd(btn) {
  const input = btn.previousElementSibling;
  const icon  = btn.querySelector('i');
  input.type  = input.type === 'password' ? 'text' : 'password';
  icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
document.getElementById('formInscription').addEventListener('submit', function(e) {
  const mdp  = document.getElementById('mot_de_passe').value;
  const conf = document.getElementById('confirmation').value;
  if (mdp !== conf) {
    e.preventDefault();
    alert('Les mots de passe ne correspondent pas !');
  }
});
</script>
