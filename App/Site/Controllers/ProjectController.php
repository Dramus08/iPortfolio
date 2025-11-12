<?php
namespace Site\Controllers;

use Core\Controller;
use Site\Models\Project;
use Site\Models\Tag;
use Site\Models\TaggedItem;
use Router\Router;
use Exception;

class ProjectController extends Controller
{
    private Project $projectModel;
    private Tag $tagModel;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->tagModel = new Tag();
    }

    public function index()
    {
        $this->requireMailConfirm();
        $projects = $this->projectModel->getItemsWithTags();
        $this->render('modules/projects/list', [
            'projects' => $projects,
            'Router' => Router::class
        ]);
    }

    public function create()
    {
        $tags = $this->tagModel->all();
        $this->render('modules/projects/form', [
            'csrf' => $this->csrfToken(),
            'tags' => $tags,
            'Router' => Router::class
        ]);
    }

    public function store()
    {
        try {
            // Création du projet
            $success = $this->projectModel->createProject($_POST, $_FILES);
            
            if ($success) {
                $this->flash('success', 'Projet créé avec succès.');
                $this->redirect(Router::route('dashboard_project_list'));
            } else {
                $errors = $this->projectModel->getErrors();
                foreach ($errors as $error) {
                    $this->flash('error', $error);
                }
                $this->redirect(Router::route('dashboard_project_create'));
            }

        } catch (Exception $e) {
            $this->flash('error', 'Erreur lors de la création du projet: ' . $e->getMessage());
            $this->redirect(Router::route('dashboard_project_create'));
        }
    }

    public function edit($id)
    {
        $project = $this->projectModel->getItemsWithTags($id);
        //echo "<pre>";var_dump($project);echo "</pre>";
        $selectedTags = $this->projectModel->getRelatedTags($id);
        $tags = $this->tagModel->all();

        if (!$project) {
            $this->flash('error', 'Projet non trouvé.');
            $this->redirect(Router::route('dashboard_project_list'));
        }

        $this->render('modules/projects/form', [
            'csrf' => $this->csrfToken(),
            'project' => $project,
            'tags' => $tags,
            'selectedTags' => $selectedTags,
            'Router' => Router::class
        ]);
    }

    public function show($id)
    {
        $project = $this->projectModel->getItemsWithTags($id);
        $tags = $this->projectModel->getRelatedTags($id);

        if (!$project) {
            $this->flash('error', 'Projet non trouvé.');
            $this->redirect(Router::route('dashboard_project_list'));
        }

        $this->render('modules/projects/show', [
            'project' => $project,
            'tags' => $tags,
            'Router' => Router::class
        ]);
    }

    public function update($id)
    {
        try {
            // Mise à jour du projet
            $success = $this->projectModel->updateProject($id, $_POST, $_FILES);
            
            if ($success) {
                $this->flash('success', 'Projet mis à jour avec succès.');
                $this->redirect(Router::route('dashboard_project_list'));
            } else {
                $errors = $this->projectModel->getErrors();
                foreach ($errors as $error) {
                    $this->flash('error', $error);
                }
                $this->redirect(Router::route('dashboard_project_edit', ['id' => $id]));
            }

        } catch (Exception $e) {
            $this->flash('error', 'Erreur lors de la mise à jour du projet: ' . $e->getMessage());
            $this->redirect(Router::route('dashboard_project_edit', ['id' => $id]));
        }
    }

    public function delete($id)
    {
        $project = $this->projectModel->find($id);
        
        if (!$project) {
            $this->flash('error', 'Projet non trouvé.');
            $this->redirect(Router::route('dashboard_project_list'));
        }

        $this->render('modules/projects/confirm_delete', [
            'csrf' => $this->csrfToken(),
            'project' => $project,
            'Router' => Router::class
        ]);
    }

    public function confirm_delete($id)
    {
        $success = $this->projectModel->deleteProject($id);
        
        if ($success) {
            $this->flash('success', 'Projet supprimé avec succès.');
        } else {
            $errors = $this->projectModel->getErrors();
            foreach ($errors as $error) {
                $this->flash('error', $error);
            }
        }

        $this->redirect(Router::route('dashboard_project_list'));
    }

    // Méthodes pour l'API/JSON
    public function apiList()
    {
        $projects = $this->projectModel->getItemsWithTags();
        $this->toJson($projects);
    }

    public function apiShow($id)
    {
        $project = $this->projectModel->getItemsWithTags($id);
        
        if (!$project) {
            $this->toJson(['error' => 'Projet non trouvé'], 404);
            return;
        }

        $this->toJson($project);
    }
}



