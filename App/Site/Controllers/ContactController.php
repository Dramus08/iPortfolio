<?php
namespace Site\Controllers;

use Core\Controller;
use Router\Router;

class ContactController extends Controller
{
    public function contact()
    {
        $this->render('contacts/contact', ['title' => 'Contact Inoua Ismail','Router' => Router::class]);
    }
}