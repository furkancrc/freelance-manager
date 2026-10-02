<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use PDO;

final class Review
{
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

    /**
     * Enregistre l'évaluation d'un freelance par le manager connecté, pour une mission donnée.
     *
     * Règles : la mission doit appartenir au manager, être "closed", et le freelance doit avoir
     * une candidature "accepted" sur cette mission (cf. commentaires de database/schema.sql).
     *
     * @throws DomainException si une règle métier n'est pas respectée
     */
    public function create(int $managerUserId, int $freelanceId, int $missionId, int $rating, ?string $comment): int
    {
        if ($rating < 1 || $rating > 5) {
            throw new DomainException('La note doit être comprise entre 1 et 5.');
        }

        $managerStmt = $this->pdo->prepare('SELECT id FROM managers WHERE user_id = :user_id');
        $managerStmt->execute(['user_id' => $managerUserId]);
        $managerId = $managerStmt->fetchColumn();

        if ($managerId === false) {
            throw new DomainException('Profil manager introuvable.');
        }

        $missionStmt = $this->pdo->prepare('SELECT manager_id, status FROM missions WHERE id = :id');
        $missionStmt->execute(['id' => $missionId]);
        $mission = $missionStmt->fetch();

        if ($mission === false) {
            throw new DomainException('Mission introuvable.');
        }

        if ((int) $mission['manager_id'] !== (int) $managerId) {
            throw new DomainException("Cette mission n'appartient pas à ce manager.");
        }

        if ($mission['status'] !== 'closed') {
            throw new DomainException('Seules les missions terminées peuvent être évaluées.');
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
        }

        $applicationStmt = $this->pdo->prepare(
            "SELECT 1 FROM applications
             WHERE mission_id = :mission_id AND freelance_id = :freelance_id AND status = 'accepted'",
        );
<<<<<<< HEAD
        $applicationStmt->execute([
            "mission_id" => $missionId,
            "freelance_id" => $freelanceId,
        ]);

        if ($applicationStmt->fetchColumn() === false) {
            throw new DomainException(
                "Ce freelance n'a pas de candidature acceptée sur cette mission.",
            );
=======
        $applicationStmt->execute(['mission_id' => $missionId, 'freelance_id' => $freelanceId]);

        if ($applicationStmt->fetchColumn() === false) {
            throw new DomainException("Ce freelance n'a pas de candidature acceptée sur cette mission.");
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
        }

        $insert = $this->pdo->prepare(
            'INSERT INTO reviews (freelance_id, manager_id, mission_id, rating, comment)
             VALUES (:freelance_id, :manager_id, :mission_id, :rating, :comment)',
        );
        $insert->execute([
<<<<<<< HEAD
            "freelance_id" => $freelanceId,
            "manager_id" => $managerId,
            "mission_id" => $missionId,
            "rating" => $rating,
            "comment" => $comment,
=======
            'freelance_id' => $freelanceId,
            'manager_id' => $managerId,
            'mission_id' => $missionId,
            'rating' => $rating,
            'comment' => $comment,
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
