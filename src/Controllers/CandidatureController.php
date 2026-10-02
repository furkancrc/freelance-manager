<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractApiController;
use App\Core\Security;
use App\Models\Candidature;
use DomainException;

final class CandidatureController extends AbstractApiController
{
    private const STATUSES = ["pending", "accepted", "rejected"];

    public function __construct(private Candidature $candidatures) {}

    public function store(int $missionId): void
    {
        $user = Security::requireJsonRole(["freelance"]);
        $data = $this->readJsonBody();

        if (
            isset($data["proposed_rate"]) &&
            !is_numeric($data["proposed_rate"])
        ) {
            $this->jsonError(["proposed_rate" => "Doit être numérique."], 422);
        }

        try {
            $id = $this->candidatures->apply(
                (int) $user["id"],
                $missionId,
                $data["message"] ?? null,
                isset($data["proposed_rate"])
                    ? (float) $data["proposed_rate"]
                    : null,
            );
            $this->json(["id" => $id], 201);
        } catch (DomainException $e) {
            $this->jsonError($e->getMessage(), $e->getCode() ?: 422);
        }
    }

    public function mine(): void
    {
        $user = Security::requireJsonRole(["freelance"]);
        $status = $this->statusFilter();

        if ($status !== false) {
            $this->json(
                $this->candidatures->listForFreelanceUser(
                    (int) $user["id"],
                    $status,
                ),
            );
        }
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
            $this->jsonError("Mission introuvable.", 404);
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
            $this->jsonError(
                "status doit valoir pending, accepted ou rejected.",
                422,
            );
            return false;
        }

        return $status;
    }
}
