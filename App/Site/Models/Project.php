<?php
namespace Site\Models;

use Core\Model;
use Exception;
use Validators\DataValidator;

class Project extends Model
{
    protected string $table = 'projects';
    protected string $primaryKey = 'id';

    // Champs spécifiques
    public int $id;
    public string $title;
    public ?string $customer;
    public string $description;
    public ?string $project_date;
    public string $category;
    public string $slug;
    public ?string $features;
    public ?string $technologies;
    public string $status;
    public ?string $image;
    public ?string $video_url;
    public ?string $link_demo;
    public string $created_at;
    public string $updated_at;

    /** @var array Liste des tags liés au projet */
    public array $tags = [];

    // Configuration des uploads
    protected array $uploadConfig = [
        'image' => [
            'folder' => 'uploads/projects/images/',
            'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'max_size' => 5 * 1024 * 1024, // 5MB
            'generate_name' => true
        ],
        'video_file' => [
            'folder' => 'uploads/projects/videos/',
            'allowed_types' => ['mp4', 'mov', 'avi', 'webm'],
            'max_size' => 50 * 1024 * 1024, // 50MB
            'generate_name' => true
        ]
    ];

    /**
     * Crée un nouveau projet avec gestion complète
     */
    public function createProject(array $data, array $files = []): bool
    {
        try {
            // Validation des données
            $errors = $this->validateProjectData($data);
            if (!empty($errors)) {
                $this->errors = $errors;
                return false;
            }

            // Préparation des données
            $projectData = $this->prepareProjectData($data);

            // Gestion des uploads
            $uploadResults = $this->handleFileUploads($files);
            if (isset($uploadResults['errors']) && !empty($uploadResults['errors'])) {
                $this->errors = array_merge($this->errors, $uploadResults['errors']);
                return false;
            }

            // Fusion des données d'upload
            $projectData = array_merge($projectData, $uploadResults['success']);

            // Création du projet
            $result = parent::create($projectData);
            if (!$result) {
                $this->errors[] = "Erreur lors de la création du projet en base de données.";
                return false;
            }

            // Récupération de l'ID créé
            $projectId = $this->db->getLastInsertId();
            
            // Gestion des tags
            if (!empty($data['tags'])) {
                $this->syncTags($projectId, $data['tags']);
            }

            return true;

        } catch (Exception $e) {
            $this->errors[] = "Erreur: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Met à jour un projet existant
     */
    public function updateProject(int $id, array $data, array $files = []): bool
    {
        try {
            // Vérifier que le projet existe
            $existing = $this->find($id);
            if (!$existing) {
                $this->errors[] = "Projet non trouvé.";
                return false;
            }

            // Validation des données
            $errors = $this->validateProjectData($data, $id);
            if (!empty($errors)) {
                $this->errors = $errors;
                return false;
            }

            // Préparation des données
            $projectData = $this->prepareProjectData($data, true);

            // Gestion des uploads
            $uploadResults = $this->handleFileUploads($files);
            if (isset($uploadResults['errors']) && !empty($uploadResults['errors'])) {
                $this->errors = array_merge($this->errors, $uploadResults['errors']);
                return false;
            }

            // Fusion des données d'upload
            $projectData = array_merge($projectData, $uploadResults['success']);

            // Mise à jour du projet
            $result = parent::update($id, $projectData);
            if (!$result) {
                $this->errors[] = "Erreur lors de la mise à jour du projet.";
                return false;
            }

            // Gestion des tags
            if (isset($data['tags'])) {
                $this->syncTags($id, $data['tags']);
            }

            return true;

        } catch (Exception $e) {
            $this->errors[] = "Erreur: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Valide les données du projet
     */
    private function validateProjectData(array $data, ?int $projectId = null): array
    {
        $errors = [];

        // Validation des champs requis
        $required = ['title', 'description', 'category'];
        foreach ($required as $field) {
            if (empty(trim($data[$field] ?? ''))) {
                $errors[$field] = "Le champ {$field} est requis.";
            }
        }

        // Validation de la longueur
        if (isset($data['title']) && strlen($data['title']) > 255) {
            $errors['title'] = "Le titre ne doit pas dépasser 255 caractères.";
        }

        // Validation du slug
        if (isset($data['title'])) {
            $slug = $this->generateSlug($data['title']);
            if ($this->slugExists($slug, $projectId)) {
                $errors['title'] = "Un projet avec un titre similaire existe déjà.";
            }
        }

        // Validation de l'URL de démo
        if (!empty($data['link_demo']) && !filter_var($data['link_demo'], FILTER_VALIDATE_URL)) {
            $errors['link_demo'] = "L'URL de démonstration n'est pas valide.";
        }

        // Validation de l'URL vidéo
        if (!empty($data['video_url']) && !filter_var($data['video_url'], FILTER_VALIDATE_URL)) {
            $errors['video_url'] = "L'URL de la vidéo n'est pas valide.";
        }

        // Validation de la date
        if (!empty($data['project_date']) && !strtotime($data['project_date'])) {
            $errors['project_date'] = "La date du projet n'est pas valide.";
        }

        return $errors;
    }

    /**
     * Prépare les données pour la sauvegarde
     */
    private function prepareProjectData(array $data, bool $isUpdate = false): array
    {
        $prepared = [];

        // Champs de base
        $fields = [
            'title', 'customer', 'description', 'project_date', 'category',
            'features', 'technologies', 'status', 'link_demo', 'video_url'
        ];

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $prepared[$field] = $data[$field];
            }
        }

        // Génération du slug
        if (isset($data['title']) && (!$isUpdate || empty($data['slug']))) {
            $prepared['slug'] = $this->generateSlug($data['title']);
        }

        // Valeurs par défaut
        if (!$isUpdate) {
            $prepared['status'] = $prepared['status'] ?? 'close';
        }

        return $prepared;
    }

    /**
     * Gère l'upload des fichiers
     */
    public function handleFileUploads(array $files): array
    {
        $results = ['success' => [], 'errors' => []];

        foreach ($this->uploadConfig as $field => $config) {
            if (!empty($files[$field]['name'])) {
                $uploadResult = $this->uploadFile($files[$field], $config);
                
                if ($uploadResult['success']) {
                    $results['success'][$field === 'video_file' ? 'video_url' : $field] = $uploadResult['path'];
                } else {
                    $results['errors'][$field] = $uploadResult['error'];
                }
            }
        }

        return $results;
    }

    /**
     * Upload un fichier avec validation
     */
    private function uploadFile(array $file, array $config): array
    {
        // Vérification des erreurs d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'error' => $this->getUploadError($file['error'])
            ];
        }

        // Vérification de la taille
        if ($file['size'] > $config['max_size']) {
            return [
                'success' => false,
                'error' => "Le fichier est trop volumineux. Taille maximum: " . ($config['max_size'] / 1024 / 1024) . "MB"
            ];
        }

        // Vérification du type
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $config['allowed_types'])) {
            return [
                'success' => false,
                'error' => "Type de fichier non autorisé. Types acceptés: " . implode(', ', $config['allowed_types'])
            ];
        }

        // Création du dossier
        if (!is_dir($config['folder'])) {
            mkdir($config['folder'], 0755, true);
        }

        // Génération du nom de fichier
        if ($config['generate_name']) {
            $filename = uniqid() . '_' . time() . '.' . $fileExtension;
        } else {
            $filename = $this->sanitizeFileName($file['name']);
        }

        $filePath = $config['folder'] . $filename;

        // Déplacement du fichier
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return [
                'success' => true,
                'path' => $filePath,
                'filename' => $filename
            ];
        }

        return [
            'success' => false,
            'error' => "Erreur lors du téléchargement du fichier."
        ];
    }

    /**
     * Nettoie le nom de fichier
     */
    private function sanitizeFileName(string $filename): string
    {
        $filename = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $filename);
        return preg_replace('/_{2,}/', '_', $filename);
    }

    /**
     * Retourne le message d'erreur d'upload
     */
    private function getUploadError(int $errorCode): string
    {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'Le fichier dépasse la taille maximale autorisée par le serveur.',
            UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la taille maximale spécifiée dans le formulaire.',
            UPLOAD_ERR_PARTIAL => 'Le fichier n\'a été que partiellement téléchargé.',
            UPLOAD_ERR_NO_FILE => 'Aucun fichier n\'a été téléchargé.',
            UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant.',
            UPLOAD_ERR_CANT_WRITE => 'Échec de l\'écriture du fichier sur le disque.',
            UPLOAD_ERR_EXTENSION => 'Une extension PHP a arrêté le téléchargement du fichier.'
        ];

        return $errors[$errorCode] ?? 'Erreur inconnue lors du téléchargement.';
    }

    /**
     * Vérifie si un slug existe déjà
     */
    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        $params = ['slug' => $slug];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $this->db->query($sql, $params);
        $result = $this->getData();
        
        return !empty($result) && $result[0]['count'] > 0;
    }

    /**
     * Récupère les tags associés à ce projet.
     */
    public function tags(): array
    {
        $tagged = new TaggedItem();
        return $tagged->getTagsForItem($this->table, $this->id);
    }

    /**
     * Met à jour la liste de tags associés à ce projet.
     */
    public function syncTags(int $id, array $data = [])
    {
        $tagged = new TaggedItem();
        return $tagged->syncTags($this->table, $id, $data);
    }

    public function getRelatedTags(?string $id = null)
    {
        return (new Tag())->relatedTagsTable($id, $this->table);
    }

    /**
     * Récupère les projets avec leurs tags
     */

    /**
     * Récupère les projets par statut
     */
    public function getByStatus(string $status): array
    {
        return $this->hydrateAll($this->where('status', $status)->getData() ?? []);
    }

    /**
     * Récupère les projets par catégorie
     */
    public function getByCategory(string $category): array
    {
        return $this->hydrateAll($this->where('category', $category)->getData() ?? []);
    }

    /**
     * Recherche des projets
     */
    public function search(string $keyword): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE title LIKE :keyword 
                   OR description LIKE :keyword 
                   OR category LIKE :keyword 
                   OR technologies LIKE :keyword
                ORDER BY created_at DESC";
        
        $this->db->query($sql, ['keyword' => "%$keyword%"]);
        return $this->hydrateAll($this->getData() ?? []);
    }

    /**
     * Récupère les erreurs
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Supprime un projet et ses fichiers associés
     */
    public function deleteProject(int $id): bool
    {
        $project = $this->find($id);
        if (!$project) {
            $this->errors[] = "Projet non trouvé.";
            return false;
        }

        // Suppression des fichiers
        if (!empty($project->image) && file_exists($project->image)) {
            unlink($project->image);
        }

        // Suppression des tags associés
        $tagged = new TaggedItem();
        $tagged->deleteItemTags($this->table, $id);

        // Suppression du projet
        return $this->delete($id);
    }
}
