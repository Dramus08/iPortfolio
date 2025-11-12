<?php
//Core/Controller.php
namespace Core;

use Database\MYSQL_DB;
use Database\DatabaseFactory;
use Database\AbstractDatabase;
use Router\Router;
use Exception;






/**
 * Classe mère de tous les contrôleurs.
 * Fournit : rendu de vues, sécurité CSRF, gestion DB et helpers session.
 */
abstract class Controller
{
        /** @var AbstractDatabase */
        protected AbstractDatabase $db;

        /** @var string|null Nom du layout à utiliser */
        protected ?string $layout = 'layout'; // layout par défaut


        /** @var array Liste des vues sans layout global */
        protected array $noLayoutViews = ['auth.login', 'auth.register','auth.waiting_confirmation','user.show','user.index','user.create','user.edit'];

        public function __construct(string|object $dbDriver = 'mysql')
    {
        // Démarre la session si non active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si c’est une instance de base de données → on l’assigne directement
        if (is_object($dbDriver)) {
            $this->db = $dbDriver;
        } else {
            // Sinon, on crée la connexion via DatabaseFactory
            $this->db = DatabaseFactory::create($dbDriver);
        }

        // Génère un token CSRF si absent
        $this->initCSRFToken();
    }

        /**
     * Envoie une réponse JSON standardisée avec le bon header
     */
    protected function toJson(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Rendu d’une vue avec données et layout optionnel.
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = VIEWS . str_replace('.', DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new Exception("Vue introuvable : {$viewPath}");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Vérifie si la vue doit être rendue sans layout
        if ($this->shouldUseLayout($view)) {
            require VIEWS . "{$this->layout}.php";
        } else {
            echo $content;
        }
    }

    /**
     * Détermine si la vue doit être incluse dans un layout.
     */
    protected function shouldUseLayout(string $view): bool
    {
        return !in_array($view, $this->noLayoutViews);
    }

    /**
     * Retourne l’instance DB (utile dans les contrôleurs enfants)
     */
    protected function getDB(): AbstractDatabase
    {
        return $this->db;
    }



    /**
 * Vérifie si l'utilisateur est admin
 */
protected function isAdmin(): bool
{
    return $this->hasRole(['admin', 'superadmin']);
}
/**
 * Vérifie l'authentification de base Utilisateur connecte
 */
protected function isAuthenticated(): bool
{
    return !empty($_SESSION['user']) && 
           !empty($_SESSION['auth']) && 
           ($_SESSION['auth'] === true) &&
           !empty($_SESSION['user']['is_active']);
}



    /**
     * Redirige vers une autre page.
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit();
    }

    /**
     * Vérifie le token CSRF pour les requêtes POST.
     */
    protected function verifyCSRF(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->verifyToken($token)) {
                die("⚠️ Erreur CSRF : le token est invalide ou expiré.");
            }
        }
    }

    /**
     * Initialise le token CSRF si non présent.
     */
    private function initCSRFToken(): void
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    /**
     * Vérifie la validité du token CSRF.
     */
    private function verifyToken(string $token): bool
    {
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    /**
     * Retourne le token CSRF actuel (utile dans les vues/formulaires)
     */
    protected function csrfToken(): string
    {
        return $_SESSION['csrf_token'] ?? '';
    }

    function checkInternetConnection($host = 'www.google.com', $port = 80, $timeout = 5) {
        $connected = @fsockopen($host, $port, $errno, $errstr, $timeout);
        
        if ($connected) {
            fclose($connected);
            return true;
        }
        
        return false;
    }

    function checkInternetConnectionCurl($url = 'https://www.google.com', $timeout = 5) {
        if (!function_exists('curl_init')) {
            return false;
        }
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return ($httpCode >= 200 && $httpCode < 300);
    }

    function getFullBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $port = $_SERVER['SERVER_PORT'];
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        
        // Ajouter le port seulement si nécessaire (pas 80 pour http, pas 443 pour https)
        if (($protocol === 'http' && $port != 80) || ($protocol === 'https' && $port != 443)) {
            $host = $host . ':' . $port;
        }
        
        $baseUrl = $protocol . '://' . $host . $scriptDir;
        
        return rtrim($baseUrl, '/');
    }

    function getDynamicBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        
        return $protocol . '://' . $host . $path;
    }

    function getBaseUrl() {
    // Déterminer le protocole (http ou https)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
    
    // Récupérer le nom d'hôte
    $host = $_SERVER['HTTP_HOST'];
    
    // Récupérer le chemin du script (sans le fichier)
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
    
    // Construire l'URL de base
    $baseUrl = $protocol . '://' . $host . $scriptPath;
    //$baseUrl = $protocol . '://' . $host ;
    
    // Supprimer le dossier final s'il existe
    return rtrim($baseUrl, 'public');
}

 
    /**
 * Vérifie si l'utilisateur est authentifié
 * sinon redirige vers la page de connexion.
 */
protected function requireAuth(): void
{
    if (empty($_SESSION['auth']) || $_SESSION['auth'] !== true) {
        $this->flash('error', 'Vous devez être connecté pour accéder à cette page.');
        $this->redirect(Router::route('login'));
        exit;
    }
}

public function isConfirmMail(): bool{
    return isset($_SESSION['user']) && isset($_SESSION['email_confirmed']) && ($_SESSION['email_confirmed'] === 1 )? true: false;
}

protected function requireMailConfirm(){
    $this->requireAuth();
    if(!$this->isConfirmMail()){
       $this->flash('error', "Vous devez confirme votre email avant d'acceder a cette page."); 
       $this->redirect(Router::route('waiting_confirmation_mail',['slug'=>$_SESSION['user']['slug']]));
    }
}

/**
 * Système de permissions avancé
 */
protected function requireRole(string|array $roles, bool $allowSuperAdmin = true): void
{
    // Vérifier l'authentification de base
    if (!$this->isAuthenticated()) {
        $this->flash('error', 'Vous devez être connecté pour accéder à cette page.');
        $this->redirect(Router::route('login'));
        exit;
    }

    // Normaliser les rôles
    $allowedRoles = is_array($roles) ? $roles : [$roles];
    
    $userRole = $_SESSION['user']['role'] ?? null;
    $isSuperAdmin = $_SESSION['user']['is_super_admin'] ?? false;
    $isActive = $_SESSION['user']['is_active'] ?? false;
    
    // Vérifier si le compte est actif
    if (!$isActive) {
        $this->flash('error', 'Votre compte est désactivé. Contactez un administrateur.');
        $this->redirect(Router::route('login'));
        exit;
    }
    
    // Les superadmins ont un accès complet si autorisé
    if ($allowSuperAdmin && $isSuperAdmin) {
        return;
    }
    
    // Vérifier les permissions
    if (!$this->hasRole($allowedRoles)) {
        $this->handleAccessDenied($allowedRoles);
        exit;
    }
}

/**
 * Vérifie si l'utilisateur a l'un des rôles requis
 */
protected function hasRole(array $roles): bool
{
    if(isset($_SESSION['user'])){
        $userRole = $_SESSION['user']['role'] ?? null;
    return in_array($userRole, $roles);
    }
    return false;
    
}

/**
 * Vérifie si l'utilisateur a exactement le rôle spécifié
 */
protected function hasExactRole(string $role): bool
{
    $userRole = $_SESSION['user']['role'] ?? null;
    return $userRole === $role;
}

/**
 * Vérifie si l'utilisateur est super admin
 */
protected function isSuperAdmin(): bool
{
    return ($_SESSION['user']['is_super_admin'] ?? false) === true;
}



/**
 * Gère l'accès refusé
 */
protected function handleAccessDenied(array $requiredRoles = []): void
{
    $roleNames = [
        'superadmin' => 'Super Administrateur',
        'admin' => 'Administrateur', 
        'manager' => 'Manager',
        'staff' => 'Staff',
        'user' => 'Utilisateur'
    ];
    
    if (!empty($requiredRoles)) {
        $requiredRolesText = array_map(fn($role) => $roleNames[$role] ?? $role, $requiredRoles);
        $message = "Accès refusé : droits insuffisants. Rôle(s) requis : " . implode(', ', $requiredRolesText);
    } else {
        $message = "Accès refusé : droits insuffisants.";
    }
    
    $this->flash('error', $message);
    
    // Redirection intelligente selon le contexte
    if ($this->isAuthenticated()) {
        $this->redirect(Router::route('dashboard_admin'));
    } else {
        $this->redirect(Router::route('login'));
    }
}



/**
 * Récupère le rôle de l'utilisateur connecté
 */
protected function getUserRole(): ?string
{
    return $_SESSION['user']['role'] ?? null;
}

/**
 * Récupère les informations de l'utilisateur connecté
 */
protected function getCurrentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}



   /**
     * Helper pour afficher un message flash
     */
    protected function flash(string $key, string $message): void
    {
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Helper pour afficher un message flash
     */
    protected function flashErrorForms(string $key, string $message): void
    {
        $_SESSION['errorForms'][$key] =$message;
    }
    protected function flashError(string $message): void
    {
        $_SESSION['errors'][] = $message;
    }

        /**
     * Récupère tous les messages flash (sans les supprimer)
     */
    protected function getAllFlash(): array
    {
        return $_SESSION['flash'] ?? [];
    }

    protected function getAllErrorForms(): array
    {
        return $_SESSION['errorForms'] ?? [];
    }
    protected function getAllErrors(): array
    {
        return $_SESSION['errors'] ?? [];
    }

    /**
     * Vide tous les messages flash après affichage
     */
    protected function clearFlash(): void
    {
        $_SESSION['flash']=[];
    }

    protected function clearErrors(): void
    {
        $_SESSION['errors']=[];
    }

    protected function clearErrorForms(): void
    {
       $_SESSION['errorForms']=[];
    }

    /**
     * Génère le HTML des messages flash sous forme d'alertes
     */
    protected function displayFlashMessages(): void
    {
        if(empty($_SESSION['flash']) && empty($_SESSION['errorForms']) && empty($_SESSION['errors'])) return;
        if (!empty($_SESSION['flash'])){
            foreach ($_SESSION['flash'] as $type => $message) {
                $class = match ($type) {
                    'success' => 'flash-success',
                    'error' => 'flash-error',
                    'danger' => 'flash-error',
                    'warning' => 'flash-warning',
                    'info' => 'flash-info',
                    default => 'flash-info'
                };
                echo "<div class='flash-message {$class}'>{$message}</div>";
            }
             $this->clearFlash();
        }
        if(!empty($_SESSION['errors'])){
             for ($i=0; $i < count($_SESSION['errors']) ; $i++) { 
                $message=$_SESSION['errors'][$i];
            
                echo "<div class='flash-message flash-error'>{$message}</div>";
            }
             $this->clearErrors();
        }

        if (!empty($_SESSION['errorForms'])){
            foreach ($_SESSION['errorForms'] as $field => $message) {
                echo "<div class='flash-message flash-error'><b>Field {$field}</b> : {$message}</div>";
            }
            $this->clearErrorForms();
        }

       
    }

    /**
     * Génère le HTML + JS pour les toasts
     */
    protected function displayToastMessages(): void
    {
        if(empty($_SESSION['flash']) && empty($_SESSION['errors']) && empty($_SESSION['errorForms'])) return;

        if (!empty($_SESSION['flash'])) {
             echo "<div class='toastMessages'><div id='toast-container-flask'></div></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('toast-container-flask');
                        const messages = " . json_encode($_SESSION['flash']) . ";
                        for (const type in messages) {
                            const t = document.createElement('div');
                            toast.className = 'toast toast-' + type;
                            toast.textContent = messages[type];
                            container.appendChild(t);
                            setTimeout(() => t.classList.add('show'), 100);
                            setTimeout(() => {
                                t.classList.remove('show');
                                setTimeout(() => t.remove(), 500);
                            }, 4000);
                        }
                    });
                </script>";
                
                $this->clearFlash();
        }
        if (!empty($_SESSION['errors'])) {
             echo "<h1>errors</h1>";
             echo "<div class='toastMessages'><div id='toast-container-errors'></div></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('toast-container-errors');
                        const messages = " . json_encode($_SESSION['errors']) . ";
                        for (const type in messages) {
                            const te = document.createElement('div');
                            toast.className = 'toast toast-error toast-danger';
                            toast.textContent = messages[type];
                            container.appendChild(te);
                            setTimeout(() => te.classList.add('show'), 100);
                            setTimeout(() => {
                                te.classList.remove('show');
                                setTimeout(() => te.remove(), 500);
                            }, 4000);
                        }
                    });
                </script>";
                $this->clearErrors();
        }
        if (!empty($_SESSION['errorForms'])) {
             echo "<div class='toastMessages'><div class='toast-container-errorForms'></div></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('toast-container-errorForms');
                        const messages = " . json_encode($_SESSION['errorForms']) . ";
                        for (const field in messages) {
                            const tf = document.createElement('div');
                            toast.className = 'toast toast-error';
                            toast.textContent = 'field '+type + '  :  'messages[type];
                            container.appendChild(tf);
                            setTimeout(() => tf.classList.add('show'), 100);
                            setTimeout(() => {
                                tf.classList.remove('show');
                                setTimeout(() => tf.remove(), 1000);
                            }, 4000);
                        }
                    });
                </script>";
                
                $this->clearErrorForms();
        }

       
    }

        /**
     * Génère le HTML + JS pour les toasts Bootstrap
     */
    protected function displayToastMessagesBootstrap(): void
    {
        if (empty($_SESSION['flash']) && empty($_SESSION['errors']) && empty($_SESSION['errorForms'])) return;

        if(!empty($_SESSION['flash'])){
                 echo "<div class='toastMessagesBootstrap'><div id='toast-container-flash' class='toast-container position-fixed top-0 end-0 p-3' style='z-index: 1100;'></div></div>";
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            
            foreach ($_SESSION['flash'] as $type => $message) {
                // Mapping des types
                $config = [
                    'success' => ['icon' => 'check-circle', 'title' => 'Succès', 'class' => 'success'],
                    'error' => ['icon' => 'exclamation-triangle', 'title' => 'Erreur', 'class' => 'danger'],
                    'warning' => ['icon' => 'exclamation-circle', 'title' => 'Avertissement', 'class' => 'warning'],
                    'info' => ['icon' => 'info-circle', 'title' => 'Information', 'class' => 'info']
                ][$type] ?? ['icon' => 'info-circle', 'title' => 'Information', 'class' => 'info'];
                
                // Échapper les caractères spéciaux pour JavaScript
                $escapedMessage = addslashes($message);
                
                echo "
                    const tbf = document.createElement('div');
                    tbf.className = 'toast';
                    tbf.setAttribute('role', 'alert');
                    tbf.setAttribute('aria-live', 'assertive');
                    tbf.setAttribute('aria-atomic', 'true');
                    
                    tbf.innerHTML = `
                        <div class='toast-header'>
                            <i class='fas fa-{$config['icon']} text-{$config['class']} me-2'></i>
                            <strong class='me-auto'>{$config['title']}</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='toast' aria-label='Close'></button>
                        </div>
                        <div class='toast-body bg-{$config['class']}'>
                            {$message}
                        </div>
                    `;
                    
                    document.getElementById('toast-container-flash').appendChild(tbf);
                    const bsToast = new bootstrap.Toast(tbf, { autohide: true, delay: 5000 });
                    bsToast.show();
                    
                    tbf.addEventListener('hidden.bs.toast', function() {
                        tbf.remove();
                    });
                ";
            }
            
            echo "});";
            echo "</script>";
            
            $this->clearFlash();
        }
        if(!empty($_SESSION['errors'])){
            echo "<div class='toastMessagesBootstrap'><div id='toast-container-errors' class='toast-container position-fixed top-0 end-0 p-3' style='z-index: 1100;'></div></div>";
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            
            foreach ($_SESSION['errors'] as $type => $message) {
                // Mapping des types
                $config = ['icon' => 'exclamation-triangle', 'title' => 'Erreur', 'class' => 'danger'];
                
                // Échapper les caractères spéciaux pour JavaScript
                $escapedMessage = addslashes($message);
                
                echo "
                    const teb = document.createElement('div');
                    teb.className = 'toast';
                    teb.setAttribute('role', 'alert');
                    teb.setAttribute('aria-live', 'assertive');
                    teb.setAttribute('aria-atomic', 'true');
                    
                    teb.innerHTML = `
                        <div class='toast-header'>
                            <i class='fas fa-{$config['icon']} text-{$config['class']} me-2'></i>
                            <strong class='me-auto'>{$config['title']}</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='toast' aria-label='Close'></button>
                        </div>
                        <div class='toast-body bg-{$config['class']}'>
                            {$message}
                        </div>
                    `;
                    
                    document.getElementById('toast-container-errors').appendChild(toast);
                    const bsToast = new bootstrap.Toast(teb, { autohide: true, delay: 5000 });
                    bsToast.show();
                    
                    teb.addEventListener('hidden.bs.toast', function() {
                        teb.remove();
                    });
                ";
            }
            
            echo "});";
            echo "</script>";
        
            $this->clearErrors();
        }

         if(!empty($_SESSION['errorForms'])){
            echo "<div class='toastMessagesBootstrap'><div id='toast-container-errorForms' class='toast-container position-fixed top-0 end-0 p-3' style='z-index: 1100;'></div></div>";
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            
             foreach ($_SESSION['errorForms'] as $type => $message) {
                // Mapping des types
                $config =  ['icon' => 'exclamation-triangle', 'title' => 'Erreur', 'class' => 'danger'];
                
                // Échapper les caractères spéciaux pour JavaScript
                $escapedMessage = addslashes($message);
                
                echo "
                    const tbe = document.createElement('div');
                    tbe.className = 'toast';
                    tbe.setAttribute('role', 'alert');
                    tbe.setAttribute('aria-live', 'assertive');
                    tbe.setAttribute('aria-atomic', 'true');
                    
                    tbe.innerHTML = `
                        <div class='toast-header'>
                            <i class='fas fa-{$config['icon']} text-{$config['class']} me-2'></i>
                            <strong class='me-auto'>{$config['title']}</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='toast' aria-label='Close'></button>
                        </div>
                        <div class='toast-body bg-{$config['class']}'>
                            {$message}
                        </div>
                    `;
                    
                    document.getElementById('toast-container-errorForms').appendChild(tbe);
                    const bsToast = new bootstrap.Toast(toast, { autohide: true, delay: 5000 });
                    bsToast.show();
                    
                    tbe.addEventListener('hidden.bs.toast', function() {
                        tbe.remove();
                    });
                ";
            }
            
            echo "});";
            echo "</script>";
        
            $this->clearErrorForms();
        }
    }


/**
 * Génère le HTML + JS pour les toasts Bootstrap
 */

       /**
     * Définit un message flash puis redirige immédiatement
     * 
     * @param string $type Type du message (success, error, warning, info)
     * @param string $message Contenu du message flash
     * @param string $url URL de redirection
     */
    protected function setFlashAndRedirect(string $type, string $message, string $url): void
    {
        $_SESSION['flash'][$type] = $message;
        header("Location: {$url}");
        exit();
    }
    

    /**
     * Récupère et supprime un message flash
     */
    protected function getFlash(string $key): ?string
    {
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
    




    public function isAjaxRequest(): bool
    {
        return (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        );
    }

    public function responseSuccess(string $message="",string $route='/'){
        if($this->isAjaxRequest()){
            $response=['success' => true, 'message' => $message,'route'=>$route];
            echo json_encode($response);
        }
        else{
            $this->flash('success', $message);
            $this->redirect($route);
        }
    }
    public function responseDelete(string $message="",string $route='/'){
        if($this->isAjaxRequest()){
            $response=['success' => true, 'message' => $message,'route'=>$route];
            echo json_encode($response);
        }
        else{
            $this->flash('warning', $message);
            $this->redirect($route);
        }
    }
    public function responseError(string $message="",string $route='/',array $errorForms=[]){
        if($this->isAjaxRequest()){
            $response=['error'=>true, 'message' => $message,'errorForms'=>$errorForms];
            echo json_encode($response);
        }
        else{
            $this->flash('danger', $message);
        }
    }
}



/**
 * Classe de base pour tous les contrôleurs.
 * Fournit des fonctions utilitaires pour le rendu des vues,
 * la gestion de la base de données, les sessions et la sécurité.
 */




