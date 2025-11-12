<?php
namespace Admin\Controllers;

use Core\Model\Model;


class AuthResetPasswordController {
    private $passwordResetService;
    
    public function __construct($db, $router) {
        $this->passwordResetService = new PasswordResetService($db, $router);
    }
    
    /**
     * Afficher le formulaire "Mot de passe oublié"
     */
    public function forgotPassword() {
        // Afficher votre template de demande de réinitialisation
        include 'views/auth/forgot-password.php';
    }
    
    /**
     * Traiter la demande de réinitialisation
     */
    public function processForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        
        if (!$email) {
            echo json_encode(['success' => false, 'message' => 'Adresse email invalide']);
            return;
        }
        
        // Vérifier la connexion Internet
        if (!$this->checkInternetConnection()) {
            echo json_encode(['success' => false, 'message' => 'Pas de connexion Internet. Veuillez vérifier votre connexion.']);
            return;
        }
        
        $result = $this->passwordResetService->requestReset($email);
        
        if ($result['success']) {
            // Envoyer l'email
            $emailSent = $this->passwordResetService->sendResetEmail($email, $result['token']);
            
            if ($emailSent) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Un email de réinitialisation a été envoyé à votre adresse.'
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer.'
                ]);
            }
        } else {
            echo json_encode($result);
        }
    }
    
    /**
     * Afficher le formulaire de réinitialisation
     */
    public function showResetForm() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            header('Location: ' . $this->router::route('forgot_password'));
            exit;
        }
        
        // Vérifier la validité du token
        $tokenValid = $this->passwordResetService->verifyToken($token);
        
        if (!$tokenValid['valid']) {
            // Rediriger avec message d'erreur
            $_SESSION['error'] = $tokenValid['message'];
            header('Location: ' . $this->router::route('forgot_password'));
            exit;
        }
        
        // Afficher le formulaire de réinitialisation
        include 'views/auth/reset-password.php';
    }
    
    /**
     * Traiter la réinitialisation du mot de passe
     */
    public function processResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($token) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis']);
            return;
        }
        
        if ($password !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas']);
            return;
        }
        
        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 8 caractères']);
            return;
        }
        
        $result = $this->passwordResetService->resetPassword($token, $password);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true, 
                'message' => 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.'
            ]);
        } else {
            echo json_encode($result);
        }
    }
    
    /**
     * Vérifier la connexion Internet
     */
    private function checkInternetConnection($host = 'www.google.com', $port = 80, $timeout = 5) {
        $connected = @fsockopen($host, $port, $errno, $errstr, $timeout);
        
        if ($connected) {
            fclose($connected);
            return true;
        }
        
        return false;
    }
}
