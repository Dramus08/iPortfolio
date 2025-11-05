<?php
namespace Database;

use PDO;
use PDOException;
use Exception;
use Utils\MessageManager;
use Core\Logger;
use Validators\DataValidator;

/**
 * Gestion de la base de données MySQL avec gestion d’erreurs, logs et réponses normalisées.
 */
class MysqlDatabase extends AbstractDatabase
{
    protected string $host;
    protected string $db;
    protected string $user;
    protected string $pass;
    protected int $port;
    protected ?Logger $logger = null;
    protected ?string $tableName = null;
    protected string $primaryKey = 'id';
    protected DataValidator $validate;
    protected array $ColumnsInfoTable = [];

    protected ?string $logFile = __DIR__ . "/logs/database.log";

    private static ?self $instance = null;

    // =====================================================
    // 🔧 CONSTRUCTEUR ET SINGLETON
    // =====================================================
    public function __construct(
        string $host = DB_HOST,
        string $user = DB_USER,
        string $db = DB_NAME,
        string $pass = DB_PASS,
        int $port = 3306
    ) {
        $this->host = $host;
        $this->user = $user;
        $this->db   = $db;
        $this->pass = $pass;
        $this->port = $port;

        $this->connect();
        $this->logger = new Logger($this->logFile);
        self::$instance = $this; // permet un usage static cohérent
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // =====================================================
    // 🔌 CONNEXION MYSQL
    // =====================================================
    protected function connect(): void
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db};port={$this->port};charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $this->user, $this->pass, $this->options);
            $this->logger?->logInfo("Connexion MySQL réussie.");
            $this->setResponse(true, "Connexion MySQL établie avec succès.");
        } catch (PDOException $e) {
            $this->logger?->logError("Erreur de connexion MySQL : " . $e->getMessage());
            $this->setResponse(false, "Erreur de connexion MySQL : " . $e->getMessage());
            $this->logError("Connexion MySQL échouée : " . $e->getMessage());
        }
    }

    // =====================================================
    // 🧩 GETTERS / SETTERS
    // =====================================================
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    public function setDbName(string $dbName): void
    {
        $this->db = $dbName;
    }

    public function getDbName(): ?string
    {
        return $this->db;
    }

    // =====================================================
    // 🧮 REQUÊTES SQL
    // =====================================================
    public function prepare(string $sql): bool
    {
        if (!$this->connection) $this->connect();

        try {
            $this->connection->prepare($sql);
            $this->setResponse(true, "Requête préparée avec succès.");
            return true;
        } catch (PDOException $e) {
            $this->handlePDOError($e, $sql);
            return false;
        }
    }

    public function execute(string $sql, array $params = []): bool
    {
        if (!$this->connection) $this->connect();

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            //$this->logger?->logInfo("✅ Requête exécutée avec succès : [$sql]");
            $this->setResponse(true, "Requête exécutée avec succès.", [
                'lastInsertId' => $this->connection->lastInsertId(),
                'rowCount'     => $stmt->rowCount(),
            ]);
            return true;

        } catch (PDOException $e) {
            $this->handlePDOError($e, $sql, $params);
            $this->logger?->logError("❌ Erreur d'exécution SQL : " . $e->getMessage());
            return false;
        }
    }

    public function query(string $sql, ?array $params = null, bool $single = false): bool
    {
        if (!$this->connection) $this->connect();

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            $results = $single ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);

            //$this->logger?->logInfo("✅ Données récupérées avec succès : [$sql]");
            $this->setResponse(true, "Données récupérées avec succès.", $results);
            return true;

        } catch (PDOException $e) {
            $this->handlePDOError($e, $sql, $params);
            $this->logger?->logError("❌ Erreur de récupération SQL : " . $e->getMessage());
            return false;
        }
    }

    public function lastInsertId(): string
    {
        return $this->connection?->lastInsertId() ?? '0';
    }

    // =====================================================
    // 🪵 LOGS
    // =====================================================
    protected function logError(string $message): void
    {
        $dir = dirname($this->logFile);
        if (!is_dir($dir)) mkdir($dir, 0775, true);

        $entry = "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL;
        file_put_contents($this->logFile, $entry, FILE_APPEND);
    }

    protected function logMessage(string $type, string $message, ?string $context = null): void
    {
        if (!$this->connection) return;

        try {
            $stmt = $this->connection->prepare("
                INSERT INTO app_logs (type, message, context, user_id, ip_address, user_agent)
                VALUES (:type, :message, :context, :user_id, :ip, :agent)
            ");

            $stmt->execute([
                ':type'    => $type,
                ':message' => $message,
                ':context' => $context,
                ':user_id' => $_SESSION['user']['id'] ?? null,
                ':ip'      => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                ':agent'   => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            ]);
        } catch (Exception $e) {
            error_log("[MessageManager] Erreur de log : " . $e->getMessage());
        }
    }

    // =====================================================
    // 🧠 UTILITAIRES DE TABLE
    // =====================================================
    public function isTableExist($table): bool
    {
        return $this->query("SHOW TABLES LIKE '{$table}'");
    }

    public function getColumnsInfoTable(string $table): array
    {
        if (!$this->isTableExist($table)) return [];
        $result = $this->query("DESCRIBE {$table}");
        return $result ? ($this->getData() ?? []) : [];
    }

    public function getColumnsTable(string $table): array
    {
        $columns = $this->getColumnsInfoTable($table);
        return !empty($columns) ? array_column($columns, 'Field') : [];
    }

    public static function getFilteredData($table, array $data): array
    {
        $instance = self::getInstance();
        $columns = $instance->getColumnsTable($table);
        $filtered = array_intersect_key($data, array_flip($columns));

        if (empty($filtered)) {
            throw new Exception("Aucune donnée valide trouvée pour la table '{$table}'.");
        }
        return $filtered;
    }

    // =====================================================
    // 🗂️ CRUD
    // =====================================================
    public static function create(string $table, array $data, array $files = []): bool
    {
        $instance = self::getInstance();
        $instance->validate = new DataValidator(
            $instance->getColumnsInfoTable($table),
            self::getFilteredData($table, $data)
        );

        $instance->validate->setTable($table);

        try {
            if (!$instance->isTableExist($table)) {
                throw new Exception("La table '{$table}' n'existe pas.");
            }

            $filtered = $instance->validate->validate();

            if ($instance->validate->isValidData()) {
                $sql = $instance->sqlCreate($table, $filtered);
                return $instance->execute($sql, $filtered);
            }

            return false;

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public static function update(string $table, int|string $id, array $data, array $files = []): bool
    {
        $instance = self::getInstance();
        $instance->validate = new DataValidator(
            $instance->getColumnsInfoTable($table),
            self::getFilteredData($table, $data),
            $id
        );

        $instance->validate->setTable($table);

        try {
            if (!$instance->isTableExist($table)) {
                throw new Exception("La table '{$table}' n'existe pas.");
            }

            $filtered = $instance->validate->validate();
            if ($instance->validate->isValidData()) {
                $sql = $instance->sqlUpdate($table, $filtered);
                $filtered['id'] = $id;
                return $instance->execute($sql, $filtered);
            }

            return false;

        } catch (Exception $e) {
            echo "[[ ERREUR ]] : " . $e->getMessage();
            return false;
        }
    }

    public function delete(string $table, int|string $id): bool
    {
        $sql = "DELETE FROM {$table} WHERE {$this->primaryKey} = :id";
        return $this->execute($sql, ['id' => $id]);
    }

    protected function sqlCreate(string $table, array $data): string
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($c) => ":$c", $columns);

        return sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
    }

    protected function sqlUpdate(string $table, array $data): string
    {
        $set = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));
        return "UPDATE {$table} SET {$set} WHERE {$this->primaryKey} = :id";
    }

    // =====================================================
    // 💧 HYDRATATION D’OBJETS
    // =====================================================
    protected function hydrate(array $row): static
    {
        $obj = new static();
        foreach ($row as $key => $value) {
            if (property_exists($obj, $key)) {
                $obj->$key = $value;
            } else {
                $obj->fields[$key] = $value;
            }
        }
        return $obj;
    }

    protected function hydrateAll(array $rows): array
    {
        return array_map(fn($r) => $this->hydrate($r), $rows);
    }
}
