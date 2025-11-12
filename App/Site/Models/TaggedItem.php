<?php
namespace Site\Models;

use Core\Model;
use Exception;

class TaggedItem extends Model
{
    protected string $table = 'tagged_items';
    protected string $primaryKey = 'id';

    /**
     * Récupère les tags liés à un élément
     */
    public function getTagsForItem(string $table, int $itemId): array
    {
        try {
            $sql = "
                SELECT t.* 
                FROM tags t
                INNER JOIN tagged_items ti ON ti.tag_id = t.id
                WHERE ti.tagged_table = :table AND ti.tagged_id = :id
                ORDER BY t.name ASC
            ";
            
            $this->db->query($sql, ['table' => $table, 'id' => $itemId]);
            return $this->getData() ?? [];
        } catch (Exception $e) {
            error_log("Erreur getTagsForItem: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Synchronise les tags d'un élément
     */
    public function syncTags(string $table, int $itemId, array $tagIds = []): bool
    {
        try {
            // Démarrer une transaction
            $this->db->beginTransaction();

            // Supprimer les anciens liens
            $this->deleteItemTags($table, $itemId);

            // Ajouter les nouveaux tags
            foreach ($tagIds as $tagId) {
                if (!is_numeric($tagId)) {
                    continue;
                }

                $tagModel = new Tag();
                $tag = $tagModel->getTag((int)$tagId);
                
                if ($tag) {
                    $result = $this->create([
                        'tag_id' => $tag->id,
                        'tagged_table' => $table,
                        'tagged_id' => $itemId
                    ]);

                    if (!$result) {
                        throw new Exception("Erreur lors de l'ajout du tag: " . $tagId);
                    }
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur syncTags: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime tous les tags d'un élément
     */
    public function deleteItemTags(string $table, int $itemId): bool
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE tagged_table = :table AND tagged_id = :id";
            $this->db->query($sql, ['table' => $table, 'id' => $itemId]);
            return true;
        } catch (Exception $e) {
            error_log("Erreur deleteItemTags: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie si un tag est déjà associé à un élément
     */
    public function isTagAssociated(string $table, int $itemId, int $tagId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE tagged_table = :table AND tagged_id = :item_id AND tag_id = :tag_id";
        
        $this->db->query($sql, [
            'table' => $table,
            'item_id' => $itemId,
            'tag_id' => $tagId
        ]);

        $result = $this->getData();
        return !empty($result) && $result[0]['count'] > 0;
    }

    /**
     * Récupère tous les éléments taggés pour un tag spécifique
     */
    public function getTaggedItems(int $tagId, string $table): array
    {
        $sql = "SELECT ti.* FROM {$this->table} ti
                WHERE ti.tag_id = :tag_id AND ti.tagged_table = :table";
        
        $this->db->query($sql, ['tag_id' => $tagId, 'table' => $table]);
        return $this->getData() ?? [];
    }
}