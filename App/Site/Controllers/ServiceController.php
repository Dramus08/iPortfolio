<?php
namespace Site\Controllers;
use Core\Controller;
use Site\Models\Project;
use Router\Router;
use Site\Models\Service;

class ServiceController extends Controller
{
    public function list()
    {
        $this->render('services/list', ['title' => 'List Project Inoua Ismail','Router' => Router::class]);
    }
    public function detail()
    {
        $this->render('services/detail', ['title' => 'detail Project Inoua Ismail','Router' => Router::class]);
    }

    public function index()
    {
        $services = (new Service())->all();
        $Router=Router::class;
        $this->render('modules/services/list', compact('services','Router'));
    }

    public function create()
    {
        $this->render('modules/services/form',['csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

    public function store()
    {
        (new Service())->add($_POST);
        $message='Service ajouté avec succès';
        $route=Router::route('dashboard_service_list');

       $this->responseSuccess($message,$route);
        
    }

    public function edit($id)
    {
        $service = (new Service())->find($id);

        $this->render('modules/services/form',['csrf' => $this->csrfToken(),'service'=> $service,'Router' => Router::class]);
    }

    public function update($id)
    {
       (new Service())->update($id, $_POST);
        $message='Service mis a jour avec succès';
        $route=Router::route('dashboard_service_list');
        $this->responseSuccess($message,$route);
    }

    public function confirm_delete($id)
    {
        (new Service())->delete($id);
        $route=Router::route('dashboard_service_list');
        $message='Service supprime avec succès ';
        $this->responseDelete($message,$route);
    }

    public function delete($id)
    {
        $this->render('modules/services/confirm_delete',['csrf' => $this->csrfToken(),'service'=> (new Service())->find($id),'Router' => Router::class]);
    }
}
