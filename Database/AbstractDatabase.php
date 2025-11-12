<?php
namespace Database;

use PDO;
use PDOException;
use stdClass;
use Core\Logger;

/**
 * Classe abstraite définissant le contrat pour tous les moteurs de base de données.
 * Fournit les comportements communs : connexion, requêtes, sécurité, etc.
 */
abstract class AbstractDatabase
{
    protected ?PDO $connection = null;

    protected array $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

   // protected ?Logger $logger = null;

    use DatabaseHelperTrait; // ← Intègre les méthodes utilitaires de réponse et d'erreur

    //public function setLogger(?Logger $logger): void{$this->logger = $logger;}

    /** Connexion à la base (doit être définie par les classes filles) */
    abstract protected function connect(): void;

    /**
     * 🔍 Exécute une requête SQL (SELECT ou INSERT/UPDATE/DELETE)
     * Retourne toujours une réponse normalisée (via DatabaseHelperTrait)
     */
    public function query(string $sql, ?array $params = null, bool $single = false): bool
    {
        try {
            if (!$this->connection) {
                $this->connect();
            }

            $stmt = $params ? $this->connection->prepare($sql) : $this->connection->query($sql);

            if ($params) {
                $stmt->execute($params);
            }

            $result = $single ? $stmt->fetch() : $stmt->fetchAll();
            $this->setResponse(true, 'Données récupérées avec succès', [
                'data' => $result,
                'lastInsertId' => $this->connection->lastInsertId(),
            ]);

            return true;

        } catch (PDOException $e) {
            $this->handlePDOError($e, $sql, $params ?? []);
            return false;
        }
    }

    /**
     * ⚙️ Exécution rapide pour INSERT / UPDATE / DELETE
     */
    public function execute(string $sql, array $params = []): bool
    {
        try {
            if (!$this->connection) {
                $this->connect();
            }

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            $this->setResponse(true, 'Opération exécutée avec succès', [
                'lastInsertId' => $this->connection->lastInsertId(),
                'rowCount'     => $stmt->rowCount(),
            ]);

            return true;

        } catch (PDOException $e) {
            $this->handlePDOError($e, $sql, $params);
            return false;
        }
    }

        /**
     * Récupère les donnees du response.
     */
    public function getData():array{
        return $this->getResponse()->data;
    }

    /**
        * ⚠️ Méthode centralisée pour gérer toutes les erreurs PDO
    */
    protected function handlePDOError(PDOException $e, string $sql = '', array $params = []): ?stdClass
    {
        $errorCode = (int)($e->errorInfo[1] ?? 0);
        $errorMsg  = $e->getMessage();
        $errorField = null;
        $userMessage = 'Erreur inconnue lors de l’exécution SQL.';

        switch ($errorCode) {
            case 1062:
                if (preg_match("/for key '(.+?)'/", $errorMsg, $m)) {
                    $errorField = $m[1];
                }
                $userMessage = "Une entrée avec cette valeur existe déjà.";
                break;

            case 1048:
                if (preg_match("/Column '(.+?)'/", $errorMsg, $m)) {
                    $errorField = $m[1];
                }
                $userMessage = "Le champ requis '{$errorField}' ne peut pas être vide.";
                break;

            case 1452:
                $userMessage = "La valeur d’une clé étrangère n’existe pas dans la table liée.";
                break;

            case 1451:
                $userMessage = "Impossible de supprimer cet enregistrement car il est référencé ailleurs.";
                break;

            case 1364:
                if (preg_match("/Field '(.+?)'/", $errorMsg, $m)) {
                    $errorField = $m[1];
                }
                $userMessage = "Le champ '{$errorField}' n’a pas de valeur par défaut.";
                break;

            case 1054:
                if (preg_match("/Unknown column '(.+?)'/", $errorMsg, $m)) {
                    $errorField = $m[1];
                }
                $userMessage = "La colonne '{$errorField}' n’existe pas dans la table.";
                break;

            case 1146:
                $userMessage = "La table spécifiée est introuvable dans la base de données.";
                break;

            case 1064:
                $userMessage = "Erreur de syntaxe SQL : vérifie ta requête.";
                break;

            default:
                $userMessage = "Erreur SQL : {$errorMsg}";
                break;
        }

        // Journalisation de l’erreur (si un logger est défini)
       // $this->logger?->logError("Erreur SQL [Code: {$errorCode}] : {$errorMsg} | Champ: {$errorField} | SQL: {$sql} | Params: " . json_encode($params));

        // Mise à jour de la réponse via DatabaseHelperTrait
        if ($errorField) {
            return $this->setResponse(false, $userMessage, [
                'field'     => $errorField,
                'sql_error' => $errorMsg,
                'code'      => $errorCode,
            ]);
        } else {
            return $this->setResponse(false, $userMessage, [
                'sql_error' => $errorMsg,
                'code'      => $errorCode,
            ]);
        }
    }

        public function rollBack(){
        $this->connection->rollBack();
    }

    public function commit(){
        $this->connection->commit();
    }

    public function beginTransaction(){
        $this->connection->beginTransaction();
    }

     public function getLastInsertId(){
        $this->connection->lastInsertId();
    }

}


