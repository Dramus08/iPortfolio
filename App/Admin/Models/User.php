<?php
namespace Admin\Models;

use Core\Model;
use Exception;

class User extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';

    // Champs spécifiques
    public int $id;
    public ?string $first_name = null;
    public ?string $last_name = null;
    public ?string $name = null;
    public string $username;
    public string $email;
    public string $password;
    public ?string $phone = null;
    public bool $is_super_admin = false;
    public string $role = 'staff';
    public bool $is_staff = false;
    public bool $email_confirmed = false;
    public bool $is_active = true;
    public ?string $confirmation_token = null;
    public string $created_at;
    public string $updated_at;
    public ?string $token_expires_at = null;

    /**
     * Crée un nouvel utilisateur avec validation
     */
    public function createUser(array $data): bool
    {
        try {
            // Validation des données
            $errors = $this->validateUserData($data);
            if (!empty($errors)) {
                throw new Exception(implode(', ', $errors));
            }

            // Préparation des données
            $userData = $this->prepareUserData($data);

            // Génération du token de confirmation si email non confirmé
            if (!$userData['email_confirmed']) {
                $tokenData = $this->generateConfirmationToken();
                $userData['confirmation_token'] = $tokenData['token'];
                $userData['token_expires_at'] = $tokenData['expires'];
            }

            // Hashage du mot de passe
            if (isset($userData['password'])) {
                $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
            }

            // Création de l'utilisateur
            return $this->create($userData);
            
        } catch (Exception $e) {
            error_log("Erreur création utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour un utilisateur avec validation
     */
    public function updateUser(int $id, array $data): bool
    {
        try {
            // Vérifier que l'utilisateur existe
            $existingUser = $this->find($id);
            if (!$existingUser) {
                throw new Exception("Utilisateur non trouvé");
            }

            // Validation des données de mise à jour
            $errors = $this->validateUserData($data, $id);
            if (!empty($errors)) {
                throw new Exception(implode(', ', $errors));
            }

            // Préparation des données
            $userData = $this->prepareUserData($data, true);

            // Ne pas mettre à jour le mot de passe s'il est vide
            if (isset($userData['password']) && empty($userData['password'])) {
                unset($userData['password']);
            } elseif (isset($userData['password'])) {
                $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
            }

            return $this->update($id, $userData);
            
        } catch (Exception $e) {
            error_log("Erreur mise à jour utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Valide les données utilisateur
     */
    private function validateUserData(array $data, ?int $userId = null): array
    {
        $errors = [];

        // Validation email
        if (isset($data['email'])) {
            if (empty($data['email'])) {
                $errors[] = "L'email est requis";
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Format d'email invalide";
            } elseif ($this->emailExists($data['email'], $userId)) {
                $errors[] = "Cet email est déjà utilisé";
            }
        }

        // Validation username
        if (isset($data['username'])) {
            if (empty($data['username'])) {
                $errors[] = "Le nom d'utilisateur est requis";
            } elseif ($this->usernameExists($data['username'], $userId)) {
                $errors[] = "Ce nom d'utilisateur est déjà utilisé";
            }
        }

        // Validation mot de passe (seulement pour la création ou si fourni)
        if ((!$userId && empty($data['password'])) || (isset($data['password']) && !empty($data['password']))) {
            if (isset($data['password']) && strlen($data['password']) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
            }
        }

        // Validation rôle
        if (isset($data['role'])) {
            $allowedRoles = ['staff', 'manager', 'admin', 'superadmin', 'user'];
            if (!in_array($data['role'], $allowedRoles)) {
                $errors[] = "Rôle invalide";
            }
        }

        return $errors;
    }

    /**
     * Prépare les données utilisateur pour la sauvegarde
     */
    private function prepareUserData(array $data, bool $isUpdate = false): array
    {
        $preparedData = [];

        // Champs de base
        $fields = ['first_name', 'last_name', 'name', 'username', 'email', 'phone', 'role', 'is_active'];
        
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = $data[$field];
            }
        }

        // Gestion du nom complet
        if (empty($preparedData['name']) && !empty($preparedData['first_name']) && !empty($preparedData['last_name'])) {
            $preparedData['name'] = $preparedData['first_name'] . ' ' . $preparedData['last_name'];
        }

        // Mot de passe (seulement si fourni)
        if (isset($data['password']) && !empty($data['password'])) {
            $preparedData['password'] = $data['password'];
        }

        // Champs booléens
        if (isset($data['is_staff'])) {
            $preparedData['is_staff'] = (bool)$data['is_staff'];
        }

        if (isset($data['is_super_admin'])) {
            $preparedData['is_super_admin'] = (bool)$data['is_super_admin'];
        }

        if (isset($data['email_confirmed'])) {
            $preparedData['email_confirmed'] = (bool)$data['email_confirmed'];
        }

        // Pour la création, définir les valeurs par défaut
        if (!$isUpdate) {
            $preparedData['is_active'] = $preparedData['is_active'] ?? true;
            $preparedData['email_confirmed'] = $preparedData['email_confirmed'] ?? false;
            $preparedData['role'] = $preparedData['role'] ?? 'user';
        }

        return $preparedData;
    }

    /**
     * Vérifie si l'email existe déjà
     */
    public function emailExists(string $email, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeUserId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeUserId;
        }

        $this->db->query($sql, $params);
        $result = $this->getData();
        return !empty($result) && $result[0]['COUNT(*)'] > 0;
    }

    /**
     * Vérifie si le username existe déjà
     */
    public function usernameExists(string $username, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = :username";
        $params = ['username' => $username];

        if ($excludeUserId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeUserId;
        }

        $this->db->query($sql, $params);
        $result = $this->getData();
        return !empty($result) && $result[0]['COUNT(*)'] > 0;
    }

    /**
     * Génère un token de confirmation
     */
    private function generateConfirmationToken(): array
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = (new \DateTime('+24 hours'))->format('Y-m-d H:i:s');

        return [
            'token' => $token,
            'expires' => $expiresAt
        ];
    }

            /**
     * Trouve un utilisateur par son email
     * @param string $email
     * @return object|null
     */
    public function findByEmail(string $email): ?static
    {
        return $this->where('email', $email);
    }

        /**
     * Trouve un utilisateur par son nom d'utilisateur
     * @param string $username
     * @return object|null
     */
    public function findByUsername(string $username): ?object
    {
        return $this->where('username', $username);
    }



    /**
     * Trouve un utilisateur par son slug
     * @param string $slug
     * @return object|null
     */
    public function findBySlug(string $slug): ?object
    {
        return $this->where('slug', $slug);
    }

    /**
     * Trouve un utilisateur par token
     */
    public function findByToken(string $token): ?static
    {
        return $this->where('confirmation_token', $token);
    }

    /**
     * Confirme l'email d'un utilisateur
     */
    public function confirmEmail(string $token): bool
    {
        $user = $this->findByToken($token);
        if (!$user) {
            return false;
        }

        // Vérifier l'expiration du token
        $now = new \DateTime();
        $expires = new \DateTime($user->token_expires_at);

        if ($now > $expires) {
            return false;
        }

        return $this->update($user->id, [
            'email_confirmed' => true,
            'confirmation_token' => null,
            'token_expires_at' => null
        ]);
    }

    /**
     * Réinitialise le mot de passe
     */
    public function resetPassword(int $id): bool
    {
        $user = $this->find($id);
        if (!$user) {
            throw new Exception("Utilisateur introuvable.");
        }

        $password = match (strtolower($user->role)) {
            'admin' => 'Admin@1234#',
            'manager' => 'Manager@1234#',
            'superadmin' => 'SuperAdmin@1234#',
            default => 'User@1234#'
        };

        return $this->update($id, ['password' => password_hash($password, PASSWORD_BCRYPT)]);
    }

    /**
     * Active un utilisateur
     */
    public function setActive(int $id): bool
    {
        return $this->db->execute("UPDATE {$this->table}  SET is_active=:is_active WHERE id = :id", ['is_active' => true,'id'=>$id]);
    }

    /**
     * Désactive un utilisateur
     */
    public function setInactive(int $id): bool
    {
        return $this->db->execute("UPDATE {$this->table} SET is_active=:is_active WHERE id = :id", ['is_active' => false,'id'=>$id]);
    }

    /**
     * Change le rôle d'un utilisateur
     */
    public function changeRole(int $id, string $role): bool
    {
        $allowedRoles = ['staff', 'manager', 'admin', 'superadmin', 'user'];
        if (!in_array($role, $allowedRoles)) {
            throw new Exception("Rôle invalide");
        }

        return $this->update($id, ['role' => $role]);
    }

    /**
     * Recherche d'utilisateurs
     */
    public function search(string $keyword): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE first_name LIKE :kw 
                   OR last_name LIKE :kw 
                   OR name LIKE :kw 
                   OR email LIKE :kw 
                   OR username LIKE :kw
                ORDER BY created_at DESC";
        
        $this->db->query($sql, ['kw' => "%$keyword%"]);
        return $this->hydrateAll($this->getData() ?? []);
    }

    /**
     * Récupère les utilisateurs par rôle
     */
    public function getByRole(string $role): array
    {
        return $this->hydrateAll($this->where('role', $role)->getData() ?? []);
    }

    /**
     * Récupère les utilisateurs actifs
     */
    public function getActiveUsers(): array
    {
        return $this->hydrateAll($this->where('is_active', true)->getData() ?? []);
    }

    /**
     * Récupère les statistiques des utilisateurs
     */
    public function getStats(): array
    {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(is_active) as active,
                SUM(email_confirmed) as confirmed,
                COUNT(*) - SUM(is_active) as inactive,
                role,
                COUNT(*) as role_count
            FROM {$this->table} 
            GROUP BY role";

        $this->db->query($sql);
        return $this->getData() ?? [];
    }

    /**
     * Vérifie si l'utilisateur peut être supprimé
     */
    public function canDelete(int $id): bool
    {
        $user = $this->find($id);
        return $user && !$user->is_super_admin;
    }
}