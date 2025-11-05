<?php
namespace Utils;

use PDO;
use Exception;
use Database\AbstractDatabase;
use Database\DatabaseFactory;
/**
 * MessageManager v2
 * -----------------
 * Gestion centralisée des messages (flash, erreurs, succès) + audit (table app_logs).
 */
class MessageManager
{
     protected AbstractDatabase $db;

    public function __construct(string $dbDriver = 'mysql')
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash'] ??= [];
        $_SESSION['errors'] ??= [];
        $_SESSION['errorForms'] ??= [];

        $this->db =  DatabaseFactory::create($dbDriver);

    }

    /* =====================================================
     * 🔹 MÉTHODES DE BASE : FLASH / ERREUR / SUCCÈS
     * ===================================================== */

    public function flash(string $type, string $message, ?string $context = null): void
    {
        $_SESSION['flash'][$type] = $message;
        $this->logMessage($type, $message, $context);
    }

    public function flashError(string $message, ?string $context = null): void
    {
        $_SESSION['errors'][] = $message;
        $this->logMessage('error', $message, $context);
    }

    public function flashErrorForm(string $field, string $message, ?string $context = null): void
    {
        $_SESSION['errorForms'][$field] = $message;
        $this->logMessage('error_form', "{$field}: {$message}", $context);
    }

    /* =====================================================
     * 🔹 LOGIQUE D’AUDIT : ENREGISTREMENT DANS app_logs
     * ===================================================== */

    protected function logMessage(string $type, string $message, ?string $context = null): void
    {
        if (!$this->db) return;

        try {
            $stmt = $this->db->prepare("
                INSERT INTO app_logs (type, message, context, user_id, ip_address, user_agent)
                VALUES (:type, :message, :context, :user_id, :ip, :agent)
            ");

            $stmt->execute([
                ':type' => $type,
                ':message' => $message,
                ':context' => $context,
                ':user_id' => $_SESSION['user']['id'] ?? null,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                ':agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            ]);
        } catch (Exception $e) {
            error_log("[MessageManager] Erreur de log : " . $e->getMessage());
        }
    }

    

    /* =====================================================
     * 🔹 RÉCUPÉRATION ET NETTOYAGE DES MESSAGES
     * ===================================================== */

    public function getAll(): array
    {
        return (object)[
            'flash' => $_SESSION['flash'] ?? [],
            'errors' => $_SESSION['errors'] ?? [],
            'errorForms' => $_SESSION['errorForms'] ?? [],
        ];
    }

    public function clearAll(): void
    {
        $_SESSION['flash'] = [];
        $_SESSION['errors'] = [];
        $_SESSION['errorForms'] = [];
    }

    /* =====================================================
     * 🔹 AFFICHAGE TOASTS (Bootstrap 5)
     * ===================================================== */

    public function displayToastsBootstrap(): void
    {
        $messages = $this->getAll();
        if (empty($messages['flash']) && empty($messages['errors']) && empty($messages['errorForms'])) return;

        echo "<div id='toast-container' class='toast-container position-fixed top-0 end-0 p-3' style='z-index:9999;'></div>";
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('toast-container');
            const data = " . json_encode($messages) . ";

            function createToast(title, message, type) {
                const toast = document.createElement('div');
                toast.className = 'toast text-white bg-' + type + ' border-0 mb-2';
                toast.setAttribute('role', 'alert');
                toast.innerHTML = `
                    <div class='d-flex'>
                        <div class='toast-body'>${message}</div>
                        <button type='button' class='btn-close btn-close-white me-2 m-auto' data-bs-dismiss='toast'></button>
                    </div>`;
                container.appendChild(toast);
                const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
                bsToast.show();
            }

            for (const type in data.flash) createToast(type, data.flash[type], type);
            for (const msg of data.errors) createToast('Erreur', msg, 'danger');
            for (const field in data.errorForms) createToast('Erreur', field + ' : ' + data.errorForms[field], 'danger');
        });
        </script>";

        $this->clearAll();
    }

    /* =====================================================
     * 🔹 UTILITAIRES POUR CONTROLLERS / AJAX
     * ===================================================== */

    public function responseSuccess(string $message = "", string $redirect = "/", ?string $context = null): void
    {
        $this->logMessage('success', $message, $context);

        if ($this->isAjaxRequest()) {
            echo json_encode(['success' => true, 'message' => $message, 'redirect' => $redirect]);
            exit;
        }

        $this->flash('success', $message, $context);
        $this->redirect($redirect);
    }

    public function responseError(string $message = "", string $redirect = "/", array $errors = [], ?string $context = null): void
    {
        $this->logMessage('error', $message, $context);

        if ($this->isAjaxRequest()) {
            echo json_encode(['error' => true, 'message' => $message, 'errors' => $errors]);
            exit;
        }

        $this->flash('danger', $message, $context);
        $this->redirect($redirect);
    }

    protected function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}
