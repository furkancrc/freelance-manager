<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Manager
{
    public function __construct(private PDO $pdo) {}

    public function create(
        int $userId,
        string $firstName,
        string $lastName,
        string $department,
        string $phone,
    ): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO managers (user_id, first_name, last_name, department, phone)
             VALUES (:user_id, :first_name, :last_name, :department, :phone)',
        );

        return $stmt->execute([
            "user_id" => $userId,
            "first_name" => $firstName,
            "last_name" => $lastName,
            "department" => $department,
            "phone" => $phone,
        ]);
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM managers WHERE user_id = :user_id",
        );
        $stmt->execute(["user_id" => $userId]);
        $manager = $stmt->fetch();

        return $manager === false ? null : $manager;
    }

    public function update(int $userId, array $data): bool
    {
        $allowed = ["first_name", "last_name", "department", "phone"];
        $set = [];
        $params = ["user_id" => $userId];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $set[] = "$field = :$field";
                $params[$field] = $data[$field];
            }
        }

        if ($set === []) {
            return false;
        }

        $sql =
            "UPDATE managers SET " .
            implode(", ", $set) .
            " WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM managers WHERE user_id = :user_id",
        );
        $stmt->execute(["user_id" => $userId]);

        return $stmt->rowCount() > 0;
    }
}
