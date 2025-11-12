<?php
namespace App\Controllers;

use Admin\Models\Auth;
use Core\Controller;
use Admin\Models\User;
use Admin\Models\AuthProfile;

class ProfileController extends Controller
{
    /**
     * Affiche le formulaire d'édition du profil
     */
    public function editProfile(): void
    {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $profileModel = new AuthProfile();
        $profile = $profileModel->findBySlug($user->slug);
        
        $data = [
            'title' => 'Modifier mon profil',
            'user' => $user,
            'profile' => $profile,
            'Router' => Router::class
        ];
        
        $this->render('profile/edit_profile', $data);
    }
    
    /**
     * Met à jour le profil utilisateur
     */
    public function updateProfile(Request $request): void
    {
        $this->requireAuth();
        
        try {
            $user = $this->getCurrentUser();
            $data = $request->all();
            $files = $request->files();
            
            // Validation des données
            $errors = $this->validateProfileData($data);
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->flash('error', $error);
                }
                Response::redirect('edit_profile');
                return;
            }
            
            $profileModel = new AuthProfile();
            $success = $profileModel->updateOrCreate($user->id, $data, $files);
            
            if ($success) {
                $this->flash('success', 'Votre profil a été mis à jour avec succès.');
                Response::redirect('user_profile');
            } else {
                $this->flash('error', 'Une erreur est survenue lors de la mise à jour du profil.');
                Response::redirect('edit_profile');
            }
            
        } catch (\Exception $e) {
            $this->flash('error', 'Erreur: ' . $e->getMessage());
            Response::redirect('edit_profile');
        }
    }
    
    /**
     * Valide les données du profil
     */
    private function validateProfileData(array $data): array
    {
        $errors = [];
        
        // Validation des champs de base
        if (empty($data['first_name'])) {
            $errors[] = 'Le prénom est requis.';
        }
        
        if (empty($data['last_name'])) {
            $errors[] = 'Le nom est requis.';
        }
        
        // Validation de la date de naissance
        if (!empty($data['date_of_birth'])) {
            $dob = \DateTime::createFromFormat('Y-m-d', $data['date_of_birth']);
            if (!$dob || $dob > new \DateTime()) {
                $errors[] = 'La date de naissance est invalide.';
            }
        }
        
        // Validation des URLs
        $urlFields = ['website', 'social_facebook', 'social_twitter', 'social_linkedin', 'social_github'];
        foreach ($urlFields as $field) {
            if (!empty($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_URL)) {
                $errors[] = "L'URL $field est invalide.";
            }
        }
        
        // Validation de la bio (longueur)
        if (!empty($data['bio']) && strlen($data['bio']) > 500) {
            $errors[] = 'La biographie ne doit pas dépasser 500 caractères.';
        }
        
        return $errors;
    }
    
    /**
     * Récupère l'utilisateur connecté
     */
    protected function getCurrentUser()
    {
        // Implémentation selon votre système d'authentification
        if (isset($_SESSION['user_id'])) {
            return (new User())->find($_SESSION['user_id']);
        }
        return null;
    }
}