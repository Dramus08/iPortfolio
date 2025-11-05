<?php
namespace Core;

use Database\AbstractDatabase;
use Exception;

/**
 * BaseController : cœur de la logique de réponse pour tout ton framework.
 * Fournit des utilitaires API, AJAX, JSON, et s’appuie sur Controller.
 */
abstract class BaseController extends Controller
{
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
     * Format un message JSON uniforme pour les requêtes AJAX/API
     */
    protected function respond(
        bool $success,
        string $message = '',
        array $data = [],
        int $statusCode = 200
    ): void {
        $payload = [
            'success' => $success,
            'error'   => !$success,
            'message' => $message,
            'data'    => $data
        ];

        $this->toJson($payload, $statusCode);
    }

    /**
     * Version améliorée de responseSuccess() :
     * - Détection automatique AJAX ou HTTP
     * - Support route ou données
     */
    protected function respondSuccess(string $message = '', string $route = '/', array $data = []): void
    {
        if ($this->isAjaxRequest()) {
            $this->respond(true, $message, array_merge(['route' => $route], $data));
        } else {
            $this->flash('success', $message);
            $this->redirect($route);
        }
    }

    /**
     * Version améliorée de responseError()
     */
    protected function respondError(string $message = '', int $statusCode = 400, array $data = []): void
    {
        if ($this->isAjaxRequest()) {
            $this->respond(false, $message, $data, $statusCode);
        } else {
            $this->flash('error', $message);
        }
    }

    /**
     * Version améliorée de responseDelete()
     */
    protected function respondDelete(string $message = '', string $route = '/', array $data = []): void
    {
        if ($this->isAjaxRequest()) {
            $this->respond(true, $message, array_merge(['route' => $route], $data));
        } else {
            $this->flash('warning', $message);
            $this->redirect($route);
        }
    }

    /**
     * Gestion centralisée des exceptions (DB, logique, etc.)
     */
    protected function handleException(Exception $e, string $context = 'Erreur'): void
    {
        $message = "{$context} : " . $e->getMessage();

        if ($this->isAjaxRequest()) {
            $this->respond(false, $message, [], 500);
        } else {
            $this->flash('error', $message);
        }
    }

    /**
     * Exécute une action avec gestion automatique des erreurs (try/catch)
     */
    protected function safeCall(callable $callback, string $context = 'Erreur', string $successMsg = '', string $redirect = '/')//: void
    {
        try {
            $result = call_user_func($callback);

            if ($successMsg) {
                $this->respondSuccess($successMsg, $redirect);
            }

            return $result;
        } catch (Exception $e) {
            $this->handleException($e, $context);
        }
    }
}


/* Cooment utiliser la classe */
/*
namespace App\Controllers;

use Core\BaseController;

class UserController extends BaseController
{
    public function create()
    {
        $this->safeCall(function() {
            // Exemple : création utilisateur
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ];
            $this->db->insert('users', $data);
        }, 'Erreur lors de la création', 'Utilisateur ajouté avec succès', '/users');
    }

    public function list()
    {
        $this->safeCall(function() {
            $users = $this->db->query("SELECT * FROM users");
            $this->respondSuccess('Liste récupérée', '', ['users' => $users]);
        });
    }
}
*/
