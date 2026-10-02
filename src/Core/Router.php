<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->add("GET", $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add("POST", $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->add("PUT", $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->add("DELETE", $path, $handler);
    }

    private function add(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = rtrim((string) parse_url($uri, PHP_URL_PATH), "/");
        $path = $path === "" ? "/" : $path;

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $params = $this->match($route, $path);
            if ($params !== null) {
                $handler(...$params);

                return;
            }
        }

        http_response_code(404);
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

        if (preg_match($pattern, $path, $matches) !== 1) {
            return null;
        }

        return array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
    }
}
