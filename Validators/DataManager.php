<?php
namespace Validators;

use Exception;
use PDO;
use Validators\BaseValidator;
use Validators\TextValidator;
use Validators\DateValidator;
use Validators\NumberValidator;
use Validators\UniqueValidator;
use Database\AbstractDatabase;
use Database\DatabaseFactory;

class DataManager
{
    //protected MYSQL_DB $db;
    protected string $table;
    protected AbstractDatabase $conn;
    protected PDO $db;
    protected array $errors=[];

    protected array $columns = [];

    public function __construct(?string $table='projects',$dbDriver='mysql')
    {
        //$this->db = MYSQL_DB::getConnection();
        $this->conn = DatabaseFactory::create($dbDriver);
        $this->db=$this->conn->getConnection();
        $this->table = $table;
        $this->columns = $this->getTableDescription();
    }

    /**
     * Récupère la description complète d'une table SQL
     */
    protected function getTableDescription(): array
    {
        $stmt = $this->db->query("DESCRIBE {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Prépare et valide les données
     */
    public function prepareData(array $data, ?int $id = null): array
    {
        $filtered = [];
        foreach ($this->columns as $col) {
            $field = $col['Field'];
            $type = strtolower($col['Type']);
            $isRequired = $col['Null'] === 'NO' && $col['Default'] === null && $col['Extra'] !== 'auto_increment';
            $value = $data[$field] ?? null;

            // Détermination automatique du validateur
            $validator = $this->getValidatorForType($type);

            // Validation
            $validatedValue = $validator->validate($field, $value, [
                'required' => $isRequired,
                'unique' => $this->isUniqueField($field),
                'table' => $this->table,
                'db' => $this->db,
                'id' => $id
            ]);

            if ($validatedValue !== null) {
                $filtered[$field] = $validatedValue;
            }
        }

        return $filtered;
    }

    /**
     * Détermine si le champ est unique dans la table
     */
    protected function isUniqueField(string $field): bool
    {
        $query = "SHOW INDEX FROM {$this->table} WHERE Column_name = :field AND Non_unique = 0";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['field' => $field]);
        return $stmt->fetch() !== false;
    }

    /**
     * Sélectionne le validateur adapté
     */
    protected function getValidatorForType(string $type): BaseValidator
    {
        if (preg_match('/int|decimal|float|double/', $type)) {
            return new NumberValidator();
        } elseif (preg_match('/date|time/', $type)) {
            return new DateValidator();
        } elseif (preg_match('/char|text|varchar/', $type)) {
            return new TextValidator();
        }
        return new BaseValidator();
    }
    public function getErrors(){
        return $this->errors;
    }
}
