<?php
namespace Admin\Controllers;

use Core\Controller;
use Admin\Models\Auth;
use Generator;
use Router\Route;
use Router\Router;
use Core\Mailer;

class AuthController extends Controller
{
    protected ?string $layout = 'layout-auth'; // layout par défaut
    protected array $noLayoutViews = ["auth.change_password","auth.change_email","auth.change_phone","auth.reset_password",'auth.profile'];
    /**
     * Connexion utilisateur
     */

    protected function initSession(array $user){
        // Authentification réussie
        $_SESSION['auth'] = true;
        $_SESSION['is_super_admin'] = $user['is_super_admin'];
        $_SESSION['is_staff'] = $user['is_staff'];
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        foreach ($user as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
    public function login()
    {
        $this->verifyCSRF();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Auth();

            try {
                $identifier = trim($_POST['email'] ?? $_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                
                $user = $auth->login($identifier, $password);
                if (!$user) {
                    $this->flash('error', "Identifiants incorrects.");
                    $this->redirect(Router::route('login'));
                }
                $this->initSession($user);
                if (!$user['email_confirmed']) {
                    $_SESSION['pending_user'] = $user;
                    $this->flash('info', 'Veuillez confirmer votre e-mail avant connexion.');
                    $this->redirect(Router::route('waiting_confirmation_mail',['slug' => $user['slug']]));
                }

                $this->flash('success', 'Bienvenue ' . htmlspecialchars($user['name']) . ' 🎉');
                $this->redirect(Router::route('home'));
            } catch (\Exception $e) {
                $this->flash('error', $e->getMessage());
                $this->redirect(Router::route('login'));
            }
        }

        return $this->render('auth.login', ['csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

    /**
     * Enregistrement utilisateur + envoi mail de confirmation
     */
    public function register()
    {
        $this->verifyCSRF();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            try {

                $auth = new Auth();
                $success=$auth->register([
                    'name' => isset($_POST['name']) ? $_POST['name']: '',
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'phone' => $_POST['phone'] ?? '' 
                ]);

                if( $success ) {
                    // Récupère l'utilisateur pour le mail
                    //$user = $auth->findByEmail(htmlspecialchars($_POST['email']));
                    $user=$auth->login(htmlspecialchars($_POST['email']),htmlspecialchars($_POST['password']));
            
                    if ($user && isset($user['confirmation_token']) && !empty($user)) {
                        //$confirmationLink = \Core\Router::route('confirm_email').'?token=' . $user['confirmation_token'];
                        $link=$this->getBaseUrl().'/auth/confirm-email';
                        $confirmationLink=$link.'?token=' . $user['confirmation_token'];
                        // Envoi du mail de confirmation
                        if($this->checkInternetConnection()) $this->sendConfirmationEmail($user['email'], $user['name'], $confirmationLink);
                        $this->initSession($user);
                    }
                    $this->responseSuccess( 'Compte créé ! Vérifiez votre e-mail pour le confirmer.',Router::route('waiting_confirmation_mail',['slug'=>$user['slug']]));
                }
                
                
            } catch (\Exception $e) {
                return $this->responseError("ERREUR : ".$e->getMessage(), Router::route('register'));
            }
        }

        return $this->render('auth.register', ['csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

    /**
     * Confirmation de l’adresse e-mail via le lien
     */
    public function confirmEmail()
    {
        if (!isset($_GET['token'])) {
            return $this->responseError( 'Lien de confirmation invalide.',Router::route('login'));
        }

        $token = $_GET['token'];
        $auth = new Auth();
        $user=$auth->findByToken($_GET['token']);
        if(isset($user) && !empty($user)) {
            if ($auth->confirmEmail($token)) {
                $this->responseSuccess( 'Votre e-mail a bien été confirmé ! Vous pouvez maintenant vous connecter.',Router::route('login'));
            } else {
                return $this->responseError( 'Lien invalide ou expiré. Veuillez vous réinscrire.',Router::route('register'));
            }
        }
    }



    /**
     * Déconnexion
     */
    public function logout()
    {
        session_destroy();
        $this->flash('info', 'Déconnexion réussie 👋');
        $this->redirect(Router::route('login'));
    }

    /**
     * Page d’attente après inscription
     */
    public function waitingConfirmation(string $slug)
    {
       
        $this->requireAuth();
        $user = (new Auth())->findBySlug($slug);
        return $this->render('auth.waiting_confirmation',['user' => $user ,'csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

   

    public function profile(string $slug)
    {
        $this->requireAuth();
        $user = (new Auth())->findBySlug($slug);
        return $this->render('auth.auth_profile',['user' => $user ,'csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

       public function auth_profile(string $slug)
    {
        $this->requireAuth();
        $user = (new Auth())->findBySlug($slug);
        return $this->render('auth.auth_profile',['user' => $user ,'csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

    /**
     * Envoi du mail de confirmation via PHPMailer
     */
    private function sendConfirmationEmail(string $email, string $name, string $link): bool
    {
        $mail = new Mailer();
        return $mail->sendConfirmationEmail($email, $name, $link);

    }

    private function sendConfirmationMailResetPassword(string $email, string $name, string $link): bool
    {
        $mail = new Mailer();
        return $mail->sendConfirmationEmail($email, $name, $link);
    }

    /**
 * ✅ Changement d’adresse e-mail + génération d’un nouveau token
 */
    public function changeEmail(string $slug)
    {
        $url=$this->getBaseUrl();

       if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $this->requireAuth();
        $newEmail = trim($_POST['email'] ?? '');
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->responseError("Adresse e-mail invalide.",Router::route("change_email",['slug'=> $slug]));
        }

        $auth = new Auth();
        $user = $auth->findBySlug($slug);
        // Vérifie si utilisateur existe
        if (!$user) {
            return $this->responseError("Utilisateur introuvable.",Router::route("change_email",['slug'=> $slug]));
        }

        // Vérifie si l’email existe déjà
        if ($auth->findByEmail($newEmail)) {
            return $this->responseError("Cette adresse e-mail est déjà utilisée.",Router::route("change_email",['slug'=> $slug]));
        }

        // Met à jour l’adresse et régénère le token
        $newToken = bin2hex(random_bytes(32));
        $auth->updateEmailAndToken($user->id, $newEmail, $newToken);
        $this->initSession((array)$user);
        
        // Renvoi de l’e-mail
        $link = $this->getBaseUrl()."auth/".$slug."/confirm-email?token={$newToken}";
        if($this->checkInternetConnection()) $this->sendConfirmationEmail($newEmail, $user->name, $link);

        return $this->responseSuccess( "Nouvelle adresse enregistrée. Vérifiez vos e-mails.",Router::route('waiting_confirmation_mail', ['slug' => $slug]));
       }
       $this->render('auth.change_email',['user'=>$_SESSION['user'],"Router"=>Router::class,'baseUrl'=>$url]);
    }

    /**
     * ✅ Renvoi du token de confirmation (sans changer l’adresse)
     */
    public function resendToken(string $slug)
    {
        //$this->verifyCSRF();
        $this->requireAuth();

        $auth = new Auth();
        $user = $auth->findBySlug($slug);

        if (!$user) {
            return $this->responseError( "Utilisateur introuvable.",Router::route('register'));
        }

        $newToken = bin2hex(random_bytes(32));
        $auth->resetToken($user->id, $newToken);

        $link = $this->getBaseUrl()."auth/".$user->slug."/confirm-email?token={$newToken}";
        if($this->checkInternetConnection()) $this->sendConfirmationEmail($user->email, $user->name, $link);

        return $this->responseSuccess( "Un nouveau lien de confirmation a été envoyé !",Router::route('waiting_confirmation_mail', ['slug' => $slug]));
    }

    

    /**
     * Affiche le formulaire de modification du mot de passe
     */


    /**
 * Vérifie si l'utilisateur connecté est bien le propriétaire du compte
 */
    private function isOwner(int|string $userId): bool
    {
        $currentUser = (new Auth())->getCurrentUser();
        
        if (!$currentUser) {
            return false;
        }
        
        // Vérifier que l'ID du compte à modifier correspond à l'ID de l'utilisateur connecté
        return (int)$currentUser->id === (int)$userId;
    }

    /**
     * Valide les données de changement de mot de passe
     */
    private function validatePasswordChange(?string $currentPassword, ?string $newPassword, ?string $confirmPassword): array
    {
        $errors = [];

        if (empty($currentPassword)) {
            $errors[] = 'Le mot de passe actuel est requis.';
        }

        if (empty($newPassword)) {
            $errors[] = 'Le nouveau mot de passe est requis.';
        } elseif (strlen($newPassword) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        } elseif (!preg_match('/[a-z]/', $newPassword)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre minuscule.';
        } elseif (!preg_match('/[A-Z]/', $newPassword)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre majuscule.';
        } elseif (!preg_match('/[0-9]/', $newPassword)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre.';
        } elseif (!preg_match('/[^A-Za-z0-9]/', $newPassword)) {
            $errors[] = 'Le mot de passe doit contenir au moins un caractère spécial.';
        }

        if (empty($confirmPassword)) {
            $errors[] = 'La confirmation du mot de passe est requise.';
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        return $errors;
    }



        public function showChangePasswordForm(string $slug)
    {
        // Vérifier que l'utilisateur est connecté
            $this->requireAuth();
            $user=(new Auth())->findBySlug($slug);
        // Vérifier que l'utilisateur modifie son propre compte
        if (!$this->isOwner($user->id)) {
            return $this->responseError('Vous n\'êtes pas autorisé à modifier ce compte.',Router::route('index'));
        }

        $data = [
            'title' => 'Modifier le mot de passe',
            'user' => $user,
            'csrf_token' => $this->csrfToken(),
            'Router' => Router::class,
        ];

        $this->render('auth.change_password', $data);
    }

    /**
     * Traite la modification du mot de passe
     */
    public function changePassword(string $slug)
    {
        // Vérifier que l'utilisateur est connecté
        $this->requireAuth();
        $user=(new Auth())->findBySlug($slug);

        // Vérifier que l'utilisateur modifie son propre compte
        if (!$this->isOwner($user->id)) {
            return $this->responseError( 'Vous n\'êtes pas autorisé à modifier ce compte.',Router::route('user_profile',['slug'=> $slug]));
            
        }

        // Valider le token CSRF
        if (!$this->verifyCSRF()) {
            return $this->responseError( 'Token de sécurité invalide.',Router::route('change_password',['slug'=> $slug]));
            //Router::route('change_password',['slug'=> $slug]);
            //return;
        }
        $currentPassword = htmlspecialchars($_POST['current_password']  ?? '');
        $newPassword = htmlspecialchars($_POST['new_password'] ?? '');
        $confirmPassword = htmlspecialchars($_POST['confirm_password'] ?? '');

        // Validation des données
        $errors = $this->validatePasswordChange($currentPassword, $newPassword, $confirmPassword);

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->flash('error', $error);
            }
            Router::redirect('change_password',['slug'=> $slug]);
            return;
        }

        // Vérifier l'ancien mot de passe
        if (!password_verify($currentPassword, $user->password)) {
            return $this->responseError( 'Le mot de passe actuel est incorrect.',Router::route('change_password',['slug'=> $slug]));
        }

        // Vérifier que le nouveau mot de passe est différent de l'ancien
        if (password_verify($newPassword, $user->password)) {
            return $this->responseError( 'Le nouveau mot de passe doit être différent de l\'ancien.',Router::route('change_password',['slug'=> $slug]));
        }

        // Mettre à jour le mot de passe
        try {
            $userModel = new Auth();
            $success = $userModel->updatePassword($user->id, $newPassword);

            if ($success) {
                // Déconnecter l'utilisateur de tous les appareils (optionnel)
                // Auth::logoutOtherDevices($newPassword);
                
                $this->responseSuccess( 'Votre mot de passe a été mis à jour avec succès.',Router::route('user_profile',['slug'=> $slug]));
            } else {
                $this->responseError( 'Une erreur est survenue lors de la mise à jour du mot de passe.',Router::route('change_password',['slug'=> $slug]));
            }
        } catch (\Exception $e) {
            return $this->responseError( 'Erreur technique: ' . $e->getMessage(),Router::route('change_password',['slug'=> $slug]));
        }
    }

     /**
     * Page pour recuperer son compte par Email
     */
    public function forgotPassword()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $email= htmlspecialchars($_POST['email']);
            $auth   = new Auth();
            $user=$auth->findByEmail($email);
            
            if ($user === null) {
                return $this->responseError( 'Utilisateur Introuvable !',Router::route('forgot_password'));
                
            }
            var_dump($user->confirmation_token);
            return $this->resendTokenResetPassword( $user->confirmation_token );
            
            //$this->responseSuccess('veuillez consulter votre addresse mail et suivre le lien !',Router::route('waiting_password_reset',['slug'=>$user->slug]));

            
        }
        return $this->render('auth.forgot_password',['csrf' => $this->csrfToken(),'Router' => Router::class]);

        
    }
    
    public function WaitingPasswordReset(string $slug){
        $auth   = new Auth();
        $user=$auth->findBySlug($slug);
        return $this->render('auth.waiting_password_reset',['user'=>$user,"Router"=>Route::class]);
    }
    
    public function resetDefaultPassword(string $slug){
       $auth   = new Auth();
        $user=$auth->findBySlug($slug);
        

        if($_SERVER['REQUEST_METHOD']==="POST"){
            
            $success=$auth->resetDefautPassword($user->id);
            if($success) return $this->responseSuccess("Votre Mot de passe a ete reinitialiser par default !",Router::route("login"));
            else return $this->responseError("Erreur lors du reinitialisation du mot de passe !",Router::route("waiting_password_reset",['slug'=>$slug]));
            //
        }
        //return $this->render('auth.reset_default_password',['user'=>$user,"Router"=>Route::class]);
    }

        /**
     * Page d’attente après inscription
     */
    public function waitingConfirmationResetPassword(string $token)
    {
       
        $this->requireAuth();
        $user = (new Auth())->findBySlug($token);
        return $this->render('auth.waiting_confirmation_mail_reset_password',['user' => $user ,'csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

        /**
     * Confirmation de l’adresse e-mail via le lien pour reinitialiser le mot de passe
     */
    public function confirmEmailResetPassword()
    {
        var_dump($_GET);
        if (!isset($_GET['token'])) {
            return ;//$this->responseError( 'Lien de confirmation invalide.',Router::route('login'));
        }

        $token = $_GET['token'];
        $auth = new Auth();
        $user=$auth->findByToken($_GET['token']);
        if(isset($user) && !empty($user)) {
            if ($auth->confirmEmailResetPassword($token)) {
                $this->responseSuccess( 'Votre mail a bien été confirmé ! changer votre mot de passe .',Router::route('change_password_by_token_email', ['token'=> $token]));
            } else {
                return $this->responseError( 'Lien invalide ou expiré. Veuillez vous réinscrire.',Router::route('resend_token_reset_password',['token'=> $token]));
            }
        }
    }

        /**
     * ✅ Renvoi du token de confirmation (sans changer l’adresse)
     */
    public function resendTokenResetPassword(string $token)
    {
        //$this->verifyCSRF();
        //$this->requireAuth();

        $auth = new Auth();
        $user = (object) $auth->findByToken($token);

        if (!$user) {
            return $this->responseError( "Utilisateur introuvable.",Router::route('register'));
        }

        $newToken = bin2hex(random_bytes(32));
        $auth->resetTokenPassword($user->id, $newToken);

        $link = $this->getBaseUrl()."auth/".$token."/confirm-email-reset-password?token={$newToken}";
        if($this->checkInternetConnection()) $this->sendConfirmationMailResetPassword($user->email, $user->name, $link);

        return $this->responseSuccess( "Un nouveau lien de confirmation a été envoyé !",Router::route('waiting_confirmation_mail_reset_password', ['token' => $token]));
    }

            /**
     * Traite la modification du mot de passe
     */
        public function changePasswordByTokenEmail(string $token)
    {
        if($_SERVER['REQUEST_METHOD']==='POST'){

       
        
        // Valider le token CSRF
        if (!$this->verifyCSRF()) {
            return $this->responseError( 'Token de sécurité invalide.',Router::route('change_password_by_token_email',['token'=> $token]));
            //Router::route('change_password',['slug'=> $slug]);
            //return;
        }

        // Vérifier que l'utilisateur existe
        $user=(object) (new Auth())->findByToken($token);
        if (!$user) {
            return $this->responseError( 'Utilisateur Introuvable.',Router::route('login'));
        }

        $now = new \DateTime();
        $expires = new \DateTime($user->token_expires_at);

        if ($now > $expires) {
            //return false; // Token expiré
            $this->confirmEmailResetPassword();
            return $this->responseError( 'Le Lien a expire.</br> Un nouveau  lien de confirmation a été envoyé veuillez consulter votre addresse de messagerie et suivre le lien !</br> le lien est valide pendant deux heures !',Router::route('waiting_confirmation_mail_reset_password', ['token'=> $token]));
        }


        $currentPassword = htmlspecialchars($_POST['current_password']  ?? '');
        $newPassword = htmlspecialchars($_POST['new_password'] ?? '');
        $confirmPassword = htmlspecialchars($_POST['confirm_password'] ?? '');

        // Validation des données
        $errors = $this->validatePasswordChange($currentPassword, $newPassword, $confirmPassword);

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->flash('error', $error);
            }
            Router::redirect('change_password_by_token_email',['token'=> $token]);
            return;
        }

        // Vérifier l'ancien mot de passe
        if (!password_verify($currentPassword, $user->password)) {
            return $this->responseError( 'Le mot de passe actuel est incorrect.',Router::route('change_password_by_token_email',['token'=> $token]));
        }

        // Vérifier que le nouveau mot de passe est différent de l'ancien
        if (password_verify($newPassword, $user->password)) {
            return $this->responseError( 'Le nouveau mot de passe doit être différent de l\'ancien.',Router::route('change_password_by_token_email',['token'=> $token]));
        }

        // Mettre à jour le mot de passe
        try {
            $userModel = new Auth();
            $success = $userModel->updatePassword($user->id, $newPassword);

            if ($success) {
                // Déconnecter l'utilisateur de tous les appareils (optionnel)
                // Auth::logoutOtherDevices($newPassword);
                
                $this->responseSuccess( 'Votre mot de passe a été mis à jour avec succès. Veuillez vous connecter !',Router::route('login'));
            } else {
                $this->responseError( 'Une erreur est survenue lors de la mise à jour du mot de passe.',Router::route('change_password_by_token_email',['token'=> $token]));
            }
        } catch (\Exception $e) {
            return $this->responseError( 'Erreur technique: ' . $e->getMessage(),Router::route('change_password_by_token_email',['token'=> $token]));
        }
        }
        return $this->render('auth.change_password_by_token_email',['token'=>$user->confirmation_token ]);
    }



}
