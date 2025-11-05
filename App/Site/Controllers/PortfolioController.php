<?php
namespace Site\Controllers;
use Core\Controller;
use Site\Models\Portfolio;
use Router\Router;

class PortfolioController extends Controller
{
    public function list()
    {
        $this->render('portfolios/list', ['title' => 'List PortFolio Inoua Ismail','Router' => Router::class]);
    }
    public function detail()
    {
        $this->render('portfolios/detail', ['title' => 'detail PortFolio Inoua Ismail','Router' => Router::class]);
    }

    public function index()
    {
        $portfolios = (new Portfolio())->all();
        $Router = Router::class;
        $this->render('modules/portfolios/list', compact('portfolios','Router'));
    }

    public function create()
    {
        $this->render('modules/portfolios/form',['csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

    public function store()
    {
        (new Portfolio())->add($_POST);
        $message='Portfolio ajouté avec succès';
        $route=Router::route('dashboard_portfolio_list');
       $this->responseSuccess($message,$route);
        
    }

    public function edit($id)
    {
        $portfolio = (new Portfolio())->find($id);

        $this->render('modules/portfolios/form',['csrf' => $this->csrfToken(),'portfolio'=> $portfolio,'Router' => Router::class]);
    }

    public function update($id)
    {
       (new Portfolio())->update($id, $_POST);
        $message='Portfolio mis a jour avec succès';
        $route=Router::route('dashboard_portfolio_list');
        $this->responseSuccess($message,$route);
    }

    public function confirm_delete($id)
    {
        (new Portfolio())->delete($id);
        $route=Router::route('dashboard_portfolio_list');
        $message='Portfolio supprime avec succès ';
        $this->responseDelete($message,$route);
    }

    public function delete($id)
    {
        $this->render('modules/portfolios/confirm_delete',['csrf' => $this->csrfToken(),'portfolio'=> (new Portfolio())->find($id),'Router' => Router::class]);
    }
}
