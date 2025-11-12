<?php
namespace Core;

use Database\AbstractDatabase;
use Database\DatabaseFactory;
use Database\MysqlDatabase;
use Validators\DataValidator;
use Exception;
use stdClass;
use PDOException;

/**
 * Classe Model de base pour ORM léger professionnel.
 * Gère automatiquement CRUD, hydratation d'objets et relations optimisées.
 */
abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected MysqlDatabase $db;
    protected array $fields = [];
    public DataValidator $validate;

    // Constantes pour les types de relations
    public const HAS_ONE = 'has_one';
    public const HAS_MANY = 'has_many';
    public const BELONGS_TO = 'belongs_to';
    public const MANY_TO_MANY = 'many_to_many';
    
    // Configuration des relations et optimisations
    protected array $relations = [];
    private array $loadedRelations = [];
    private array $loadingDepth = [];
    private int $maxDepth = 3;
    
    // Configuration des conventions de nommage
    protected array $namingConventions = [
        'foreign_key' => '{table}_id',
        'pivot_table' => '{table1}_{table2}',
        'relation_name' => '{table}'
    ];
    
    // Cache pour le batch loading
    private static array $batchCache = [];
    
    // les differents dossiers pour enregistrer les media 
    public string $folderImageFile = 'uploads/images/';
    public string $folderVideoFile = 'uploads/videos/';
    public string $folderAudioFile = 'uploads/audios/';
    public string $folderFile = 'uploads/files/';
    protected array $errors = [];
    protected array $errorForms = [];

    public function __construct(string $dbDriver = 'mysql')
    {
        $this->db = DatabaseFactory::create($dbDriver);
        $this->defineRelations();
    }

    // =========================================================================
    // MÉTHODES EXISTANTES (conservées sans modification)
    // =========================================================================

    /**
     * Vérifie si la table existe.
     */
    public function isTableExist(): bool
    {
        return $this->db->isTableExist($this->table);
    }

    public function generateSlug(string $fieldData): string
    {
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
        return $this->db->getFilteredData($this->table, $data);
    }

    public function isInvalidData(): bool
    {
        return !empty($this->validate->getErrors());
    }

    public function isValidData(): bool
    {
        return empty($this->validate->getErrors());
    }

    public function managementFile($column)
    {
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
     * Vérifie si un slug existe déjà
     * @param string $slug Le slug à vérifier
     * @param int|null $excludeUserId ID de l'utilisateur à exclure (pour les mises à jour)
     * @return bool
     */
    public function slugExists(string $slug, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        $params = ['slug' => $slug];

        if ($excludeUserId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeUserId;
        }

        $this->db->query($sql, $params);
        $result = $this->getData();
        
        return !empty($result) && $result[0]['count'] > 0;
    }

        public function generateUniqueSlug(string $name): string
    {
        $slug = $this->generateSlug($name);
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        //echo "📝 Slug généré : {$slug}\n";
        return $slug;
    }

    /**
     * Gestion centralisée de l'upload.
     */
    private function uploadFile($file, $destination)
    {
        return MysqlDatabase::uploadFile($this->table, $file, $destination);
    }

    /**
     * Crée un nouvel enregistrement.
     */
    public function create(array $data, array $files = []): bool
    {

        return MysqlDatabase::create($this->table, $data, $files);
    }

    /**
     * Met à jour un enregistrement.
     */
    public function update(int|string $id, array $data = [], array $files = []): bool
    {
        $DbInstance = $this->db->getInstance();
        return $DbInstance::update($this->table, $id, $data, $files);
    }

    /**
     * Définit une réponse standardisée (succès ou erreur)
     */
    public function setResponse(
        bool $success = true,
        string $message = '',
        array $dataOrError = []): ?stdClass
    {
        return $this->db->setResponse($success, $message, $dataOrError);
    }

    /**
     * Retourne la dernière réponse complète (toujours un objet stdClass)
     */
    public function getResponse(): stdClass
    {
        return $this->db->getResponse();
    }

    protected function handlePDOError(PDOException $e, string $sql = '', array $params = []): ?stdClass
    {
        $errorCode = (int)($e->errorInfo[1] ?? 0);
        $errorMsg = $e->getMessage();
        $errorField = null;

        $messages = [
            1062 => "Une entrée avec cette valeur existe déjà.",
            1048 => "Le champ requis '{field}' ne peut pas être vide.",
            1452 => "La valeur d'une clé étrangère n'existe pas dans la table liée.",
            1451 => "Impossible de supprimer cet enregistrement car il est référencé ailleurs.",
            1364 => "Le champ '{field}' n'a pas de valeur par défaut.",
            1054 => "La colonne '{field}' n'existe pas dans la table.",
            1146 => "La table spécifiée est introuvable dans la base de données.",
            1064 => "Erreur de syntaxe SQL : vérifie ta requête."
        ];

        if (in_array($errorCode, [1062, 1048, 1364, 1054])) {
            if (preg_match("/(?:for key|Column|Field|Unknown column) '(.+?)'/", $errorMsg, $m)) {
                $errorField = $m[1];
            }
        }

        $userMessage = $messages[$errorCode] ?? "Erreur SQL : {$errorMsg}";
        if ($errorField) {
            $userMessage = str_replace('{field}', $errorField, $userMessage);
        }

        $this->logger?->logError(
            "Erreur SQL [Code: {$errorCode}] : {$errorMsg} | Champ: {$errorField} | SQL: {$sql} | Params: " . json_encode($params)
        );

        $responseData = ['sql_error' => $errorMsg, 'code' => $errorCode];
        if ($errorField) {
            $responseData['field'] = $errorField;
        }

        return $this->setResponse(false, $userMessage, $responseData);
    }

    /**
     * Récupère les donnees du response.
     */
    public function getData(): array
    {
        return $this->getResponse()->data;
    }

    /**
     * Récupère le premier enregistrement correspondant.
     */
    public function first(string $column, mixed $value, string $operator = '='): mixed
    {
        return $this->where($column, $value, $operator);
    }

    /**
     * Supprime un enregistrement.
     */
    public function delete(int|string $id): bool
    {
        return $this->db->delete($this->table, $id);
    }

    /**
     * Nettoie et sécurise les données d'un formulaire.
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
                $clean[$key] = htmlspecialchars($val);
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

    public function getItemsWithTags(?int $id = null, ?string $table = null): mixed
    {
        $table = $table ?? $this->table;
        if (!$table) {
            throw new Exception("Aucune table spécifiée pour getItemsWithTags().");
        }

        $columns = $this->getColumnsTable();
        $cols = implode(", ", array_map(fn($c) => "p.$c", $columns));

        $sql = "
            SELECT 
                $cols,
                t.id AS tag_id,
                t.name AS tag_name,
                t.color AS tag_color
            FROM {$table} AS p
            LEFT JOIN tagged_items ti 
                ON ti.tagged_table = :table 
                AND ti.tagged_id = p.id
            LEFT JOIN tags t 
                ON t.id = ti.tag_id
        ";

        $params = ['table' => $table];
        if (!is_null($id)) {
            $sql .= " WHERE p.id = :id";
            $params['id'] = $id;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $this->db->query($sql, $params);
        $results = $this->getData();

        if (empty($results)) {
            return $id ? null : [];
        }

        $items = [];
        foreach ($results as $row) {
            $pid = $row['id'];

            if (!isset($items[$pid])) {
                $mainData = [];
                foreach ($columns as $col) {
                    $mainData[$col] = $row[$col] ?? null;
                }
                
                $items[$pid] = $this->hydrate($mainData);
                $items[$pid]->tags = [];
            }

            if (!empty($row['tag_id'])) {
                $tagData = [
                    'id' => $row['tag_id'],
                    'name' => $row['tag_name'],
                    'color' => $row['tag_color']
                ];
                
                $items[$pid]->tags[] = (object)$tagData;
            }
        }

        $final = array_values($items);
        
        if ($id) {
            return $final[0] ?? null;
        }
        
        return $final;
    }

    // =========================================================================
    // SYSTÈME DE RELATIONS OPTIMISÉ (Nouvelles méthodes)
    // =========================================================================

    /**
     * Méthode à surcharger dans les modèles enfants pour définir les relations
     */
    protected function defineRelations(): void
    {
        // À implémenter dans les classes enfants
    }

    /**
     * Définit une relation has_one
     */
    protected function hasOne(string $relatedModel, ?string $foreignKey = null, ?string $localKey = null): void
    {
        $foreignKey = $foreignKey ?? $this->getDefaultForeignKey();
        $localKey = $localKey ?? $this->primaryKey;
        
        $this->relations[] = [
            'type' => self::HAS_ONE,
            'related_model' => $relatedModel,
            'foreign_key' => $foreignKey,
            'local_key' => $localKey,
            'lazy' => true
        ];
    }

    /**
     * Définit une relation has_many
     */
    protected function hasMany(string $relatedModel, ?string $foreignKey = null, ?string $localKey = null): void
    {
        $foreignKey = $foreignKey ?? $this->getDefaultForeignKey();
        $localKey = $localKey ?? $this->primaryKey;
        
        $this->relations[] = [
            'type' => self::HAS_MANY,
            'related_model' => $relatedModel,
            'foreign_key' => $foreignKey,
            'local_key' => $localKey,
            'lazy' => true
        ];
    }

    /**
     * Définit une relation belongs_to
     */
    protected function belongsTo(string $relatedModel, ?string $foreignKey = null, ?string $ownerKey = null): void
    {
        $foreignKey = $foreignKey ?? $this->getDefaultBelongsToForeignKey($relatedModel);
        $ownerKey = $ownerKey ?? 'id';
        
        $this->relations[] = [
            'type' => self::BELONGS_TO,
            'related_model' => $relatedModel,
            'foreign_key' => $foreignKey,
            'owner_key' => $ownerKey,
            'lazy' => true
        ];
    }

    /**
     * Définit une relation many_to_many
     */
    protected function belongsToMany(string $relatedModel, string $pivotTable, ?string $foreignPivotKey = null, ?string $relatedPivotKey = null): void
    {
        $foreignPivotKey = $foreignPivotKey ?? $this->getDefaultForeignKey();
        $relatedPivotKey = $relatedPivotKey ?? $this->getDefaultForeignKey($relatedModel);
        
        $this->relations[] = [
            'type' => self::MANY_TO_MANY,
            'related_model' => $relatedModel,
            'pivot_table' => $pivotTable,
            'foreign_pivot_key' => $foreignPivotKey,
            'related_pivot_key' => $relatedPivotKey,
            'lazy' => true
        ];
    }

    /**
     * Récupère le nom de la table
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Récupère la clé primaire
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Charge uniquement les relations spécifiées
     */
    public function with(array $relations): static
    {
        foreach ($relations as $relation) {
            $this->loadRelation($relation);
        }
        return $this;
    }

    /**
     * Charge toutes les relations pour cet objet avec contrôle de profondeur
     */
    public function loadRelations(int $maxDepth = 3): static
    {
        $this->maxDepth = $maxDepth;
        $this->loadingDepth = [];
        $this->loadRelationsRecursive(1);
        return $this;
    }

    /**
     * Charge une relation spécifique
     */
    public function loadRelation(string $relationName): static
    {
        foreach ($this->relations as $relation) {
            $relationKey = $this->getRelationKey($relation['related_model']);
            
            if ($relationKey === $relationName) {
                $this->loadSingleRelation($relation, 1);
                break;
            }
        }
        return $this;
    }

    /**
     * Récupère tous les enregistrements avec relations optimisées
     */
    public function all(bool $withRelations = false, array $specificRelations = []): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC";
        $this->db->query($sql);

        $data = $this->getData() ?? [];
        $objects = $this->hydrateAll($data);
        
        if ($withRelations && !empty($this->relations)) {
            if (!empty($specificRelations)) {
                $this->batchLoadSpecificRelations($objects, $specificRelations);
            } else {
                $this->batchLoadAllRelations($objects);
            }
        }
        
        return $objects;
    }

    /**
     * Récupère tous les enregistrements avec des relations spécifiques
     */
    public function allWith(array $relations = []): array
    {
        return $this->all(true, $relations);
    }

    /**
     * Récupère un enregistrement par ID avec ses relations
     */
    public function find(int|string $id, bool $withRelations = false, array $specificRelations = []): ?static
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $this->db->query($sql, ['id' => $id]);

        $data = $this->getData()[0] ?? null;
        
        if (!$data) {
            return null;
        }
        
        $object = $this->hydrate($data);
        
        if ($withRelations) {
            if (!empty($specificRelations)) {
                foreach ($specificRelations as $relation) {
                    $object->loadRelation($relation);
                }
            } else {
                $object->loadRelations();
            }
        }
        
        return $object;
    }

    /**
     * Condition simple WHERE avec relations optionnelles
     */
    public function where(string $column, mixed $value, string $operator = '=', bool $withRelations = false): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :val";
        $this->db->query($sql, ['val' => $value]);
        $data = $this->getData()[0] ?? [];
        
        if (!$data) {
            return null;
        }
        
        $object = $this->hydrate($data);
        
        if ($withRelations) {
            $object->loadRelations();
        }
        
        return $object;
    }

    /**
     * Pagination optimisée avec relations
     */
    public function paginate(int $page = 1, int $perPage = 15, array $relations = []): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql, ['limit' => $perPage, 'offset' => $offset]);
        $data = $this->getData() ?? [];
        $objects = $this->hydrateAll($data);
        
        if (!empty($relations)) {
            $this->batchLoadSpecificRelations($objects, $relations);
        }
        
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $this->db->query($countSql);
        $total = $this->getData()[0]['total'] ?? 0;
        
        return [
            'data' => $objects,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }

    /**
     * Retourne les données sous forme de tableau avec contrôle des relations
     */
    public function toArray(bool $withRelations = false): array
    {
        $data = [];
        
        foreach ($this->fields as $key => $value) {
            $data[$key] = $value;
        }
        
        if ($withRelations) {
            foreach ($this->relations as $relation) {
                $relationKey = $this->getRelationKey($relation['related_model']);
                if (isset($this->{$relationKey})) {
                    if ($relation['type'] === self::HAS_MANY || $relation['type'] === self::MANY_TO_MANY) {
                        $data[$relationKey] = array_map(fn($item) => $item->toArray(false), $this->{$relationKey});
                    } else {
                        $data[$relationKey] = $this->{$relationKey}->toArray(false);
                    }
                }
            }
        }
        
        return $data;
    }

    /**
     * Méthode magique pour accéder aux relations dynamiquement avec lazy loading
     */
    public function __get(string $name): mixed
    {
        if (array_key_exists($name, $this->fields)) {
            return $this->fields[$name];
        }
        
        foreach ($this->relations as $relation) {
            $relationKey = $this->getRelationKey($relation['related_model']);
            
            if ($relationKey === $name) {
                if ($relation['lazy'] ?? true) {
                    $this->loadRelation($name);
                }
                return $this->{$name} ?? null;
            }
        }
        
        return null;
    }

    // =========================================================================
    // MÉTHODES PRIVÉES OPTIMISÉES
    // =========================================================================

    private function loadRelationsRecursive(int $currentDepth): void
    {
        if ($currentDepth > $this->maxDepth) {
            return;
        }
        
        foreach ($this->relations as $relation) {
            $relationKey = $this->getRelationKey($relation['related_model']);
            $depthKey = $relationKey . '_' . $currentDepth;
            
            if (in_array($depthKey, $this->loadingDepth)) {
                continue;
            }
            
            $this->loadingDepth[] = $depthKey;
            $this->loadSingleRelation($relation, $currentDepth);
        }
    }

    private function loadSingleRelation(array $relation, int $currentDepth): void
    {
        $relationKey = $this->getRelationKey($relation['related_model']);
        
        if (in_array($relationKey, $this->loadedRelations)) {
            return;
        }
        
        $this->loadedRelations[] = $relationKey;
        
        switch ($relation['type']) {
            case self::HAS_ONE:
                $this->loadHasOneRelation($relation, $relationKey, $currentDepth);
                break;
            case self::HAS_MANY:
                $this->loadHasManyRelation($relation, $relationKey, $currentDepth);
                break;
            case self::BELONGS_TO:
                $this->loadBelongsToRelation($relation, $relationKey, $currentDepth);
                break;
            case self::MANY_TO_MANY:
                $this->loadManyToManyRelation($relation, $relationKey, $currentDepth);
                break;
        }
    }

    private function loadHasOneRelation(array $relation, string $relationKey, int $currentDepth): void
    {
        $relatedModel = new $relation['related_model']();
        $foreignKey = $relation['foreign_key'];
        $localKeyValue = $this->{$relation['local_key']} ?? null;
        
        if ($localKeyValue) {
            $result = $relatedModel->where($foreignKey, $localKeyValue);
            if ($result && $currentDepth < $this->maxDepth) {
                $result->loadRelationsRecursive($currentDepth + 1);
            }
            $this->{$relationKey} = $result;
        }
    }

    private function loadHasManyRelation(array $relation, string $relationKey, int $currentDepth): void
    {
        $relatedModel = new $relation['related_model']();
        $foreignKey = $relation['foreign_key'];
        $localKeyValue = $this->{$relation['local_key']} ?? null;
        
        if ($localKeyValue) {
            $sql = "SELECT * FROM {$relatedModel->getTable()} WHERE {$foreignKey} = :value";
            $relatedModel->db->query($sql, ['value' => $localKeyValue]);
            $results = $relatedModel->getData();
            $objects = $relatedModel->hydrateAll($results);
            
            if ($currentDepth < $this->maxDepth) {
                foreach ($objects as $object) {
                    $object->loadRelationsRecursive($currentDepth + 1);
                }
            }
            
            $this->{$relationKey} = $objects;
        }
    }

    private function loadBelongsToRelation(array $relation, string $relationKey, int $currentDepth): void
    {
        $relatedModel = new $relation['related_model']();
        $foreignKey = $relation['foreign_key'];
        $foreignKeyValue = $this->{$foreignKey} ?? null;
        
        if ($foreignKeyValue) {
            $result = $relatedModel->find($foreignKeyValue);
            if ($result && $currentDepth < $this->maxDepth) {
                $result->loadRelationsRecursive($currentDepth + 1);
            }
            $this->{$relationKey} = $result;
        }
    }

    private function loadManyToManyRelation(array $relation, string $relationKey, int $currentDepth): void
    {
        $relatedModel = new $relation['related_model']();
        $pivotTable = $relation['pivot_table'];
        $foreignPivotKey = $relation['foreign_pivot_key'];
        $relatedPivotKey = $relation['related_pivot_key'];
        $localKeyValue = $this->{$this->primaryKey} ?? null;
        
        if ($localKeyValue) {
            $sql = "
                SELECT r.* 
                FROM {$relatedModel->getTable()} r
                INNER JOIN {$pivotTable} p ON r.{$relatedModel->getPrimaryKey()} = p.{$relatedPivotKey}
                WHERE p.{$foreignPivotKey} = :value
            ";
            
            $relatedModel->db->query($sql, ['value' => $localKeyValue]);
            $results = $relatedModel->getData();
            $objects = $relatedModel->hydrateAll($results);
            
            if ($currentDepth < $this->maxDepth) {
                foreach ($objects as $object) {
                    $object->loadRelationsRecursive($currentDepth + 1);
                }
            }
            
            $this->{$relationKey} = $objects;
        }
    }

    private function batchLoadAllRelations(array $objects): void
    {
        foreach ($this->relations as $relation) {
            $this->batchLoadRelation($objects, $relation);
        }
    }

    private function batchLoadSpecificRelations(array $objects, array $relationNames): void
    {
        foreach ($this->relations as $relation) {
            $relationKey = $this->getRelationKey($relation['related_model']);
            if (in_array($relationKey, $relationNames)) {
                $this->batchLoadRelation($objects, $relation);
            }
        }
    }

    private function batchLoadRelation(array $objects, array $relation): void
    {
        if (empty($objects)) return;
        
        $relatedModel = new $relation['related_model']();
        $foreignKey = $relation['foreign_key'];
        
        $ids = array_map(fn($obj) => $obj->{$relation['local_key']}, $objects);
        $ids = array_unique(array_filter($ids));
        
        if (empty($ids)) return;
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        switch ($relation['type']) {
            case self::HAS_ONE:
            case self::HAS_MANY:
                $sql = "SELECT * FROM {$relatedModel->getTable()} WHERE {$foreignKey} IN ($placeholders)";
                break;
            case self::BELONGS_TO:
                $sql = "SELECT * FROM {$relatedModel->getTable()} WHERE {$relatedModel->getPrimaryKey()} IN ($placeholders)";
                break;
            case self::MANY_TO_MANY:
                $pivotTable = $relation['pivot_table'];
                $foreignPivotKey = $relation['foreign_pivot_key'];
                $relatedPivotKey = $relation['related_pivot_key'];
                $sql = "
                    SELECT r.*, p.{$foreignPivotKey} as pivot_key
                    FROM {$relatedModel->getTable()} r
                    INNER JOIN {$pivotTable} p ON r.{$relatedModel->getPrimaryKey()} = p.{$relatedPivotKey}
                    WHERE p.{$foreignPivotKey} IN ($placeholders)
                ";
                break;
            default:
                return;
        }
        
        $relatedModel->db->query($sql, $ids);
        $relatedData = $relatedModel->getData();
        
        $groupedData = [];
        foreach ($relatedData as $item) {
            if ($relation['type'] === self::MANY_TO_MANY) {
                $fkValue = $item['pivot_key'];
                unset($item['pivot_key']);
            } else {
                $fkValue = $relation['type'] === self::BELONGS_TO ? $item[$relatedModel->getPrimaryKey()] : $item[$foreignKey];
            }
            
            $groupedData[$fkValue][] = $item;
        }
        
        foreach ($objects as $object) {
            $relationKey = $this->getRelationKey($relation['related_model']);
            $objectId = $object->{$relation['local_key']};
            
            if (isset($groupedData[$objectId])) {
                if ($relation['type'] === self::HAS_ONE || $relation['type'] === self::BELONGS_TO) {
                    $object->{$relationKey} = $relatedModel->hydrate($groupedData[$objectId][0]);
                } else {
                    $object->{$relationKey} = $relatedModel->hydrateAll($groupedData[$objectId]);
                }
            }
        }
    }

    private function getDefaultForeignKey(?string $modelName = null): string
    {
        if ($modelName) {
            $modelClass = new $modelName();
            $tableName = $modelClass->getTable();
            return str_replace('{table}', $tableName, $this->namingConventions['foreign_key']);
        }
        
        return str_replace('{table}', $this->table, $this->namingConventions['foreign_key']);
    }

    private function getDefaultBelongsToForeignKey(string $relatedModel): string
    {
        return $this->getDefaultForeignKey($relatedModel);
    }

    private function getRelationKey(string $modelClass): string
    {
        $model = new $modelClass();
        $tableName = $model->getTable();
        
        $pattern = $this->namingConventions['relation_name'];
        $key = str_replace('{table}', $tableName, $pattern);
        
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
    }
}