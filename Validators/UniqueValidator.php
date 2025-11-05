<?php
namespace Validators;

use Exception;
use PDO;

class UniqueValidator extends BaseValidator
{
    public function validateUnique(PDO $db, string $table, string $field, $value, ?int $id = null)
    {
        $query = "SELECT COUNT(*) FROM {$table} WHERE {$field} = :value";
        $params = ['value' => $value];
        if ($id) {
            $query .= " AND id != :id";
            $params['id'] = $id;
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $this->addErrors($field,"La valeur du champ '<b>{$field}</b>' doit être unique.");
            return null;
            //throw new Exception("La valeur du champ '{$field}' doit être unique.");
        }
    }
}
