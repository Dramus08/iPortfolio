<?php
namespace Validators;

use Exception;

class NumberValidator extends BaseValidator
{
    public function validate(string $field, $value, array $options = [])
    {
        $value = parent::validate($field, $value, $options);
        if ($value === null) return null;

        if (!is_numeric($value)) {
            $this->addErrors($field,"Le champ '<b>{$field}</b>' doit être un nombre.");
            return null;
            //throw new Exception("Le champ '{$field}' doit être un nombre.");
        }

        return $value + 0; // conversion automatique
    }
}
