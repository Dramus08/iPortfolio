<?php
namespace Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    protected PHPMailer $mailer;
    protected array $config;
    protected array $templates;
    
    public function __construct(array $config = [], bool $exceptions = true)
    {
        $this->mailer = new PHPMailer($exceptions);
        $this->loadDefaultConfig();
        $this->mergeConfig($config);
        $this->loadDefaultTemplates();
        $this->initialize();
    }

    /**
     * Configuration par défaut
     */
    protected function loadDefaultConfig(): void
    {
        $this->config = [
            'host' => $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com',
            'username' => $_ENV['SMTP_USERNAME'] ?? 'inouaismailcoding@gmail.com',
            'password' => $_ENV['SMTP_PASSWORD'] ?? 'hcxcywbjbwkrncbu',
            'name' => $_ENV['SMTP_FROM_NAME'] ?? 'Inoua Ismail Coding',
            'smtp_auth' => true,
            'smtp_secure' => PHPMailer::ENCRYPTION_STARTTLS,
            'port' => 587,
            'charset' => 'UTF-8',
            'debug' => $_ENV['SMTP_DEBUG'] ?? false,
            'debug_level' => $_ENV['SMTP_DEBUG_LEVEL'] ?? SMTP::DEBUG_OFF
        ];
    }

    /**
     * Templates d'emails par défaut
     */
    protected function loadDefaultTemplates(): void
    {
        $this->templates = [
            'confirmation' => [
                'subject' => 'Confirmez votre inscription sur {app_name}',
                'template' => __DIR__ . '/Templates/confirmation.html'
            ],
            'password_reset' => [
                'subject' => 'Réinitialisation de votre mot de passe',
                'template' => __DIR__ . '/Templates/password_reset.html'
            ],
            'welcome' => [
                'subject' => 'Bienvenue sur {app_name}',
                'template' => __DIR__ . '/Templates/welcome.html'
            ],
            'notification' => [
                'subject' => 'Notification - {app_name}',
                'template' => __DIR__ . '/Templates/notification.html'
            ]
        ];
    }

    /**
     * Fusionner avec configuration personnalisée
     */
    public function mergeConfig(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * Initialisation du mailer
     */
    protected function initialize(): void
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->config['host'];
        $this->mailer->SMTPAuth = $this->config['smtp_auth'];
        $this->mailer->Username = $this->config['username'];
        $this->mailer->Password = $this->config['password'];
        $this->mailer->SMTPSecure = $this->config['smtp_secure'];
        $this->mailer->Port = $this->config['port'];
        $this->mailer->CharSet = $this->config['charset'];
        
        // Debug
        if ($this->config['debug']) {
            $this->mailer->SMTPDebug = $this->config['debug_level'];
        }
    }

    /**
     * Configuration rapide avec un tableau
     */
    public function configure(array $config): self
    {
        return $this->mergeConfig($config)->initialize();
    }

    /**
     * Définir l'expéditeur
     */
    public function setFrom(string $email = null, string $name = null, bool $auto = true): self
    {
        $this->mailer->setFrom(
            $email ?? $this->config['username'],
            $name ?? $this->config['name'],
            $auto
        );
        return $this;
    }

    /**
     * Ajouter un destinataire
     */
    public function addAddress(string $email, string $name = ''): self
    {
        $this->mailer->addAddress($email, $name);
        return $this;
    }

    /**
     * Ajouter plusieurs destinataires
     */
    public function addAddresses(array $addresses): self
    {
        foreach ($addresses as $email => $name) {
            if (is_numeric($email)) {
                $this->addAddress($name);
            } else {
                $this->addAddress($email, $name);
            }
        }
        return $this;
    }

    /**
     * Ajouter une pièce jointe
     */
    public function addAttachment(string $path, string $name = ''): self
    {
        $this->mailer->addAttachment($path, $name);
        return $this;
    }

    /**
     * Ajouter une réponse à
     */
    public function addReplyTo(string $email, string $name = ''): self
    {
        $this->mailer->addReplyTo($email, $name);
        return $this;
    }

    /**
     * Ajouter en CC
     */
    public function addCC(string $email, string $name = ''): self
    {
        $this->mailer->addCC($email, $name);
        return $this;
    }

    /**
     * Ajouter en BCC
     */
    public function addBCC(string $email, string $name = ''): self
    {
        $this->mailer->addBCC($email, $name);
        return $this;
    }

    /**
     * Définir le contenu HTML
     */
    public function setHTMLContent(string $subject, string $body, string $altBody = ''): self
    {
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        $this->mailer->AltBody = $altBody ?: strip_tags($body);
        return $this;
    }

    /**
     * Définir le contenu texte
     */
    public function setTextContent(string $subject, string $body): self
    {
        $this->mailer->isHTML(false);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        return $this;
    }

    /**
     * Charger un template
     */
    public function loadTemplate(string $templateName, array $data = []): string
    {
        if (!isset($this->templates[$templateName])) {
            throw new \InvalidArgumentException("Template '$templateName' non trouvé");
        }

        $templateConfig = $this->templates[$templateName];
        
        if (file_exists($templateConfig['template'])) {
            $content = file_get_contents($templateConfig['template']);
        } else {
            $content = $this->getDefaultTemplate($templateName);
        }

        return $this->replaceTemplateVariables($content, $data);
    }

    /**
     * Templates par défaut intégrés
     */
    protected function getDefaultTemplate(string $templateName): string
    {
        $defaultTemplates = [
            'confirmation' => '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Confirmation d\'email</title>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .button { display: inline-block; background: #4361ee; color: white; 
                                padding: 12px 24px; text-decoration: none; border-radius: 5px; }
                        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; 
                                font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>Bienvenue, {name} 👋</h2>
                        <p>Merci de vous être inscrit sur <strong>{app_name}</strong>.</p>
                        <p>Veuillez confirmer votre adresse e-mail en cliquant sur le lien ci-dessous :</p>
                        <p><a href="{link}" class="button">Confirmer mon compte</a></p>
                        <p>Si le bouton ne fonctionne pas, copiez ce lien :<br>{link}</p>
                        <p><small>Ce lien expirera dans 24 heures.</small></p>
                        <div class="footer">
                            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                        </div>
                    </div>
                </body>
                </html>
            ',
            'password_reset' => '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Réinitialisation de mot de passe</title>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .button { display: inline-block; background: #dc3545; color: white; 
                                padding: 12px 24px; text-decoration: none; border-radius: 5px; }
                        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; 
                                font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>Réinitialisation de mot de passe</h2>
                        <p>Bonjour {name},</p>
                        <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
                        <p>Cliquez sur le lien suivant pour créer un nouveau mot de passe :</p>
                        <p><a href="{link}" class="button">Réinitialiser mon mot de passe</a></p>
                        <p>Si le bouton ne fonctionne pas, copiez ce lien :<br>{link}</p>
                        <p><small>Ce lien expirera dans 1 heure.</small></p>
                        <p>Si vous n\'avez pas demandé cette réinitialisation, ignorez cet email.</p>
                        <div class="footer">
                            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                        </div>
                    </div>
                </body>
                </html>
            '
        ];

        return $defaultTemplates[$templateName] ?? '';
    }

    /**
     * Remplacer les variables dans le template
     */
    protected function replaceTemplateVariables(string $content, array $data): string
    {
        $defaultData = [
            'app_name' => $_ENV['APP_NAME'] ?? 'MonSite',
            'current_year' => date('Y'),
            'base_url' => $_ENV['BASE_URL'] ?? 'http://localhost'
        ];

        $allData = array_merge($defaultData, $data);

        foreach ($allData as $key => $value) {
            $content = str_replace("{{$key}}", $value, $content);
        }

        return $content;
    }

    /**
     * Envoyer un email avec template
     */
    public function sendTemplate(string $templateName, array $data, string $toEmail = null, string $toName = ''): bool
    {
        try {
            $templateConfig = $this->templates[$templateName] ?? null;
            if (!$templateConfig) {
                throw new \InvalidArgumentException("Template '$templateName' non configuré");
            }

            $subject = $this->replaceTemplateVariables($templateConfig['subject'], $data);
            $body = $this->loadTemplate($templateName, $data);

            $this->setFrom()
                 ->setHTMLContent($subject, $body);

            if ($toEmail) {
                $this->addAddress($toEmail, $toName);
            }

            return $this->send();

        } catch (Exception $e) {
            error_log("Erreur envoi email template '$templateName': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un email de confirmation
     */
    public function sendConfirmationEmail(string $email, string $name, string $link, array $data = []): bool
    {
        $templateData = array_merge([
            'name' => $name,
            'link' => $link
        ], $data);

        return $this->sendTemplate('confirmation', $templateData, $email, $name);
    }

    /**
     * Envoyer un email de réinitialisation de mot de passe
     */
    public function sendPasswordResetEmail(string $email, string $name, string $link, array $data = []): bool
    {
        $templateData = array_merge([
            'name' => $name,
            'link' => $link
        ], $data);

        return $this->sendTemplate('password_reset', $templateData, $email, $name);
    }

    /**
     * Envoyer un email de bienvenue
     */
    public function sendWelcomeEmail(string $email, string $name, array $data = []): bool
    {
        $templateData = array_merge(['name' => $name], $data);
        return $this->sendTemplate('welcome', $templateData, $email, $name);
    }

    /**
     * Ajouter un template personnalisé
     */
    public function addTemplate(string $name, string $subject, string $templatePath = null): self
    {
        $this->templates[$name] = [
            'subject' => $subject,
            'template' => $templatePath
        ];
        return $this;
    }

    /**
     * Envoyer l'email
     */
    public function send(): bool
    {
        try {
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Erreur envoi email: " . $this->mailer->ErrorInfo);
            throw new MailException($this->mailer->ErrorInfo, $e->getCode(), $e);
        }
    }

    /**
     * Réinitialiser pour un nouvel email
     */
    public function clear(): self
    {
        $this->mailer->clearAddresses();
        $this->mailer->clearCCs();
        $this->mailer->clearBCCs();
        $this->mailer->clearAttachments();
        $this->mailer->clearReplyTos();
        $this->mailer->clearCustomHeaders();
        return $this;
    }

    /**
     * Obtenir les erreurs
     */
    public function getError(): string
    {
        return $this->mailer->ErrorInfo;
    }

    /**
     * Obtenir l'instance PHPMailer
     */
    public function getMailer(): PHPMailer
    {
        return $this->mailer;
    }
}

/**
 * Exception personnalisée pour les emails
 */
class MailException extends \Exception
{
    public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Erreur d'envoi d'email: $message", $code, $previous);
    }
}

class PathHelper
{
    public static function toAbsolute(string $relativePath): string
    {
        // Si le chemin est déjà absolu (commence par C:\, D:\, etc.)
        if (preg_match('/^[A-Z]:\\\\/', $relativePath) || 
            preg_match('/^[A-Z]:\//', $relativePath)) {
            return $relativePath;
        }
        
        // Si c'est un chemin relatif, le convertir en absolu
        $baseDir = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__);
        
        // Nettoyer les séparateurs
        $relativePath = str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        
        // Retirer le slash initial si présent
        $relativePath = ltrim($relativePath, '/\\');
        
        return $baseDir . DIRECTORY_SEPARATOR . $relativePath;
    }
    
    public static function exists(string $path): bool
    {
        $absolutePath = self::toAbsolute($path);
        return file_exists($absolutePath);
    }
    
    public static function getUploadsPath(string $filename = ''): string
    {
        $uploadsDir = self::toAbsolute('uploads');
        
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }
        
        if ($filename) {
            return $uploadsDir . DIRECTORY_SEPARATOR . $filename;
        }
        
        return $uploadsDir;
    }
}



// Version sécurisée avec validation
/*
class SecureMailer {
    private $mailer;
    private $allowedTypes = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];
    
    private $maxFileSize = 10 * 1024 * 1024; // 10MB
    
    public function __construct() {
        $this->mailer = new Mailer();
    }
    
    public function addSecureAttachment($filePath, $customName = null) {
        // Vérifier que le fichier existe
        if (!file_exists($filePath)) {
            throw new \Exception("Fichier non trouvé: $filePath");
        }
        
        // Vérifier la taille
        try{
            if (filesize($filePath) > $this->maxFileSize) {
            throw new Exception("Fichier trop volumineux: " . basename($filePath));
        }

            }catch(\Exception $e){
                throw new \Exception("Erreur chargement fichier". $e->getMessage());
            }
        
        
        // Vérifier le type
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $filePath);
        finfo_close($fileInfo);
        
        if (!in_array($mimeType, $this->allowedTypes)) {
            throw new \InvalidArgumentException("Type de fichier non autorisé: $mimeType");
        }
        
        // Ajouter la pièce jointe
        return $this->mailer->addAttachment($filePath, $customName ?? basename($filePath));
    }
    
    public function envoyerFichiersSecurises($destinataire, $fichiers) {
        $this->mailer->clear()->setFrom()->addAddress($destinataire);
        
        foreach ($fichiers as $fichier) {
            $this->addSecureAttachment($fichier['path'], $fichier['name'] ?? null);
        }
        
        return $this->mailer->setHTMLContent('Vos fichiers', 'Contenu')->send();
    }
}

// Utilisation sécurisée
$secureMailer = new SecureMailer();

try {
    $secureMailer->envoyerFichiersSecurises('client@example.com', [
        ['path' => '/documents/rapport.pdf', 'name' => 'rapport_annuel.pdf'],
        ['path' => '/images/logo.png']
    ]);
    echo "Fichiers envoyés avec succès!";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}







// Traitement de fichiers uploadés par l'utilisateur
class DocumentService {
    private $mailer;
    
    public function __construct(Mailer $mailer) {
        $this->mailer = $mailer;
    }
    
    public function envoyerDocuments($destinataire, $nom, $fichiersUploades) {
        $this->mailer->clear()
               ->setFrom()
               ->addAddress($destinataire, $nom);
        
        // Traiter chaque fichier uploadé
        foreach ($fichiersUploades as $fichier) {
            if ($fichier['error'] === UPLOAD_ERR_OK) {
                $cheminTemporaire = $fichier['tmp_name'];
                $nomFichier = $fichier['name'];
                
                // Ajouter comme pièce jointe
                $this->mailer->addAttachment($cheminTemporaire, $nomFichier);
            }
        }
        
        $this->mailer->setHTMLContent(
            'Vos documents',
            '<p>Veuillez trouver vos documents en pièces jointes.</p>'
        );
        
        return $this->mailer->send();
    }
}

// Utilisation dans un contrôleur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mailer = new Mailer();
    $documentService = new DocumentService($mailer);
    
    $resultat = $documentService->envoyerDocuments(
        $_POST['email'],
        $_POST['nom'],
        $_FILES['documents']
    );
    
    if ($resultat) {
        echo "Documents envoyés avec succès!";
    } else {
        echo "Erreur lors de l'envoi: " . $mailer->getError();
    }
}





// Utilisation dans un contrôleur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mailer = new Mailer();
    $documentService = new DocumentService($mailer);
    
    $resultat = $documentService->envoyerDocuments(
        $_POST['email'],
        $_POST['nom'],
        $_FILES['documents']
    );
    
    if ($resultat) {
        echo "Documents envoyés avec succès!";
    } else {
        echo "Erreur lors de l'envoi: " . $mailer->getError();
    }
}





function envoyerRapportQuotidien($emailDestinataire, $nomDestinataire) {
    $mailer = new Mailer();
    
    // Générer le rapport PDF (exemple avec une librairie fictive)
    $pdfPath = generateDailyReport();
    
    // Générer un Excel de statistiques
    $excelPath = exportStatisticsToExcel();
    
    try {
        $mailer->setFrom('rapports@monsite.com', 'Système de Rapports')
               ->addAddress($emailDestinataire, $nomDestinataire)
               
               // Pièces jointes générées dynamiquement
               ->addAttachment($pdfPath, 'rapport_quotidien_' . date('Y-m-d') . '.pdf')
               ->addAttachment($excelPath, 'statistiques_' . date('Y-m-d') . '.xlsx')
               
               ->setHTMLContent(
                   'Rapport Quotidien - ' . date('d/m/Y'),
                   "
                   <h2>Rapport Automatique</h2>
                   <p>Bonjour {$nomDestinataire},</p>
                   <p>Veuillez trouver ci-joint votre rapport quotidien.</p>
                   <p><strong>Fichiers inclus :</strong></p>
                   <ul>
                       <li>Rapport détaillé (PDF)</li>
                       <li>Statistiques exportables (Excel)</li>
                   </ul>
                   <p><em>Cet email est généré automatiquement.</em></p>
                   "
               );
        
        $result = $mailer->send();
        
        // Nettoyer les fichiers temporaires
        unlink($pdfPath);
        unlink($excelPath);
        
        return $result;
        
    } catch (Exception $e) {
        // Nettoyer même en cas d'erreur
        if (file_exists($pdfPath)) unlink($pdfPath);
        if (file_exists($excelPath)) unlink($excelPath);
        throw $e;
    }
}

// Utilisation
envoyerRapportQuotidien('inouaismail@gmail.com', 'Inoua Ismail');

*/