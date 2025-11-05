<?php
namespace Site\Models;

use Core\Model;

class TaggedItem extends Model
{
    protected string $table = 'tagged_items';
    protected string $primaryKey = 'id';

    /**
     * Récupère les tags liés à un élément (table + id)
     */
    public function getTagsForItem(string $table, int $itemId): array
    {
        $sql = "
            SELECT t.* 
            FROM tags t
            INNER JOIN tagged_items ti ON ti.tag_id = t.id
            WHERE ti.tagged_table = :table AND ti.tagged_id = :id
        ";
        $res = $this->db->query($sql, ['table' => $table, 'id' => $itemId]);
        return $this->getData() ?? [];
    }

    /**
     * Synchronise les tags d’un élément : ajoute les nouveaux, supprime les anciens.
     */
    public function syncTags(string $table, int $itemId,array $tags=[])
    {
        $tagModel = new Tag();

        // Supprimer les anciens liens
        $del=$this->db->query("DELETE FROM {$this->table} WHERE tagged_table = :tbl AND tagged_id = :id", [
            'tbl' => $table, 'id' => $itemId
        ]);

        foreach ($tags as $tagId) {
            //$tag = $tagModel->firstOrCreate($tagName);
            $tag=$tagModel->getTags($tagId);
            
            if ($tag) {
                $this->create([
                    'tag_id' => $tag->id,
                    'tagged_table' => $table,
                    'tagged_id' => $itemId
                ]);
            }else{
                echo "Tag inexistant";
            }
        }
    }

    
}
