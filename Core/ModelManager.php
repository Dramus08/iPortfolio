<?php
namespace Core;

use Exception;
use Core\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv as CsvWriter;
use Dompdf\Dompdf;

/**
 * ModelManager v3
 * - Gère les relations (hasOne, hasMany, belongsTo)
 * - Fournit pagination, filtres dynamiques
 * - Fournit fonctions d'export (csv, json, xlsx, pdf)
 *
 * Utilisation typique :
 *   $manager = new ModelManager(new \App\Models\User());
 *   $users = $manager->filter(['status' => 'active'])->paginate(1, 20);
 */
class ModelManager
{
    protected Model $model;
    protected AbstractDatabase $db;
    protected array $wheres = [];
    protected array $orders = [];
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $with = []; // relations à charger (e.g. ['profile', 'posts'])
    protected array $allowedFilters = []; // définir dans le model si besoin

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->db = DatabaseFactory::create(); // par défaut mysql, le model peut overrider
    }

    /* -----------------------------
     * FILTRAGE & REQUÊTE BUILDERS
     * ----------------------------- */

    /**
     * Ajouter un filtre simple (AND)
     * @param string $column
     * @param mixed $value
     * @param string $operator
     */
    public function where(string $column, mixed $value, string $operator = '='): self
    {
        $this->wheres[] = [$column, $operator, $value];
        return $this;
    }

    /**
     * Ajoute un tri
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orders[] = [$column, strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC'];
        return $this;
    }

    /**
     * Définir relations à charger (eager loading)
     * ex: with(['profile', 'posts'])
     */
    public function with(array $relations): self
    {
        $this->with = array_merge($this->with, $relations);
        return $this;
    }

    /**
     * Appliquer un tableau de filtres (ex: from request)
     * respecte allowedFilters si défini sur le model
     */
    public function filter(array $filters): self
    {
        // Si le model expose allowedFilters, on ne prend que ceux autorisés
        $allowed = $this->model->fields ?? null;
        if (!empty($this->model->fields) && is_array($this->model->fields)) {
            foreach ($filters as $k => $v) {
                if (in_array($k, $this->model->fields, true)) {
                    $this->where($k, $v);
                }
            }
        } else {
            // pas de restriction : on ajoute tout
            foreach ($filters as $k => $v) $this->where($k, $v);
        }
        return $this;
    }

    /**
     * Limite et offset (pour pagination ou requêtes sur mesure)
     */
    public function limit(int $limit, int $offset = 0): self
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /* -----------------------------
     * RÉCUPÉRATION DE DONNÉES
     * ----------------------------- */

    /**
     * Construit la clause WHERE et paramètres pour PDO
     */
    protected function buildWhereClause(): array
    {
        if (empty($this->wheres)) return ['', []];

        $parts = [];
        $params = [];
        foreach ($this->wheres as $i => $w) {
            [$col, $op, $val] = $w;
            $paramKey = "p{$i}";
            $parts[] = "`{$col}` {$op} :{$paramKey}";
            $params[$paramKey] = $val;
        }
        $clause = ' WHERE ' . implode(' AND ', $parts);
        return [$clause, $params];
    }

    /**
     * Build ORDER BY clause
     */
    protected function buildOrderBy(): string
    {
        if (empty($this->orders)) return '';
        $parts = array_map(fn($o) => "{$o[0]} {$o[1]}", $this->orders);
        return ' ORDER BY ' . implode(', ', $parts);
    }

    /**
     * Récupère tous les résultats selon les conditions en cours.
     * @return array<Model>
     */
    public function get(): array
    {
        $table = $this->model->getTable();
        [$where, $params] = $this->buildWhereClause();
        $order = $this->buildOrderBy();
        $limit = $this->limit ? " LIMIT {$this->limit}" : '';
        $offset = ($this->limit && $this->offset) ? " OFFSET {$this->offset}" : '';

        $sql = "SELECT * FROM `{$table}` {$where} {$order} {$limit} {$offset}";
        $res = $this->db->query($sql, $params);
        $rows = $res->data ?? [];
        $objects = $this->model->hydrateAll($rows);

        // Eager load relations si demandé
        if (!empty($this->with)) {
            $objects = $this->eagerLoadRelations($objects, $this->with);
        }

        return $objects;
    }

    /**
     * Pagination simple : page commence à 1
     * @return array {data: [], total: int, per_page: int, current_page: int, last_page: int}
     */
    public function paginate(int $page = 1, int $perPage = 15): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        // Count total
        $table = $this->model->getTable();
        [$where, $params] = $this->buildWhereClause();
        $countSql = "SELECT COUNT(*) as total FROM `{$table}` {$where}";
        $countRes = $this->db->query($countSql, $params);
        $total = (int)($countRes->data[0]->total ?? 0);

        // Get page
        $this->limit($perPage, $offset);
        $data = $this->get();

        $lastPage = (int)ceil($total / $perPage);

        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => $lastPage
        ];
    }

    /* -----------------------------
     * RELATIONS (convention over configuration)
     * ----------------------------- */

    /**
     * hasOne: ex User hasOne Profile (profile.user_id => users.id)
     * @param Model $relatedModel instance du model relié (ex new Profile())
     * @param string $foreignKey colonne du relatedModel qui référence parent (ex 'user_id')
     * @param string $localKey colonne locale (ex 'id')
     */
    public function hasOne(Model $relatedModel, string $foreignKey, string $localKey = 'id'): array
    {
        $table = $this->model->getTable();
        $relatedTable = $relatedModel->getTable();

        $sql = "SELECT r.* FROM `{$relatedTable}` r 
                JOIN `{$table}` t ON r.{$foreignKey} = t.{$localKey}";
        $res = $this->db->query($sql);
        return $relatedModel->hydrateAll($res->data ?? []);
    }

    /**
     * hasMany: ex Post hasMany Comments (comments.post_id => posts.id)
     */
    public function hasMany(Model $relatedModel, string $foreignKey, string $localKey = 'id'): array
    {
        return $this->hasOne($relatedModel, $foreignKey, $localKey);
    }

    /**
     * belongsTo: ex Comment belongsTo Post (comments.post_id => posts.id)
     * @param Model $relatedModel
     * @param string $foreignKey colonne sur current model
     * @param string $ownerKey colonne sur related model (default 'id')
     */
    public function belongsTo(Model $relatedModel, string $foreignKey, string $ownerKey = 'id')
    {
        // Cette méthode retourne une fonction qui peut récupérer la relation pour un enregistrement donné
        return function ($record) use ($relatedModel, $foreignKey, $ownerKey) {
            $fkVal = $record->{$foreignKey} ?? null;
            if ($fkVal === null) return null;
            return $relatedModel->find($fkVal);
        };
    }

    /**
     * Eager load relations pour un ensemble d'objets (simple implementation)
     */
    protected function eagerLoadRelations(array $objects, array $relations): array
    {
        // Pour chaque relation demandée, on va appeler une méthode sur le Model si elle existe
        foreach ($relations as $relation) {
            if (!method_exists($this->model, $relation)) continue;

            // collect ids
            $ids = array_map(fn($o) => $o->{$this->model->getPrimaryKey()} ?? null, $objects);
            $ids = array_unique(array_filter($ids));

            // appeler la méthode relation sur le model, elle doit accepter un tableau d'ids ou gérer le groupBy
            $relatedData = $this->model->{$relation}($ids);

            // Attacher relatedData sur les objets par convention: relatedData keyed by parent id
            foreach ($objects as $obj) {
                $key = $obj->{$this->model->getPrimaryKey()} ?? null;
                $obj->{$relation} = $relatedData[$key] ?? ($relatedData[$key] ?? null);
            }
        }
        return $objects;
    }

    /* -----------------------------
     * EXPORTS (csv, json, xlsx, pdf)
     * ----------------------------- */

    /**
     * Export des résultats actuels (après filter/where/order/limit)
     * @param string $format csv|json|xlsx|pdf
     * @param string $filename sans extension
     */
    public function export(string $format = 'csv', string $filename = 'export'): void
    {
        $rows = $this->get(); // array of model objects
        if (empty($rows)) {
            throw new Exception("Aucune donnée à exporter.");
        }

        // Convertir objects -> array (tableau associatif)
        $arrayData = array_map(function ($obj) {
            return (array)$obj;
        }, $rows);

        $format = strtolower($format);
        $filename = $filename . '.' . $format;

        switch ($format) {
            case 'csv':
                $this->exportCSV($arrayData, $filename);
                break;
            case 'json':
                $this->exportJSON($arrayData, $filename);
                break;
            case 'xlsx':
                $this->exportXLSX($arrayData, $filename);
                break;
            case 'pdf':
                $this->exportPDF($arrayData, $filename);
                break;
            default:
                throw new Exception("Format non supporté pour l'export: {$format}");
        }
    }

    protected function exportCSV(array $data, string $filename): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($this->normalizeForSheet($data), null, 'A1');
        $writer = new CsvWriter($spreadsheet);
        $writer->save($filename);
        // téléchargeable si appelé depuis navigateur : header + readfile (optionnel)
    }

    protected function exportXLSX(array $data, string $filename): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($this->normalizeForSheet($data), null, 'A1');
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
    }

    protected function exportJSON(array $data, string $filename): void
    {
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    protected function exportPDF(array $data, string $filename): void
    {
        $html = '<h3>Export</h3><table border="1" cellpadding="5"><thead><tr>';
        $headers = array_keys((array)$data[0]);
        foreach ($headers as $h) $html .= "<th>{$h}</th>";
        $html .= '</tr></thead><tbody>';
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $col) $html .= '<td>' . htmlspecialchars((string)$col) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => 0]); // 0 = open in browser, 1 = force download
    }

    /**
     * Prépare un tableau 2D pour PhpSpreadsheet (ajoute headers)
     */
    protected function normalizeForSheet(array $data): array
    {
        $rows = [];
        $headers = array_keys((array)$data[0]);
        $rows[] = $headers;
        foreach ($data as $row) {
            $rows[] = array_values((array)$row);
        }
        return $rows;
    }

    /* -----------------------------
     * UTILITAIRES
     * ----------------------------- */

    /**
     * Reset du builder pour réutiliser l'instance
     */
    public function reset(): self
    {
        $this->wheres = [];
        $this->orders = [];
        $this->limit = null;
        $this->offset = null;
        $this->with = [];
        return $this;
    }
}
