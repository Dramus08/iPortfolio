<?php
ini_set('display_errors', 1);
require "../vendor/autoload.php";

//require "../autoload_home.php";
//use Router\Router;
use Core\Exceptions\NotFoundException;
use Core\CSRFProtection;
use Router\Router;
use Router\routes;



// === CONSTANTES ===
define('VIEWS', dirname(__DIR__) . '/Views/');
define('ASSETS', "/".basename(dirname(__DIR__)) . '/assets/');
define('INCLUDES', (dirname(__DIR__)) . '/includes/');
define('SCRIPTS', dirname($_SERVER['SCRIPT_NAME']) . '/');
define('HTDOCS', '/'.basename(dirname(__DIR__)));
//define('ERROR_LOG_PATH', '/' . HTDOCS . '/logs/site_errors.log');
//define('SQL_LOG_PATH', '/' . HTDOCS . '/logs/sql_queries.log');
// === CONFIG BASE DE DONNÉES ===
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio');
define('DB_USER', 'root');
define('DB_PASS', 'SandatuAdamou@1965');
// === CSRF Protection ===
$csrf = new CSRFProtection();
$token = $_POST['csrf_token'] ?? ($_SESSION['csrf_token'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$csrf->verifyToken($token)) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(["error"=>true,"message" => "Le token CSRF est invalide ou a expiré."]);
        exit();
    }
    die("Erreur : Le token CSRF est invalide ou a expiré.");


}

    // === ROUTES ===
    $router = new Router($_GET['url'] ?? '/');

    // Exemple routes User
    $router->get('/', 'Site\Controllers\HomeController@index','index');
    $router->get('/users', 'Admin\Controllers\UserController@index', 'user_list');
    $router->get('/users/create', 'Admin\Controllers\UserController@create','user_create');
    $router->post('/users/create', 'Admin\Controllers\UserController@create');
    $router->get('/users/edit/:id', 'Admin\Controllers\UserController@edit','user_edit');
    $router->post('/users/edit/:id', 'Admin\Controllers\UserController@edit');
    $router->get('/users/show/:id', 'Admin\Controllers\UserController@show','user_show');
    $router->get('/users/delete/:id', 'Admin\Controllers\UserController@delete',"user_delete");
    $router->get('/users/reset-password/:id', 'Admin\\Controllers\\UserController@resetPassword', 'user_reset');
    $router->get('/users/activate/:id', 'Admin\\Controllers\\UserController@activate', 'user_activate');
    $router->get('/users/deactivate/:id', 'Admin\\Controllers\\UserController@deactivate', 'user_deactivate');
    $router->get('/users/search', 'Admin\\Controllers\\UserController@search', 'user_search');
    $router->get('/auth/login', 'Admin\\Controllers\\AuthController@login','login');
    $router->post('/auth/login', 'Admin\\Controllers\\AuthController@login');

    $router->get('/auth/register', 'Admin\\Controllers\\AuthController@register','register');
    $router->post('/auth/register', 'Admin\\Controllers\\AuthController@register');
    $router->get('/auth/profile/:id', 'Admin\\Controllers\\AuthController@profile','profile_user');

    $router->get('/auth/logout', 'Admin\\Controllers\\AuthController@logout','logout');
    $router->get('/auth/waiting-confirmation/:id', 'Admin\\Controllers\\AuthController@waitingConfirmation','waiting_confirmation_mail');
    $router->get('/auth/confirm-email', 'Admin\\Controllers\\AuthController@confirmEmail', 'confirm_email');
    $router->post('/auth/change-email/:id', 'Admin\\Controllers\\AuthController@changeEmail', 'change_email');
    $router->post('/auth/resend-token/:id', 'Admin\\Controllers\\AuthController@resendToken', 'resend_token');


    // Les liens de mon site
    $router->get('/about', 'Site\\Controllers\\AboutController@about','about');
    $router->get('/contact', 'Site\\Controllers\\ContactController@contact','contact');
    $router->get('/portfolio', 'Site\\Controllers\\PortfolioController@list','portfolio_list');
    $router->get('/portfolio/detail', 'Site\\Controllers\\PortfolioController@detail','portfolio_detail');
    $router->get('/pricing', 'Site\\Controllers\\PricingController@pricing','pricing');
    $router->get('/project', 'Site\\Controllers\\ProjectController@list','project_list');
    $router->get('/project/detail', 'Site\\Controllers\\ProjectController@detail','project_detail');
    $router->get('/service', 'Site\\Controllers\\ServiceController@list','service_list');
    $router->get('/service/detail', 'Site\\Controllers\\ServiceController@detail','service_detail');
    $router->get('/testimonial', 'Site\\Controllers\\testimonialController@list','testimonial_list');
    $router->get('/testimonial/detail', 'Site\\Controllers\\testimonialController@detail','testimonial_detail');


    // les differesnts des liens des dashboard
    $router->get('/dashboard', 'Admin\\Controllers\\DashboardController@index','dashboard_admin');

    // CRUD des projets
    $router->get('/projects', 'Site\\Controllers\\ProjectController@index','dashboard_project_list');
    $router->get('/projects/create','Site\\Controllers\\ProjectController@create','dashboard_project_create');
    $router->post('/projects/store', 'Site\\Controllers\\ProjectController@store','dashboard_project_store');
    $router->get('/projects/edit/:id', 'Site\\Controllers\\ProjectController@edit' ,'dashboard_project_edit');
    $router->post('/projects/update/:id', 'Site\\Controllers\\ProjectController@update','dashboard_project_update');
    $router->get('/projects/delete/:id', 'Site\\Controllers\\ProjectController@delete','dashboard_project_delete');
    $router->get('/projects/show/:id', 'Site\\Controllers\\ProjectController@show','dashboard_project_show');

    $router->post('/projects/confirm_delete/:id', 'Site\\Controllers\\ProjectController@confirm_delete','dashboard_project_confirm_delete');

    // routes  des services 
    $router->get('/services', 'Site\\Controllers\\ServiceController@index','dashboard_service_list');
    $router->get('/services/create','Site\\Controllers\\ServiceController@create','dashboard_service_create');
    $router->post('/services/store', 'Site\\Controllers\\ServiceController@store','dashboard_service_store');
    $router->get('/services/edit/:id', 'Site\\Controllers\\ServiceController@edit' ,'dashboard_service_edit');
    $router->post('/services/update/:id', 'Site\\Controllers\\ServiceController@update','dashboard_service_update');
    $router->get('/services/delete/:id', 'Site\\Controllers\\ServiceController@delete','dashboard_service_delete');
    $router->post('/services/confirm_delete/:id', 'Site\\Controllers\\ServiceController@confirm_delete','dashboard_service_confirm_delete');


    // routes  des temoignages 
    $router->get('/testimonials', 'Site\\Controllers\\TestimonialController@index','dashboard_testimonial_list');
    $router->get('/testimonials/create','Site\\Controllers\\TestimonialController@create','dashboard_testimonial_create');
    $router->post('/testimonials/store', 'Site\\Controllers\\TestimonialController@store','dashboard_testimonial_store');
    $router->get('/testimonials/edit/:id', 'Site\\Controllers\\TestimonialController@edit' ,'dashboard_testimonial_edit');
    $router->post('/testimonials/update/:id', 'Site\\Controllers\\TestimonialController@update','dashboard_testimonial_update');
    $router->get('/testimonials/delete/:id', 'Site\\Controllers\\TestimonialController@delete','dashboard_testimonial_delete');
    $router->post('/testimonials/confirm_delete/:id', 'Site\\Controllers\\TestimonialController@confirm_delete','dashboard_testimonial_confirm_delete');


        // routes  des Portfolios 
    $router->get('/portfolios', 'Site\\Controllers\\PortfolioController@index','dashboard_portfolio_list');
    $router->get('/portfolios/create','Site\\Controllers\\PortfolioController@create','dashboard_portfolio_create');
    $router->post('/portfolios/store', 'Site\\Controllers\\PortfolioController@store','dashboard_portfolio_store');
    $router->get('/portfolios/edit/:id', 'Site\\Controllers\\PortfolioController@edit' ,'dashboard_portfolio_edit');
    $router->post('/portfolios/update/:id', 'Site\\Controllers\\PortfolioController@update','dashboard_portfolio_update');
    $router->get('/portfolios/delete/:id', 'Site\\Controllers\\PortfolioController@delete','dashboard_portfolio_delete');
    $router->post('/portfolios/confirm_delete/:id', 'Site\\Controllers\\PortfolioController@confirm_delete','dashboard_portfolio_confirm_delete');




// === Lancer le routeur ===
try {
    $router->run();
} catch (NotFoundException $e) {
    $e->error404();
} catch (Exception $e) {
    echo "<h1>Erreur :</h1> <p>{$e->getMessage()}</p>";
}
