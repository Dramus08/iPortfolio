<?php
namespace Site\Controllers;
use Core\Controller;

use Site\Models\Testimonial;
use Router\Router;

class TestimonialController extends Controller
{
    public function list()
    {
        $this->render('testimonials/list', ['title' => 'List Testimonial Inoua Ismail','Router' => Router::class]);
    }
    public function detail()
    {
        $this->render('testimonials/detail', ['title' => 'detail Testimonial Inoua Ismail','Router' => Router::class]);
    }

    public function index()
    {
        $testimonials = (new Testimonial)->all();
        $Router = Router::class;
        $this->render('modules/testimonials/list', compact('testimonials','Router'));
    }

    public function create()
    {
        $this->render('modules/testimonials/form',['csrf' => $this->csrfToken(),'Router' => Router::class]);
    }

    public function store()
    {
        (new Testimonial())->add($_POST);
        $message='Temoignage ajouté avec succès';
        $route=Router::route('dashboard_testimonial_list');
        $this->responseSuccess($message,$route);
        
    }

    public function edit($id)
    {
        $testimonial = (new Testimonial())->find($id);

        $this->render('modules/testimonials/form',['csrf' => $this->csrfToken(),'testimonial'=> $testimonial,'Router' => Router::class]);
    }

    public function update($id)
    {
       (new Testimonial())->update($id, $_POST);
        $message='Temoignage mis a jour avec succès';
        $route=Router::route('dashboard_testimonial_list');
        $this->responseSuccess($message,$route);
    }

    public function confirm_delete($id)
    {
        (new Testimonial())->delete($id);
        $route=Router::route('dashboard_testimonial_list');
        $message='Temoignage supprime avec succès ';
        $this->responseDelete($message,$route);
    }

    public function delete($id)
    {
        $this->render('modules/testimonials/confirm_delete',['csrf' => $this->csrfToken(),'testimonial'=> (new Testimonial())->find($id),'Router' => Router::class]);
    }
}
