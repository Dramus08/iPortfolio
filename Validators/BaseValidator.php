<?php
namespace Validators;

use Exception;

class BaseValidator extends DataManager
{
    protected array $errors=[];
    public function validate(string $field, $value, array $options = [])
    {
        // Vérifie si requis
        if (!empty($options['required']) && ($value === null || trim((string)$value) === '')) {
            $this->addErrors($field,"Le champ '<b>{$field}</b>' est obligatoire.");
            return null;
            //throw new Exception("Le champ '{$field}' est obligatoire.");
        }

        // Valeur vide autorisée
        if ($value === null || $value === '') {
            return null;
        }

        return $value;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function addErrors(string $field,string $message):void{
        //echo "<pre>";print_r($this->errors); echo "</pre>";
        $this->errors[$field][] = $message;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

}
