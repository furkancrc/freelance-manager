<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Freelance
{
<<<<<<< HEAD
<<<<<<< HEAD
    public function __construct(private PDO $pdo) {}

    /**
=======
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Recherche des freelances avec filtres optionnels.
     *
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
    public function __construct(private PDO $pdo) {}

    /**
>>>>>>> f8e10eb (fix: freelance controller)
     * @param array{q?: ?string, availability?: ?string, location?: ?string, min_rate?: ?string, max_rate?: ?string} $filters
     * @return array<int, array<string, mixed>>
     */
    public function search(array $filters): array
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $sql =
            "SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE 1=1";
        $params = [];

        if (!empty($filters["q"])) {
            $sql .=
                " AND (f.first_name LIKE :q1 OR f.last_name LIKE :q2 OR f.title LIKE :q3)";
            $needle = "%" . $filters["q"] . "%";
            $params["q1"] = $needle;
            $params["q2"] = $needle;
            $params["q3"] = $needle;
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

        $sql .= " ORDER BY f.last_name, f.first_name";
=======
        $sql = 'SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE 1=1';
=======
        $sql =
            "SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE 1=1";
>>>>>>> f8e10eb (fix: freelance controller)
        $params = [];

        if (!empty($filters["q"])) {
            $sql .=
                " AND (f.first_name LIKE :q1 OR f.last_name LIKE :q2 OR f.title LIKE :q3)";
            $needle = "%" . $filters["q"] . "%";
            $params["q1"] = $needle;
            $params["q2"] = $needle;
            $params["q3"] = $needle;
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

<<<<<<< HEAD
        $sql .= ' ORDER BY f.last_name, f.first_name';
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
        $sql .= " ORDER BY f.last_name, f.first_name";
>>>>>>> f8e10eb (fix: freelance controller)

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
<<<<<<< HEAD
<<<<<<< HEAD
            "SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE f.id = :id",
        );
        $stmt->execute(["id" => $id]);
=======
            'SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE f.id = :id',
        );
        $stmt->execute(['id' => $id]);
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
            "SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE f.id = :id",
        );
        $stmt->execute(["id" => $id]);
>>>>>>> f8e10eb (fix: freelance controller)

        $freelance = $stmt->fetch();

        return $freelance === false ? null : $freelance;
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
=======
     * Crée le compte utilisateur (role=freelance) et le profil associé, dans une transaction.
     *
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
>>>>>>> f8e10eb (fix: freelance controller)
     * @param array{email: string, password: string, first_name: string, last_name: string, title?: ?string, bio?: ?string, daily_rate?: ?string, availability?: ?string, location?: ?string} $data
     */
    public function create(array $data): int
    {
        $this->pdo->beginTransaction();

        try {
            $userStmt = $this->pdo->prepare(
                "INSERT INTO users (email, password_hash, role, is_active) VALUES (:email, :password_hash, 'freelance', 1)",
            );
            $userStmt->execute([
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> f8e10eb (fix: freelance controller)
                "email" => $data["email"],
                "password_hash" => password_hash(
                    $data["password"],
                    PASSWORD_DEFAULT,
                ),
<<<<<<< HEAD
=======
                'email' => $data['email'],
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
>>>>>>> f8e10eb (fix: freelance controller)
            ]);
            $userId = (int) $this->pdo->lastInsertId();

            $freelanceStmt = $this->pdo->prepare(
                'INSERT INTO freelances (user_id, first_name, last_name, title, bio, daily_rate, availability, location)
                 VALUES (:user_id, :first_name, :last_name, :title, :bio, :daily_rate, :availability, :location)',
            );
            $freelanceStmt->execute([
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> f8e10eb (fix: freelance controller)
                "user_id" => $userId,
                "first_name" => $data["first_name"],
                "last_name" => $data["last_name"],
                "title" => $data["title"] ?? null,
                "bio" => $data["bio"] ?? null,
                "daily_rate" => $data["daily_rate"] ?? null,
                "availability" => $data["availability"] ?? "available",
                "location" => $data["location"] ?? null,
<<<<<<< HEAD
=======
                'user_id' => $userId,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'title' => $data['title'] ?? null,
                'bio' => $data['bio'] ?? null,
                'daily_rate' => $data['daily_rate'] ?? null,
                'availability' => $data['availability'] ?? 'available',
                'location' => $data['location'] ?? null,
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
>>>>>>> f8e10eb (fix: freelance controller)
            ]);
            $freelanceId = (int) $this->pdo->lastInsertId();

            $this->pdo->commit();

            return $freelanceId;
        } catch (\PDOException $e) {
            $this->pdo->rollBack();

            throw $e;
        }
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
=======
     * Met à jour les champs de profil fournis (email/mot de passe exclus).
     *
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
>>>>>>> f8e10eb (fix: freelance controller)
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> f8e10eb (fix: freelance controller)
        $allowed = [
            "first_name",
            "last_name",
            "title",
            "bio",
            "daily_rate",
            "availability",
            "location",
        ];
<<<<<<< HEAD

        $set = [];
        $params = ["id" => $id];
=======
        $allowed = ['first_name', 'last_name', 'title', 'bio', 'daily_rate', 'availability', 'location'];

        $set = [];
        $params = ['id' => $id];
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======

        $set = [];
        $params = ["id" => $id];
>>>>>>> f8e10eb (fix: freelance controller)
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $set[] = "$field = :$field";
                $params[$field] = $data[$field];
            }
        }

        if ($set === []) {
            return false;
        }

<<<<<<< HEAD
<<<<<<< HEAD
        $sql =
            "UPDATE freelances SET " . implode(", ", $set) . " WHERE id = :id";
=======
        $sql = 'UPDATE freelances SET ' . implode(', ', $set) . ' WHERE id = :id';
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
        $sql =
            "UPDATE freelances SET " . implode(", ", $set) . " WHERE id = :id";
>>>>>>> f8e10eb (fix: freelance controller)
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM users WHERE id = (SELECT user_id FROM freelances WHERE id = :id)",
        );
        $stmt->execute(["id" => $id]);
=======
    /** Supprime le compte utilisateur associé ; le profil freelance disparaît par cascade (fk_freelances_user). */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = (SELECT user_id FROM freelances WHERE id = :id)');
        $stmt->execute(['id' => $id]);
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM users WHERE id = (SELECT user_id FROM freelances WHERE id = :id)",
        );
        $stmt->execute(["id" => $id]);
>>>>>>> f8e10eb (fix: freelance controller)

        return $stmt->rowCount() > 0;
    }
}
