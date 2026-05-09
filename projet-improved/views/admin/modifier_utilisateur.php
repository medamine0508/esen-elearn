<?php /* Vue : Modifier un utilisateur (Admin) */ ?>

<div class="container" style="padding:40px 20px;max-width:640px">
  <div style="display:flex;align-items:center;gap:16px;margin-bottom:32px">
    <a href="<?= BASE_URL ?>?ctrl=admin&action=utilisateurs" class="btn-icon"><i class="fas fa-arrow-left"></i></a>
    <h1 style="color:var(--primary);font-size:1.5rem">Modifier l'utilisateur</h1>
  </div>

  <div class="card">
    <div class="card-header"><h3><i class="fas fa-user-edit"></i> <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h3></div>
    <div style="padding:24px">
      <form method="POST" action="<?= BASE_URL ?>?ctrl=admin&action=modifierUtilisateur&id=<?= $user['id_user'] ?>">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="form-group">
            <label>Nom *</label>
            <input type="text" name="nom" required value="<?= htmlspecialchars($user['nom']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label>Prénom *</label>
            <input type="text" name="prenom" required value="<?= htmlspecialchars($user['prenom']) ?>" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label>Email *</label>
          <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>" class="form-control">
        </div>
        <div class="form-group">
          <label>Nouveau mot de passe <small style="color:var(--gray)">(laisser vide pour ne pas changer)</small></label>
          <input type="password" name="nouveau_mdp" minlength="6" placeholder="Nouveau mot de passe" class="form-control">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="form-group">
            <label>Rôle *</label>
            <select name="role" class="form-control">
              <option value="etudiant"   <?= $user['role']==='etudiant'?'selected':'' ?>>Étudiant</option>
              <option value="professeur" <?= $user['role']==='professeur'?'selected':'' ?>>Professeur</option>
              <option value="admin"      <?= $user['role']==='admin'?'selected':'' ?>>Administrateur</option>
            </select>
          </div>
          <div class="form-group">
            <label>Statut</label>
            <select name="actif" class="form-control">
              <option value="1" <?= $user['actif']?'selected':'' ?>>Actif</option>
              <option value="0" <?= !$user['actif']?'selected':'' ?>>Inactif</option>
            </select>
          </div>
        </div>
        <div style="display:flex;gap:12px;margin-top:8px">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
          <a href="<?= BASE_URL ?>?ctrl=admin&action=utilisateurs" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
