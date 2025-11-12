<?php
namespace Admin\Controllers;

use Core\Controller;
use Router\Router;
use Admin\Models\Auth;

class HomeController extends Controller
{
    protected ?string $layout = 'layout-home'; // layout par défaut

    public function index()
    {        
        $this->render('index', ['title' => 'Bienvenue sur mon mini framework','Router' => Router::class]);
    }

    public function home()
    {
        $this->requireMailConfirm();
        $auth=new Auth();
        $user=$auth->find($_SESSION['user_id']);
        $this->render('home', ['title' => 'Welcome '.$user->username,'Router' => Router::class,'user'=> $user,]);
    }
}
