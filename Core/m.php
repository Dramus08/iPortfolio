<?php

namespace Core;
use PDO;
use PDOException;
use Database\MYSQL_DB;
use Core\Logger;
use Database\DatabaseFactory;
use Database\AbstractDatabase;
use stdClass; use Exception;

/**
 * Classe Model de base pour ORM léger
 * Gère automatiquement CRUD et hydratation d’objets.
 */
abstract class Model
{
    /** @var string Nom de la table */
    protected string $table;

    /** @var string Nom de la clé primaire */
    protected string $primaryKey = 'id';

    /** @var AbstractDatabase */
    protected AbstractDatabase $db;

    /** @var array Champs du modèle (doivent correspondre à la table) */
    protected array $fields = [];

    public function __construct(string $dbDriver = 'mysql')
    {
        $this->db = DatabaseFactory::create($dbDriver);
    }
/**
 * Insère un nouvel enregistrement dans la table.
 * 
 * ✅ Vérifie que :
 * - seules les colonnes existantes sont insérées (les autres sont ignorées)
 * - les champs obligatoires (NOT NULL sans DEFAULT) sont bien fournis
 * 
 * @param array $data Données à insérer (clé = nom du champ)
 * @return bool Succès ou échec de l’insertion
 */

public function is_table_exist():bool{
    // 1️⃣ Vérifie si la table existe
    $tableCheck = $this->db->query("SHOW TABLES LIKE '{$this->table}'");
    return $tableCheck == true ? true:false;
}


public function getColumnsInfoTable(){
    // Récupère les colonnes existantes dans la table
    if($this->is_table_exist()===true){
         $columnsInfo = $this->db->query("DESCRIBE {$this->table}");
        return $columnsInfo ==true ?$this->db->response['data']['data']:[];
    } 
    return [];
       
           
}

public function getColumnsTable(){
    $columnsInfo=$this->getColumnsInfoTable();
    if(!empty($columnsInfo)) return array_column($columnsInfo, 'Field');

   return []; 
}

public function getFilteredData($data):array{
    // 3️⃣ Filtrer $data pour ne garder que les champs existants dans la table

   $filteredData= array_intersect_key($data, array_flip($this->getColumnsTable())); 
   return !empty($filteredData)?$filteredData:[];
} 

    public function create(array $data): bool
    {
        try {
            // 1️⃣ Vérifie si la table existe
            $tableCheck = $this->is_table_exist();
            if ($tableCheck == false || empty($tableCheck)) {
                throw new Exception("La table '{$this->table}' n'existe pas dans la base de données.");
            }

            // 2️⃣ Récupère les colonnes existantes dans la table
            $columnsInfo = $this->getColumnsInfoTable();
           
            if (empty($columnsInfo)) {
                throw new Exception("Impossible de récupérer la structure de la table '{$this->table}'.");
            }
            // 3️⃣ Filtrer $data pour ne garder que les champs existants dans la table
            $filteredData = $this->getFilteredData($data);
            
            
            if (empty($filteredData)) {
                throw new Exception("Aucune donnée valide trouvée pour la table '{$this->table}'.");
            }

            // 4️⃣ Vérifie les champs requis (NOT NULL sans DEFAULT)
            
            foreach ($columnsInfo as $col) {
                
                $isRequired = $col['Null'] === 'NO' && $col['Default'] === null && $col['Extra'] !== 'auto_increment';
                $fieldName = $col['Field'];
                


                if ($isRequired && (!isset($filteredData[$fieldName]) || trim((string)$filteredData[$fieldName]) === '') && $fieldName === 'slug') {
                    $filteredData['slug']=strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $filteredData['title']), '-'));
                    
                }elseif ($isRequired && (!isset($filteredData[$fieldName]) || trim((string)$filteredData[$fieldName]) === '')) {
                    throw new Exception("Le champ obligatoire '{$fieldName}' est manquant ou vide.");
                    //echo "Le champ obligatoire '{$fieldName}' est manquant ou vide.</br>";
                }
                elseif ($col['Type']==='date' && $filteredData[$fieldName]==='') {
                    # code...
                    $filteredData[$fieldName]=null;
                }
            }

            // 5️⃣ Prépare la requête SQL
            $columns = array_keys($filteredData);
            $placeholders = array_map(fn($col) => ':' . $col, $columns);
            // 1️⃣ Transformation de base
        


            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                $this->table,
                implode(', ', $columns),
                implode(', ', $placeholders)
            );
            

            // 6️⃣ Exécute la requête
            $sanitizeData=$this->sanitizeFormData($filteredData);
            $result = $this->db->query($sql, $sanitizeData);

            // 7️⃣ Vérifie le résultat
            if ($result===false) {
                throw new Exception("Erreur lors de l'insertion dans la table '{$this->table}'.");
            }

            return true;

        } catch (Exception $e) {
            //$this->logError("[CREATE ERROR] " . $e->getMessage());
            return false;
        }
    }



    /**
     * Récupère tous les enregistrements.
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC" ;
        $result = $this->db->query($sql);

        return $this->hydrateAll($this->db->response['data']['data'] ?? []);
    }

    /**
     * Récupère un enregistrement par ID.
     */
    public function find(int|string $id): ?static
    {   
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        
        $result = $this->db->query($sql, ['id' => $id]);

        if ($result === true) {
            echo "</br>";echo "<pre>";print_r($this->db->response['data']['data'][0]);echo "</pre>";echo "</br>";

            return $this->hydrate($this->db->response['data']['data'][0]);
        }

        return null;
    }

    /**
     * Récupère les enregistrements selon une condition simple.
     */
    public function where(string $column, mixed $value, string $operator = '='): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        $result = $this->db->query($sql, ['value' => $value]);

        if($result ===true)return $this->hydrateAll($this->db->response['data']['data'] ?? []);
    }

    /**
     * Récupère le premier enregistrement correspondant à une condition.
     */
    public function first(string $column, mixed $value, string $operator = '='): ?static
    {
        $res = $this->where($column, $value, $operator);
        return $this->response['data']['data'][0] ?? null;
    }

    public function describe(){
        // 1️⃣ Vérifie si la table existe
        return $this->db->query("SHOW TABLES LIKE '{$this->table}'");
        
    }

    public function getResponse(){
        return $this->db->response;
    }
     

    /**
     * Met à jour un enregistrement.
     */
    public function update(int|string $id, array $data): bool
    {

        try {
            // 1️⃣ Vérifie si la table existe
            $tableCheck = $this->db->query("SHOW TABLES LIKE '{$this->table}'");
            if ($tableCheck ===false ) {
                throw new Exception("La table '{$this->table}' n'existe pas dans la base de données.");
            }

            // 2️⃣ Récupère les colonnes existantes dans la table
            $result = $this->db->query("DESCRIBE {$this->table}");
            
            if ($result===false) {
                throw new Exception("Impossible de récupérer la structure de la table '{$this->table}'.");
            }

            $tableColumns = array_column($this->getResponse()['data']['data'], 'Field');
            // 3️⃣ Filtrer $data pour ne garder que les champs existants dans la table
            $filteredData = array_intersect_key($data, array_flip($tableColumns));
           
            if (empty($filteredData)) {
                throw new Exception("Aucune donnée valide trouvée pour la table '{$this->table}'.");
            }

            // 4️⃣ Vérifie les champs requis (NOT NULL sans DEFAULT)
            foreach ($columnsInfo->data as $col) {
                
                $isRequired = $col['Null'] === 'NO' && $col['Default'] === null && $col['Extra'] !== 'auto_increment';
                $fieldName = $col['Field'];
                //echo $col['Field']." </br>";var_dump($isRequired);echo "</br>";


                if ($isRequired && (!isset($filteredData[$fieldName]) || trim((string)$filteredData[$fieldName]) === '') && $fieldName === 'slug') {
                    $filteredData['slug']=strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $filteredData['title']), '-'));
                    
                }elseif ($isRequired && (!isset($filteredData[$fieldName]) || trim((string)$filteredData[$fieldName]) === '')) {
                    //throw new Exception("Le champ obligatoire '{$fieldName}' est manquant ou vide.");
                    echo "Le champ obligatoire '{$fieldName}' est manquant ou vide.</br>";
                }
                elseif ($col['Type']==='date' && $filteredData[$fieldName]==='') {
                    # code...
                    $filteredData[$fieldName]=null;
                }
            }

            // 5️⃣ Prépare la requête SQL
            $columns = array_keys($filteredData);
            $placeholders = array_map(fn($col) => ':' . $col, $columns);
            // 1️⃣ Transformation de base
        


            $set = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($filteredData)));

            $data[$this->primaryKey] = $id;
            $sql = sprintf(
                "UPDATE %s SET %s WHERE %s = :%s",
                $this->table,
                $set,
                $this->primaryKey,
                $this->primaryKey
            );
            
            // 6️⃣ Exécute la requête
            $sanitizeData=$this->sanitizeFormData($filteredData);
            $sanitizeData[$this->primaryKey]=$id;
            $result = $this->db->query($sql, $sanitizeData);
            echo "</br>";echo "<pre>";print_r($sanitizeData);echo "</pre>";echo "</br>";
            

            // 7️⃣ Vérifie le résultat
            if (isset($result) && $result === false) {
                throw new Exception("Erreur lors de l'insertion dans la table '{$this->table}'.");
            }

            return $result === true  ? true :false;

        } catch (Exception $e) {
            error_log("[CREATE ERROR] " . $e->getMessage());
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Supprime un enregistrement.
     */
    public function delete(int|string $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        //return $this->db->query($sql, ['id' => $id])->success;
        $result= $this->db->query($sql, ['id' => $id]);
        if($result==true) return true;
        else {
            return false;
        }
        
    }

    /**
     * Hydrate un objet à partir d’un tableau.
     */
    protected function hydrate(array $row): static
    {
        
        $object = new static();
        foreach ($row as $key => $value) {
            if (property_exists($object, $key)) {
                $object->$key = $value;
            } else {
                $object->fields[$key] = $value;
            }
        }
        return $object;
        

    
    }
    /**
 * Récupère et nettoie les données envoyées par un formulaire.
 *
 * 🔒 Fonctionnalités :
 * - Applique htmlspecialchars() à chaque champ
 * - Supprime les balises HTML potentiellement dangereuses
 * - Gère à la fois $_POST et $_GET
 * - Ignore les champs vides si besoin
 *
 * @param array|null $source Par défaut $_POST, peut être $_GET ou autre tableau
 * @param bool $ignoreEmpty Si true, ignore les champs vides
 * @return array Tableau nettoyé
 */
    public function sanitizeFormData(?array $source = null, bool $ignoreEmpty = false): array
    {
        // Utilise $_POST par défaut si aucune source n'est fournie
        $data = $source ?? $_POST;

        $cleanData = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Nettoie récursivement les sous-tableaux
                $cleanData[$key] = $this->sanitizeFormData($value, $ignoreEmpty);
            } else {
                $value = trim($value); // Supprime les espaces inutiles
                if ($ignoreEmpty && $value === '') {
                    continue;
                }
                // Encode les caractères spéciaux pour prévenir les attaques XSS
                $cleanData[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }

        return $cleanData;
    }


    /**
     * Hydrate plusieurs objets.
     */
    protected function hydrateAll(array $rows): array
    {
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }

    /**
     * Retourne la table associée (utile pour debug)
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Retourne la clé primaire.
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
}



### Points améliorés :
// 1. **Réutilisation des requêtes SQL** : Le code est plus concis et réutilise des méthodes génériques pour exécuter les requêtes SQL.
// 2. **Flexibilité** : La classe peut facilement être adaptée à d'autres bases de données en changeant simplement les paramètres d'initialisation.
// 3. **Modularité** : Chaque fonctionnalité (connexion, exécution de requêtes, gestion des fichiers) est bien isolée, ce qui rend le code plus facile à maintenir et à étendre.
// 4. **Commentaires clairs et organisation logique** : Le code est bien structuré et plus compréhensible avec des méthodes bien nommées.

// Ce script est maintenant plus optimal, extensible et facile à maintenir, en ligne avec vos préférences.

    