<?php
namespace Core;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    protected PHPMailer $mailer ;
    protected string $host = 'smtp.gmail.com';
    protected string $username = 'inouaismailcoding@gmail.com';
    protected string $password = 'hcxcywbjbwkrncbu';
    protected bool $smtpAuth = true;
    protected string $name = 'Inoua Ismail Coding';

    protected string $smtpSecure = '';
    protected int $port = 587;

    public function __construct(bool $exception = true) {
        $this->mailer = new PHPMailer(exceptions: $exception);
        $this->smtpSecure=PHPMailer::ENCRYPTION_STARTTLS;
    }

    /* Modifier le username : l'addresse email qui envoi le mail */
    public function setUsername(string $name){$this->username = $name;}

      /* Modifier le nom de lexpediteur  qui envoi le mail */
    public function setName(string $name){$this->name = $name;}

    /* Modifier le Host (quelle messagerie est utiliser) : lien de la messagerie */

    public function setHost(string $host_name){$this->host = $host_name;}

    /* Modifier le Mot de passe (quelle messagerie est utiliser) : lien de la messagerie */
    public function setPassword(string $password){  $this->password = $password;}


    /* Modifier l'authentification par smtp (quelle messagerie est utiliser) : lien de la messagerie */
    public function setSmtpAuth(bool $bool=true){  $this->smtpAuth = $bool;}
    public function getUsername(){  return $this->username;}
     public function getName(){  return $this->name;}

    public function getPassword(){  return $this->password;}

    public function getHost(){ return $this->host;}
    public function getSmtpAuth(){ return $this->smtpAuth;}

    public function getMailer(){ return $this->mailer;}

        /**
     * Configuration du protocole SMTP
     */

    public function configSMTP(){
        // Configuration SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->host;
            $this->mailer->SMTPAuth = $this->smtpAuth;
            $this->mailer->Username = $this->username;
            $this->mailer->Password = $this->password; // mot de passe d’application
            $this->mailer->SMTPSecure = $this->smtpSecure;
            $this->mailer->Port = $this->port;
    }

    /* Les Informations de l'expediteur */
    public function setFrom(?string $mail_expedition=null,?string $name=null,bool $auto=true){
        // detail expediteur
            return $this->mailer->setFrom($mail_expedition ?? $this->username, $name ?? $this->name,$auto);
    }

     /* Les Informations du destinataire */
    public function addAddress(string $mail_destination,?string $name=null){
        // detail destinataire
            return $this->mailer->addAddress($mail_destination , $name ?? '');
    }

    /* activation mail html et Remplissage du mail par HTML  */
    public function mailHTML(?string $subject=null,?string $body=null,$other=null){
        // Contenu HTML
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject ?? '';
        $this->mailer->Body =$body ?? '';
    }
    public function sendConfirmationEmail(string $email, string $name, string $link): bool
    {

        try {
            $this->configSMTP();

            // Expéditeur et destinataire
            $this->setFrom();
            $this->addAddress($email, $name);

            // Contenu HTML
            $body="
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
            $this->mailHTML("Confirmez votre inscription sur MonSite",$body);
            $this->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur envoi mail : " . $this->mailer->ErrorInfo);
            return false;
        }
    }

    public function send(){
        return $this->mailer->send();
    }
}