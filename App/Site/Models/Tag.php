<?php
namespace Site\Models;

use Core\Model;
use Exception;

class Tag extends Model
{
    protected string $table = 'tags';
    protected string $primaryKey = 'id';

    public string $name;
    public string $color;
    public string $id;

    /**
     * Crée un tag s'il n'existe pas déjà.
     */
    public function firstOrCreate(string $name, string $color = '#0d6efd'): ?object
    {
        try {
            $existing = $this->first('name', $name);
            if ($existing) {
                return $existing;
            }

            $data = [
                'name' => $name,
                'color' => $color
            ];

            if ($this->create($data)) {
                return $this->first('name', $name);
            }

            return null;
        } catch (Exception $e) {
            error_log("Erreur firstOrCreate Tag: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère tous les tags triés par nom
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
        $this->db->query($sql);
        return $this->hydrateAll($this->getData() ?? []);
    }

    /**
     * Récupère les tags associés à un élément d'une table
     */
    public function relatedTagsTable(?string $id, string $table): array
    {
        if (!$id) {
            return [];
        }

        $sql = "SELECT t.* FROM tags t
                INNER JOIN tagged_items ti ON t.id = ti.tag_id
                WHERE ti.tagged_table = :table AND ti.tagged_id = :id";
        
        $this->db->query($sql, ['table' => $table, 'id' => $id]);
        return $this->hydrateAll($this->getData() ?? []);
    }

    /**
     * Récupère un tag par son ID
     */
    public function getTag(int $id): ?object
    {
        return $this->find($id);
    }

    /**
     * Récupère les tags les plus utilisés
     */
    public function getPopularTags(int $limit = 10): array
    {
        $sql = "SELECT t.*, COUNT(ti.id) as usage_count 
                FROM tags t
                LEFT JOIN tagged_items ti ON t.id = ti.tag_id
                GROUP BY t.id
                ORDER BY usage_count DESC, t.name ASC
                LIMIT :limit";
        
        $this->db->query($sql, ['limit' => $limit]);
        return $this->getData() ?? [];
    }

    /**
     * Vérifie si un tag existe déjà
     */
    public function tagExists(string $name, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE name = :name";
        $params = ['name' => $name];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $this->db->query($sql, $params);
        $result = $this->getData();
        
        return !empty($result) && $result[0]['count'] > 0;
    }

    /**
     * Crée un nouveau tag avec validation
     */
    public function createTag(string $name, string $color = '#0d6efd'): bool
    {
        if ($this->tagExists($name)) {
            $this->errors[] = "Un tag avec ce nom existe déjà.";
            return false;
        }

        $data = [
            'name' => trim($name),
            'color' => $color
        ];

        return $this->create($data);
    }

    /**
     * Met à jour un tag existant
     */
    public function updateTag(int $id, string $name, string $color): bool
    {
        if ($this->tagExists($name, $id)) {
            $this->errors[] = "Un tag avec ce nom existe déjà.";
            return false;
        }

        $data = [
            'name' => trim($name),
            'color' => $color
        ];

        return $this->update($id, $data);
    }
}