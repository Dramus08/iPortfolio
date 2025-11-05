<h1>Liste des utilisateurs</h1>

<a href="<?=\Core\Router::route('user_create');?>">➕ Ajouter un utilisateur</a>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th><th>Nom</th><th>Email</th><th>Username</th><th>Rôle</th><th>Is_active</th><th>Email_confirm</th><th>created_at</th><th>updated_at</th><th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user->id) ?></td>
        <td><?= htmlspecialchars($user->name) ?></td>
        <td><?= htmlspecialchars($user->email) ?></td>
        <td><?= htmlspecialchars($user->username) ?></td>
        <td><?= htmlspecialchars($user->role) ?></td>
        <td style="background-color:<?= htmlspecialchars($user->is_active) == 1 ? "green" : "red"; ?>;color:white;"><?= htmlspecialchars($user->is_active) == 1 ? "On" : "Off"; ?></td>
        <td style="background-color:<?= htmlspecialchars($user->is_active) == 1 ? "green" : "red"; ?>;color:white;"><?= htmlspecialchars($user->email_confirmed) == 1 ? "Confirm" : "Not Confirm"; ?></td>
        <td><?= htmlspecialchars($user->created_at) ?></td>
        <td><?= htmlspecialchars($user->updated_at) ?></td>
        <td>
            <a href="<?=\Core\Router::route('user_show', ['id' => $user->id]);?>">👁️</a> |
            <a href="<?=\Core\Router::route('user_edit', ['id' => $user->id]);?>">✏️</a> |
            <a href="<?=\Core\Router::route('user_delete', ['id' => $user->id]);?>" onclick="return confirm('Supprimer ?')">🗑️</a>
            <?php if ($user->is_active): ?>
                    <a href="<?=\Core\Router::route('user_deactivate', ['id' => $user->id]);?>">🚫</a>
            <?php else: ?>
                    <a href="<?=\Core\Router::route('user_activate', ['id' => $user->id]);?>">✅</a>
            <?php endif; ?>
                <a href="<?=\Core\Router::route('user_reset', ['id' => $user->id]);?>" onclick="return confirm('Réinitialiser le mot de passe ?')">🔑</a>
            
        </td>
    </tr>
    <?php endforeach; ?>
</table>
