<?php
namespace Admin\Controllers;

use Core\Controller;
use Admin\Models\User;
use Router\Router;
class DashboardController extends Controller
{


    public function dashboard()
    {
        $this->requireRole('superadmin');

        $this->render('users.dashboard', [
            'user' => $_SESSION['user'] ?? null,'Router' => Router::class
        ]);
    }



        public function resetPassword(int $id)
    {
        $this->requireRole('superadmin');
        $userModel = new User();

        try {
            $userModel->resetPassword($id);
            $this->flash('success', "Le mot de passe a été réinitialisé avec succès.");
        } catch (\Exception $e) {
            $this->flash('error', $e->getMessage());
        }

        $this->redirect(Router::route('user_list'));
    }

    public function activate(int $id)
    {
        $this->requireRole('superadmin');
        (new User())->setActive($id);
        $this->flash('success', "Utilisateur activé.");
        $this->redirect(Router::route('user_list'));
    }

    public function deactivate(int $id)
    {
        $this->requireRole('superadmin');
        (new User())->setInactive($id);
        $this->flash('warning', "Utilisateur désactivé.");
        $this->redirect(Router::route('user_list'));
    }

    public function search()
    {
        $this->requireAuth();
        //$this->requireRole('superadmin');
        $keyword = $_GET['q'] ?? '';
        $users = $keyword ? (new User())->search($keyword) : (new User())->all();
        return $this->render('users/index', ['users' => $users, 'search' => $keyword,'Router' => Router::class]);
    }


    public function index()
    {
        $this->requireRole('superadmin');
        $users = (new User())->all();
        return $this->render('dashboard/dashboard', ['users' => $users,'Router' => Router::class]);
    }

     public function home()
    {
        //$this->requireRole('superadmin');
        if($this->isAdmin()){
            $users = (new User())->all();
            return $this->render('dashboard/dashboard', ['users' => $users,'Router' => Router::class]);
        }
        $message = "Accès refusé : droits insuffisants.";
        $this->flash("error", $message);
        $this->redirect(Router::route('index'));
        
    }


    public function create()
    {
        $this->requireRole('superadmin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
           $user = new User();
           $user->create($_POST);
           $this->flash('success', "L'utilisateur a ete creer avec success.");
           $this->redirect(Router::route('user_list'));
        }

        return $this->render('users/create',['Router' => Router::class]);
    }

    public function edit(int $id)
    {
       $this->requireRole('superadmin');
        $userModel = new User();
        $user = $userModel->find($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel->update($id, $_POST);
            $this->flash('warning', "L'utilisateur a ete modifier avec succès.");
            $this->redirect(Router::route('user_list'));
            exit;
        }

        return $this->render('users/edit', ['user' => $user,'Router' => Router::class]);
    }

    public function show(int $id)
    {
        $this->requireAuth();
        $this->requireRole('superadmin');
        $user = (new User())->find($id);
        return $this->render('users/show', ['user' => $user,'Router' => Router::class]);
    }

    public function delete(int $id)
    {
        $this->requireAuth();
        $this->requireRole('superadmin');
        (new User())->delete($id);
        $this->redirect(Router::route('user_list'));
        exit;
    }

}
