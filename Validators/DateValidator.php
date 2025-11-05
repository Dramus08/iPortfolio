<?php
namespace Validators;

use Exception;

class DateValidator extends BaseValidator
{
    public function validate(string $field, $value, array $options = [])
    {
        $value = parent::validate($field, $value, $options);
        if ($value === null || empty($value) || $value =='') return null;
        if (!strtotime($value)) {
            $this->addErrors($field,"Le champ '<b>{$field}</b>' doit contenir une date valide.");
            throw new Exception("Le champ '<b>{$field}</b>' doit contenir une date valide.");
        }

        return date('Y-m-d H:i:s', strtotime($value));
    }
}
