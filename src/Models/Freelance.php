<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Freelance
{
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
     * @param array{q?: ?string, availability?: ?string, location?: ?string, min_rate?: ?string, max_rate?: ?string} $filters
     * @return array<int, array<string, mixed>>
     */
    public function search(array $filters): array
    {
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
        $params = [];

        if (!empty($filters['q'])) {
            // PDO::ATTR_EMULATE_PREPARES=false (prépares natives MySQL) interdit de réutiliser
            // un même paramètre nommé plusieurs fois dans la requête.
            $sql .= ' AND (f.first_name LIKE :q1 OR f.last_name LIKE :q2 OR f.title LIKE :q3)';
            $needle = '%' . $filters['q'] . '%';
            $params['q1'] = $needle;
            $params['q2'] = $needle;
            $params['q3'] = $needle;
        }

        if (!empty($filters['availability'])) {
            $sql .= ' AND f.availability = :availability';
            $params['availability'] = $filters['availability'];
        }

        if (!empty($filters['location'])) {
            $sql .= ' AND f.location LIKE :location';
            $params['location'] = '%' . $filters['location'] . '%';
        }

        if (!empty($filters['min_rate'])) {
            $sql .= ' AND f.daily_rate >= :min_rate';
            $params['min_rate'] = $filters['min_rate'];
        }

        if (!empty($filters['max_rate'])) {
            $sql .= ' AND f.daily_rate <= :max_rate';
            $params['max_rate'] = $filters['max_rate'];
        }

        $sql .= ' ORDER BY f.last_name, f.first_name';
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
<<<<<<< HEAD
            "SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE f.id = :id",
        );
        $stmt->execute(["id" => $id]);
=======
            'SELECT f.*, u.email FROM freelances f JOIN users u ON u.id = f.user_id WHERE f.id = :id',
        );
        $stmt->execute(['id' => $id]);
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))

        $freelance = $stmt->fetch();

        return $freelance === false ? null : $freelance;
    }

    /**
<<<<<<< HEAD
=======
     * Crée le compte utilisateur (role=freelance) et le profil associé, dans une transaction.
     *
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
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
                "email" => $data["email"],
                "password_hash" => password_hash(
                    $data["password"],
                    PASSWORD_DEFAULT,
                ),
=======
                'email' => $data['email'],
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
            ]);
            $userId = (int) $this->pdo->lastInsertId();

            $freelanceStmt = $this->pdo->prepare(
                'INSERT INTO freelances (user_id, first_name, last_name, title, bio, daily_rate, availability, location)
                 VALUES (:user_id, :first_name, :last_name, :title, :bio, :daily_rate, :availability, :location)',
            );
            $freelanceStmt->execute([
<<<<<<< HEAD
                "user_id" => $userId,
                "first_name" => $data["first_name"],
                "last_name" => $data["last_name"],
                "title" => $data["title"] ?? null,
                "bio" => $data["bio"] ?? null,
                "daily_rate" => $data["daily_rate"] ?? null,
                "availability" => $data["availability"] ?? "available",
                "location" => $data["location"] ?? null,
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
=======
     * Met à jour les champs de profil fournis (email/mot de passe exclus).
     *
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool
    {
<<<<<<< HEAD
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
=======
        $allowed = ['first_name', 'last_name', 'title', 'bio', 'daily_rate', 'availability', 'location'];

        $set = [];
        $params = ['id' => $id];
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
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
        $sql =
            "UPDATE freelances SET " . implode(", ", $set) . " WHERE id = :id";
=======
        $sql = 'UPDATE freelances SET ' . implode(', ', $set) . ' WHERE id = :id';
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

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

        return $stmt->rowCount() > 0;
    }
}
