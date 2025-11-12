<?php
namespace Admin\Models;

use Core\Model;

class AuthProfile extends Model
{
    protected string $table = 'profile_user';
    protected string $primaryKey = 'id';
    
    /**
     * Trouve un profil par user_id
     */
    public function findByUserId(int $userId): ?object
    {
        return $this->where('user_id', $userId);
    }
    
    /**
     * Crée ou met à jour un profil
     */
    public function updateOrCreate(int $userId, array $data, array $files = []): bool
    {
        $existingProfile = $this->findByUserId($userId);
        
        // Préparation des données pour profile_user
        $profileData = $this->prepareProfileData($data);
        $profileData['user_id'] = $userId;
        
        // Gestion de l'avatar
        if (!empty($files['avatar'])) {
            $profileData['avatar'] = $this->handleAvatarUpload($files['avatar'], $userId);
        } elseif (isset($data['delete_avatar']) && $data['delete_avatar'] == '1') {
            $profileData['avatar'] = null;
        }
        
        if ($existingProfile) {
            // Mise à jour
            return $this->update($existingProfile->id, $profileData);
        } else {
            // Création
            return $this->create($profileData);
        }
    }
    
    /**
     * Prépare les données pour la table profile_user
     */
    private function prepareProfileData(array $data): array
    {
        $profileData = [];
        
        // Mapping des champs
        $fieldMapping = [
            'date_of_birth' => 'date_of_birth',
            'gender' => 'gender',
            'bio' => 'bio',
            'address' => 'address',
            'city' => 'city',
            'country' => 'country',
            'postal_code' => 'postal_code',
            'website' => 'website',
            'company' => 'company',
            'job_title' => 'job_title',
            'social_facebook' => 'social_facebook',
            'social_twitter' => 'social_twitter',
            'social_linkedin' => 'social_linkedin',
            'social_github' => 'social_github',
            'notification_email' => 'notification_email',
            'notification_sms' => 'notification_sms',
            'privacy_public_profile' => 'privacy_public_profile',
            'privacy_show_email' => 'privacy_show_email',
            'language' => 'language',
            'timezone' => 'timezone',
            'currency' => 'currency'
        ];
        
        foreach ($fieldMapping as $formField => $dbField) {
            if (isset($data[$formField])) {
                $profileData[$dbField] = $data[$formField];
            }
        }
        
        // Conversion des booléens
        $booleanFields = ['notification_email', 'notification_sms', 'privacy_public_profile', 'privacy_show_email'];
        foreach ($booleanFields as $field) {
            if (isset($profileData[$field])) {
                $profileData[$field] = (bool)$profileData[$field];
            }
        }
        
        return $profileData;
    }
    
    /**
     * Gère l'upload de l'avatar
     */
    private function handleAvatarUpload(array $file, int $userId): string
    {
        $uploadDir = 'uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'avatar_' . $userId . '_' . time() . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $filePath;
        }
        
        throw new \Exception('Erreur lors du téléchargement de l\'avatar.');
    }
}