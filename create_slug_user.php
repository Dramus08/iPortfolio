<?php
// migrate_add_slug_to_users.php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Database\MysqlDatabase;


echo "Migration : Ajout du champ slug à la table users\n";
echo "------------------------------------------------\n";

try {
    $mysq = new MysqlDatabase("localhost","root","portfolio","SandatuAdamou@1965");
    $db = $mysq::getPDO();
    
    // Vérifier si la colonne slug existe déjà
    $checkSql = "SHOW COLUMNS FROM users LIKE 'slug'";
    $stmt = $db->query($checkSql);
    $columnExists = $stmt->fetch();
    
    if ($columnExists) {
        echo "⚠️  La colonne 'slug' existe déjà dans la table users.\n";
        exit;
    }
    
    // Ajouter la colonne slug
    $alterSql = "ALTER TABLE users 
                ADD COLUMN slug VARCHAR(255) NULL AFTER name,
                ADD UNIQUE INDEX idx_slug_unique (slug)";
    
    $db->exec($alterSql);
    echo "✅ Colonne 'slug' ajoutée avec succès.\n";
    
    // Mettre à jour les slugs existants
    echo "🔄 Mise à jour des slugs pour les utilisateurs existants...\n";
    
    $selectSql = "SELECT id, name, username FROM users WHERE slug IS NULL";
    $stmt = $db->query($selectSql);
    $users = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    $updated = 0;
    foreach ($users as $user) {
        // Générer le slug
        $slug = generateSlug($user->name ?: $user->username);
        
        // Vérifier l'unicité
        $counter = 1;
        $originalSlug = $slug;
        while (slugExists($db, $slug, $user->id)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        // Mettre à jour l'utilisateur
        $updateSql = "UPDATE users SET slug = :slug WHERE id = :id";
        $stmt = $db->prepare($updateSql);
        $stmt->execute([':slug' => $slug, ':id' => $user->id]);
        
        $updated++;
        echo "  - {$user->username} -> {$slug}\n";
    }
    
    echo "✅ {$updated} utilisateurs mis à jour avec des slugs.\n";
    
    // Rendre la colonne slug obligatoire
    $requiredSql = "ALTER TABLE users MODIFY slug VARCHAR(255) NOT NULL";
    $db->exec($requiredSql);
    echo "✅ Colonne 'slug' rendue obligatoire.\n";
    
    echo "🎉 Migration terminée avec succès !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la migration : " . $e->getMessage() . "\n";
}

/**
 * Génère un slug à partir d'une chaîne
 */
function generateSlug(string $text): string
{
    // Conversion des caractères spéciaux
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    
    // Remplacement des caractères accentués
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    
    // Translitération
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
    // Suppression des caractères non alphanumériques
    $text = preg_replace('~[^-\w]+~', '', $text);
    
    // Nettoyage des tirets multiples
    $text = preg_replace('~-+~', '-', $text);
    
    // Suppression des tirets en début et fin
    $text = trim($text, '-');
    
    // Conversion en minuscules
    $text = strtolower($text);
    
    // Si vide, utiliser un slug par défaut
    if (empty($text)) {
        return 'user-' . uniqid();
    }
    
    return $text;
}

/**
 * Vérifie si un slug existe déjà
 */
function slugExists(PDO $db, string $slug, ?int $excludeUserId = null): bool
{
    $sql = "SELECT COUNT(*) FROM users WHERE slug = :slug";
    $params = [':slug' => $slug];
    
    if ($excludeUserId) {
        $sql .= " AND id != :exclude_id";
        $params[':exclude_id'] = $excludeUserId;
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchColumn() > 0;
}