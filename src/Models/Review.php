<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use PDO;
use PDOException;

final class Review
{
    public function __construct(private PDO $pdo) {}

    public function create(
        int $managerUserId,
        int $freelanceId,
        int $missionId,
        int $rating,
        ?string $comment,
    ): int {
        if ($rating < 1 || $rating > 5) {
            throw new DomainException(
                "La note doit être comprise entre 1 et 5.",
                422,
            );
        }

        $managerStmt = $this->pdo->prepare(
            "SELECT id FROM managers WHERE user_id = :user_id",
        );
        $managerStmt->execute(["user_id" => $managerUserId]);
        $managerId = $managerStmt->fetchColumn();

        if ($managerId === false) {
            throw new DomainException("Profil manager introuvable.", 404);
        }

        $missionStmt = $this->pdo->prepare(
            "SELECT manager_id, status FROM missions WHERE id = :id",
        );
        $missionStmt->execute(["id" => $missionId]);
        $mission = $missionStmt->fetch();

        if ($mission === false) {
            throw new DomainException("Mission introuvable.", 404);
        }

        if ((int) $mission["manager_id"] !== (int) $managerId) {
            throw new DomainException(
                "Cette mission n'appartient pas à ce manager.",
                403,
            );
        }

        if ($mission["status"] !== "closed") {
            throw new DomainException(
                "Seules les missions terminées peuvent être évaluées.",
                422,
            );
        }

        $appStmt = $this->pdo->prepare(
            "SELECT 1 FROM applications WHERE mission_id = :mission_id AND freelance_id = :freelance_id AND status = 'accepted'",
        );
        $appStmt->execute([
            "mission_id" => $missionId,
            "freelance_id" => $freelanceId,
        ]);

        if ($appStmt->fetchColumn() === false) {
            throw new DomainException(
                "Ce freelance n'a pas de candidature acceptée sur cette mission.",
                422,
            );
        }

        try {
            $insert = $this->pdo->prepare(
                'INSERT INTO reviews (freelance_id, manager_id, mission_id, rating, comment)
                 VALUES (:freelance_id, :manager_id, :mission_id, :rating, :comment)',
            );
            $insert->execute([
                "freelance_id" => $freelanceId,
                "manager_id" => $managerId,
                "mission_id" => $missionId,
                "rating" => $rating,
                "comment" => $comment,
            ]);

            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                throw new DomainException(
                    "Ce freelance a déjà été évalué pour cette mission.",
                    409,
                );
            }
            throw $e;
        }
    }
}
