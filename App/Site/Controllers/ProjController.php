<?php
namespace Site\Controllers;

use Core\Controller;
use Site\Models\Project;
use Site\Models\Tag;
use Site\Models\TaggedItem;
use Validators\DataManager;
use Database\MYSQL_DB;
use Router\Router;

class ProjController extends Controller
{
    public function list()
    {
        $this->render('projects/list', ['title' => 'List Project Inoua Ismail','Router' => Router::class ]);
    }
    public function detail()
    {
        $this->render('projects/detail', ['title' => 'detail Project Inoua Ismail','Router' => Router::class]);
    }

    public function index()
    {
        $projects = (new Project())->all();
        $Router = Router::class;
        $projets=(new Project())->getItemsTableWithTags();
        $this->render('modules/projects/list', compact('projects','Router','projets'));
    }

    public function create()
    {
        $tags=(new Tag())->all();
        $this->render('modules/projects/form',['csrf' => $this->csrfToken(),'tags'=>$tags,'Router' => Router::class]);
    }

   public function storeFirst(){
        $projectModel = new Project();
        (new Project())->create($_POST);
        $message='Projet ajouté avec succès ';
        $route=Router::route('dashboard_project_list');
        $this->responseSuccess($message,$route);
        
    }

    public function edit($id)
    {
        $project = (new Project())->find($id);
        $selectedTags=(new Project())->getRelatedTags($id);
        $tags=(new Tag())->all();
        $this->render('modules/projects/form',['csrf' => $this->csrfToken(),'project'=> $project,'tags'=>$tags,'selectedTags'=>$selectedTags,'Router' => Router::class]);
    }
     public function show($id)
    {
        $project = (new Project())->find($id);
        $tags=(new Project())->getRelatedTags($id);
        $this->render('modules/projects/show',['csrf' => $this->csrfToken(),'project'=> $project,'Router' => Router::class,'tags'=>$tags]);
    }

    public function updateFirst($id)
    {
       (new Project())->update($id, $_POST);
        $route=Router::route('dashboard_project_list');
        $message='Projet mis a jour avec succès';
        $this->responseSuccess($message,$route);
    }

    public function confirm_delete($id)
    {
        (new Project())->delete($id);
        $route=Router::route('dashboard_project_list');
        $message='Projet supprime avec succès ';
        $this->responseDelete($message,$route);
    }

    public function delete($id)
    {
        $this->render('modules/projects/confirm_delete',['csrf' => $this->csrfToken(),'project'=> (new Project())->find($id),'Router' => Router::class]);
    }

    public function store(){
        $projectModel = new Project();
        $_POST['slug']=$projectModel->generateSlug($_POST['title']);

           try {
            if($projectModel->create($_POST)){
                $message='Projet ajouté avec succès ';
                $route=Router::route('dashboard_project_list');
                $this->responseSuccess($message,$route);
            }else{
                foreach ($projectModel->validate->getErrors() as $key => $value) {
                    $this->flashErrorForms($key,$value);
                }
                $message="Erreur :  Donnees transmis non valide veuiller reverifier le formulaire et remplir les champs obligtatoire !";
                $route=Router::route('dashboard_project_create');
                $this->responseError($message,$route,$projectModel->validate->getErrors());
            }
            
        } catch (Exception $e) {
            $this->responseError($e->getMessage(),$route,$projectModel->validate->getErrors());
        }

    }


    /**
     * Stocke un nouveau projet.
     */
    public function brouillon()
    {
        $projectModel = new Project();
        $data=$projectModel->validate->validate();
        if($projectModel->validate->isValidData()){
            $data = $projectModel->sanitizeFormData($prepared);

        }else{
            foreach ($projectModel->getErrors() as $key => $value) {
                    $this->flashErrorForms($key,$value);
                }
                $message="Erreur :  Donnees transmis non valide veuiller reverifier le formulaire et remplir les champs obligtatoire !";
                $route=Router::route('dashboard_project_create');
                $this->responseError($message,$route,$projectModel->getErrors());
        }

        // Gestion des fichiers
        if (!empty($_FILES['image']['name'])) {
            $data['image'] = htmlspecialchars($this->uploadFile($_FILES['image'], 'uploads/projects/images/'));
        }

        if (!empty($_FILES['video_file']['name'])) {
            $data['video_url'] = htmlspecialchars($this->uploadFile($_FILES['video_file'], 'uploads/projects/videos/'));
        }


        // Création du projet
        if ($projectModel->create($data)) {
            $project = $projectModel->first('slug', $data['slug']);

            // Gestion des tags
            //$tags = json_decode($data['tags'] ?? '[]', true);
            $tags=isset($data['tags']) && !empty($data['tags']);

            if (!empty($tags)) {
                    $projectModel->syncTags($project->id,$data['tags']);
            }

            Router::redirect('dashboard_project_list');
        } else {
            echo "Erreur lors de la création du projet.";
        }
    }

    /**
     * Met à jour un projet existant.
     */
    public function update($id)
    {
        $projectModel = new Project();
        $project = $projectModel->find($id);


        if (empty($project)) {
            echo "Projet introuvable.";
            return;
        }

        $data = $projectModel->sanitizeFormData($_POST);
        // Gestion des fichiers
        if (!empty($_FILES['image']['name'])) {
            $data['image'] = $this->uploadFile($_FILES['image'], 'uploads/images/');
        }

        if (!empty($_FILES['video_file']['name'])) {
            $data['video_url'] = $this->uploadFile($_FILES['video_file'], 'uploads/videos/');
        }

        $data['slug'] = $projectModel->generateSlug($data['title']);

        $result=$projectModel->update($id, $data);
        
        if ($result === true) {
            // Mise à jour des tags
            //$tags = json_decode($data['tags'] ?? '[]', true);
            $tags=isset($data['tags']) && !empty($data['tags']);
            if (!empty($tags)) {
                try {
                    $project->syncTags($id,$data['tags']);
                } catch (\Exception $e) {
                    $this->responseError("Erreur lors de l'enregistrement des tags");
                }
            }
            Router::redirect('dashboard_project_list');
            $this->responseSuccess("Le Projet a ete mis a jour avec success",Router::route('dashboard_project_list'));
        } else {
            $this->responseError("Erreur lors de la mise à jour du projet bla bla.");
        }
    }

    /**
     * Gestion centralisée de l’upload.
     */
    private function uploadFile($file, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $filename = time() . '_' . basename($file['name']);
        $targetPath = $destination . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $targetPath;
        }
        return null;
    }
}


