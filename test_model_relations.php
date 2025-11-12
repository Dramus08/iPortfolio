<?php
require_once 'vendor/autoload.php'; 
// Adaptez le chemin selon votre structure

// Simulation des classes nécessaires


// =============================================================================
// MODÈLES DE TEST
// =============================================================================

use Database\MysqlDatabase;
use Core\Model;

class ModelManager extends Model{
        public function __construct(){
            $this->db = new MysqlDatabase('localhost','root','orm_test_db','SandatuAdamou@1965');
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
}

class Language extends ModelManager
{
    protected string $table = 'languages';
    protected string $primaryKey = 'id';
    
    protected function defineRelations(): void
    {
        $this->belongsTo(Profile::class, 'profile_id');
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
}

class Post extends ModelManager
{
    protected string $table = 'posts';
    protected string $primaryKey = 'id';
    
    protected function defineRelations(): void
    {
        $this->belongsTo(User::class, 'user_id');
    }
}

// =============================================================================
// FONCTIONS D'AFFICHAGE
// =============================================================================

function displaySeparator($title) {
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "🚀 " . $title . "\n";
    echo str_repeat("=", 80) . "\n";
}

function displayResult($description, $result) {
    echo "📌 " . $description . ":\n";
    
    if ($result === null) {
        echo "   ❌ Aucun résultat\n";
        return;
    }
    
    if (is_array($result)) {
        echo "   📊 Nombre d'éléments: " . count($result) . "\n";
        if (count($result) > 0) {
            foreach ($result as $key => $item) {
                if (is_object($item) && method_exists($item, 'toArray')) {
                    $data = $item->toArray(false);
                    echo "   [" . $key . "] " . json_encode(array_intersect_key($data, array_flip(['id', 'name', 'title', 'email']))) . "\n";
                } else if (is_array($item)) {
                    echo "   [" . $key . "] " . json_encode(array_intersect_key($item, array_flip(['id', 'name', 'title', 'email']))) . "\n";
                } else {
                    echo "   [" . $key . "] " . (is_scalar($item) ? $item : gettype($item)) . "\n";
                }
            }
        }
    } elseif (is_object($result) && method_exists($result, 'toArray')) {
        $data = $result->toArray(false);
        $limitedData = array_intersect_key($data, array_flip(['id', 'name', 'email', 'title', 'bio']));
        echo "   " . json_encode($limitedData, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "   " . (is_bool($result) ? ($result ? '✅ true' : '❌ false') : (is_scalar($result) ? $result : gettype($result))) . "\n";
    }
    echo "\n";
}

function displayRelationInfo($object, $relationName) {
    if (isset($object->{$relationName})) {
        $relation = $object->{$relationName};
        if (is_array($relation)) {
            echo "   ✅ " . $relationName . " (" . count($relation) . " éléments)\n";
        } else {
            echo "   ✅ " . $relationName . " (objet)\n";
        }
    } else {
        echo "   ❌ " . $relationName . " (non chargé)\n";
    }
}

// =============================================================================
// EXÉCUTION DES TESTS
// =============================================================================

displaySeparator("DÉMARRAGE DES TESTS - SYSTÈME DE RELATIONS ORM");

try {
    // TEST 1: Vérification de la connexion et structure de base
    displaySeparator("TEST 1 - Vérification de la connexion et structure");
    
    $userModel = new User();
    
    echo "🔍 Vérification de la connexion à la base de données:\n";
    echo "   - Table 'users' existe: " . ($userModel->isTableExist() ? '✅ Oui' : '❌ Non') . "\n";
    echo "   - Colonnes users: " . implode(', ', $userModel->getColumnsTable()) . "\n";
    echo "   - Clé primaire: " . $userModel->getPrimaryKey() . "\n\n";

    // TEST 2: Récupération simple sans relations
    displaySeparator("TEST 2 - Récupération simple sans relations");
    
    $users = $userModel->all();
    displayResult("Tous les utilisateurs (sans relations)", $users);
    
    $singleUser = $userModel->find(1);
    displayResult("Utilisateur ID 1 (sans relations)", $singleUser);

    // TEST 3: Récupération avec relations
    displaySeparator("TEST 3 - Récupération avec relations");
    
    $userWithRelations = $userModel->find(1, true);
    if ($userWithRelations) {
        echo "👤 Utilisateur avec relations chargées:\n";
        echo "   - Nom: " . ($userWithRelations->name ?? 'N/A') . "\n";
        echo "   - Email: " . ($userWithRelations->email ?? 'N/A') . "\n";
        
        displayRelationInfo($userWithRelations, 'profile');
        displayRelationInfo($userWithRelations, 'post');
        
        if (isset($userWithRelations->profile)) {
            echo "\n   📝 Profile details:\n";
            echo "      - Bio: " . (substr($userWithRelations->profile->bio ?? 'N/A', 0, 50) . "...") . "\n";
            echo "      - Location: " . ($userWithRelations->profile->location ?? 'N/A') . "\n";
        }
        
        if (isset($userWithRelations->post) && is_array($userWithRelations->post)) {
            echo "\n   📮 Posts (" . count($userWithRelations->post) . "):\n";
            foreach ($userWithRelations->post as $index => $post) {
                echo "      " . ($index + 1) . ". " . ($post->title ?? 'N/A') . "\n";
            }
        }
    }

    // TEST 4: Chargement sélectif de relations
    displaySeparator("TEST 4 - Chargement sélectif de relations");
    
    $userSelective = $userModel->find(1);
    echo "📝 Avant chargement sélectif:\n";
    displayRelationInfo($userSelective, 'profile');
    displayRelationInfo($userSelective, 'post');
    
    $userSelective->with(['profile']);
    echo "\n📝 Après chargement du profile seulement:\n";
    displayRelationInfo($userSelective, 'profile');
    displayRelationInfo($userSelective, 'post');

    // TEST 5: Test du Lazy Loading
    displaySeparator("TEST 5 - Lazy Loading avec méthode magique");
    
    $userLazy = $userModel->find(1);
    echo "📝 État initial (relations non chargées):\n";
    displayRelationInfo($userLazy, 'profile');
    displayRelationInfo($userLazy, 'post');
    
    echo "\n⚡ Accès lazy aux relations:\n";
    if ($userLazy->profile) {
        echo "   👤 Profile chargé à la volée:\n";
        echo "      - Bio: " . (substr($userLazy->profile->bio ?? 'N/A', 0, 30) . "...") . "\n";
    }
    
    if ($userLazy->post) {
        echo "   📮 Posts chargés à la volée: " . (is_array($userLazy->post) ? count($userLazy->post) : '1') . " post(s)\n";
    }

    // TEST 6: Navigation dans les relations profondes
    displaySeparator("TEST 6 - Navigation dans les relations profondes");
    
    $userDeep = $userModel->find(1, true);
    if ($userDeep && $userDeep->profile) {
        echo "🔍 Navigation User → Profile → Languages & Skills:\n";
        echo "   👤 User: " . ($userDeep->name ?? 'N/A') . "\n";
        echo "   📝 Profile: " . (substr($userDeep->profile->bio ?? 'N/A', 0, 40) . "...") . "\n";
        
        // Chargement des relations du profile
        $userDeep->profile->loadRelations();
        
        if ($userDeep->profile->language) {
            echo "   🌐 Languages:\n";
            $languages = $userDeep->profile->language;
            if (is_array($languages)) {
                foreach ($languages as $language) {
                    echo "      - " . ($language->name ?? 'N/A') . " (" . ($language->level ?? 'N/A') . ")\n";
                }
            }
        }
        
        if ($userDeep->profile->skill) {
            echo "   💻 Skills:\n";
            $skills = $userDeep->profile->skill;
            if (is_array($skills)) {
                foreach ($skills as $skill) {
                    echo "      - " . ($skill->name ?? 'N/A') . " (Niveau: " . ($skill->level ?? 'N/A') . ")\n";
                }
            }
        }
    }

    // TEST 7: Récupération multiple avec relations
    displaySeparator("TEST 7 - Récupération multiple avec relations");
    
    $usersWithProfiles = $userModel->allWith(['profile']);
    echo "👥 Tous les utilisateurs avec leurs profils:\n";
    foreach ($usersWithProfiles as $user) {
        echo "   - " . ($user->name ?? 'N/A') . " : ";
        if (isset($user->profile)) {
            echo "✅ Profile (" . (substr($user->profile->bio ?? '', 0, 20) . "...") . ")\n";
        } else {
            echo "❌ Aucun profile\n";
        }
    }

    // TEST 8: Pagination avec relations
    displaySeparator("TEST 8 - Pagination avec relations");
    
    $paginatedResult = $userModel->paginate(1, 2, ['profile']);
    if (isset($paginatedResult['data'])) {
        echo "📄 Résultat paginé (page 1, 2 éléments):\n";
        echo "   - Total éléments: " . ($paginatedResult['pagination']['total'] ?? 'N/A') . "\n";
        echo "   - Page courante: " . ($paginatedResult['pagination']['current_page'] ?? 'N/A') . "\n";
        echo "   - Dernière page: " . ($paginatedResult['pagination']['last_page'] ?? 'N/A') . "\n";
        echo "   - Données: " . count($paginatedResult['data']) . " utilisateur(s)\n";
        
        foreach ($paginatedResult['data'] as $user) {
            echo "      👤 " . ($user->name ?? 'N/A') . " - Profile: " . (isset($user->profile) ? '✅' : '❌') . "\n";
        }
    }

    // TEST 9: Sérialisation en tableau
    displaySeparator("TEST 9 - Sérialisation en tableau");
    
    $userForAPI = $userModel->find(1, true);
    if ($userForAPI) {
        $lightData = $userForAPI->toArray(false);
        $fullData = $userForAPI->toArray(true);
        
        echo "📊 Données légères (sans relations):\n";
        echo json_encode($lightData, JSON_PRETTY_PRINT) . "\n\n";
        
        echo "📊 Données complètes (avec relations - version courte):\n";
        // On limite l'affichage pour plus de lisibilité
        $limitedFullData = [
            'user' => array_intersect_key($fullData, array_flip(['id', 'name', 'email'])),
            'profile' => isset($fullData['profile']) ? array_intersect_key($fullData['profile'], array_flip(['id', 'bio', 'location'])) : null,
            'posts_count' => isset($fullData['post']) ? count($fullData['post']) : 0
        ];
        echo json_encode($limitedFullData, JSON_PRETTY_PRINT) . "\n";
    }

    // TEST 10: Test des autres modèles
    displaySeparator("TEST 10 - Test des autres modèles");
    
    $profileModel = new Profile();
    $profiles = $profileModel->allWith(['user', 'language', 'skill']);
    
    echo "👤 Profils avec leurs relations:\n";
    foreach ($profiles as $profile) {
        echo "   - Profile ID " . ($profile->id ?? 'N/A') . " : ";
        echo "User: " . (isset($profile->user) ? '✅' : '❌') . " ";
        echo "Languages: " . (isset($profile->language) ? count($profile->language) : 0) . " ";
        echo "Skills: " . (isset($profile->skill) ? count($profile->skill) : 0) . "\n";
    }

    // TEST 11: Performance - Batch Loading
    displaySeparator("TEST 11 - Test de performance");
    
    $startTime = microtime(true);
    
    $allUsersBatch = $userModel->allWith(['profile', 'post']);
    
    $endTime = microtime(true);
    $executionTime = round(($endTime - $startTime) * 1000, 2);
    
    echo "⚡ Performance Batch Loading:\n";
    echo "   - Temps d'exécution: " . $executionTime . " ms\n";
    echo "   - Utilisateurs chargés: " . count($allUsersBatch) . "\n";
    echo "   - Relations chargées: profile, post\n";
    
    $stats = ['avec_profile' => 0, 'avec_posts' => 0];
    foreach ($allUsersBatch as $user) {
        if (isset($user->profile)) $stats['avec_profile']++;
        if (isset($user->post) && is_array($user->post) && count($user->post) > 0) $stats['avec_posts']++;
    }
    
    echo "   - Stats: " . $stats['avec_profile'] . " avec profile, " . $stats['avec_posts'] . " avec posts\n";

    // TEST 12: Contrôle de profondeur
    displaySeparator("TEST 12 - Contrôle de profondeur des relations");
    
    $userControlled = $userModel->find(1);
    $userControlled->loadRelations(1); // Profondeur max = 1
    
    echo "🎯 Profondeur limitée à 1:\n";
    displayRelationInfo($userControlled, 'profile');
    if (isset($userControlled->profile)) {
        echo "   Relations du profile (devraient être non chargées):\n";
        displayRelationInfo($userControlled->profile, 'language');
        displayRelationInfo($userControlled->profile, 'skill');
    }

    displaySeparator("TESTS TERMINÉS AVEC SUCCÈS 🎉");

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . " Ligne: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// =============================================================================
// RÉSUMÉ DE LA STRUCTURE
// =============================================================================

displaySeparator("RÉSUMÉ DE LA STRUCTURE TESTÉE");

echo "📊 Tables utilisées dans les tests:\n\n";

echo "👥 Table 'users' (One-to-One → profiles, One-to-Many → posts):\n";
echo "   - id, name, email, created_at\n\n";

echo "👤 Table 'profiles' (Many-to-One → users, One-to-Many → languages/skills):\n";
echo "   - id, user_id, bio, avatar, location, website, created_at\n\n";

echo "🌐 Table 'languages' (Many-to-One → profiles):\n";
echo "   - id, profile_id, name, level, created_at\n\n";

echo "💻 Table 'skills' (Many-to-One → profiles):\n";
echo "   - id, profile_id, name, level, category, created_at\n\n";

echo "📮 Table 'posts' (Many-to-One → users):\n";
echo "   - id, user_id, title, content, status, created_at, updated_at\n\n";

echo "💡 Types de relations testées:\n";
echo "   ✅ One-to-One: User ↔ Profile\n";
echo "   ✅ One-to-Many: User ↔ Posts, Profile ↔ Languages, Profile ↔ Skills\n";
echo "   ✅ Many-to-One: Posts → User, Languages → Profile, Skills → Profile\n";
echo "   ✅ Lazy Loading via __get()\n";
echo "   ✅ Eager Loading avec allWith()\n";
echo "   ✅ Chargement sélectif avec with()\n";
echo "   ✅ Batch Loading pour éviter N+1\n";
echo "   ✅ Contrôle de profondeur\n";
echo "   ✅ Sérialisation avec toArray()\n";
echo "   ✅ Pagination avec relations\n";

echo "\n🎯 Pour exécuter ce test:\n";
echo "   php test_model_relations.php\n";