<?php /* Vue : Ajouter un utilisateur (Admin) */ ?>

<div class="container" style="padding:40px 20px;max-width:640px">
  <div style="display:flex;align-items:center;gap:16px;margin-bottom:32px">
    <a href="<?= BASE_URL ?>?ctrl=admin&action=utilisateurs" class="btn-icon"><i class="fas fa-arrow-left"></i></a>
    <h1 style="color:var(--primary);font-size:1.5rem">Ajouter un utilisateur</h1>
  </div>

  <div class="card">
    <div class="card-header"><h3><i class="fas fa-user-plus"></i> Nouveau compte</h3></div>
    <div style="padding:24px">
      <form method="POST" action="<?= BASE_URL ?>?ctrl=admin&action=ajouterUtilisateur">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="form-group">
            <label>Nom *</label>
            <input type="text" name="nom" required placeholder="Nom" class="form-control">
          </div>
          <div class="form-group">
            <label>Prénom *</label>
            <input type="text" name="prenom" required placeholder="Prénom" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label>Email *</label>
          <input type="email" name="email" required placeholder="email@esen.tn" class="form-control">
        </div>
        <div class="form-group">
          <label>Mot de passe *</label>
          <input type="password" name="mot_de_passe" required minlength="6" placeholder="Minimum 6 caractères" class="form-control">
        </div>
        <div class="form-group">
          <label>Rôle *</label>
          <select name="role" class="form-control">
            <option value="etudiant">Étudiant</option>
            <option value="professeur">Professeur</option>
            <option value="admin">Administrateur</option>
          </select>
        </div>
        <div style="display:flex;gap:12px;margin-top:8px">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Créer le compte</button>
          <a href="<?= BASE_URL ?>?ctrl=admin&action=utilisateurs" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
