<?php
namespace Admin\Models;

use Core\Model; 
use Exception;

class Auth extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';

    // Champs spécifiques
    public int $id;
    public string $name;
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

            throw new Exception("Nom d'utilisateur, email et mot de passe sont requis.");
        }

        // Vérifie si email déjà utilisé
        if ($this->first('email', $data['email'])) {
            throw new Exception("Cette adresse e-mail est déjà utilisée.");
        }

        // Vérifie si nom d'utilisateur déjà utilisé
        if ($this->first('username', $data['username'])) {
            throw new Exception("Ce nom d'utilisateur est déjà utilisé.");
        }
        
        // Génère token de confirmation
        $token = bin2hex(random_bytes(32));
        $expiresAt = (new \DateTime('+24 hours'))->format('Y-m-d H:i:s');
        
        // Hash du mot de passe
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['email_confirmed'] = 0;
        $data['confirmation_token'] = $token;
        $data['token_expires_at'] = $expiresAt;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
       
        return $this->create($data) ? true : false;
        
    }

    /**
     * Authentifie un utilisateur (login)
     */
    public function login(string $us, string $password): ?array
    {
        $user = $this->first('email', $us) ?? $this->first('username', $us);

        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'email_confirmed' => $user->email_confirmed,
        ];
    }

    /**
     * Trouve un utilisateur par e-mail
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $res = $this->db->query($sql, ['email' => $email]);
        return $this->getData()[0] ?? null;
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
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$newEmail, $token, $id]) ? true : false;
}

public function resetToken(int $id, string $token): bool
{
    $sql = "UPDATE users SET confirmation_token = ?, token_expires_at = NOW() + INTERVAL 1 DAY WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$token, $id]) ? true : false;
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
}



