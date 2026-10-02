<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use PDO;
use PDOException;

final class Freelance
{
    public function __construct(private PDO $pdo) {}

    /** SF2 + SF19 : $limit = 0 renvoie tous les résultats. */
    public function search(
        array $filters,
        int $limit = 0,
        int $offset = 0,
    ): array {
        [$where, $params] = $this->filters($filters);

        $sql =
            "SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE 1=1" .
            $where .
            " ORDER BY f.last_name, f.first_name";

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
            "SELECT COUNT(*) FROM freelances f WHERE 1=1" . $where,
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
            $sql .=
                " AND (f.first_name LIKE :q1 OR f.last_name LIKE :q2 OR f.title LIKE :q3)";
            $params["q1"] = $params["q2"] = $params["q3"] =
                "%" . $filters["q"] . "%";
        }

        if (!empty($filters["availability"])) {
            $sql .= " AND f.availability = :availability";
            $params["availability"] = $filters["availability"];
        }

        if (!empty($filters["location"])) {
            $sql .= " AND f.location LIKE :location";
            $params["location"] = "%" . $filters["location"] . "%";
        }

        if (!empty($filters["min_rate"])) {
            $sql .= " AND f.daily_rate >= :min_rate";
            $params["min_rate"] = $filters["min_rate"];
        }

        if (!empty($filters["max_rate"])) {
            $sql .= " AND f.daily_rate <= :max_rate";
            $params["max_rate"] = $filters["max_rate"];
        }

        return [$sql, $params];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE f.id = :id",
        );
        $stmt->execute(["id" => $id]);
        $freelance = $stmt->fetch();

        return $freelance === false ? null : $freelance;
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE f.user_id = :user_id",
        );
        $stmt->execute(["user_id" => $userId]);
        $freelance = $stmt->fetch();

        return $freelance === false ? null : $freelance;
    }

    public function create(array $data): int
    {
        $this->pdo->beginTransaction();

        try {
            $userStmt = $this->pdo->prepare(
                "INSERT INTO users (email, password_hash, role, is_active) VALUES (:email, :password_hash, 'freelance', 1)",
            );
            $userStmt->execute([
                "email" => $data["email"],
                "password_hash" => password_hash(
                    $data["password"],
                    PASSWORD_DEFAULT,
                ),
            ]);

            $userId = (int) $this->pdo->lastInsertId();

            $freelanceStmt = $this->pdo->prepare(
                'INSERT INTO freelances (user_id, first_name, last_name, title, bio, daily_rate, availability, location)
                 VALUES (:user_id, :first_name, :last_name, :title, :bio, :daily_rate, :availability, :location)',
            );
            $freelanceStmt->execute([
                "user_id" => $userId,
                "first_name" => $data["first_name"],
                "last_name" => $data["last_name"],
                "title" => $data["title"] ?? null,
                "bio" => $data["bio"] ?? null,
                "daily_rate" => $data["daily_rate"] ?? null,
                "availability" => $data["availability"] ?? "available",
                "location" => $data["location"] ?? null,
            ]);

            $freelanceId = (int) $this->pdo->lastInsertId();
            $this->pdo->commit();

            return $freelanceId;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            if ($e->getCode() === "23000") {
                throw new DomainException("Cet email est déjà utilisé.", 409);
            }
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $allowed = [
            "first_name",
            "last_name",
            "title",
            "bio",
            "daily_rate",
            "availability",
            "location",
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

        $sql =
            "UPDATE freelances SET " . implode(", ", $set) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM users WHERE id = (SELECT user_id FROM freelances WHERE id = :id)",
        );
        $stmt->execute(["id" => $id]);

        return $stmt->rowCount() > 0;
    }
}
