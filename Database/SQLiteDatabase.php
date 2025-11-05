<?php
namespace Database;

use PDO;
use PDOException;
use Exception;

/**
 * Gestion de la base de données SQLite.
 */
class SQLiteDatabase extends AbstractDatabase
{
    use DatabaseHelperTrait;
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->connect();
    }

    protected function connect(): void
    {
        $dsn = "sqlite:" . $this->path;
        try {
            $this->connection = new PDO($dsn, null, null, $this->options);
        } catch (PDOException $e) {
            $this->logError("Connexion SQLite échouée : " . $e->getMessage());
            throw new Exception("Erreur de connexion SQLite : " . $e->getMessage());
        }
    }
}
