SELECT 
    kcu.TABLE_NAME AS table_name,kcu.COLUMN_NAME AS column_name,
    kcu.REFERENCED_TABLE_NAME AS referenced_table,
    kcu.REFERENCED_COLUMN_NAME AS referenced_column
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS kcu
WHERE kcu.TABLE_SCHEMA = DATABASE()
  AND kcu.TABLE_NAME = 'medias'
  AND kcu.REFERENCED_TABLE_NAME IS NOT NULL;


CREATE TABLE clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100),
  telephone VARCHAR(20),
  adresse TEXT,
  latitude DOUBLE,
  longitude DOUBLE
);

CREATE TABLE images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT,
  image_url TEXT,
  FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE videos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT,
  videos_url TEXT,
  FOREIGN KEY (client_id) REFERENCES clients(id)
);
CREATE TABLE medias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  image_id INT,
  video_id INT,
  FOREIGN KEY (image_id) REFERENCES images(id),
  FOREIGN KEY (video_id) REFERENCES videos(id)
);

protected function hydrateRelations(array $rows, array $foreignKeys): array
{
    $results = [];

    foreach ($rows as $row) {
        $project = [];
        $relatedData = [];

        foreach ($row as $key => $value) {
            if (str_starts_with($key, 'p_')) {
                $project[str_replace('p_', '', $key)] = $value;
            } else {
                // On regroupe les données liées (ex: catégorie)
                $relatedData[$key] = $value;
            }
        }

        // Hydrate l'objet principal
        $projectObj = $this->hydrate($project);

        // Hydrate chaque relation
        foreach ($foreignKeys as $fk) {
            $relTable = $fk['REFERENCED_TABLE_NAME'];
            $prefix = "r_{$relTable}_";

            $rel = [];
            foreach ($relatedData as $key => $val) {
                if (str_starts_with($key, $prefix)) {
                    $rel[str_replace($prefix, '', $key)] = $val;
                }
            }

            // Ajoute la relation comme sous-objet
            if (!empty($rel)) {
                $projectObj->$relTable = (object) $rel;
            }
        }

        $results[] = $projectObj;
    }

    return $results;
}


public function getWithRelations(string $table = null): array
{
    $table = $this->table ?? $table;
    if (!$table) throw new Exception("Aucune table spécifiée.");

    $schema = $this->db->query("SELECT DATABASE()")->fetchColumn();

    // 1️⃣ Récupération des infos sur la table
    $columns = $this->db->query("
        SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = '$schema' AND TABLE_NAME = '$table'
    ")->fetchAll(PDO::FETCH_COLUMN);

    $foreignKeys = $this->db->query("
        SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = '$schema' 
          AND TABLE_NAME = '$table' 
          AND REFERENCED_TABLE_NAME IS NOT NULL
    ")->fetchAll(PDO::FETCH_ASSOC);

    // 2️⃣ Colonnes principales
    $cols = implode(", ", array_map(fn($c) => "p.$c AS p_$c", $columns));

    // 3️⃣ Construction dynamique des LEFT JOIN
    $joins = "";
    $extraCols = [];
    foreach ($foreignKeys as $fk) {
        $refTable = $fk['REFERENCED_TABLE_NAME'];
        $refCol = $fk['REFERENCED_COLUMN_NAME'];
        $col = $fk['COLUMN_NAME'];

        $joins .= " LEFT JOIN $refTable r_$refTable ON r_$refTable.$refCol = p.$col";
        $extraCols[] = "r_$refTable.*";
    }

    // 4️⃣ Construction de la requête finale
    $sql = "
        SELECT 
            $cols
            " . (!empty($extraCols) ? ', ' . implode(', ', $extraCols) : '') . "
        FROM $table p
        $joins
        ORDER BY p.id
    ";

    $stmt = $this->db->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 5️⃣ Hydratation automatique
    return $this->hydrateRelations($rows, $foreignKeys);
}




Parfait 🔥
C’est là qu’on va rendre ton ORM vraiment intelligent : il saura récupérer toutes les relations normales ET polymorphes (comme les tags), sans rien écrire à la main.

🧩 OBJECTIF

Faire en sorte qu’un simple appel :

$projects = $db->getWithRelations('projects');


te renvoie directement :

[
  (object)[
    'id' => 1,
    'title' => 'Portfolio AI',
    'category' => (object)[ 'id' => 2, 'name' => 'Web App' ],
    'tags' => [
      (object)['id' => 1, 'name' => 'AI', 'color' => '#0d6efd'],
      (object)['id' => 4, 'name' => 'PHP', 'color' => '#198754']
    ]
  ],
  ...
]

⚙️ PLAN D’ACTION

On va modifier la méthode getWithRelations() pour :

🔹 récupérer les relations normales (foreign keys)

🔹 récupérer les relations polymorphes via tagged_items

🔹 hydrater automatiquement les objets et sous-objets liés

🧩 ÉTAPE 1 — Version complète de getWithRelations()
public function getWithRelations(string $table = null): array
{
    $table = $this->table ?? $table;
    if (!$table) throw new Exception("Aucune table spécifiée.");

    $schema = $this->db->query("SELECT DATABASE()")->fetchColumn();

    // 1️⃣ Colonnes principales
    $columns = $this->db->query("
        SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = '$schema' AND TABLE_NAME = '$table'
    ")->fetchAll(PDO::FETCH_COLUMN);

    // 2️⃣ Foreign keys sortantes
    $foreignKeys = $this->db->query("
        SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = '$schema' 
          AND TABLE_NAME = '$table' 
          AND REFERENCED_TABLE_NAME IS NOT NULL
    ")->fetchAll(PDO::FETCH_ASSOC);

    // 3️⃣ Requête principale
    $cols = implode(", ", array_map(fn($c) => "p.$c AS p_$c", $columns));

    $joins = "";
    $extraCols = [];
    foreach ($foreignKeys as $fk) {
        $refTable = $fk['REFERENCED_TABLE_NAME'];
        $refCol = $fk['REFERENCED_COLUMN_NAME'];
        $col = $fk['COLUMN_NAME'];

        $joins .= " LEFT JOIN $refTable r_$refTable ON r_$refTable.$refCol = p.$col";
        $extraCols[] = "r_$refTable.*";
    }

    $sql = "
        SELECT 
            $cols
            " . (!empty($extraCols) ? ', ' . implode(', ', $extraCols) : '') . "
        FROM $table p
        $joins
        ORDER BY p.id
    ";

    $rows = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    // 4️⃣ On hydrate les objets et leurs relations normales
    $items = $this->hydrateRelations($rows, $foreignKeys);

    // 5️⃣ On ajoute maintenant les relations polymorphes (tags)
    $this->attachPolymorphicRelations($items, $table);

    return $items;
}

🧩 ÉTAPE 2 — Hydratation des relations normales

(identique à avant)

protected function hydrateRelations(array $rows, array $foreignKeys): array
{
    $results = [];

    foreach ($rows as $row) {
        $main = [];
        $related = [];

        foreach ($row as $key => $value) {
            if (str_starts_with($key, 'p_')) {
                $main[str_replace('p_', '', $key)] = $value;
            } else {
                $related[$key] = $value;
            }
        }

        $object = $this->hydrate($main);

        foreach ($foreignKeys as $fk) {
            $relTable = $fk['REFERENCED_TABLE_NAME'];
            $prefix = "r_{$relTable}_";

            $rel = [];
            foreach ($related as $key => $val) {
                if (str_starts_with($key, $prefix)) {
                    $rel[str_replace($prefix, '', $key)] = $val;
                }
            }

            if (!empty($rel)) {
                $object->$relTable = (object)$rel;
            }
        }

        $results[] = $object;
    }

    return $results;
}

🧩 ÉTAPE 3 — Gestion automatique des relations polymorphes

Voici la magie 💫 : on cherche dans tagged_items tous les tags liés à la table demandée (projects, services, etc.), puis on les rattache aux objets correspondants.

protected function attachPolymorphicRelations(array &$items, string $table): void
{
    if (empty($items)) return;

    // Récupération des IDs de la table principale
    $ids = array_map(fn($obj) => $obj->id, $items);
    $idList = implode(',', array_map('intval', $ids));

    // On récupère tous les tags liés à ces items
    $sql = "
        SELECT 
            ti.tagged_id,
            t.id AS tag_id,
            t.name AS tag_name,
            t.color AS tag_color
        FROM tagged_items ti
        JOIN tags t ON t.id = ti.tag_id
        WHERE ti.tagged_table = :table
          AND ti.tagged_id IN ($idList)
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['table' => $table]);
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // On associe les tags à leurs items respectifs
    $tagMap = [];
    foreach ($tags as $t) {
        $tagMap[$t['tagged_id']][] = (object)[
            'id' => $t['tag_id'],
            'name' => $t['tag_name'],
            'color' => $t['tag_color']
        ];
    }

    // Injection dans chaque objet principal
    foreach ($items as $item) {
        $item->tags = $tagMap[$item->id] ?? [];
    }
}

✅ Exemple d’utilisation
$projects = $db->getWithRelations('projects');

foreach ($projects as $p) {
    echo "<h3>{$p->title}</h3>";
    echo "<p>{$p->description}</p>";

    // Catégorie (relation simple)
    if (isset($p->categories)) {
        echo "<b>Catégorie :</b> {$p->categories->name}<br>";
    }

    // Tags (relation polymorphe)
    foreach ($p->tags as $tag) {
        echo "<span style='background:{$tag->color};color:#fff;padding:4px;border-radius:4px;margin-right:5px;'>";
        echo htmlspecialchars($tag->name);
        echo "</span>";
    }

    echo "<hr>";
}

💎 Résumé
Fonction	Rôle
getWithRelations()	Récupère tout : colonnes, foreign keys, tags
hydrateRelations()	Crée les objets principaux et leurs sous-objets
attachPolymorphicRelations()	Lie automatiquement les tags à leurs projets


[
  (object)[
    'id' => 1,
    'title' => 'Portfolio AI',
    'category' => (object)[ 'id' => 2, 'name' => 'Web App' ],
    'customer' => (object)[ 'id' => 4, 'name' => 'Inoua Ismail' ],
    'ville' => (object)[ 'id' => 9, 'ville' => 'Inoua Ismail','pays'=>(object)['id'=>12,'pays'=>'cameroun']],
    'tags' => [
      (object)['id' => 1, 'name' => 'AI', 'color' => '#0d6efd'],
      (object)['id' => 4, 'name' => 'PHP', 'color' => '#198754']
    ]
  ],
  
]
