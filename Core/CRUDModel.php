<?php
namespace Core;
use PDO;
use PDOException;
use Database\MYSQL_DB;
use Core\Logger;
use Database\DatabaseFactory;
use stdClass; use Exception;

/**
 * Classe Model de base pour ORM léger
 * Gère automatiquement CRUD et hydratation d’objets.
 */
class CRUDModel extends Mysql_DB {
    protected $table;

    public function all() { return $this->query("SELECT * FROM {$this->table}"); }
    public function find($id) { return $this->query("SELECT * FROM {$this->table} WHERE id=?", [$id]); }
    public function create($data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = implode(',', array_fill(0, count($fields), '?'));
        $this->query("INSERT INTO {$this->table} (".implode(',', $fields).") VALUES ($placeholders)", $values);
    }
    public function update($id, $data) {
        $fields = implode(',', array_map(fn($k) => "$k=?", array_keys($data)));
        $values = array_values($data);
        $values[] = $id;
        $this->query("UPDATE {$this->table} SET $fields WHERE id=?", $values);
    }
    public function delete($id) {
        $this->query("DELETE FROM {$this->table} WHERE id=?", [$id]);
    }
}
