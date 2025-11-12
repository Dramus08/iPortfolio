<?php
namespace Admin\Models;

use Core\Model; 
use Exception;
use PDO;

class Auth extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';

    // Champs spécifiques
    public int $id;
    public ?string $name;
    public ?string $first_name;
    public ?string $last_name;
    public string $email;
    public string $username;
    public ?string $password = null;
    public string $role = 'user';
    public bool $is_active = true; // True = utilisateur actif
    public bool $email_confirmed = false; // false = e-mail non confirmé
    public ?string $confirmation_token = null;
    public ?string $token_expires_at = null;
    public string $created_at;
    public string $updated_at;

    /**
     * Enregistre un nouvel utilisateur avec mot de passe hashé.
     */
    public function register(array $data):bool
    {
        
        if (empty($data['username']) || empty($data['password']) || empty($data['email'])) {
            //echo "Nom d'utilisateur, email et mot de passe sont requis.";

            throw new Exception("Nom d'utilisateur, email et mot de passe sont requis.");
        }

        // Vérifie si email déjà utilisé
        if ($this->first('email', $data['email'])) {
            //echo "Cette adresse e-mail est déjà utilisée.";
            throw new Exception("Cette adresse e-mail est déjà utilisée.");
        }

        // Vérifie si nom d'utilisateur déjà utilisé
        if ($this->first('username', $data['username'])) {
           // echo "Ce nom d'utilisateur est déjà utilisé.";
            throw new Exception("Ce nom d'utilisateur est déjà utilisé.");
        }
        
        // Génère token de confirmation
        $token = bin2hex(random_bytes(32));
        $expiresAt = (new \DateTime('+24 hours'))->format('Y-m-d H:i:s');
        
        // Hash du mot de passe
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['confirmation_token'] = $token;
        $data['token_expires_at'] = $expiresAt;
        $data['slug'] =$this->generateUniqueSlug($data['username']);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
       
        return $this->create($data);
        
    }

    /**
     * Authentifie un utilisateur (login)
     */
    public function login(string $us, string $password): mixed
    {
        $user = $this->first('email', $us) ?? $this->first('username', $us);

        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }
        $conn=$this->db->getConnection();
        $stmt= $conn->prepare("SELECT * FROM users WHERE id=:id");
        $sucess=$stmt->execute(['id'=>$user->id]);
        if ($sucess){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return [];
    }

       /**
     * Réinitialise le mot de passe
     */
    public function resetDefautPassword(int $id): bool
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

        return $this->db->execute("UPDATE {$this->table} SET password=:password WHERE id=:id", ['password' => password_hash($password, PASSWORD_BCRYPT),'id'=>$id]);
    }
    /**
     * Trouve un utilisateur par token
     */
    public function findByToken(string $token): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE confirmation_token = :token LIMIT 1";
        $res = $this->db->query($sql, ['token' => $token]);
        return $this->getData()[0] ?? null;
    }

    /**
     * Confirme l’email via le token (si valide)
     */
    public function confirmEmail(string $token): bool
    {
        $user = $this->findByToken($token);
        if (!$user) {
            return false;
        }

        $now = new \DateTime();
        $expires = new \DateTime($user['token_expires_at']);

        if ($now > $expires) {
            return false; // Token expiré
        }

        $sql = "UPDATE {$this->table} 
                SET email_confirmed = 1, confirmation_token = NULL, token_expires_at = NULL 
                WHERE id = :id";
        $this->db->query($sql, ['id' => $user['id']]);
        return true;
    }

        /**
     * Confirme l’email via le token (si valide)
     */
    public function confirmEmailResetPassword(string $token): bool
    {
        $user = $this->findByToken($token);
        if (!$user) {
            return false;
        }

        $now = new \DateTime();
        $expires = new \DateTime($user['token_expires_at']);

        if ($now > $expires) {
            return false; // Token expiré
        }
        return true;
    }

    /**
     * Regénère un token de confirmation si expiré
     */
    public function regenerateToken(int $userId): ?array
    {
        $newToken = bin2hex(random_bytes(32));
        $expiresAt = (new \DateTime('+24 hours'))->format('Y-m-d H:i:s');

        $this->update($userId, [
            'confirmation_token' => $newToken,
            'token_expires_at' => $expiresAt,
        ]);

        return [
            'token' => $newToken,
            'expires_at' => $expiresAt
        ];
    }

        /**
     * Regénère un token de confirmation si expiré
     */
    public function regenerateTokenResetPassword(int $userId): ?array
    {
        $newToken = bin2hex(random_bytes(32));
        $expiresAt = (new \DateTime('+2 hours'))->format('Y-m-d H:i:s');

        $this->update($userId, [
            'confirmation_token' => $newToken,
            'token_expires_at' => $expiresAt,
        ]);

        return [
            'token' => $newToken,
            'expires_at' => $expiresAt
        ];
    }

    /**
     * Vérifie si un utilisateur a confirmé son e-mail
     */
    public function isEmailConfirmed(string $email): bool
    {
        $user = $this->findByEmail($email);
        return $user && $user['email_confirmed'] == true;
    }

    public function updateEmailAndToken(int $id, string $newEmail, string $token): bool
{
    $sql = "UPDATE users SET email = ?, confirmation_token = ?, token_expires_at = NOW() + INTERVAL 1 DAY, email_confirmed = 0 WHERE id = ?";
    $stmt = $this->db->getConnection()->prepare($sql);
    return $stmt->execute([$newEmail, $token, $id]);
}

public function resetToken(int $id, string $token): bool
{
    $sql = "UPDATE users SET confirmation_token = ?, token_expires_at = NOW() + INTERVAL 1 DAY WHERE id = ?";
    return $this->db->execute($sql,[$token, $id]) ? true : false;
}

public function resetTokenPassword(int $id, string $token): bool
{
    $sql = "UPDATE users SET confirmation_token = ?, token_expires_at = NOW() + INTERVAL 2 HOUR WHERE id = ?";
    return $this->db->execute($sql,[$token, $id]) ? true : false;
}

   /**
     * Crée un utilisateur avec un rôle spécifique
     */
    public function createUser(array $data, string $role = 'user',bool $is_super_admin=false): bool
    {
        if (empty($data['email']) || empty($data['password'])) {
            throw new Exception("Email et mot de passe obligatoires.");
        }

        $sql = "INSERT INTO users (name, username, email, password, phone, role, confirmation_token, token_expires_at)
                VALUES (:name, :username, :email, :password, :phone, :role, :confirmation_token, :token_expires_at)";
        
        $stmt = $this->db->prepare($sql);
        $token = bin2hex(random_bytes(32));
        $expires = (new \DateTime('+24 hours'))->format('Y-m-d H:i:s');

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        return $stmt->execute([
            'name' => $data['name'] ?? '',
            'username' => $data['username'] ?? '',
            'email' => $data['email'],
            'password' => $hashedPassword,
            'phone' => $data['phone'] ?? '',
            'role' => $role,
            'confirmation_token' => $token,
            'is_super_admin' => $is_super_admin === true ? 1 : 0,
            'token_expires_at' => $expires
        ]);
    }

    /**
     * Crée un super administrateur
     */
    public function createSuperAdmin(array $data): bool
    {
        return $this->createUser($data, 'superadmin',true);
    }

     /**
     * Met à jour le mot de passe de l'utilisateur
     */
    public function updatePassword(int|string $userId, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatedAt = date('Y-m-d H:i:s');

        $sql = "UPDATE users SET password = ?, updated_at = ? WHERE id = ?";
        
        try {
            return $this->db->execute($sql,[$hashedPassword, $updatedAt, $userId]);
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour mot de passe: " . $e->getMessage());
            return false;
        }
    }

    /**
 * Récupère l'utilisateur actuellement connecté
 */
    public function getCurrentUser(): ?object
    {
        // Selon votre système d'authentification
        if (isset($_SESSION['user_id'])) {
            return $this->find($_SESSION['user_id']);
        }
        
        return null;
    }

    /**
     * Vérifie si le mot de passe a été utilisé récemment (optionnel - pour la sécurité)
     */
    public function isPasswordInHistory(int $userId, string $newPassword): bool
    {
        // Implémentation optionnelle pour vérifier l'historique des mots de passe
        // Cette méthode nécessite une table 'password_history'
        return false;
    }

     /**
     * Vérifie si un nom d'utilisateur existe déjà
     * @param string $username Le nom d'utilisateur à vérifier
     * @param int|null $excludeUserId ID de l'utilisateur à exclure (pour les mises à jour)
     * @return bool
     */
    public function usernameExists(string $username, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = :username";
        $params = ['username' => $username];

        if ($excludeUserId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeUserId;
        }

        $this->db->query($sql, $params);
        $result = $this->getData();
        
        return !empty($result) && $result[0]['count'] > 0;
    }

    /**
     * Vérifie si un email existe déjà
     * @param string $email L'email à vérifier
     * @param int|null $excludeUserId ID de l'utilisateur à exclure (pour les mises à jour)
     * @return bool
     */
    public function emailExists(string $email, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeUserId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeUserId;
        }

        $this->db->query($sql, $params);
        $result = $this->getData();
        
        return !empty($result) && $result[0]['count'] > 0;
    }

    /**
     * Vérifie si un slug existe déjà
     * @param string $slug Le slug à vérifier
     * @param int|null $excludeUserId ID de l'utilisateur à exclure (pour les mises à jour)
     * @return bool
     */
    public function slugExists(string $slug, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        $params = ['slug' => $slug];

        if ($excludeUserId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeUserId;
        }

        $this->db->query($sql, $params);
        $result = $this->getData();
        
        return !empty($result) && $result[0]['count'] > 0;
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
     * Trouve un utilisateur par son email
     * @param string $email
     * @return object|null
     */
    public function findByEmail(string $email): ?object
    {
        return $this->where('email', $email);
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
     * Vérifie si un utilisateur peut être créé avec ces identifiants
     * @param array $data Données de l'utilisateur
     * @param int|null $excludeUserId ID à exclure
     * @return array Tableau d'erreurs
     */
    public function validateUserCredentials(array $data, ?int $excludeUserId = null): array
    {
        $errors = [];

        // Validation username
        if (isset($data['username'])) {
            if (empty($data['username'])) {
                $errors[] = "Le nom d'utilisateur est requis.";
            } elseif ($this->usernameExists($data['username'], $excludeUserId)) {
                $errors[] = "Ce nom d'utilisateur est déjà utilisé.";
            }
        }

        // Validation email
        if (isset($data['email'])) {
            if (empty($data['email'])) {
                $errors[] = "L'email est requis.";
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Le format de l'email est invalide.";
            } elseif ($this->emailExists($data['email'], $excludeUserId)) {
                $errors[] = "Cet email est déjà utilisé.";
            }
        }

        // Validation slug
        if (isset($data['slug'])) {
            if (empty($data['slug'])) {
                $errors[] = "Le slug est requis.";
            } elseif ($this->slugExists($data['slug'], $excludeUserId)) {
                $errors[] = "Ce slug est déjà utilisé.";
            }
        }

        return $errors;
    }
}



