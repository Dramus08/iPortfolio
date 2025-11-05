<?php
// create_super_admin_interactive.php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Admin\Models\Auth;

echo "Création interactive d'un Super Admin\n";
echo "-------------------------------------\n";

try {
    $auth = new Auth();

    // Vérifie s'il existe déjà un super admin
    $existing = $auth->first('role', 'superadmin');
    if ($existing) {
        echo "⚠️ Un super admin existe déjà : " . $existing->username . "\n";
        //exit;
    }

    // Lecture des informations depuis le terminal
    echo "Nom complet : ";
    $name = trim(fgets(STDIN));

    echo "Nom d'utilisateur : ";
    $username = trim(fgets(STDIN));

    echo "Email : ";
    $email = trim(fgets(STDIN));

    // Mot de passe sécurisé
    echo "Mot de passe : ";
    $password = trim(fgets(STDIN));

    // Confirmation mot de passe
    echo "Confirmer le mot de passe : ";
    $passwordConfirm = trim(fgets(STDIN));

    if ($password !== $passwordConfirm) {
        echo "❌ Les mots de passe ne correspondent pas.\n";
        exit;
    }

    // Données du super admin
    $data = [
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'role' => 'superadmin',
        'is_active' => true,
        'email_confirmed' => true,
        'is_super_admin'=> true,
        'is_staff' => true,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    // Création via la méthode create du modèle
    if ($auth->create($data)) {
        echo "✅ Super admin créé avec succès !\n";
    } else {
        echo "❌ Erreur lors de la création du super admin.\n";
    }

} catch (\Exception $e) {
    echo "❌ Exception : " . $e->getMessage() . "\n";
}
