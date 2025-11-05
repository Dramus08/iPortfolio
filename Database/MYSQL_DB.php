<?php

namespace Database;

use PDO;
use PDOException;
use Exception;
use stdClass;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Database\AbstractDatabase ;

/**
 * Class MYSQL_DB
 * Gestionnaire PDO universel pour MySQL.
 * Fournit des fonctions CRUD, d'introspection et d'exportation.
 * 
 * @package Core
 */
class MYSQL_DB extends AbstractDatabase
{
    private PDO $conn;
    protected array $options;
            private string $dsn;

    public function __construct(
        private string $host = DB_HOST,
        private string $user = DB_USER,
        private string $dbase = DB_NAME,
        private string $pass = DB_PASS,
        private int $port = 3306
    ) {
        $this->options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
        ];

        $this->connect();
    }

    /**
     * Initialise la connexion PDO
     */
    protected function connect(): void
    {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbase}";
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $this->options);
        } catch (PDOException $e) {
            die("❌ [MYSQL_DB] Connexion échouée : " . $e->getMessage());
        }
    }

    public static function getConnection(): PDO
    {
        var_dump($this->dsn);
        return $this->conn;
    }

    public function lastInsertId(): string
    {
        return $this->conn->lastInsertId();
    }

    // ===============================================================
    // 🔹 GESTION DES REQUÊTES
    // ===============================================================

    /**
     * Exécute une requête SQL sécurisée
     */
    public function querys(string $sql, ?array $params = null, bool $single = false): stdClass
    {
        $response = new stdClass();

        try {
            $stmt = $params ? $this->conn->prepare($sql) : $this->conn->query($sql);
            if ($params) $stmt->execute($params);

            $response->success = true;
            $response->data = $single ? $stmt->fetch() : $stmt->fetchAll();

        } catch (PDOException $e) {
            $response->success = false;
            $response->error = $e->getMessage();
            if ($this->logger) $this->logger->logError($e->getMessage());
        }

        return $response;
    }

    // ===============================================================
    // 🔹 UTILITAIRES D'INTROSPECTION
    // ===============================================================

    public function listDatabases(): stdClass
    {
        return $this->query("SHOW DATABASES");
    }

    public function getTables(?string $db = null): stdClass
    {
        $db = $db ?? $this->dbase;
        return $this->query(
            "SELECT table_name FROM information_schema.tables WHERE table_schema = :db",
            ['db' => $db]
        );
    }

    public function describeTable(string $table): stdClass
    {
        return $this->query("DESCRIBE {$table}");
    }

    public function getColumnNames(string $table): array
    {
        $cols = $this->describeTable($table)->data ?? [];
        return array_column($cols, 'Field');
    }

    // ===============================================================
    // 🔹 TRAITEMENT DES DONNÉES
    // ===============================================================

    protected function sanitize(array $data): array
    {
        $fields = [];
        foreach ($data as $key => $val) {
            $val = htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
            if (stripos($key, 'pass') !== false)
                $val = password_hash($val, PASSWORD_BCRYPT);
            $fields[$key] = $val;
        }
        return $fields;
    }

    private function cleanData(string $table, array $data): array
    {
        $validCols = $this->getColumnNames($table);
        return array_intersect_key($data, array_flip($validCols));
    }

    // ===============================================================
    // 🔹 CRUD SIMPLIFIÉ
    // ===============================================================

    public function all(string $table): stdClass
    {
        return $this->query("SELECT * FROM {$table} ORDER BY id DESC");
    }

    public function find(string $table, int $id): stdClass
    {
        return $this->query("SELECT * FROM {$table} WHERE id = :id", ['id' => $id], true);
    }

    public function create(string $table, array $data, ?array $files = null): stdClass
    {
        $data = $this->sanitize($this->handleFiles($data, $files));
        $data = $this->cleanData($table, $data);

        $keys = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$keys}) VALUES ({$placeholders})";
        return $this->query($sql, $data);
    }

    public function update(string $table, int $id, array $data): stdClass
    {
        $data = $this->sanitize($this->cleanData($table, $data));

        $set = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($data)));
        $data['id'] = $id;

        $sql = "UPDATE {$table} SET {$set} WHERE id = :id";
        return $this->query($sql, $data);
    }

    public function delete(string $table, int $id): stdClass
    {
        return $this->query("DELETE FROM {$table} WHERE id = :id", ['id' => $id]);
    }

    private function handleFiles(array $data, ?array $files = null): array
    {
        if (!$files) return $data;
        foreach ($files as $key => $file) {
            if ($file['error'] === UPLOAD_ERR_OK)
                $data[$key] = basename($file['name']);
        }
        return $data;
    }

    // ===============================================================
    // 🔹 EXPORTATION (EXCEL, CSV, PDF)
    // ===============================================================

    public function export(string $table, string $format = 'xlsx', string $filename = 'export'): void
    {
        $data = $this->all($table)->data;
        if (!$data) return;

        $filename .= '.' . strtolower($format);

        switch ($format) {
            case 'xlsx':
                $writer = new Xlsx($this->arrayToSheet($data));
                break;
            case 'csv':
                $writer = new Csv($this->arrayToSheet($data));
                break;
            case 'pdf':
                $this->exportToPDF($table, $filename);
                return;
            default:
                throw new Exception("Format non pris en charge : {$format}");
        }

        $writer->save($filename);
    }

    private function arrayToSheet(array $data): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray($data, null, 'A1');
        return $spreadsheet;
    }

    private function exportToPDF(string $table, string $filename): void
    {
        $rows = $this->all($table)->data;
        $html = "<h2>Données de la table {$table}</h2><table border='1' cellpadding='5'><tr>";
        if ($rows) {
            foreach (array_keys((array)$rows[0]) as $col) $html .= "<th>{$col}</th>";
            $html .= "</tr>";
            foreach ($rows as $row) {
                $html .= "<tr>";
                foreach ($row as $val) $html .= "<td>{$val}</td>";
                $html .= "</tr>";
            }
        }
        $html .= "</table>";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename);
    }
}
