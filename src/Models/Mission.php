<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use PDO;

final class Mission
{
    public function __construct(private PDO $pdo) {}

    /**
     * SF7 + SF19 : $limit = 0 renvoie tous les résultats.
     *
     * @return array<int, array<string, mixed>>
     */
    public function search(
        array $filters,
        int $limit = 0,
        int $offset = 0,
    ): array {
        [$where, $params] = $this->filters($filters);

        $sql =
            "SELECT * FROM missions WHERE 1=1" . $where . " ORDER BY created_at DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . $limit . " OFFSET " . $offset;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function count(array $filters): int
    {
        [$where, $params] = $this->filters($filters);

        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM missions WHERE 1=1" . $where,
        );
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    /** @return array{0: string, 1: array<string, mixed>} */
    private function filters(array $filters): array
    {
        $sql = "";
        $params = [];

        if (!empty($filters["q"])) {
            $sql .= " AND (title LIKE :q1 OR description LIKE :q2)";
            $params["q1"] = $params["q2"] = "%" . $filters["q"] . "%";
        }

        if (!empty($filters["status"])) {
            $sql .= " AND status = :status";
            $params["status"] = $filters["status"];
        }

        if (!empty($filters["location"])) {
            $sql .= " AND location LIKE :location";
            $params["location"] = "%" . $filters["location"] . "%";
        }

        if (!empty($filters["manager_user_id"])) {
            $sql .=
                " AND manager_id = (SELECT id FROM managers WHERE user_id = :manager_user_id)";
            $params["manager_user_id"] = $filters["manager_user_id"];
        }

        return [$sql, $params];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM missions WHERE id = :id");
        $stmt->execute(["id" => $id]);
        $mission = $stmt->fetch();

        return $mission === false ? null : $mission;
    }

    public function create(int $managerUserId, array $data): int
    {
        $managerId = $this->managerIdForUser($managerUserId);
        if ($managerId === null) {
            throw new DomainException("Profil manager introuvable.", 404);
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO missions (manager_id, title, description, budget, daily_rate, start_date, end_date, location, status, created_at)
             VALUES (:manager_id, :title, :description, :budget, :daily_rate, :start_date, :end_date, :location, :status, NOW())',
        );

        $stmt->execute([
            "manager_id" => $managerId,
            "title" => $data["title"],
            "description" => $data["description"],
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

        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM missions WHERE id = :id");
        $stmt->execute(["id" => $id]);

        return $stmt->rowCount() > 0;
    }

    public function isOwnedBy(int $id, int $managerUserId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM missions m JOIN managers mg ON mg.id = m.manager_id WHERE m.id = :id AND mg.user_id = :user_id",
        );
        $stmt->execute(["id" => $id, "user_id" => $managerUserId]);

        return $stmt->fetchColumn() !== false;
    }

    public function statistics(): array
    {
        $stmt = $this->pdo->query(
            "SELECT COUNT(*) as total_missions, AVG(budget) as avg_budget FROM missions",
        );
        return $stmt->fetch();
    }

    private function managerIdForUser(int $userId): ?int
    {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM managers WHERE user_id = :user_id",
        );
        $stmt->execute(["user_id" => $userId]);
        $id = $stmt->fetchColumn();

        return $id === false ? null : (int) $id;
    }
}
