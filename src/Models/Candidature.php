<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use PDO;
<<<<<<< HEAD
=======
use PDOException;
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31

final class Candidature
{
    public function __construct(private PDO $pdo) {}

    public function apply(
        int $freelanceUserId,
        int $missionId,
        ?string $message,
        ?float $proposedRate,
    ): int {
        $freelanceId = $this->freelanceIdForUser($freelanceUserId);
        if ($freelanceId === null) {
<<<<<<< HEAD
            throw new DomainException("Profil freelance introuvable.");
=======
            throw new DomainException("Profil freelance introuvable.", 404);
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        }

        $stmt = $this->pdo->prepare(
            "SELECT status FROM missions WHERE id = :id",
        );
        $stmt->execute(["id" => $missionId]);
        $status = $stmt->fetchColumn();

        if ($status === false) {
<<<<<<< HEAD
            throw new DomainException("Mission introuvable.");
=======
            throw new DomainException("Mission introuvable.", 404);
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        }

        if ($status !== "open") {
            throw new DomainException(
                "Cette mission n'accepte plus de candidatures.",
<<<<<<< HEAD
            );
        }

        $insert = $this->pdo->prepare(
            'INSERT INTO applications (mission_id, freelance_id, message, proposed_rate)
             VALUES (:mission_id, :freelance_id, :message, :proposed_rate)',
        );
        $insert->execute([
            "mission_id" => $missionId,
            "freelance_id" => $freelanceId,
            "message" => $message,
            "proposed_rate" => $proposedRate,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
=======
                422,
            );
        }

        try {
            $insert = $this->pdo->prepare(
                'INSERT INTO applications (mission_id, freelance_id, message, proposed_rate)
                 VALUES (:mission_id, :freelance_id, :message, :proposed_rate)',
            );
            $insert->execute([
                "mission_id" => $missionId,
                "freelance_id" => $freelanceId,
                "message" => $message,
                "proposed_rate" => $proposedRate,
            ]);

            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                throw new DomainException(
                    "Vous avez déjà postulé à cette mission.",
                    409,
                );
            }
            throw $e;
        }
    }

>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
    public function listForFreelanceUser(
        int $freelanceUserId,
        ?string $status = null,
    ): array {
        $sql = 'SELECT a.*, m.title AS mission_title, m.status AS mission_status,
                       m.location AS mission_location, m.daily_rate AS mission_daily_rate
                FROM applications a
                JOIN freelances f ON f.id = a.freelance_id
                JOIN missions m ON m.id = a.mission_id
                WHERE f.user_id = :user_id';
<<<<<<< HEAD
=======

>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        $params = ["user_id" => $freelanceUserId];

        if ($status !== null) {
            $sql .= " AND a.status = :status";
            $params["status"] = $status;
        }

        $stmt = $this->pdo->prepare($sql . " ORDER BY a.created_at DESC");
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

<<<<<<< HEAD
    /**
     * @return array<int, array<string, mixed>>
     */
=======
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
    public function listForMission(
        int $missionId,
        ?string $status = null,
    ): array {
        $sql = 'SELECT a.*, f.first_name, f.last_name, f.title, f.daily_rate AS freelance_daily_rate,
                       f.availability, f.location, u.email
                FROM applications a
                JOIN freelances f ON f.id = a.freelance_id
                JOIN users u ON u.id = f.user_id
                WHERE a.mission_id = :mission_id';
<<<<<<< HEAD
=======

>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        $params = ["mission_id" => $missionId];

        if ($status !== null) {
            $sql .= " AND a.status = :status";
            $params["status"] = $status;
        }

        $stmt = $this->pdo->prepare($sql . " ORDER BY a.created_at DESC");
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function missionExists(
        int $missionId,
        ?int $managerUserId = null,
    ): bool {
        $sql = "SELECT 1 FROM missions m";
        $params = ["id" => $missionId];

        if ($managerUserId !== null) {
            $sql .=
                " JOIN managers mg ON mg.id = m.manager_id AND mg.user_id = :user_id";
            $params["user_id"] = $managerUserId;
        }

        $stmt = $this->pdo->prepare($sql . " WHERE m.id = :id");
        $stmt->execute($params);

        return $stmt->fetchColumn() !== false;
    }

    private function freelanceIdForUser(int $userId): ?int
    {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM freelances WHERE user_id = :user_id",
        );
        $stmt->execute(["user_id" => $userId]);
        $id = $stmt->fetchColumn();

        return $id === false ? null : (int) $id;
    }
}
