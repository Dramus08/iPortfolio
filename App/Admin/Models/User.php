<?php
namespace Admin\Models;

use Core\Model;

class User extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';

    // Champs spécifiques
    public int $id;
    public string $name;
    public string $email;
    public string $username;
    public string $role;
    public string $created_at;
    public string $updated_at;
    public ?string $password = null;
    public bool $is_active = true;
    public bool $email_confirmed  = false; // false = e-mail non confirmé

        /**
     * Trouve un utilisateur par e-mail
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ?: null;
    }


    
    /**
     * Trouve un utilisateur par token
     */
    public function findByToken(string $token): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE confirmation_token = :token LIMIT 1");
        $stmt->execute([':token' => $token]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Confirme l’e-mail si le token est encore valide
     */
    public function confirmEmail(string $token): bool
    {
        $user = $this->findByToken($token);
        if (!$user) return false;

        $now = new DateTime();
        $expires = new DateTime($user['token_expires_at']);

        if ($now > $expires) {
            return false; // Token expiré
        }

        $sql = "UPDATE users SET email_confirmed = 1, confirmation_token = NULL, token_expires_at = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $user['id']]);
    }

     /**
     * Regénère un token si expiré
     */
    public function regenerateToken(int $userId): array
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = (new DateTime('+24 hours'))->format('Y-m-d H:i:s');

        $sql = "UPDATE users SET confirmation_token = :token, token_expires_at = :expires WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':token' => $token, ':expires' => $expiresAt, ':id' => $userId]);

        return ['token' => $token, 'expires' => $expiresAt];
    }

      /**
     * Réinitialise le mot de passe par défaut selon le rôle.
     */
    public function resetPassword(int|string $id): bool
    {
        $user = $this->find($id);
        if (!$user) {
            throw new Exception("Utilisateur introuvable.");
        }

        $password = match (strtolower($user->role)) {
            'admin' => 'Admin@1234#',
            'manager' => 'Manager@1234#',
            default => 'User@1234#'
        };

        return $this->update($id, ['password' => password_hash($password, PASSWORD_BCRYPT)]);
    }

    /**
     * Active un utilisateur.
     */
    public function setActive(int|string $id): bool
    {
        return $this->update($id, ['is_active' => 1]);
    }

    /**
     * Désactive un utilisateur.
     */
    public function setInactive(int|string $id): bool
    {
        return $this->update($id, ['is_active' => 0]);
    }

    /**
     * Recherche d’utilisateurs par nom ou email.
     */
    public function search(string $keyword): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE :kw OR email LIKE :kw OR username LIKE :kw";
        $result = $this->db->query($sql, ['kw' => "%$keyword%"]);
        return $this->hydrateAll($this->getData() ?? []);
    }
}
