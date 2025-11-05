<?php
namespace Validators;

use Exception;
use Database\AbstractDatabase;
use Database\DatabaseFactory; 

/**
 * Classe extensible pour valider et préparer les données avant enregistrement.
 * Gère chaque type de donnée via des méthodes séparées.
 */
class DataValidator
{
    protected array $columnsInfo = [];
    protected array $data = [];
    protected ?int $id = null;
    protected array $errors = [];
    protected array $errorForms=[];
    protected AbstractDatabase $db;
    protected ?string $table;


    public function __construct(array $columnsInfo, array $data, int|string $id = null,string $dbDriver = 'mysql',?string $table=null)
    {
        $this->columnsInfo = $columnsInfo;
        $this->data = $data;
        $this->table = $table;
        $this->id = $id;
        $this->db = DatabaseFactory::create($dbDriver);

    }

    /**
     * Condition simple WHERE.
     */
    public function is_unique(string $column, mixed $value, string $operator = '=',?string $table=null): bool
    {
        if(!isset($this->id))
        {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$column} {$operator} :val";
        $this->db->query($sql, ['val' => $value]);
        $data = $this->db->getResponse()->data[0]['count'] ?? [];
        return $data > 0 ? true : false;

        }else{
            return false;
        }
        
    }

    public function setTable(string $name){
        $this->table=$name;
    }
    public function getTable(){
        return $this->table;
    }

    /* ============================================================
       ==========  MÉTHODES PRINCIPALES  ==========================
       ============================================================ */

    public function validate(): array
    {
        $filter=$this->filterColumns();

        foreach ($this->columnsInfo as $col) {
            $field = $col['Field'];
            $type  = strtolower($col['Type']);
            $this->applyValidations($field, $col, $type);
        }

        if (!empty($this->errors)) {
            //throw new Exception("Erreur(s) de validation détectée(s).");
            return $this->errors;
        }

        return $this->data;
    }

    /**
     * Garde uniquement les champs existants dans la table.
     */
    protected function filterColumns(): void
    {
        $fields = array_column($this->columnsInfo, 'Field');
        $this->data = array_intersect_key($this->data, array_flip($fields));
    }

    /**
     * Applique dynamiquement les validations selon le type ou les contraintes.
     */
    protected function applyValidations(string $field, array $col, string $type): void
    {
        if ($col['Null'] === 'NO' && $col['Default'] === null && $col['Extra'] !== 'auto_increment') {
            $this->validateNotNull($field);
        }

        if (isset($col['Key']) && $col['Key'] === 'UNI') {
            $this->validateUnique($field);
        }

        if (preg_match('/varchar\((\d+)\)/', $type, $match)) {
            $this->validateTextLength($field, (int)$match[1]);
        }

        if (strpos($type, 'text') !== false) {
            $this->validateText($field);
        }

        if (strpos($type, 'date') !== false && strpos($type, 'time') === false) {
            $this->validateDate($field);
        }

        if (strpos($type, 'datetime') !== false) {
            $this->validateDateTime($field);
        }

        if (strpos($type, 'int') !== false && $col['Extra'] !== 'auto_increment') {
            $this->validateInteger($field);
        }

        if (strpos($type, 'tinyint(1)') !== false) {
            $this->validateBoolean($field);
        }

        /*
            if (strpos($field, 'slug') !== false) {
                $this->validateSlug($field);
            }
        */
    }

    /* ============================================================
       ==========  MÉTHODES DE VALIDATION PAR TYPE  ================
       ============================================================ */

    protected function validateNotNull(string $field): void
    {
        if (!isset($this->data[$field]) || trim((string)$this->data[$field]) === '') {
            $this->errors[$field] = "Le champ <strong>{$field}</strong> est obligatoire.";
        }else{
            if(isset($this->data[$field])){
                 $this->data[$field]=htmlspecialchars($this->data[$field]);
            }
        }
    }

    protected function validateUnique(string $field): void
    {
        // ⚠️ Simulation : à connecter à ta base dans une vraie version
        // Exemple : SELECT COUNT(*) FROM table WHERE $field = :value
        // Ici, on simule une vérification.
        $value = $this->data[$field] ?? null;
        if ($this->is_unique($field,$value)) { // Exemple pour test
            $this->errors[$field] = "La valeur '{$value}' du champ <strong>{$field}</strong> existe déjà.";
        }else{
            $this->data[$field]=htmlspecialchars($this->data[$field]);
        }
    }

    protected function validateText(string $field): void
    {
        if (isset($this->data[$field]) && !is_string($this->data[$field])) {
            $this->errors[$field]= "Le champ <strong>{$field}</strong> doit être un texte.";
        }
        else{
            if(isset($this->data[$field])){
                $this->data[$field]=nl2br(htmlspecialchars($this->data[$field]));
            }
            
        }
    }

    protected function validateTextLength(string $field, int $max): void
    {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "Le champ <strong>{$field}</strong> dépasse la longueur maximale de {$max} caractères.";
        }else{
            if(isset($this->data[$field])){
                 $this->data[$field]=htmlspecialchars($this->data[$field]);
            }
        }
    }

    protected function validateDate(string $field): void
    {
        if (empty($this->data[$field])) {
            $this->data[$field] = null;
        }
        elseif (!empty($this->data[$field]) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->data[$field])) {
            $this->errors[$field] = "Le champ <strong>{$field}</strong> doit être une date valide (YYYY-MM-DD).";
        }else{
            if(isset($this->data[$field])){
                 $this->data[$field]=htmlspecialchars($this->data[$field]);
            }
           
        }
        
    }

    protected function validateDateTime(string $field): void
    {
        if (!empty($this->data[$field]) && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $this->data[$field])) {
            $this->errors[$field] = "Le champ <strong>{$field}</strong> doit être un datetime valide (YYYY-MM-DD HH:MM:SS).";
        }else{
            if(isset($this->data[$field])){
                 $this->data[$field]=htmlspecialchars($this->data[$field]);
            }
        }
    }

    protected function validateInteger(string $field): void
    {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
            $this->errors[$field] = "Le champ <strong>{$field}</strong> doit être un entier valide.";
        }else{
            if(isset($this->data[$field])){
                 $this->data[$field]=htmlspecialchars($this->data[$field]);
            }
        }
    }

    protected function validateBoolean(string $field): void
    {
        if (isset($this->data[$field])) {
            $this->data[$field] = (int) (bool) $this->data[$field];
        } else {
            if(isset($this->data[$field])){
                 $this->data[$field]= 0;
            }
        }
    }

    protected function validateSlug($column){
        if($field == 'slug'){
            $this->data[$field] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->data[$column]), '-'));
        }
        
    }

    public function getData():array{
        return $this->data;
    }


    /* ============================================================
       ==========  MÉTHODES DE GESTION DES ERREURS  ================
       ============================================================ */

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function isInvalidData(): bool
    {
        return !empty($this->errors);
    }

    public function isValidData(): bool
    {
        return empty($this->errors);
    }

    public function setErrors(string $field,string $message): array
    {
        return $this->errors[$field]=$message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

     public function clearErrors(): void
    {
        $this->errors=[];
    }

    public function renderErrors(): string
    {
        if (!$this->hasErrors()) return '';

        $html = '<div class="alert alert-danger" style="padding:10px;border-radius:8px;">';
        $html .= '<h5>Erreurs de validation :</h5><ul>';
        foreach ($this->errors as $field => $msg) {
                $html .= "<li>{$msg}</li>";
        }
        $html .= '</ul></div>';

        return $html;
    }
        /**
     * Génère le HTML + JS pour les toasts
     */
    protected function displayToastMessages(): void
    {
        if (!$this->hasErrors()) return ;

        echo "<div id='toast-container'></div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('toast-container');
            const messages = " . json_encode($this->errors) . ";
            for (const type in messages) {
                const toast = document.createElement('div');
                toast.className = 'toast toast-error toast-danger';
                toast.innerHTML = messages[type];
                container.appendChild(toast);
                setTimeout(() => toast.classList.add('show'), 100);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 500);
                }, 4000);
            }
        });
        </script>";
        
        $this->clearErrors();
    }
}
