<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use PDO;

final class Review
{
<<<<<<< HEAD
<<<<<<< HEAD
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
            );
        }

        $managerStmt = $this->pdo->prepare(
            "SELECT id FROM managers WHERE user_id = :user_id",
        );
        $managerStmt->execute(["user_id" => $managerUserId]);
        $managerId = $managerStmt->fetchColumn();

        if ($managerId === false) {
            throw new DomainException("Profil manager introuvable.");
        }

        $missionStmt = $this->pdo->prepare(
            "SELECT manager_id, status FROM missions WHERE id = :id",
        );
        $missionStmt->execute(["id" => $missionId]);
        $mission = $missionStmt->fetch();

        if ($mission === false) {
            throw new DomainException("Mission introuvable.");
        }

        if ((int) $mission["manager_id"] !== (int) $managerId) {
            throw new DomainException(
                "Cette mission n'appartient pas à ce manager.",
            );
        }

        if ($mission["status"] !== "closed") {
            throw new DomainException(
                "Seules les missions terminées peuvent être évaluées.",
            );
=======
    public function __construct(private PDO $pdo)
    {
    }
=======
    public function __construct(private PDO $pdo) {}
>>>>>>> f8e10eb (fix: freelance controller)

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
            );
        }

        $managerStmt = $this->pdo->prepare(
            "SELECT id FROM managers WHERE user_id = :user_id",
        );
        $managerStmt->execute(["user_id" => $managerUserId]);
        $managerId = $managerStmt->fetchColumn();

        if ($managerId === false) {
            throw new DomainException("Profil manager introuvable.");
        }

        $missionStmt = $this->pdo->prepare(
            "SELECT manager_id, status FROM missions WHERE id = :id",
        );
        $missionStmt->execute(["id" => $missionId]);
        $mission = $missionStmt->fetch();

        if ($mission === false) {
            throw new DomainException("Mission introuvable.");
        }

        if ((int) $mission["manager_id"] !== (int) $managerId) {
            throw new DomainException(
                "Cette mission n'appartient pas à ce manager.",
            );
        }

<<<<<<< HEAD
        if ($mission['status'] !== 'closed') {
            throw new DomainException('Seules les missions terminées peuvent être évaluées.');
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
        if ($mission["status"] !== "closed") {
            throw new DomainException(
                "Seules les missions terminées peuvent être évaluées.",
            );
>>>>>>> f8e10eb (fix: freelance controller)
        }

        $applicationStmt = $this->pdo->prepare(
            "SELECT 1 FROM applications
             WHERE mission_id = :mission_id AND freelance_id = :freelance_id AND status = 'accepted'",
        );
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> f8e10eb (fix: freelance controller)
        $applicationStmt->execute([
            "mission_id" => $missionId,
            "freelance_id" => $freelanceId,
        ]);
<<<<<<< HEAD

        if ($applicationStmt->fetchColumn() === false) {
            throw new DomainException(
                "Ce freelance n'a pas de candidature acceptée sur cette mission.",
            );
=======
        $applicationStmt->execute(['mission_id' => $missionId, 'freelance_id' => $freelanceId]);

        if ($applicationStmt->fetchColumn() === false) {
            throw new DomainException("Ce freelance n'a pas de candidature acceptée sur cette mission.");
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======

        if ($applicationStmt->fetchColumn() === false) {
            throw new DomainException(
                "Ce freelance n'a pas de candidature acceptée sur cette mission.",
            );
>>>>>>> f8e10eb (fix: freelance controller)
        }

        $insert = $this->pdo->prepare(
            'INSERT INTO reviews (freelance_id, manager_id, mission_id, rating, comment)
             VALUES (:freelance_id, :manager_id, :mission_id, :rating, :comment)',
        );
        $insert->execute([
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> f8e10eb (fix: freelance controller)
            "freelance_id" => $freelanceId,
            "manager_id" => $managerId,
            "mission_id" => $missionId,
            "rating" => $rating,
            "comment" => $comment,
<<<<<<< HEAD
=======
            'freelance_id' => $freelanceId,
            'manager_id' => $managerId,
            'mission_id' => $missionId,
            'rating' => $rating,
            'comment' => $comment,
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
>>>>>>> f8e10eb (fix: freelance controller)
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
