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
    $router->get('/', 'Admin\Controllers\HomeController@index','index');
    $router->get('/home', 'Admin\Controllers\HomeController@home','home');

    $router->get('/brouillon', 'Admin\Controllers\BrouillonController@brouillon','brouillon');

    /* Les Differents urls pour la gestion des utilisateurs  */ 

    $router->get('/users', 'Admin\Controllers\UserController@index', 'user_list');
    $router->get('/users/create', 'Admin\Controllers\UserController@create','user_create');
    $router->post('/users/create', 'Admin\Controllers\UserController@create');
    $router->get('/users/edit/:id', 'Admin\Controllers\UserController@edit','user_edit');
    $router->post('/users/edit/:id', 'Admin\Controllers\UserController@edit');
    $router->get('/users/show/:id', 'Admin\Controllers\UserController@show','user_show');
    $router->get('/users/delete/:id', 'Admin\Controllers\UserController@delete',"user_delete");
    $router->get('/users/:id/reset-password/', 'Admin\\Controllers\\UserController@resetPassword', 'user_reset');
    $router->get('/users/:id/activate/', 'Admin\\Controllers\\UserController@activate', 'user_activate');
    $router->get('/users/:id/deactivate/', 'Admin\\Controllers\\UserController@deactivate', 'user_deactivate');
    $router->get('/users/search', 'Admin\\Controllers\\UserController@search', 'user_search');





    /* Les Differents urls pour authentification et gestion du profile  */ 
    
    $router->get('/auth/login', 'Admin\\Controllers\\AuthController@login','login');
    $router->post('/auth/login', 'Admin\\Controllers\\AuthController@login');

    $router->get('/auth/register', 'Admin\\Controllers\\AuthController@register','register');
    $router->post('/auth/register', 'Admin\\Controllers\\AuthController@register');
    $router->get('/auth/:slug/profile/', 'Admin\\Controllers\\AuthProfileController@profile','auth_profile');
    $router->get('/auth/profile/:slug/edit', 'Admin\\Controllers\\AuthProfileController@profile_edit','auth_profile_edit');
    $router->get('/auth/profile/:slug/create', 'Admin\\Controllers\\AuthProfileController@profile_create','auth_profile_create');
    $router->get('/auth/forgot-password', 'Admin\\Controllers\\AuthController@forgotPassword','forgot_password');
    $router->get('/auth/:slug/waiting-password-reset', 'Admin\\Controllers\\AuthController@WaitingPasswordReset','waiting_password_reset');
    $router->post('/auth/forgot-password', 'Admin\\Controllers\\AuthController@forgotPassword','forgot_password');
    $router->get('/auth/logout', 'Admin\\Controllers\\AuthController@logout','logout');
    $router->get('/auth/:slug/waiting-confirmation/', 'Admin\\Controllers\\AuthController@waitingConfirmation','waiting_confirmation_mail');
    $router->get('/auth/:slug/confirm-email/', 'Admin\\Controllers\\AuthController@confirmEmail', 'confirm_email');
    $router->post('/auth/:slug/change-email/', 'Admin\\Controllers\\AuthController@changeEmail', 'change_email');
    $router->get('/auth/:slug/change-email/', 'Admin\\Controllers\\AuthController@changeEmail', 'change_email');

    $router->post('/auth/:slug/resend-token/', 'Admin\\Controllers\\AuthController@resendToken', 'resend_token');
    $router->post('/auth/:slug/change-password', 'Admin\\Controllers\\AuthController@changePassword');

    $router->get('/auth/:slug/change-password/', 'Admin\\Controllers\\AuthController@showChangePasswordForm', 'change_password');

    
    $router->get('/auth/:slug/waiting-password-reset', 'Admin\\Controllers\\AuthController@WaitingPasswordReset','waiting_password_reset');
    $router->get('/auth/:slug/reset-default-password', 'Admin\\Controllers\\AuthController@resetDefaultPassword','reset_default_password');
    $router->post('/auth/:slug/reset-default-password', 'Admin\\Controllers\\AuthController@resetDefaultPassword','reset_default_password');


    $router->post('/auth/:token/resend-token/', 'Admin\\Controllers\\AuthController@resendTokenResetPassword', 'resend_token_reset_password');

    $router->post('/auth/:token/change-password', 'Admin\\Controllers\\AuthController@changePasswordByTokenEmail','change_password_by_token_email');

    $router->get('/auth/:token/change-password', 'Admin\\Controllers\\AuthController@changePasswordByTokenEmail','change_password_by_token_email');

    $router->get('/auth/:token/confirm-email-reset-password/', 'Admin\\Controllers\\AuthController@confirmEmailResetPassword', 'confirm_email_reset_password');

    $router->get('/auth/:token/waiting-confirmation-reset-password', 'Admin\\Controllers\\AuthController@waitingConfirmationResetPassword','waiting_confirmation_mail_reset_password');






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
    $router->get('/dashboard', 'Admin\\Controllers\\DashboardController@home','dashboard_admin');

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
