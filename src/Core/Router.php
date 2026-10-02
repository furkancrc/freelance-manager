<?php

declare(strict_types=1);

namespace App\Core;

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
/**
 * Routeur minimal : associe une méthode HTTP + un chemin (avec paramètres
 * `{nom}`) à un callable. Pas de regroupement ni de middleware, volontairement
 * simple pour un projet pédagogique.
 */
<<<<<<< HEAD
=======
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $this->add('GET', $path, $handler);
=======
        $this->add("GET", $path, $handler);
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        $this->add('GET', $path, $handler);
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
    }

    public function post(string $path, callable $handler): void
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->add('DELETE', $path, $handler);
<<<<<<< HEAD
=======
        $this->add("POST", $path, $handler);
>>>>>>> dccf881 (feat: clean auth and seed)
    }

=======
        $this->add('POST', $path, $handler);
    }

>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
    }

>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
    private function add(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
    /**
     * Résout l'URI courante et exécute le handler associé.
     * Affiche une 404 si aucune route ne correspond.
     */
    public function dispatch(string $method, string $uri): void
    {
        $path = rtrim((string) parse_url($uri, PHP_URL_PATH), '/');
        $path = $path === '' ? '/' : $path;
<<<<<<< HEAD
=======
    public function dispatch(string $method, string $uri): void
    {
        $path = rtrim((string) parse_url($uri, PHP_URL_PATH), "/");
        $path = $path === "" ? "/" : $path;
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $params = $this->match($route, $path);
            if ($params !== null) {
                $handler(...$params);

                return;
            }
        }

        http_response_code(404);
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
        require __DIR__ . '/../Views/errors/404.php';
    }

    /**
     * @return array<string, string>|null Paramètres extraits, ou null si la route ne correspond pas.
     */
    private function match(string $route, string $path): ?array
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';
<<<<<<< HEAD
=======
        require __DIR__ . "/../Views/errors/404.php";
    }

    /**
     * @return array<string, string>|null
     */
    private function match(string $route, string $path): ?array
    {
        $pattern = preg_replace(
            "#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#",
            '(?P<$1>[^/]+)',
            $route,
        );
        $pattern = "#^" . $pattern . '$#';
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))

        if (preg_match($pattern, $path, $matches) !== 1) {
            return null;
        }

<<<<<<< HEAD
<<<<<<< HEAD
        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
=======
        return array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
    }
}
