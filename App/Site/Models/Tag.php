<?php
namespace Site\Models;

use Core\Model;

class Tag extends Model
{
    protected string $table = 'tags';
    protected string $primaryKey = 'id';

    public string $name;
    public string $color;
    public string $id;

    /**
     * Crée un tag s’il n’existe pas déjà.
     */
    public function firstOrCreate(string $name, string $color = '#0d6efd')
    {
        $existing = $this->first('name', $name);
        if ($existing) {
            return $existing;
        }
        $this->create([
            'name' => $name,
            'color' => $color
        ]);
        return $this->first('name', $name);
    }

    public function all():array{
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC" ;
        $result = $this->db->query($sql);

        if($result) return $this->hydrateAll($this->getData() ?? []);
    }


    public function relatedTagsTable($id,$table){
        $sql = "SELECT t.* FROM tags t
            INNER JOIN tagged_items ti ON t.id = ti.tag_id
            WHERE ti.tagged_table = '$table' AND ti.tagged_id = '{$id}'" ;
        $result = $this->db->query($sql);
        if($result) return $this->hydrateAll($this->getData() ?? []);
    }
    public function getTags($id){
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        
        $result = $this->db->query($sql, ['id' => $id]);
        if ($result) {
            return $this->hydrate($this->getData()[0]);
        }

        return null;
    }
}
