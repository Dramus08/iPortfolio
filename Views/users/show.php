<h1>Détails de l’utilisateur</h1>

<p><strong>ID :</strong> <?= htmlspecialchars($user->id) ?></p>
<p><strong>Nom :</strong> <?= htmlspecialchars($user->name) ?></p>
<p><strong>Email :</strong> <?= htmlspecialchars($user->email) ?></p>
<p><strong>Username :</strong> <?= htmlspecialchars($user->username) ?></p>
<p><strong>Rôle :</strong> <?= htmlspecialchars($user->role) ?></p>

    <a href="<?=\Core\Router::route('user_edit', ['id' => $user->id]);?>">✏️ Modifier</a> |
<a href="<?=\Core\Router::route('user_delete', ['id' => $user->id]);?>"onclick="return confirm('Supprimer ?')">🗑️ Supprimer</a> |
<br><br>
<a href="<?=\Core\Router::route('user_list');?>">⬅ Retour</a> |
