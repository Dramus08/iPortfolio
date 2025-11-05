<?php
namespace Database;

use PDOException;
use Exception;
use stdClass;

/**
 * 🔧 Trait utilitaire pour la gestion standardisée des réponses,
 * erreurs et retours JSON dans les opérations de base de données.
 */
trait DatabaseHelperTrait
{
    /** @var array Liste d'erreurs globales */
    protected array $errors = [];

    /** @var array Liste d'erreurs spécifiques à des champs */
    protected array $fieldErrors = [];

    /** @var array Données de la dernière opération */
    protected array $data = [];

    /** @var stdClass Réponse finale */
    protected stdClass $response;

    public function __construct()
    {
        $this->resetResponse();
    }

    /**
     * ✅ Retourne la dernière réponse complète (toujours un objet stdClass)
     */
    public function getResponse(): stdClass
    {
        return $this->response;
    }

    /**
     * ✅ Définit une réponse standardisée (succès ou erreur)
     */
    public function setResponse(
        bool $success = true,
        string $message = '',
        array $dataOrError = []): stdClass {

        if ($success) {
            $this->data = $dataOrError;
            $this->response = (object) [
                'success' => true,
                'message' => $message ?: 'Opération réussie.',
                'data' => $this->data,
            ];
        } else {
            $this->errors = $dataOrError;
            $this->response = (object) [
                'error' => true,
                'message' => $message ?: 'Une erreur est survenue.',
                'errors' => $this->errors,
            ];
        }

        return $this->response;
    }

    /**
     * ✅ Ajoute une erreur personnalisée
     */
    public function addError(string $key, string $message, bool $field = false): void
    {
        if ($field) {
            $this->fieldErrors[$key] = $message;
        } else {
            $this->errors[$key] = $message;
        }
    }

    /**
     * ✅ Supprime une erreur existante
     */
    public function removeError(string $key, bool $field = false): void
    {
        if ($field && isset($this->fieldErrors[$key])) {
            unset($this->fieldErrors[$key]);
        } elseif (isset($this->errors[$key])) {
            unset($this->errors[$key]);
        }
    }

    /**
     * ✅ Modifie une erreur existante
     */
    public function updateError(string $key, string $newMessage, bool $field = false): void
    {
        if ($field && isset($this->fieldErrors[$key])) {
            $this->fieldErrors[$key] = $newMessage;
        } elseif (isset($this->errors[$key])) {
            $this->errors[$key] = $newMessage;
        }
    }

    /**
     * ✅ Réinitialise complètement la réponse et les erreurs
     */
    public function resetResponse(): stdClass
    {
        $this->errors = [];
        $this->fieldErrors = [];
        $this->data = [];

        $this->response = new stdClass();
        //$this->response->success = false;
        //$this->response->message = '';
        //$this->response->dataOrError = [];

        return $this->response;
    }

    /**
     * ⚠️ Lève une exception si une erreur est détectée
     */
    public function throwIfError(): void
    {
        if (isset($this->response->error) && $this->response->error === true) {
            throw new Exception($this->response->message);
        }
    }

    /**
     * ⚠️ Interrompt le script si une erreur est détectée
     */
    public function exitIfError(): void
    {
        if (isset($this->response->error) && $this->response->error === true) {
            exit(json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * ✅ Renvoie la réponse au format JSON.
     *
     * @param bool $exitAfter Si true, le script s'arrête après l'envoi (utile pour AJAX)
     * @param int $httpCode Code HTTP (ex: 200, 400, 500)
     */
    public function toJson(bool $exitAfter = false, int $httpCode = 200): string
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code($httpCode);
        }

        $json = json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($exitAfter) {
            exit($json);
        }

        return $json;
    }

    /**
     * 🚀 Méthode simplifiée pour les APIs
     * Combine setResponse() + toJson()
     *
     * @param bool $success Succès ou erreur
     * @param string $message Message à retourner
     * @param array $data Données optionnelles
     * @param array $errors Erreurs optionnelles
     * @param int $httpCode Code HTTP (200, 400, 500, etc.)
     * @param bool $exitAfter Arrêter après l’envoi (par défaut true)
     */
    public function sendResponse(
        bool $success,
        string $message = '',
        array $data = [],
        array $errors = [],
        int $httpCode = 200,
        bool $exitAfter = true
    ): string {
        $this->setResponse($success, $message, $data, $errors);

        if (!$success) {
            // Si c’est une erreur, ajuster le code HTTP automatiquement
            $httpCode = $httpCode >= 400 ? $httpCode : 400;
        }

        return $this->toJson($exitAfter, $httpCode);
    }
}


