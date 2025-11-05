<?php
namespace Site\Controllers;

use Core\Controller;
use Router\Router;

class HomeController extends Controller
{
    public function index()
    {
        $this->render('home', ['title' => 'Bienvenue sur mon mini framework','Router' => Router::class]);
    }
}
