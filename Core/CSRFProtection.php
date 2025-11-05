<?php
namespace Core;

class CSRFProtection
{
    private int $tokenExpiry = 3600; // 1 heure

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $this->generateToken();
        }
    }

    /**
     * Génère un nouveau token
     */
    public function generateToken(): string
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie si le token est valide
     */
    public function verifyToken(?string $token): bool
    {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }

        if ($this->isTokenExpired()) {
            $this->clearToken();
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Vérifie si le token a expiré
     */
    private function isTokenExpired(): bool
    {
        return (time() - ($_SESSION['csrf_token_time'] ?? 0)) > $this->tokenExpiry;
    }

    /**
     * Supprime le token
     */
    public function clearToken(): void
    {
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
    }

    /**
     * Retourne un champ HTML caché avec le token
     */
    public function csrfInput(): string
    {
        return "<input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'] ?? $this->generateToken()) . "'>";
    }
}

