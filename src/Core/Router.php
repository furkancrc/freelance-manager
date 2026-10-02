<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Routeur minimal : associe une méthode HTTP + un chemin (avec paramètres
 * `{nom}`) à un callable. Pas de regroupement ni de middleware, volontairement
 * simple pour un projet pédagogique.
 */
final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    /**
     * Résout l'URI courante et exécute le handler associé.
     * Affiche une 404 si aucune route ne correspond.
     */
    public function dispatch(string $method, string $uri): void
    {
        $path = rtrim((string) parse_url($uri, PHP_URL_PATH), '/');
        $path = $path === '' ? '/' : $path;

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $params = $this->match($route, $path);
            if ($params !== null) {
                $handler(...$params);

                return;
            }
        }

        http_response_code(404);
        require __DIR__ . '/../Views/errors/404.php';
    }

    /**
     * @return array<string, string>|null Paramètres extraits, ou null si la route ne correspond pas.
     */
    private function match(string $route, string $path): ?array
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $path, $matches) !== 1) {
            return null;
        }

        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }
}
