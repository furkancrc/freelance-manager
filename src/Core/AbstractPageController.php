<?php

declare(strict_types=1);

namespace App\Core;

/** Base des contrôleurs qui affichent des pages HTML. */
abstract class AbstractPageController
{
    protected const PER_PAGE = 10;

    /** Paramètre GET en texte, null s'il est absent ou vide. */
    protected function query(string $key): ?string
    {
        $value = $_GET[$key] ?? null;

        return is_string($value) && trim($value) !== "" ? trim($value) : null;
    }

    protected function currentPage(): int
    {
        return max(1, (int) $this->query("page"));
    }

    protected function pageCount(int $total): int
    {
        return max(1, (int) ceil($total / self::PER_PAGE));
    }

    protected function notFound(): never
    {
        http_response_code(404);
        require __DIR__ . "/../Views/errors/404.php";
        exit();
    }

    protected function forbidden(): never
    {
        http_response_code(403);
        require __DIR__ . "/../Views/errors/403.php";
        exit();
    }
}
