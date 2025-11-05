<?php
namespace Admin\Controllers;

use Core\Controller;
use Admin\Models\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Router\Router;

class AuthController extends Controller
{
    /**
     * Connexion utilisateur
     */
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
                    //exit();
                }

                if (!$user['email_confirmed']) {
                    $_SESSION['pending_user'] = $user;
                    $this->flash('info', 'Veuillez confirmer votre e-mail avant connexion.');
                    $this->redirect(Router::route('waiting_confirmation_mail',['id' => $user['id']]));
                }

                // Authentification réussie
                $_SESSION['auth'] = true;
                $_SESSION['is_super_admin'] = true;
                $_SESSION['is_staff'] = true;
                $_SESSION['user'] = $user;
                $_SESSION['role'] = $user['role'];

                $this->flash('success', 'Bienvenue ' . htmlspecialchars($user['name']) . ' 🎉');
                $this->redirect(Router::route('index'));
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
                $auth->register([
                    'name' => $_POST['name'],
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'phone' => $_POST['phone'] ?? '',
                ]);

                
                    // Récupère l'utilisateur pour le mail
                $user = $auth->findByEmail($_POST['email']);
            
                if ($user && isset($user['confirmation_token'])) {
                    //$confirmationLink = \Core\Router::route('confirm_email').'?token=' . $user['confirmation_token'];
                    $l="localhost/iportfolio".'/auth/confirm-email';
                    $confirmationLink=$l.'?token=' . $user['confirmation_token'];
                    // Envoi du mail de confirmation
                    //$this->sendConfirmationEmail($user['email'], $user['name'], $confirmationLink);
                }
                $this->flash('success', 'Compte créé ! Vérifiez votre e-mail pour le confirmer.');
                
                $this->redirect(Router::route('waiting_confirmation_mail',['id'=>$user['id']]));
                
            } catch (\Exception $e) {
                $this->flash('error', $e->getMessage());
                $this->redirect(Router::route('register'));
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
            $this->flash('error', 'Lien de confirmation invalide.');
            $this->redirect(Router::route('login'));
        }

        $token = $_GET['token'];
        $auth = new Auth();

        if ($auth->confirmEmail($token)) {
            $this->flash('success', 'Votre e-mail a bien été confirmé ! Vous pouvez maintenant vous connecter.');
            $this->redirect(Router::route('login'));
        } else {
            $this->flash('error', 'Lien invalide ou expiré. Veuillez vous réinscrire.');
            $this->redirect(Router::route('register'));
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
    public function waitingConfirmation(int|string $id=25)
    {
        $user = (new Auth())->find($id);
        return $this->render('auth.waiting_confirmation',['user' => $user ,'csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

    public function profile(int $id)
    {
        $user = (new Auth())->find($id);
        return $this->render('auth.profile',['user' => $user ,'csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

    /**
     * Envoi du mail de confirmation via PHPMailer
     */
    private function sendConfirmationEmail(string $email, string $name, string $link): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'inouaismailcoding@gmail.com';
            $mail->Password = 'hcxcywbjbwkrncbu'; // mot de passe d’application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Expéditeur et destinataire
            $mail->setFrom('inouaismailcoding@gmail.com', 'IIcoding');
            $mail->addAddress($email, $name);

            // Contenu HTML
            $mail->isHTML(true);
            $mail->Subject = "Confirmez votre inscription sur MonSite";
            $mail->Body = "
                <h2>Bienvenue, {$name} 👋</h2>
                <p>Merci de vous être inscrit sur <strong>MonSite</strong>.</p>
                <p>Veuillez confirmer votre adresse e-mail en cliquant sur le lien ci-dessous :</p>
                <p>
                    <a href='{$link}' 
                       style='display:inline-block;background:#4CAF50;color:white;
                       padding:10px 15px;text-decoration:none;border-radius:5px;'>
                       Confirmer mon compte
                    </a>
                </p>
                <p>Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
                <p>{$link}</p>
                <p><small>Ce lien expirera dans 24 heures.</small></p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur envoi mail : " . $mail->ErrorInfo);
            return false;
        }
    }

    /**
 * ✅ Changement d’adresse e-mail + génération d’un nouveau token
 */
public function changeEmail(int $id)
{
    //$this->verifyCSRF();

    $newEmail = trim($_POST['new_email'] ?? '');
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $this->flash('error', "Adresse e-mail invalide.");
        return $this->redirect(Router::route('waiting_confirmation_mail', ['id' => $id]));
    }

    $auth = new Auth();
    $user = $auth->find($id);

    if (!$user) {
        $this->flash('error', "Utilisateur introuvable.");
        return $this->redirect(Router::route('register'));
    }

    // Vérifie si l’email existe déjà
    if ($auth->findByEmail($newEmail)) {
        $this->flash('error', "Cette adresse e-mail est déjà utilisée.");
        return $this->redirect(Router::route('waiting_confirmation_mail', ['id' => $id]));
    }

    // Met à jour l’adresse et régénère le token
    $newToken = bin2hex(random_bytes(32));
    $auth->updateEmailAndToken($id, $newEmail, $newToken);

    // Renvoi de l’e-mail
    $link = "http://localhost/iportfolio/confirm-email?token={$newToken}";
    $this->sendConfirmationEmail($newEmail, $user->name, $link);

    $this->flash('success', "Nouvelle adresse enregistrée. Vérifiez vos e-mails.");
    $this->redirect(Router::route('waiting_confirmation_mail', ['id' => $id]));
}

/**
 * ✅ Renvoi du token de confirmation (sans changer l’adresse)
 */
public function resendToken(int $id)
{
    //$this->verifyCSRF();

    $auth = new Auth();
    $user = $auth->find($id);

    if (!$user) {
        $this->flash('error', "Utilisateur introuvable.");
        return $this->redirect(Router::route('register'));
    }

    $newToken = bin2hex(random_bytes(32));
    $auth->resetToken($id, $newToken);

    $link = "http://localhost/iportfolio/confirm-email?token={$newToken}";
    $this->sendConfirmationEmail($user->email, $user->name, $link);

    $this->flash('success', "Un nouveau lien de confirmation a été envoyé !");
    $this->redirect(Router::route('waiting_confirmation_mail', ['id' => $id]));
}


}



