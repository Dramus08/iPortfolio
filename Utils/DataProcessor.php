<?php
namespace Utils;

use Utils\DateHelper;
use Exception;

class DataPreparator
{
    protected $db;
    protected $table;

    public function __construct($db, string $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    /**
     * Prépare et nettoie les données avant insertion ou mise à jour.
     */
    public function prepare(array $data, int|string $id = null): array
    {
        $columns = $this->getColumnsInfo();

        $clean = [];
        foreach ($columns as $col) {
            $field = $col['Field'];
            $type = strtolower($col['Type']);
            $isRequired = $col['Null'] === 'NO' && $col['Default'] === null && $col['Extra'] !== 'auto_increment';

            // On ne garde que les colonnes connues
            if (!array_key_exists($field, $data)) {
                continue;
            }

            $value = trim((string)($data[$field] ?? ''));

            // ✅ Gestion des champs obligatoires
            if ($isRequired && $value === '') {
                if ($id === null) { // pour CREATE
                    throw new Exception("Le champ '{$field}' est obligatoire.");
                }
                continue;
            }

            // ✅ Gestion des champs de type date/datetime/timestamp
            if (preg_match('/date|datetime|timestamp/i', $type)) {
                if ($value === '') {
                    $clean[$field] = null;
                } elseif (!DateHelper::isValid($value, 'Y-m-d') && !DateHelper::isValid($value, 'Y-m-d H:i:s')) {
                    throw new Exception("Le champ '{$field}' contient une date invalide : '{$value}'.");
                } else {
                    $clean[$field] = DateHelper::toSqlFormat($value);
                }
                continue;
            }

            // ✅ Gestion des types numériques
            if (preg_match('/int|float|double|decimal/i', $type)) {
                $clean[$field] = is_numeric($value) ? $value : null;
                continue;
            }

            // ✅ Gestion des types texte
            if (preg_match('/text|char|varchar/i', $type)) {
                $clean[$field] = htmlspecialchars(strip_tags($value));
                continue;
            }

            // ✅ Sinon, on garde brut
            $clean[$field] = $value;
        }

        return $clean;
    }

    /**
     * Récupère la structure complète de la table
     */
    protected function getColumnsInfo(): array
    {
        $sql = "SHOW COLUMNS FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
