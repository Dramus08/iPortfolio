<?php
namespace Router;


use Core\Exceptions\NotFoundException;
class Router
{
    private string $url;
    private array $routes = [];
    private static array $namedRoutes = [];

    public function __construct(string $url)
    {
        $this->url = trim($url, "/");
    }

    public function get(string $path, string $action, ?string $name = null): void
    {
        $route = new Route($path, $action, $name);
        $this->routes['GET'][] = $route;
        if ($name) {
            self::$namedRoutes[$name] = $route;
        }
    }

    public function post(string $path, string $action, ?string $name = null): void
    {
        $route = new Route($path, $action, $name);
        $this->routes['POST'][] = $route;
        if ($name) {
            self::$namedRoutes[$name] = $route;
        }
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (!isset($this->routes[$method])) {
            throw new NotFoundException("Méthode HTTP non gérée : {$method}");
        }

        foreach ($this->routes[$method] as $route) {
            if ($route->matches($this->url)) {
                return $route->execute();
            }
        }

        throw new NotFoundException("La page demandée est introuvable.");
    }

    /**
     * Récupérer une URL par son nom (fonction globale Laravel-style)
     */
    public static function route(string $name, array $params = []): string
    {
        if (!isset(self::$namedRoutes[$name])) {
            throw new \Exception("Route nommée '$name' introuvable.");
        }

        return self::$namedRoutes[$name]->generateUrl($params);
    }

    public static function redirect(string $name, array $params = []): void
    {
        $route=self::route($name,$params);
        header("Location: {$route}");
        exit();
    }

    public static function getRoutes(): array{
        return self::$namedRoutes;
    }
}
