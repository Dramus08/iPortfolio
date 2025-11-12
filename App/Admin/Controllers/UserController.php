<?php
namespace Admin\Controllers;

use Core\Controller;
use Admin\Models\User;
use Router\Router;

class UserController extends Controller

{
    /**
     * Liste tous les utilisateurs
     */
    public function index()
    {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        
        $userModel = new User();
        $users = $userModel->all();
        
        return $this->render('users/index', [
            'users' => $users,
            'Router' => Router::class
        ]);
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            var_dump($_POST);
            $success = $userModel->createUser($_POST);

            if ($success) {
                $this->flash('success', "L'utilisateur a été créé avec succès.");
                $this->redirect(Router::route('user_list'));
            } else {
                $this->flash('error', "Erreur lors de la création de l'utilisateur.");
                // Réafficher le formulaire avec les données saisies
                return $this->render('users/create', [
                    'formData' => $_POST,
                    'Router' => Router::class
                ]);
            }
        }

        return $this->render('users/create', [
            'formData' => [],
            'Router' => Router::class
        ]);
    }

    /**
     * Affiche les détails d'un utilisateur
     */
    public function show(string $slug)
    {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        
        $user = (new User())->findBySlug($slug);
        if (!$user) {
            $this->flash('error', "Utilisateur non trouvé.");
            $this->redirect(Router::route('user_list'));
        }

        return $this->render('users/show', [
            'user' => $user,
            'Router' => Router::class
        ]);
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(string $slug)
    {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);

        $userModel = new User();
        $user = $userModel->findBySlug($slug);
        
        if (!$user) {
            $this->flash('error', "Utilisateur non trouvé.");
            $this->redirect(Router::route('user_list'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $userModel->updateUser($user->id, $_POST);

            if ($success) {
                $this->flash('success', "L'utilisateur a été modifié avec succès.");
                $this->redirect(Router::route('user_list'));
            } else {
                $this->flash('error', "Erreur lors de la modification de l'utilisateur.");
                // Réafficher le formulaire avec les données saisies
                return $this->render('users/edit', [
                    'user' => $user,
                    'formData' => $_POST,
                    'Router' => Router::class
                ]);
            }
        }

        return $this->render('users/edit', [
            'user' => $user,
            'formData' => (array)$user,
            'Router' => Router::class
        ]);
    }

    /**
     * Supprime un utilisateur
     */
    public function delete(string $slug)
    {
        $this->requireAuth();
        $this->requireRole(['superadmin']);

        $userModel = new User();
        $user= $userModel->findBySlug($slug);
        if (!$user) {
            $this->flash('error', 'utilisateur Introuvable !');
        }
        if (!$userModel->canDelete($user->id)) {
            $this->flash('error', "Impossible de supprimer cet utilisateur.");
            $this->redirect(Router::route('user_list'));
        }

        $success = $userModel->delete($user->id);
        
        if ($success) {
            $this->flash('success', "Utilisateur supprimé avec succès.");
        } else {
            $this->flash('error', "Erreur lors de la suppression de l'utilisateur.");
        }

        $this->redirect(Router::route('user_list'));
    }

    /**
     * Active un utilisateur
     */
    public function activate(string $slug)
    {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);

        $userModel = new User();
        $user = $userModel->findBySlug($slug);
        $success = $userModel->setActive($user->id);
        
        if ($success) {
            $this->flash('success', "Utilisateur activé avec succès.");
        } else {
            $this->flash('error', "Erreur lors de l'activation de l'utilisateur.");
        }

        $this->redirect(Router::route('user_list'));
    }

    /**
     * Désactive un utilisateur
     */
    public function deactivate(string $slug)
    {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);

        $userModel = new User();
        $user = $userModel->findBySlug($slug);

        $success = $userModel->setInactive($user->id);
        
        if ($success) {
            $this->flash('warning', "Utilisateur désactivé avec succès.");
        } else {
            $this->flash('error', "Erreur lors de la désactivation de l'utilisateur.");
        }

        $this->redirect(Router::route('user_list'));
    }

    /**
     * Réinitialise le mot de passe
     */
    public function resetPassword(string $slug)
    {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);

        $userModel = new User();
        $user= $userModel->findBySlug($slug);
        
        try {
            $success = $userModel->resetPassword($user->id);
            
            if ($success) {
                $this->flash('success', "Le mot de passe a été réinitialisé avec succès.");
            } else {
                $this->flash('error', "Erreur lors de la réinitialisation du mot de passe.");
            }
        } catch (\Exception $e) {
            $this->flash('error', $e->getMessage());
        }

        $this->redirect(Router::route('user_list'));
    }

    /**
     * Change le rôle d'un utilisateur
     */
    public function changeRole(string $slug)
    {
        $this->requireAuth();
        $this->requireRole(['superadmin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? '';
            
            $userModel = new User();
            $user=$userModel->findBySlug($slug);
            
            try {
                $success = $userModel->changeRole($user->id, $role);
                
                if ($success) {
                    $this->flash('success', "Rôle de l'utilisateur modifié avec succès.");
                } else {
                    $this->flash('error', "Erreur lors du changement de rôle.");
                }
            } catch (\Exception $e) {
                $this->flash('error', $e->getMessage());
            }
        }

        $this->redirect(Router::route('user_list'));
    }

    /**
     * Recherche d'utilisateurs
     */
    public function search()
    {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);

        $keyword = $_GET['q'] ?? '';
        $userModel = new User();
        
        $users = $keyword ? $userModel->search($keyword) : $userModel->all();

        return $this->render('users/index', [
            'users' => $users,
            'search' => $keyword,
            'Router' => Router::class
        ]);
    }

    /**
     * Tableau de bord utilisateur
     */
    public function dashboard()
    {
        $this->requireAuth();

        $userModel = new User();
        $stats = $userModel->getStats();

        return $this->render('users.dashboard', [
            'user' => $_SESSION['user'] ?? null,
            'stats' => $stats,
            'Router' => Router::class
        ]);
    }

    /**
     * Confirme l'email d'un utilisateur (pour les liens de confirmation)
     */
    public function confirmEmail(string $token)
    {
        $userModel = new User();
        $success = $userModel->confirmEmail($token);

        if ($success) {
            $this->flash('success', "Votre email a été confirmé avec succès. Vous pouvez maintenant vous connecter.");
        } else {
            $this->flash('error', "Lien de confirmation invalide ou expiré.");
        }

        $this->redirect(Router::route('login'));
    }
}
