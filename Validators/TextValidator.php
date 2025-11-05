<?php
namespace Validators;

class TextValidator extends BaseValidator
{
    public function validate(string $field, $value, array $options = [])
    {
        $value = parent::validate($field, $value, $options);
        if ($value === null) {
            $this->addErrors($field,"La valeur du champ '<b>{$field}</b>' doit être unique.");
            return null;
        }

        return trim(strip_tags($value)); // nettoyage texte
    }
}
