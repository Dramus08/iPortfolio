<?php
// test_model_string_representation.php

require_once 'vendor/autoload.php';

// =============================================================================
// MODÈLES AVEC REPRÉSENTATION TEXTUELLE PERSONNALISÉE
// =============================================================================

use Database\AbstractDatabase;
use Database\DatabaseFactory;
use Validators\DataValidator;
use Exception;
use stdClass;
use PDOException;

/**
 * Classe Model de base pour ORM léger professionnel.
 * Gère automatiquement CRUD, hydratation d'objets et relations optimisées.
 */
abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected AbstractDatabase $db;
    protected array $fields = [];
    public DataValidator $validate;

    // Constantes pour les types de relations
    public const HAS_ONE = 'has_one';
    public const HAS_MANY = 'has_many';
    public const BELONGS_TO = 'belongs_to';
    public const MANY_TO_MANY = 'many_to_many';
    
    // Configuration des relations et optimisations
    protected array $relations = [];
    private array $loadedRelations = [];
    private array $loadingDepth = [];
    private int $maxDepth = 3;
    
    // Configuration des conventions de nommage
    protected array $namingConventions = [
        'foreign_key' => '{table}_id',
        'pivot_table' => '{table1}_{table2}',
        'relation_name' => '{table}'
    ];
    
    // Cache pour le batch loading
    private static array $batchCache = [];
    
    // les differents dossiers pour enregistrer les media 
    public string $folderImageFile = 'uploads/images/';
    public string $folderVideoFile = 'uploads/videos/';
    public string $folderAudioFile = 'uploads/audios/';
    public string $folderFile = 'uploads/files/';
    protected array $errors = [];
    protected array $errorForms = [];

    public function __construct(string $dbDriver = 'mysql')
    {
        $this->db = DatabaseFactory::create($dbDriver);
        $this->defineRelations();
    }

    // =========================================================================
    // MÉTHODE __toString() POUR LA REPRÉSENTATION TEXTUELLE
    // =========================================================================

    /**
     * Retourne la représentation textuelle de l'objet
     * Similaire à __str__() de Django
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Méthode à surcharger dans les modèles enfants pour personnaliser 
     * la représentation textuelle
     */
    public function toString(): string
    {
        // Par défaut, on essaie d'utiliser un champ 'name', 'title', 'email' ou l'ID
        if (isset($this->fields['name'])) {
            return $this->fields['name'];
        }
        if (isset($this->fields['title'])) {
            return $this->fields['title'];
        }
        if (isset($this->fields['email'])) {
            return $this->fields['email'];
        }
        if (isset($this->fields[$this->primaryKey])) {
            return (string)$this->fields[$this->primaryKey];
        }
        
        return get_class($this) . '#' . ($this->fields['id'] ?? 'unknown');
    }

    /**
     * Retourne une représentation détaillée de l'objet
     */
    public function toDetailedString(): string
    {
        $className = get_class($this);
        $baseName = substr($className, strrpos($className, '\\') + 1);
        
        $details = [];
        foreach (['name', 'title', 'email', 'bio'] as $field) {
            if (isset($this->fields[$field]) && !empty($this->fields[$field])) {
                $details[] = $field . ': "' . substr($this->fields[$field], 0, 50) . '"';
            }
        }
        
        if (!empty($details)) {
            return $baseName . ' [' . implode(', ', $details) . ']';
        }
        
        return $baseName . ' #' . ($this->fields['id'] ?? '?');
    }

    // =========================================================================
    // MÉTHODE MAGIQUE __get() AMÉLIORÉE
    // =========================================================================

    /**
     * Méthode magique pour accéder aux relations dynamiquement avec lazy loading
     * et représentation textuelle des relations
     */
    public function __get(string $name): mixed
    {
        // D'abord vérifier dans les champs normaux
        if (array_key_exists($name, $this->fields)) {
            return $this->fields[$name];
        }
        
        // Ensuite vérifier si c'est une relation
        foreach ($this->relations as $relation) {
            $relationKey = $this->getRelationKey($relation['related_model']);
            
            if ($relationKey === $name) {
                if ($relation['lazy'] ?? true) {
                    $this->loadRelation($name);
                }
                return $this->{$name} ?? null;
            }
        }
        
        // Si on arrive ici, essayer les méthodes personnalisées
        $customMethod = 'get' . ucfirst($name) . 'Attribute';
        if (method_exists($this, $customMethod)) {
            return $this->{$customMethod}();
        }
        
        return null;
    }

    /**
     * Méthode magique pour vérifier l'existence des propriétés
     */
    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->fields) || 
               $this->hasRelation($name) ||
               method_exists($this, 'get' . ucfirst($name) . 'Attribute');
    }

    /**
     * Vérifie si une relation existe
     */
    private function hasRelation(string $name): bool
    {
        foreach ($this->relations as $relation) {
            $relationKey = $this->getRelationKey($relation['related_model']);
            if ($relationKey === $name) {
                return true;
            }
        }
        return false;
    }

    // ... (le reste de votre classe Model existante reste inchangé)
}
use Core\Model;
use Database\MysqlDatabase;

class ModelManager extends Model {
    public function __construct() {
        parent::__construct('mysql');
        $this->db = new MysqlDatabase('localhost', 'root', 'SandatuAdamou@1965', 'orm_test_db');
    }
}

class User extends ModelManager
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';
    
    protected function defineRelations(): void
    {
        $this->hasOne(Profile::class, 'user_id');
        $this->hasMany(Post::class, 'user_id');
    }
    
    /**
     * Représentation textuelle personnalisée pour User
     */
    public function toString(): string
    {
        return $this->name . ' (' . $this->email . ')';
    }
    
    /**
     * Accesseur personnalisé pour le nom complet
     */
    public function getFullNameAttribute(): string
    {
        return $this->name . ' [ID: ' . $this->id . ']';
    }
    
    /**
     * Accesseur personnalisé pour les initiales
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        return $initials;
    }
}

class Profile extends ModelManager
{
    protected string $table = 'profiles';
    protected string $primaryKey = 'id';
    
    protected function defineRelations(): void
    {
        $this->belongsTo(User::class, 'user_id');
        $this->hasMany(Language::class, 'profile_id');
        $this->hasMany(Skill::class, 'profile_id');
    }
    
    /**
     * Représentation textuelle personnalisée pour Profile
     */
    public function toString(): string
    {
        $userName = $this->user ? $this->user->name : 'Utilisateur inconnu';
        $bioPreview = $this->bio ? substr($this->bio, 0, 30) . '...' : 'Aucune bio';
        return 'Profile de ' . $userName . ' - ' . $bioPreview;
    }
    
    /**
     * Accesseur personnalisé pour la localisation formatée
     */
    public function getFormattedLocationAttribute(): string
    {
        if (!$this->location) {
            return 'Localisation non spécifiée';
        }
        return '📍 ' . $this->location;
    }
    
    /**
     * Accesseur personnalisé pour le résumé du profil
     */
    public function getSummaryAttribute(): string
    {
        $skillsCount = $this->skill ? count($this->skill) : 0;
        $languagesCount = $this->language ? count($this->language) : 0;
        
        return sprintf(
            "%s - %d compétences - %d langues",
            $this->user->name ?? 'Anonyme',
            $skillsCount,
            $languagesCount
        );
    }
}

class Language extends ModelManager
{
    protected string $table = 'languages';
    protected string $primaryKey = 'id';
    
    protected function defineRelations(): void
    {
        $this->belongsTo(Profile::class, 'profile_id');
    }
    
    public function toString(): string
    {
        return $this->name . ' (' . $this->level . ')';
    }
    
    /**
     * Accesseur personnalisé pour le niveau avec emoji
     */
    public function getLevelWithEmojiAttribute(): string
    {
        $emojis = [
            'Débutant' => '🟡',
            'Intermédiaire' => '🟠', 
            'Avancé' => '🔴',
            'Expert' => '🟣'
        ];
        
        return ($emojis[$this->level] ?? '⚪') . ' ' . $this->level;
    }
}

class Skill extends ModelManager
{
    protected string $table = 'skills';
    protected string $primaryKey = 'id';
    
    protected function defineRelations(): void
    {
        $this->belongsTo(Profile::class, 'profile_id');
    }
    
    public function toString(): string
    {
        return $this->name . ' - Niveau ' . $this->level . '%';
    }
    
    /**
     * Accesseur personnalisé pour la barre de progression
     */
    public function getProgressBarAttribute(): string
    {
        $width = (int)($this->level / 5); // 20 caractères max pour 100%
        $bar = str_repeat('█', $width) . str_repeat('░', 20 - $width);
        return $bar . ' ' . $this->level . '%';
    }
}

class Post extends ModelManager
{
    protected string $table = 'posts';
    protected string $primaryKey = 'id';
    
    protected function defineRelations(): void
    {
        $this->belongsTo(User::class, 'user_id');
    }
    
    public function toString(): string
    {
        $titlePreview = substr($this->title, 0, 40);
        if (strlen($this->title) > 40) {
            $titlePreview .= '...';
        }
        return '"' . $titlePreview . '" par ' . ($this->user->name ?? 'Auteur inconnu');
    }
    
    /**
     * Accesseur personnalisé pour le résumé du contenu
     */
    public function getExcerptAttribute(): string
    {
        $content = strip_tags($this->content);
        return substr($content, 0, 100) . (strlen($content) > 100 ? '...' : '');
    }
    
    /**
     * Accesseur personnalisé pour le statut formaté
     */
    public function getFormattedStatusAttribute(): string
    {
        $statuses = [
            'draft' => '📝 Brouillon',
            'published' => '✅ Publié', 
            'archived' => '📁 Archivé'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }
}

// =============================================================================
// SCRIPT DE DÉMONSTRATION
// =============================================================================

function displaySeparator($title) {
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "🚀 " . $title . "\n";
    echo str_repeat("=", 80) . "\n";
}

function displayTest($description, $value) {
    echo "📌 " . $description . ":\n";
    echo "   → " . $value . "\n\n";
}

displaySeparator("DÉMONSTRATION - SYSTÈME __toString() ET ACCESSORS");

try {
    $userModel = new User();
    $profileModel = new Profile();
    $postModel = new Post();
    
    // TEST 1: Représentation de base avec __toString()
    displaySeparator("TEST 1 - Représentation de base");
    
    $user = $userModel->find(1);
    if ($user) {
        displayTest("User __toString()", (string)$user);
        displayTest("User toString()", $user->toString());
        displayTest("User toDetailedString()", $user->toDetailedString());
    }
    
    $profile = $profileModel->find(1);
    if ($profile) {
        displayTest("Profile __toString()", (string)$profile);
        displayTest("Profile toString()", $profile->toString());
    }
    
    $post = $postModel->find(1);
    if ($post) {
        displayTest("Post __toString()", (string)$post);
        displayTest("Post toString()", $post->toString());
    }
    
    // TEST 2: Accesseurs personnalisés
    displaySeparator("TEST 2 - Accesseurs personnalisés");
    
    if ($user) {
        displayTest("User->fullName", $user->fullName);
        displayTest("User->initials", $user->initials);
    }
    
    if ($profile) {
        displayTest("Profile->formattedLocation", $profile->formattedLocation);
        displayTest("Profile->summary", $profile->summary);
        
        // Charger les relations pour le summary
        $profile->loadRelations();
        displayTest("Profile->summary (avec relations)", $profile->summary);
    }
    
    if ($post) {
        displayTest("Post->excerpt", $post->excerpt);
        displayTest("Post->formattedStatus", $post->formattedStatus);
    }
    
    // TEST 3: Utilisation dans les relations
    displaySeparator("TEST 3 - Représentation des relations");
    
    $userWithRelations = $userModel->find(1, true);
    if ($userWithRelations && $userWithRelations->profile) {
        displayTest("User->profile (via __toString)", (string)$userWithRelations->profile);
        
        if ($userWithRelations->post) {
            echo "📮 Posts de l'utilisateur:\n";
            foreach ($userWithRelations->post as $index => $post) {
                echo "   " . ($index + 1) . ". " . (string)$post . "\n";
            }
            echo "\n";
        }
    }
    
    // TEST 4: Collection d'objets
    displaySeparator("TEST 4 - Collections d'objets");
    
    $users = $userModel->all();
    echo "👥 Liste des utilisateurs:\n";
    foreach ($users as $user) {
        echo "   - " . (string)$user . "\n";
    }
    echo "\n";
    
    $profiles = $profileModel->allWith(['user']);
    echo "👤 Liste des profils:\n";
    foreach ($profiles as $profile) {
        echo "   - " . (string)$profile . "\n";
    }
    echo "\n";
    
    // TEST 5: Compétences et langues avec représentations avancées
    displaySeparator("TEST 5 - Représentations avancées");
    
    $languageModel = new Language();
    $languages = $languageModel->all();
    
    echo "🌐 Langues avec niveaux:\n";
    foreach ($languages as $language) {
        echo "   - " . (string)$language . " → " . $language->levelWithEmoji . "\n";
    }
    echo "\n";
    
    $skillModel = new Skill();
    $skills = $skillModel->all();
    
    echo "💻 Compétences avec barres de progression:\n";
    foreach ($skills as $skill) {
        echo "   - " . (string)$skill . "\n";
        echo "     " . $skill->progressBar . "\n";
    }
    
    // TEST 6: Utilisation dans les templates (simulation)
    displaySeparator("TEST 6 - Simulation d'utilisation dans les templates");
    
    echo "📝 Exemple d'affichage dans un template:\n";
    echo "----------------------------------------\n";
    
    if ($userWithRelations) {
        echo "Utilisateur: " . (string)$userWithRelations . "\n";
        echo "Nom complet: " . $userWithRelations->fullName . "\n";
        echo "Initiales: " . $userWithRelations->initials . "\n\n";
        
        if ($userWithRelations->profile) {
            echo "Profil: " . (string)$userWithRelations->profile . "\n";
            echo "Localisation: " . $userWithRelations->profile->formattedLocation . "\n";
            echo "Résumé: " . $userWithRelations->profile->summary . "\n\n";
        }
        
        if ($userWithRelations->post) {
            echo "Derniers articles:\n";
            foreach (array_slice($userWithRelations->post, 0, 3) as $post) {
                echo "  • " . (string)$post . "\n";
                echo "    Extrait: " . $post->excerpt . "\n";
                echo "    Statut: " . $post->formattedStatus . "\n\n";
            }
        }
    }
    
    displaySeparator("DÉMONSTRATION TERMINÉE 🎉");

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . " Ligne: " . $e->getLine() . "\n";
}

echo "\n💡 Fonctionnalités implémentées:\n";
echo "✅ __toString() pour la représentation textuelle\n";
echo "✅ toString() personnalisable par modèle\n";
echo "✅ toDetailedString() pour plus de détails\n";
echo "✅ Accesseurs personnalisés via getXxxAttribute()\n";
echo "✅ Support des relations dans les représentations\n";
echo "✅ Utilisation intuitive comme Django __str__\n";