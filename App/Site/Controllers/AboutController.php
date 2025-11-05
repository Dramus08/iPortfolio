<?php
namespace Site\Controllers;

use Core\Controller;
use Router\Router;

class AboutController extends Controller
{
    public function about()
    {
        $this->render('abouts/about', ['title' => 'A propos Inoua Ismail','Router' => Router::class]);
    }
}