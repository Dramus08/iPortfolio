<?php
namespace Admin\Models;
use Core\Model;
use Router\Router;

class PasswordResetService extends Model {
    private $router;
    
    public function __construct() {
        $this->router = Router::class;
    }
    
    /**
     * Demander une réinitialisation de mot de passe
     */
    public function requestReset($email) {
        try {
            $stmt = $this->db->query("CALL RequestPasswordReset(?)",[$email]);
           // $stmt->execute([$email]);
            $result =$this->db->getData();
            
            if ($result['success']) {
                return [
                    'success' => true,
                    'token' => $result['token'],
                    'user_id' => $result['user_id']
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Erreur inconnue'
                ];
            }
        } catch (\PDOException $e) {
            error_log("Erreur requestReset: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur serveur'
            ];
        }
    }
    
    /**
     * Vérifier la validité d'un token
     */
    public function verifyToken($token) {
        try {
            $stmt = $this->db->query("CALL VerifyResetToken(?)",[$token]);
            if($stmt)  $result = $$this->getData();
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur verifyToken: " . $e->getMessage());
            return [
                'valid' => 0,
                'message' => 'Erreur serveur'
            ];
        }
    }
    
    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword($token, $newPassword) {
        try {
            // Hasher le nouveau mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $this->db->query("CALL ResetPassword(?, ?)",[$token, $hashedPassword]);
            if($stmt) $result = $this->getData();
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur resetPassword: " . $e->getMessage());
            return [
                'success' => 0,
                'message' => 'Erreur serveur'
            ];
        }
    }
    
    /**
     * Envoyer l'email de réinitialisation
     */
    public function sendResetEmail($email, $token) {
        // Récupérer le nom d'utilisateur
        $userStmt = $this->db->query("SELECT username FROM users WHERE email = ?",[$email]);
        if ($userStmt) $user = $this->getData();
        
        // Générer le lien de réinitialisation
        $baseUrl = $this->getBaseUrl();
        $resetLink = $baseUrl . $this->router::route('reset_password') . "?token=" . urlencode($token);
        
        // Sujet et message
        $subject = "Réinitialisation de votre mot de passe";
        $message = "
        <html>
        <head>
            <title>Réinitialisation de mot de passe</title>
        </head>
        <body>
            <h2>Bonjour " . htmlspecialchars($user['username']) . ",</h2>
            <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
            <p>Cliquez sur le lien suivant pour créer un nouveau mot de passe :</p>
            <p><a href='$resetLink' style='background-color: #4361ee; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Réinitialiser mon mot de passe</a></p>
            <p><strong>Ce lien expirera dans 1 heure.</strong></p>
            <p>Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email.</p>
            <br>
            <p>Cordialement,<br>L'équipe de support</p>
        </body>
        </html>
        ";
        
        // En-têtes pour l'email HTML
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@votredomaine.com" . "\r\n";
        
        return mail($email, $subject, $message, $headers);
    }
    
    /**
     * Récupérer l'URL de base dynamiquement
     */
    private function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        return $protocol . '://' . $host . $path;
    }
}