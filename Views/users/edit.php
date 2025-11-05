<h1>Modifier l’utilisateur</h1>

<form method="POST" action="<?=\Core\Router::route('user_edit');?>">
    <input type="hidden" name="csrf_token" value="<?= $csrf; ?>" >
    <label>Nom :</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user->name) ?>" required><br>

    <label>Email :</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required><br>

    <label>Username :</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user->username) ?>" required><br>

    <label>Rôle :</label>
    <select name="role">
        <option value="">Veuillez choisir un role</option>
        <option value="staff" <?= $user->role === 'staff' ? 'selected' : '' ?>>Utilisateur</option>
        <option value="admin" <?= $user->role === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        <option value="manager" <?= $user->role === 'manager' ? 'selected' : '' ?>>Manager</option>
    </select><br>

    <button type="submit">Mettre à jour</button>
</form>
<a href="<?=\Core\Router::route('user_list');?>">⬅ Retour</a> |
