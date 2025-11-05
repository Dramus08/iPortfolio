<?php
namespace Site\Controllers;

use Core\Controller;
use Router\Router;

class PricingController extends Controller
{
    public function pricing()
    {
        $this->render('pricings/pricing', ['title' => 'Prix Inoua Ismail','Router' => Router::class]);
    }
}