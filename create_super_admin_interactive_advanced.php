<?php
// create_super_admin_interactive_advanced.php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Admin\Models\Auth;

class SuperAdminCreator
{
    private Auth $auth;
    
    public function __construct()
    {
        $this->auth = new Auth();
    }
    
    public function run(): void
    {
        echo "🎯 Création interactive d'un Super Admin\n";
        echo "========================================\n\n";
        
        try {
            $this->checkExistingSuperAdmin();
            $data = $this->collectUserData();
            $this->createSuperAdmin($data);
            
        } catch (Exception $e) {
            echo "❌ Erreur : " . $e->getMessage() . "\n";
            exit(1);
        }
    }
    
    private function checkExistingSuperAdmin(): void
    {
        $existing = $this->auth->first('role', 'superadmin');
        if ($existing) {
            echo "⚠️  Un super admin existe déjà : {$existing->username}\n";
            echo "Voulez-vous continuer ? (o/N) : ";
            $response = trim(fgets(STDIN));
            
            if (!in_array(strtolower($response), ['o', 'oui', 'y', 'yes'])) {
                echo "Opération annulée.\n";
                exit(0);
            }
            echo "\n";
        }
    }
    
    private function collectUserData(): array
    {
        $data = [];
        
        // Nom complet
        while (empty($data['name'])) {
            echo "👤 Nom complet : ";
            $data['name'] = trim(fgets(STDIN));
            
            if (empty($data['name'])) {
                echo "❌ Le nom complet est obligatoire.\n";
            }
        }
        
        // Nom d'utilisateur
        while (empty($data['username'])) {
            echo "🔑 Nom d'utilisateur : ";
            $data['username'] = trim(fgets(STDIN));
            
            if (empty($data['username'])) {
                echo "❌ Le nom d'utilisateur est obligatoire.\n";
            } elseif ($this->auth->usernameExists($data['username'])) {
                echo "❌ Ce nom d'utilisateur est déjà utilisé.\n";
                $data['username'] = '';
            }
        }
        
        // Email
        while (empty($data['email'])) {
            echo "📧 Email : ";
            $data['email'] = trim(fgets(STDIN));
            
            if (empty($data['email'])) {
                echo "❌ L'email est obligatoire.\n";
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                echo "❌ Format d'email invalide.\n";
                $data['email'] = '';
            } elseif ($this->auth->emailExists($data['email'])) {
                echo "❌ Cet email est déjà utilisé.\n";
                $data['email'] = '';
            }
        }
        
        // Mot de passe
        $data['password'] = $this->collectPassword();
        
        // Génération du slug
        $data['slug'] = $this->generateUniqueSlug($data['name']);
        
        return $data;
    }
    
    private function collectPassword(): string
    {
        while (true) {
            echo "🔒 Mot de passe : ";
            $password = trim(fgets(STDIN));
            
            if (strlen($password) < 8) {
                echo "❌ Le mot de passe doit contenir au moins 8 caractères.\n";
                continue;
            }
            
            echo "🔒 Confirmer le mot de passe : ";
            $passwordConfirm = trim(fgets(STDIN));
            
            if ($password !== $passwordConfirm) {
                echo "❌ Les mots de passe ne correspondent pas.\n";
                continue;
            }
            
            return $password;
        }
    }
    
    private function generateUniqueSlug(string $name): string
    {
        $slug = $this->auth->generateSlug($name);
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->auth->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        echo "📝 Slug généré : {$slug}\n";
        return $slug;
    }
    
    private function createSuperAdmin(array $data): void
    {
        $userData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'slug' => $data['slug'],
            'role' => 'superadmin',
            'is_active' => true,
            'email_confirmed' => true,
            'is_super_admin' => true,
            'is_staff' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        if ($this->auth->create($userData)) {
            echo "\n🎉 Super admin créé avec succès !\n";
            echo "===============================\n";
            echo "👤 Nom : {$userData['name']}\n";
            echo "🔑 Username : {$userData['username']}\n";
            echo "📧 Email : {$userData['email']}\n";
            echo "📝 Slug : {$userData['slug']}\n";
            echo "👑 Rôle : {$userData['role']}\n";
        } else {
            throw new Exception("Erreur lors de la création du super admin.");
        }
    }
}

// Exécution du script
$creator = new SuperAdminCreator();
$creator->run();