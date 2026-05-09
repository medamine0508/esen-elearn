<?php /* Vue : Connexion – contenu injecté dans le layout auth */ ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>ESEN<span>E-Learn</span></h1>
            <p>Connectez-vous à votre espace pédagogique</p>
        </div>

        <?php if (isset($_SESSION['flash'])): ?>
            <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
            <div class="alert alert-<?= $type ?>">
                <i class="fas fa-<?= $type==='success'?'check-circle':'exclamation-circle' ?>"></i>
                <?= $msg ?>
            </div>
            <?php endforeach; unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>?ctrl=auth&action=login" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Adresse email</label>
                <input type="email" id="email" name="email" required
                       placeholder="votre@email.tn"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="mot_de_passe"><i class="fas fa-lock"></i> Mot de passe</label>
                <div class="input-password">
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required
                           placeholder="••••••••">
                    <button type="button" class="toggle-password" onclick="togglePwd(this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <div class="auth-footer">
            <p>Pas encore de compte ? <a href="<?= BASE_URL ?>?ctrl=auth&action=showRegister">S'inscrire</a></p>
            <p><a href="<?= BASE_URL ?>">← Retour à l'accueil</a></p>
        </div>

        <!-- Comptes de démo -->
        <div class="demo-accounts">
            <p><strong>Comptes de démonstration :</strong></p>
            <table>
                <tr><th>Rôle</th><th>Email</th><th>Mot de passe</th></tr>
                <tr><td>Admin</td><td>admin@esen-elearn.tn</td><td>password</td></tr>
                <tr><td>Étudiant</td><td>ghazouani.ala@etud.esen.tn</td><td>password</td></tr>
                <tr><td>Prof.</td><td>sami.benali@esen.tn</td><td>password</td></tr>
            </table>
        </div>
    </div>
</div>

<script>
function togglePwd(btn) {
    const input = btn.previousElementSibling;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
