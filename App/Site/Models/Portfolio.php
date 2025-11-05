<?php
namespace Site\Models;

use Core\CRUDModel;
use Core\Model;
use Exception;
//class Project extends CRUDModel {protected $table = 'projects';}

//namespace App\Models;



class Portfolio extends Model
{
    protected string $table = 'services';
    protected string $primaryKey = 'id';

    // Champs spécifiques
    public int $id;
    public string $name;
    //public string $customer;
    public string $description;
    //public string $project_date;
    //public string $category;
    //public string $slug;
    //public string $features;
    //public string $technologies;
    //public string $status;
    //public ?string $image;
    //public string $video_url;
    //public string $link_demo;
    public ?string $icon;
    public string $created_at;
    public string $updated_at;

    /**
 * Vérifie si la requête est une requête AJAX (Fetch, XMLHttpRequest…)
 *
 * @return bool
 */


    public function add(array $data): bool
    {
       return $this->create($data);
    }

   
   
    

   
}
