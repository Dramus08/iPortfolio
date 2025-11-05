<?php
namespace Core;

use Database\AbstractDatabase;
use Database\DatabaseFactory;
use Database\MysqlDatabase;
use Validators\DataValidator;
//use Validators\DataManager;

use Exception;
use stdClass;

/**
 * Classe Model de base pour ORM léger.
 * Gère automatiquement CRUD et hydratation d’objets.
 */
abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected AbstractDatabase $db;
    protected array $fields = [];
    public DataValidator $validate;
    // les differents dossiers pour enregistrer les media 

    public string $folderImageFile='uploads/images/';
    public string $folderVideoFile='uploads/videos/';
    public string $folderAudioFile='uploads/audios/';
    public string $folderFile='uploads/files/';
    protected array $errors=[];
    protected array $errorForms=[];

    public function __construct(string $dbDriver = 'mysql')
    {
        $this->db = DatabaseFactory::create($dbDriver);   
    }
        public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Vérifie si la table existe.
     */
    public function isTableExist(): bool
    {
        return  $this->db->isTableExist($this->table);
    }

    public function generateSlug(string $fieldData){
        // Génération du slug
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', htmlspecialchars($fieldData)), '-'));
    }

    /**
     * Récupère la structure de la table.
     */
    public function getColumnsInfoTable(): array
    {
        return $this->db->getColumnsInfoTable($this->table);
    }

    /**
     * Récupère uniquement les noms des colonnes.
     */
    public function getColumnsTable(): array
    {
        return $this->db->getColumnsTable($this->table);
    }

    /**
     * Filtre les données pour ne garder que les champs existants dans la table.
     */
    public function getFilteredData(array $data): array
    {
        return $this->db->getFilteredData($this->table,$data);
    }
    public function isInvalidData(): bool
    {
        return !empty($this->validate->getErrors());
    }

    public function isValidData(): bool
    {
        return empty($this->validate->getErrors());
    }

    public function managementFile($column){
         // Gestion des fichiers
        if (!empty($_FILES[$column]['name'])) {
            $data[$column] = $this->uploadFile($_FILES[$column], $this->folderImageFile);
        }

        if (!empty($_FILES[$column]['name'])) {
            $data[$column] = $this->uploadFile($_FILES[$column], $this->folderVideoFile);
        }
        if (!empty($_FILES[$column]['name'])) {
            $data[$column] = $this->uploadFile($_FILES[$column], $this->folderAudioFile);
        }
         if (!empty($_FILES[$column]['name'])) {
            $data[$column] = $this->uploadFile($_FILES[$column], $this->folderFile);
        }
    }

      /**
     * Gestion centralisée de l’upload.
     */
    private function uploadFile($file, $destination)
    {
        return MysqlDatabase::uploadFile($this->table,$file,$destination);
    }

    /**
     * Crée un nouvel enregistrement.
     */
    public function create(array $data,array $files=[]): bool
    {
       return MysqlDatabase::create($this->table,$data,$files);
    }

       /**
     * Met à jour un enregistrement.
     */
    public function update(int|string $id, array $data=[],array $files=[]): bool
    {
         return $this->db::update($this->table,$id,$data,$files);
    }


    /**
     * On met a jour une response
     */
        /**
     * ✅ Définit une réponse standardisée (succès ou erreur)
     */
    public function setResponse(
        bool $success = true,
        string $message = '',
        array $dataOrError = []): stdClass{
        return $this->db->setResponse($success, $message,$dataOrError);
    }

    /**
     * ✅ Retourne la dernière réponse complète (toujours un objet stdClass)
     */
    public function getResponse(): stdClass
    {
        return $this->db->getResponse();
    }

    protected function handlePDOError(PDOException $e, string $sql = '', array $params = []): array
    {
        $errorCode  = (int)($e->errorInfo[1] ?? 0);
        $errorMsg   = $e->getMessage();
        $errorField = null;

        // Messages d’erreur spécifiques par code
        $messages = [
            1062 => "Une entrée avec cette valeur existe déjà.",
            1048 => "Le champ requis '{field}' ne peut pas être vide.",
            1452 => "La valeur d’une clé étrangère n’existe pas dans la table liée.",
            1451 => "Impossible de supprimer cet enregistrement car il est référencé ailleurs.",
            1364 => "Le champ '{field}' n’a pas de valeur par défaut.",
            1054 => "La colonne '{field}' n’existe pas dans la table.",
            1146 => "La table spécifiée est introuvable dans la base de données.",
            1064 => "Erreur de syntaxe SQL : vérifie ta requête."
        ];

        // Détecte le champ lié à l’erreur si nécessaire
        if (in_array($errorCode, [1062, 1048, 1364, 1054])) {
            if (preg_match("/(?:for key|Column|Field|Unknown column) '(.+?)'/", $errorMsg, $m)) {
                $errorField = $m[1];
            }
        }

        // Message final
        $userMessage = $messages[$errorCode] ?? "Erreur SQL : {$errorMsg}";
        if ($errorField) {
            $userMessage = str_replace('{field}', $errorField, $userMessage);
        }

        // Journalisation si logger défini
        $this->logger?->logError(
            "Erreur SQL [Code: {$errorCode}] : {$errorMsg} | Champ: {$errorField} | SQL: {$sql} | Params: " . json_encode($params)
        );

        // Retourne la réponse standardisée
        $responseData = ['sql_error' => $errorMsg, 'code' => $errorCode];
        if ($errorField) {
            $responseData['field'] = $errorField;
        }

        return $this->setResponse(false, $userMessage, $responseData);
    }

    /**
     * Récupère les donnees du response.
     */
    public function getData():array{
        return $this->getResponse()->data;
    }

    /**
     * Récupère tous les enregistrements.
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC";
        $this->db->query($sql);

        $data = $this->getData() ?? [];
        return $this->hydrateAll($data);
    }

    /**
     * Récupère un enregistrement par ID.
     */
    public function find(int|string $id): ?static
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $this->db->query($sql, ['id' => $id]);

        $data = $this->getData()[0] ?? null;
        return $data ? $this->hydrate($data) : null;
    }

    /**
     * Condition simple WHERE.
     */
    public function where(string $column, mixed $value, string $operator = '='): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :val";
        $this->db->query($sql, ['val' => $value]);
        $data = $this->getData()[0] ?? [];
        return $data ? $this->hydrate($data) : null;
    }

    /**
     * Récupère le premier enregistrement correspondant.
     */
    public function first(string $column, mixed $value, string $operator = '='): mixed
    {
        return $this->where($column, $value, $operator) ;
    }

 

    /**
     * Supprime un enregistrement.
     */
    public function delete(int|string $id): bool
    {
        return $this->db->delete($this->table,$id); 
    }

    /**
     * Nettoie et sécurise les données d’un formulaire.
     */
    public function sanitizeFormData(?array $source = null, bool $ignoreEmpty = false): array
    {
        $data = $source ?? $_POST;
        $clean = [];

        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $clean[$key] = $this->sanitizeFormData($val, $ignoreEmpty);
            } else {
                $val = trim($val);
                if ($ignoreEmpty && $val === '') continue;
                $clean[$key] = htmlspecialchars($val);//, ENT_QUOTES | ENT_HTML5, 'UTF-8'
            }
        }

        return $clean;
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

    /**
     * Retourne la dernière réponse DB.
     */

/**
 * Récupère les éléments d'une table avec leurs tags associés.
 *
 * @param PDO $pdo Instance PDO connectée à la base de données
 * @param string $table Nom de la table à récupérer (ex: 'projects', 'services', etc.)
 * @param array $columns Colonnes à récupérer de la table principale (ex: ['id','title','description'])
 * @return array Liste des éléments avec leurs tags
 */

public function getItemsWithTags(string $table = null,int|string $id=null): ?array 
{
    // Détermine la table concernée
    $table = $this->table ?? $table;
    if (!$table) {
        throw new Exception("Aucune table spécifiée pour getItemsWithTags().");
    }


    // Récupère les colonnes de la table principale
    $columns = $this->getColumnsTable();
    $cols = implode(", ", array_map(fn($c) => "p.$c", $columns));
    $is_id= $id ? "WHERE id='".$this->primaryKey."'": '' ;
    // Requête SQL
    $sql = "
        SELECT 
            $cols,
            t.id AS tag_id,
            t.name AS tag_name,
            t.color AS tag_color
        FROM $table p
        LEFT JOIN tagged_items ti 
            ON ti.tagged_table = :table AND ti.tagged_id = p.id
        LEFT JOIN tags t 
            ON t.id = ti.tag_id
        $is_id
            ORDER BY p.id
    ";

    // Exécution de la requête (préparée)
    $stmt = $this->db->query($sql,['table' => $table]);
    //$stmt->execute();

    // Récupération des données
    //$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results= !$id ? $this->getData(): $this->getData()[0];
    //var_dump($this->hydrateAll($results));
     //echo "</br><pre>";print_r($this->hydrateAll($results)); echo "</pre></br>";

    if (!$results) {
        return [];
    }

    // Construction des items avec tags
    $items = [];
    foreach ($results as $row) {
        $id = $row['id'];

        if (!isset($items[$id])) {
            // Initialise les données du projet/élément
            $items[$id] = [
                'tags' => []
            ];
            foreach ($columns as $col) {
                $items[$id][$col] = $row[$col] ?? null;
            }
        }

        if (!empty($row['tag_id'])) {
            $items[$id]['tags'][] = (object)[
                'id' => $row['tag_id'],
                'name' => $row['tag_name'],
                'color' => $row['tag_color']
            ];
        }
    }

    // Renvoie les résultats sous forme de tableau réindexé
    //return array_values($items);
    return $this->hydrateAll(array_values($items));
}




// Exemple d'utilisation :
// $projects = getItemsWithTags($pdo, 'projects', ['id','title','slug','category','description']);
// $services = getItemsWithTags($pdo, 'services', ['id','name','description']);
// print_r($projects);
// print_r($services);

}














