<?php /* Vue : Gestion des Utilisateurs (Admin) */ ?>

<!-- Barre de recherche et filtres -->
<div class="filter-bar">
    <form method="GET" action="<?= BASE_URL ?>">
        <input type="hidden" name="ctrl" value="admin">
        <input type="hidden" name="action" value="utilisateurs">
        <div class="filter-group">
            <input type="text" name="q" value="<?= htmlspecialchars($terme ?? '') ?>"
                   placeholder="Rechercher par nom, prénom ou email..." class="search-input">
            <select name="role" class="filter-select">
                <option value="">Tous les rôles</option>
                <option value="etudiant" <?= ($role??'')==='etudiant'?'selected':'' ?>>Étudiants</option>
                <option value="professeur" <?= ($role??'')==='professeur'?'selected':'' ?>>Professeurs</option>
                <option value="admin" <?= ($role??'')==='admin'?'selected':'' ?>>Administrateurs</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Rechercher</button>
            <a href="?ctrl=admin&action=utilisateurs" class="btn btn-secondary">Réinitialiser</a>
        </div>
    </form>
    <a href="?ctrl=admin&action=ajouterUtilisateur" class="btn btn-success">
        <i class="fas fa-user-plus"></i> Ajouter
    </a>
</div>

<!-- Tableau des utilisateurs -->
<div class="card">
    <div class="card-header">
        <h3>Utilisateurs (<?= count($users) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Inscription</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr><td colspan="7" class="empty-state">Aucun utilisateur trouvé</td></tr>
                <?php else: ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id_user'] ?></td>
                    <td>
                        <div class="user-avatar">
                            <span class="avatar-circle"><?= strtoupper(substr($user['prenom'],0,1).substr($user['nom'],0,1)) ?></span>
                            <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <?php
                        $roleBadge = match($user['role']) {
                            'admin' => ['Admin', '#dc3545'],
                            'professeur' => ['Professeur', '#fd7e14'],
                            default => ['Étudiant', '#198754'],
                        };
                        ?>
                        <span class="badge" style="background:<?= $roleBadge[1] ?>20;color:<?= $roleBadge[1] ?>">
                            <?= $roleBadge[0] ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($user['date_inscription'])) ?></td>
                    <td>
                        <span class="badge <?= $user['actif'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $user['actif'] ? 'Actif' : 'Inactif' ?>
                        </span>
                    </td>
                    <td class="actions-cell">
                        <a href="?ctrl=admin&action=modifierUtilisateur&id=<?= $user['id_user'] ?>"
                           class="btn-icon" title="Modifier"><i class="fas fa-edit"></i></a>
                        <?php if ($user['id_user'] !== $_SESSION['user']['id']): ?>
                        <a href="?ctrl=admin&action=supprimerUtilisateur&id=<?= $user['id_user'] ?>"
                           class="btn-icon btn-danger" title="Désactiver"
                           onclick="return confirm('Désactiver ce compte ?')">
                            <i class="fas fa-user-slash"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
