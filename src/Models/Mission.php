<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Mission
{
    public function __construct(private PDO $pdo) {}

    /** @return array<int, array<string, mixed>> */
    public function all(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM missions ORDER BY created_at DESC",
        );
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM missions WHERE id = :id");
        $stmt->execute(["id" => $id]);
        $mission = $stmt->fetch();

        return $mission === false ? null : $mission;
    }

    public function create(int $managerId, array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO missions (manager_id, title, description, budget, daily_rate, start_date, end_date, location, status, created_at)
             VALUES (:manager_id, :title, :description, :budget, :daily_rate, :start_date, :end_date, :location, :status, NOW())',
        );

        $stmt->execute([
            "manager_id" => $managerId,
            "title" => $data["title"],
            "description" => $data["description"] ?? null,
            "budget" => $data["budget"],
            "daily_rate" => $data["daily_rate"],
            "start_date" => $data["start_date"],
            "end_date" => $data["end_date"],
            "location" => $data["location"],
            "status" => $data["status"] ?? "open",
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $allowed = [
            "title",
            "description",
            "budget",
            "daily_rate",
            "start_date",
            "end_date",
            "location",
            "status",
        ];
        $set = [];
        $params = ["id" => $id];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $set[] = "$field = :$field";
                $params[$field] = $data[$field];
            }
        }

        if ($set === []) {
            return false;
        }

        $sql = "UPDATE missions SET " . implode(", ", $set) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM missions WHERE id = :id");
        $stmt->execute(["id" => $id]);

        return $stmt->rowCount() > 0;
    }

    public function statistics(): array
    {
        $stmt = $this->pdo->query(
            "SELECT COUNT(*) as total_missions, AVG(budget) as avg_budget FROM missions",
        );
        return $stmt->fetch();
    }
}
