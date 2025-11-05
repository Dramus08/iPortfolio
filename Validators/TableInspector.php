<?php
namespace Validators;

use PDO;

class TableInspector
{
    public static function describe(PDO $db, string $table): array
    {
        $stmt = $db->query("DESCRIBE {$table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
