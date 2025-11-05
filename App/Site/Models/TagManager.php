<?php
class TagManager extends Model {
    protected string $table = 'tags';
    protected string $primaryKey = 'id';

    // Champs spécifiques
    public int $id;
    public string $name;
    public string $email;
    public string $username;
    public string $role;
    public string $created_at;
    public string $updated_at;
    public ?string $password = null;
    public bool $is_active = true;
    public bool $email_confirmed  = false; // false = e-mail non confirmé


    /**
     * Associe des tags à un élément d'une table donnée
     */
    public function attachTags(array $tags, string $table, int $itemId): void {
        foreach ($tags as $tagName) {
            // Vérifie si le tag existe
            $stmt = $this->db->prepare("SELECT id FROM tags WHERE name = ?");
            $stmt->execute([$tagName]);
            $tag = $stmt->fetch(PDO::FETCH_ASSOC);

            // Sinon, le créer
            if (!$tag) {
                $insert = $this->db->prepare("INSERT INTO tags (name) VALUES (?)");
                $insert->execute([$tagName]);
                $tagId = $this->db->lastInsertId();
            } else {
                $tagId = $tag['id'];
            }

            // Lier le tag à l'élément
            $link = $this->db->prepare("
                INSERT IGNORE INTO tagged_items (tag_id, tagged_table, tagged_id)
                VALUES (?, ?, ?)
            ");
            $link->execute([$tagId, $table, $itemId]);
        }
    }

    /**
     * Supprime toutes les associations de tags pour un élément donné
     */
    public function detachAllTags(string $table, int $itemId): void {
        $stmt = $this->db->prepare("DELETE FROM tagged_items WHERE tagged_table = ? AND tagged_id = ?");
        $stmt->execute([$table, $itemId]);
    }

    /**
     * Récupère les tags associés à un élément
     */
    public function getTags(string $table, int $itemId): array {
        $stmt = $this->db->prepare("
            SELECT t.* FROM tags t
            INNER JOIN tagged_items ti ON t.id = ti.tag_id
            WHERE ti.tagged_table = ? AND ti.tagged_id = ?
        ");
        $stmt->execute([$table, $itemId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
