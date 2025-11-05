<?php
namespace Database;

/**
 * Fabrique de connexions à différents SGBD.
 * Simplifie la création du bon moteur selon les besoins.
 */
class DatabaseFactory
{
    public static function create(string $driver = 'mysql'): AbstractDatabase
    {
        return match (strtolower($driver)) {
            'mysql' => new MysqlDatabase(),
            'sqlite' => new SQLiteDatabase(__DIR__ . '/../app.db'),
            default => throw new \Exception("Pilote inconnu : {$driver}")
        };
    }
}
