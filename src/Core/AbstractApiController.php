<?php

declare(strict_types=1);

namespace App\Core;

abstract class AbstractApiController
{
    /** @return array<string, mixed> */
    protected function readJsonBody(): array
    {
        $data = json_decode((string) file_get_contents("php://input"), true);
        return is_array($data) ? $data : [];
    }

    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }

    protected function jsonError(string|array $errors, int $status = 400): void
    {
        $data = is_array($errors)
            ? ["errors" => $errors]
            : ["error" => $errors];
        $this->json($data, $status);
    }
}
