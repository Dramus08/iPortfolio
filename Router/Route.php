<?php
namespace Router;

use Database\MYSQL_DB;
use Core\Exceptions\NotFoundException;
define('ROOT', '/'.basename(dirname(__DIR__))."/");


class Route
{
    private string $path;
    private string $action;
    private array $matches = [];
    private ?string $name = null;

    public function __construct(string $path, string $action, ?string $name = null)
    {
        $this->path = trim($path, "/");
        $this->action = $action;
        $this->name = $name;
    }

    /**
     * Vérifie si l'URL correspond à cette route
     */
    public function matches(string $url): bool
    {
        $pattern = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $pattern = "#^$pattern$#";

        if (preg_match($pattern, trim($url, "/"), $matches)) {
            $this->matches = $matches;
            return true;
        }

        return false;
    }

    /**
     * Exécute l'action associée à la route
     */
    public function execute()
    {
        [$controllerClass, $method] = explode("@", $this->action);

        if (!class_exists($controllerClass)) {
            throw new \Exception("Le contrôleur {$controllerClass} est introuvable.");
        }

        $controller = new $controllerClass(
            new MYSQL_DB(DB_HOST, DB_USER, DB_NAME, DB_PASS)
        );

        if (!method_exists($controller, $method)) {
            throw new \Exception("La méthode {$method} n'existe pas dans le contrôleur {$controllerClass}.");
        }

        return $this->matches[1] ?? null
            ? $controller->$method($this->matches[1])
            : $controller->$method();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function generateUrl(array $params = []): string
    {
        $url = $this->path;
        foreach ($params as $key => $value) {
            $url = str_replace(":$key", $value, $url);
        }
        return ROOT.$url;
    }
}
