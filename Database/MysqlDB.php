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
    // Le trait est déjà inclus dans AbstractDatabase, pas besoin de le réimporter ici.
    // use DatabaseHelperTrait;

    protected string $host;
    protected string $db;
    protected string $user;
    protected string $pass;
    protected int $port;
    protected ?Logger $logger = null;
    protected ?string $tableName = null;
    protected string $primaryKey = 'id';
    protected DataValidator $validate;
    protected array $ColumnsInfoTable=[];


    protected ?string $logFile = __DIR__."/logs/database.log";

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
        $this->logger=new Logger($this->logFile);

    }

    /**
     * 🔌 Connexion à MySQL
     */
    protected function connect(): void
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db};port={$this->port};charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $this->user, $this->pass, $this->options);

            // ✅ Connexion réussie
            // Journalisation de l’erreur (si un logger est défini)
            $this->logger?->logInfo(
                "Connection Reussi !" 
            );
            $this->setResponse(true, "Connexion MySQL établie avec succès.");

        } catch (PDOException $e) {
            // ❌ Connexion échouée
            $this->logger?->logError(
                "Erreur de connexion MySQL : " 
            );
            $this->setResponse(false, "Erreur de connexion MySQL : " . $e->getMessage());
            $this->logError("Connexion MySQL échouée : " . $e->getMessage());
        }
    }

    public function getConnection():PDO{
        return $this->connection;
    }

    public function getTableName():?string{
        return $this->tableName;
    }

    public function setTableName(string $tableName):void{
         $this->tableName=$tableName;
    }

    public function setDbName(string $dbName):void{
         $this->db=$dbName;
    }

    public function getDbName(string $dbName):?string{
         return  $this->db;
    }

    /**
     * 📄 Prépare une requête SQL
     */
    public function prepare(string $sql): bool
    {
        if (!$this->connection) {
            $this->connect();
        }

        try {
            $this->connection->prepare($sql);
            $this->setResponse(true, "Requête préparée avec succès.");
            return true;

        } catch (PDOException $e) {
            $this->handlePDOError($e, $sql);
            return false;
        }
    }

    /**
     * ⚙️ Exécute une requête SQL générique (INSERT, UPDATE, DELETE)
     */
    public function execute(string $sql, array $params = []): bool
    {
        if (!$this->connection) {
            $this->connect();
        }

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            if($params){
                $set="";
                foreach ($params as $key => $value) {
                    $set.="'$key' => '".$params[$key]."' \n";
                }
            }
            $this->logger?->logInfo(
                "success ==>\tRequête exécutée avec succès."."\n\nSQL : [$sql] ==>  [$set]".$set 
            );
            $this->logMessage("success","\tRequête exécutée avec succès."."\n\nSQL : [$sql] ==>  [$set]".$set);
            
            $this->setResponse(true, "Requête exécutée avec succès.", [
                'lastInsertId' => $this->connection->lastInsertId(),
                'rowCount'     => $stmt->rowCount(),
            ]);

            return true;

        } catch (PDOException $e) {
            $this->handlePDOError($e, $sql, $params);
            $set="";
            foreach ($params as $key => $value) {
                $set.="'$key' => '".$params[$key]."' \n";
            }
            $this->logger?->logError(
                "error ==>\t".$e->getMessage()."\n\nSQL : [$sql] ==>  [$set]".$set 
            );
           $this->logMessage("error","\n".$e->getMessage()."\n\nSQL : [$sql] ==>  [$set]".$set);
            return false;
        }
    }

    /**
     * 🔍 Exécute une requête SELECT et retourne les résultats.
     */
    public function query(string $sql, ?array $params = null, bool $single = false): bool
    {
        if (!$this->connection) {
            $this->connect();
        }

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            $results = $single ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$set = implode(', ', array_map(fn($params) => "'$col' => '".$params[$col]."'", array_keys($params)));
             $set="";
            if($params){
               
            foreach ($params as $key => $value) {
                $set.="'$key' => '".$params[$key]."' \n";
            }
            }
            $this->logger?->logInfo(
                "success ==>\tDonnées récupérées avec succès."."\n\nSQL : [$sql] ==>  [$set]" 
            );
            $this->logMessage("success","\tDonnées récupérées avec succès."."\n\nSQL : [$sql] ==>  [$set]");
            $this->setResponse(true, "Données récupérées avec succès.",$results);
            return true;

        } catch (PDOException $e) {
            $this->handlePDOError($e, $sql, $params);
            $set="";
            foreach ($params as $key => $value) {
                $set.="'$key' => '".$params[$key]."' \n";
            }
            $this->logMessage("error","\n".$e->getMessage()."\n\nSQL : [$sql] ==>  [$set]".$set);
            return false;
        }
    }

    /**
     * 🔢 Récupère la dernière ID insérée
     */
    public function lastInsertId(): string
    {
        return $this->connection?->lastInsertId() ?? '0';
    }

    /**
     * 🧠 Fonction de debug SQL (interpolation des paramètres)
     */
    public function debugQuery(string $sql, array $params = []): void
    {
        $interpolated = $sql;
        foreach ($params as $key => $val) {
            $interpolated = str_replace(":$key", is_numeric($val) ? $val : "'$val'", $interpolated);
        }

        echo "<pre style='background:#222;color:#0f0;padding:10px;border-radius:5px'>DEBUG SQL:\n$interpolated</pre>";
    }

    /**
     * 🪵 Écrit un message d’erreur dans le fichier log
     */
    protected function logError(string $message): void
    {
        $dir = dirname($this->logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $entry = "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL;
        file_put_contents($this->logFile, $entry, FILE_APPEND);
    }

     /* =====================================================
     * 🔹 LOGIQUE D’AUDIT : ENREGISTREMENT DANS app_logs
     * ===================================================== */

    protected function logMessage(string $type, string $message, ?string $context = null): void
    {
        if (!$this->connection) return;

        try {
            $stmt = $this->connection->prepare("
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
     /**
     * Vérifie si la table existe.
     */
    public function isTableExist($table): bool
    {
        return  $this->query("SHOW TABLES LIKE '{$table}'");
    }

        /**
     * Récupère la structure de la table.
     */
    public function getColumnsInfoTable(string $table): array
    {
        if (!$this->isTableExist($table)) return [];
        $result = $this->query("DESCRIBE {$table}");
        return $result ? ($this->getData() ?? []) : [];
    }

    /**
     * Récupère uniquement les noms des colonnes.
     */
    public  function getColumnsTable(string $table): array
    {
        $columns = $this->getColumnsInfoTable($table);
        
        return !empty($columns) ? array_column($columns, 'Field') : [];
    }

        /**
     * Filtre les données pour ne garder que les champs existants dans la table.
     */
    public static function getFilteredData($table,array $data): array
    {
        $filtered=array_intersect_key($data, array_flip($this->getColumnsTable($table)));
        
         if (empty($filtered)) {
                throw new Exception("Aucune donnée valide trouvée pour la table '{$table}'.");
        }else{
            return $filtered;
        }
    }

    /**
     * Gestion centralisée de l’upload.
     */
    private static function uploadFile($table,$file, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $filename = $table."_".time() . '_' . basename($file['name']);
        $targetPath = $destination . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $targetPath;
        }
        return null;
    }

    /**
     * Crée un nouvel enregistrement.
     */
    public static function create(string $table,array $data,array $files=[]): bool
    {
        $this->validate=new DataValidator($this->getColumnsInfoTable($table),self::getFilteredData($table,$data));
        $this->validate->setTable($table);
        try {
            if (!$this->isTableExist()) {
                throw new Exception("La table '{$table}' n'existe pas.");
            }
            $filtered=$this->validate->validate();
            if($this->validate->isValidData()){
                $sql=$this->sqlCreate($table,$filtered);
                return $this->execute($sql, $filtered);
            }
            else{return false;}

        } catch (Exception $e) {
            //$this->setErrors('exception', $e->getMessage());
            echo $e->getMessage();
            return false;
        }
    }

       /**
     * Met à jour un enregistrement.
     */
    public static function update(string $table,int|string $id, array $data,array $files=[]): bool
    {
        $this->validate=new DataValidator($this->getColumnsInfoTable($table),self::getFilteredData($table,$data),$id);
        $this->validate->setTable($table);

        try {
            if (!$this->isTableExist()) {
                throw new Exception("La table '{$table}' n'existe pas.");
            }
            $filtered=$this->validate->validate();
            if($this->validate->isValidData()){
                $sql=$this->sqlUpdate($table,$filtered);
                $filtered['id']=$id;
                return $this->execute($sql, $filtered);
            
            }else{
                return false;
            }
        } catch (Exception $e) {
            //$this->setErrors('exception', $e->getMessage());
            echo "[[ ERREUR ]] : ".$e->getMessage();
            return false;
        }
    }

     /**
     * Supprime un enregistrement.
     */
    public  function delete(string $table,int|string $id): bool
    {
        $sql = "DELETE FROM {$table} WHERE {$this->primaryKey} = :id";
        return $this->execute($sql, ['id' => $id]);
    }

    protected function sqlCreate(string $table,array $data){
        $columns = array_keys($data);
        $placeholders = array_map(fn($c) => ":$c", $columns);
                $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                $table,
                implode(', ', $columns),
                implode(', ', $placeholders)
            );
            return $sql;
    }

    protected function sqlUpdate(string $table,array $data):string{
        $set = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));
            $sql = "UPDATE {$table} SET {$set} WHERE {$this->primaryKey} = :id";
        return $set;
    }
    

    /**
     * Hydrate un objet depuis un tableau.
     */
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

     /**
     * Hydrate plusieurs objets.
     */
    protected function hydrateAll(array $rows): array
    {
        return array_map(fn($r) => $this->hydrate($r), $rows);
    }


    

}


