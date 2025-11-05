<h1>Créer un utilisateur</h1>

<form method="POST" action="<?=\Core\Router::route('user_create');?>">
    <input type="hidden" name="csrf_token" value="<?= $csrf; ?>" >
    <label>Nom :</label>
    <input type="text" name="name" required><br>

    <label>Email :</label>
    <input type="email" name="email" required><br>

    <label>Username :</label>
    <input type="text" name="username" required><br>

    <label>Mot de passe :</label>
    <input type="password" name="password" required><br>

    <label>Rôle :</label>
    <select name="role">
        <option value="staff">Utilisateur</option>
        <option value="manager">Manager</option>
        <option value="admin">Administrateur</option>
    </select><br>

    <button type="submit">Créer</button>
</form>

<a href="<?=\Core\Router::route('user_list');?>">⬅ Retour</a> |
