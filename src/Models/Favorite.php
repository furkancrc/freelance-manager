<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use PDO;
use PDOException;

final class Favorite
{
    public function __construct(private PDO $pdo) {}

    public function add(int $freelanceUserId, int $missionId): void
    {
        $freelanceId = $this->freelanceIdForUser($freelanceUserId);

        $stmt = $this->pdo->prepare("SELECT 1 FROM missions WHERE id = :id");
        $stmt->execute(["id" => $missionId]);
        if ($stmt->fetchColumn() === false) {
            throw new DomainException("Mission introuvable.", 404);
        }

        try {
            $this->pdo
                ->prepare(
                    "INSERT INTO favorites (freelance_id, mission_id) VALUES (:freelance_id, :mission_id)",
                )
                ->execute([
                    "freelance_id" => $freelanceId,
                    "mission_id" => $missionId,
                ]);
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                throw new DomainException("Mission déjà en favori.", 409);
            }

            throw $e;
        }
    }

    public function remove(int $freelanceUserId, int $missionId): void
    {
        $freelanceId = $this->freelanceIdForUser($freelanceUserId);

        $stmt = $this->pdo->prepare(
            "DELETE FROM favorites WHERE freelance_id = :freelance_id AND mission_id = :mission_id",
        );
        $stmt->execute([
            "freelance_id" => $freelanceId,
            "mission_id" => $missionId,
        ]);

        if ($stmt->rowCount() === 0) {
            throw new DomainException(
                "Cette mission n'est pas en favori.",
                404,
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listForFreelanceUser(int $freelanceUserId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT m.id, m.title, m.description, m.budget, m.daily_rate, m.start_date,
                    m.end_date, m.location, m.status, fav.created_at AS favorited_at
             FROM favorites fav
             JOIN freelances f ON f.id = fav.freelance_id
             JOIN missions m ON m.id = fav.mission_id
             WHERE f.user_id = :user_id
             ORDER BY fav.created_at DESC",
        );
        $stmt->execute(["user_id" => $freelanceUserId]);

        return $stmt->fetchAll();
    }

    private function freelanceIdForUser(int $userId): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM freelances WHERE user_id = :user_id",
        );
        $stmt->execute(["user_id" => $userId]);
        $id = $stmt->fetchColumn();

        if ($id === false) {
            throw new DomainException("Profil freelance introuvable.", 404);
        }

        return (int) $id;
    }
}
