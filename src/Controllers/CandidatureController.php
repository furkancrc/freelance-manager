<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Security;
use App\Models\Candidature;
use DomainException;
use PDOException;

final class CandidatureController
{
    private const STATUSES = ["pending", "accepted", "rejected"];

    private Candidature $candidatures;

    public function __construct()
    {
        $this->candidatures = new Candidature(Database::getConnection());
    }

    public function store(int $missionId): void
    {
        $user = Security::requireJsonRole(["freelance"]);

        $data = $this->readJsonBody();

        if (
            isset($data["proposed_rate"]) &&
            !is_numeric($data["proposed_rate"])
        ) {
            $this->json(
                ["errors" => ["proposed_rate" => "Doit être numérique."]],
                422,
            );

            return;
        }

        try {
            $id = $this->candidatures->apply(
                (int) $user["id"],
                $missionId,
                isset($data["message"]) ? (string) $data["message"] : null,
                isset($data["proposed_rate"])
                    ? (float) $data["proposed_rate"]
                    : null,
            );
        } catch (DomainException $e) {
            $this->json(["error" => $e->getMessage()], 422);

            return;
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $this->json(
                    ["error" => "Vous avez déjà postulé à cette mission."],
                    409,
                );

                return;
            }

            throw $e;
        }

        $this->json(["id" => $id], 201);
    }

    public function mine(): void
    {
        $user = Security::requireJsonRole(["freelance"]);

        $status = $this->statusFilter();
        if ($status === false) {
            return;
        }

        $this->json(
            $this->candidatures->listForFreelanceUser(
                (int) $user["id"],
                $status,
            ),
        );
    }

    public function forMission(int $missionId): void
    {
        $user = Security::requireJsonRole(["admin", "manager"]);

        $status = $this->statusFilter();
        if ($status === false) {
            return;
        }

        $ownerId = $user["role"] === "manager" ? (int) $user["id"] : null;
        if (!$this->candidatures->missionExists($missionId, $ownerId)) {
            $this->json(["error" => "Mission introuvable."], 404);

            return;
        }

        $this->json($this->candidatures->listForMission($missionId, $status));
    }

    private function statusFilter(): string|false|null
    {
        $status = $_GET["status"] ?? null;

        if ($status === null || $status === "") {
            return null;
        }

        if (!in_array($status, self::STATUSES, true)) {
            $this->json(
                [
                    "error" =>
                        "status doit valoir pending, accepted ou rejected.",
                ],
                422,
            );

            return false;
        }

        return $status;
    }

    /** @return array<string, mixed> */
    private function readJsonBody(): array
    {
        $data = json_decode((string) file_get_contents("php://input"), true);

        return is_array($data) ? $data : [];
    }

    private function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}
