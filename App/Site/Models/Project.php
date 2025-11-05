<?php
namespace Site\Models;

use Core\CRUDModel;
use Core\Model;
use Core\Controller;
use Exception;
use Validators\DataValidator;
//class Project extends CRUDModel {protected $table = 'projects';}

//namespace App\Models;



class Project extends Model
{
    protected string $table = 'projects';
    protected string $primaryKey = 'id';

    // Champs spécifiques
    public int $id;
    public string $title;
    public ?string $customer;
    public string $description;
    public ?string $project_date;
    public string $category;
    public string $slug;
    public ?string $features;
    public ?string $technologies;
    public string $status;
    public ?string $image;
    public ?string $video_url;
    public ?string $link_demo;
    public string $created_at;
    public string $updated_at;



    /** @var array Liste des tags liés au projet */
    public array $tags = [];
    public string $name;
    public string $color;


    /**
 * Vérifie si la requête est une requête AJAX (Fetch, XMLHttpRequest…)
 *
 * @return bool
 */


    public function update(int|string $id,array $data=[],array $files=[]):bool{
        return Model::update($id,$data,$files);
    }
         /**
     * Récupère les tags associés à ce projet.
     */
    public function tags(): array
    {
        $tagged = new TaggedItem();
        return $tagged->getTagsForItem($this->table, $this->id);
    }

    /**
     * Met à jour la liste de tags associés à ce projet.
     */
    public function syncTags(int $id,array $data=[])
    {
        $tagged = new TaggedItem();
        return $tagged->syncTags($this->table, $id,$data);
    }

    public function getRelatedTags(?string $id=null){
        return (new Tag())->relatedTagsTable($id,$this->table);
    }

    public function getItemsTableWithTags( ?int $id = null): ?array
    {
        $table = $this->table;
        if (!$table) {
            throw new Exception("Aucune table spécifiée pour getItemsWithTags().");
        }

        // 🔍 Récupération des colonnes de la table principale
        $columns = $this->getColumnsTable();
        $cols = implode(", ", array_map(fn($c) => "p.$c", $columns));

        // 🔧 Construction de la requête SQL
        $sql = "
            SELECT 
                $cols,
                t.id AS tag_id,
                t.name AS tag_name,
                t.color AS tag_color
            FROM {$table} AS p
            LEFT JOIN tagged_items ti 
                ON ti.tagged_table = :table 
                AND ti.tagged_id = p.id
            LEFT JOIN tags t 
                ON t.id = ti.tag_id
        ";

        // Ajout conditionnel du WHERE
        $params = ['table' => $table];
        if (!is_null($id)) {
            $sql .= " WHERE p.id = :id";
            $params['id'] = $id;
        }

        $sql .= " ORDER BY p.id";

        // 🔒 Exécution de la requête préparée
        $stmt = $this->db->query($sql,$params);
        $results = $this->getData();

        if (empty($results)) {
            return $id ? null : []; // Si un id est fourni et rien trouvé → null, sinon tableau vide
        }

        // 🧠 Regroupement intelligent par ID principal
        $items = [];
        foreach ($results as $row) {
            $pid = $row['id'];

            // Crée l'entrée principale si inexistante
            if (!isset($items[$pid])) {
                $items[$pid] = [
                    'id'   => $pid,
                    'tags' => [],
                ];

                // Ajoute les colonnes principales
                foreach ($columns as $col) {
                    $items[$pid][$col] = $row[$col] ?? null;
                }
            }

            // Ajout des tags liés
            if (!empty($row['tag_id'])) {
                $items[$pid]['tags'][] = [
                    'id'    => $row['tag_id'],
                    'name'  => $row['tag_name'],
                    'color' => $row['tag_color'],
                ];
            }
        }

        // ✅ Retourne un seul enregistrement ou la liste complète
        $final = array_values($items);
        return $id ? $final[0] : $this->hydrateAll($final);
    }



    public function validator($data){
        $columnsInfo=$this->getColumnsInfoTable();
        $validator = new DataValidator($columnsInfo, $data);
        
        try {
            $prepared = $validator->validate();
            echo "✅ Données valides et prêtes à insérer.</br>";
            echo "<pre>";print_r($prepared); echo "</pre><br>";
        } catch (Exception $e) {
            $_SESSION['errorForms']=$validator->getErrors();
            //var_dump($_SESSION['errorForms']);

            //echo $validator->renderErrors();
        }

    }




   
   
    

   
}

