<?php
use Router;

$router = new Router();
$router->get('/', 'HomeController@index');

return $router;
