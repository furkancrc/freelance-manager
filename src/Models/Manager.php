<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use PDO;
use PDOException;

final class Manager
{
    public function __construct(private PDO $pdo) {}

    /** @return array<int, array<string, mixed>> */
    public function all(): array
    {
        $stmt = $this->pdo->query(
            "SELECT m.*, u.email FROM managers m JOIN users u ON u.id = m.user_id ORDER BY m.last_name, m.first_name",
        );

        return $stmt->fetchAll();
    }

    /** Crée le compte utilisateur et le profil manager, renvoie le user_id. */
    public function create(array $data): int
    {
        $this->pdo->beginTransaction();

        try {
            $userStmt = $this->pdo->prepare(
                "INSERT INTO users (email, password_hash, role, is_active) VALUES (:email, :password_hash, 'manager', 1)",
            );
            $userStmt->execute([
                "email" => $data["email"],
                "password_hash" => password_hash(
                    $data["password"],
                    PASSWORD_DEFAULT,
                ),
            ]);

            $userId = (int) $this->pdo->lastInsertId();

            $managerStmt = $this->pdo->prepare(
                'INSERT INTO managers (user_id, first_name, last_name, department, phone)
                 VALUES (:user_id, :first_name, :last_name, :department, :phone)',
            );
            $managerStmt->execute([
                "user_id" => $userId,
                "first_name" => $data["first_name"],
                "last_name" => $data["last_name"],
                "department" => $data["department"] ?? null,
                "phone" => $data["phone"] ?? null,
            ]);

            $this->pdo->commit();

            return $userId;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            if ($e->getCode() === "23000") {
                throw new DomainException("Cet email est déjà utilisé.", 409);
            }
            throw $e;
        }
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT m.*, u.email FROM managers m JOIN users u ON u.id = m.user_id WHERE m.user_id = :user_id",
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

        return $stmt->execute($params);
    }

    public function delete(int $userId): bool
    {
        try {
            $stmt = $this->pdo->prepare(
                "DELETE FROM users WHERE id = :user_id AND role = 'manager'",
            );
            $stmt->execute(["user_id" => $userId]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                throw new DomainException(
                    "Ce manager a encore des missions, impossible de le supprimer.",
                    409,
                );
            }
            throw $e;
        }
    }
}
