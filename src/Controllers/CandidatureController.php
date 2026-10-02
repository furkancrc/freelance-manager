<?php

declare(strict_types=1);

namespace App\Controllers;

<<<<<<< HEAD
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
=======
use App\Core\AbstractApiController;
use App\Core\Security;
use App\Models\Candidature;
use DomainException;

final class CandidatureController extends AbstractApiController
{
    private const STATUSES = ["pending", "accepted", "rejected"];

    public function __construct(private Candidature $candidatures) {}
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31

    public function store(int $missionId): void
    {
        $user = Security::requireJsonRole(["freelance"]);
<<<<<<< HEAD

=======
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        $data = $this->readJsonBody();

        if (
            isset($data["proposed_rate"]) &&
            !is_numeric($data["proposed_rate"])
        ) {
<<<<<<< HEAD
            $this->json(
                ["errors" => ["proposed_rate" => "Doit être numérique."]],
                422,
            );

            return;
=======
            $this->jsonError(["proposed_rate" => "Doit être numérique."], 422);
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        }

        try {
            $id = $this->candidatures->apply(
                (int) $user["id"],
                $missionId,
<<<<<<< HEAD
                isset($data["message"]) ? (string) $data["message"] : null,
=======
                $data["message"] ?? null,
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
                isset($data["proposed_rate"])
                    ? (float) $data["proposed_rate"]
                    : null,
            );
<<<<<<< HEAD
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
=======
            $this->json(["id" => $id], 201);
        } catch (DomainException $e) {
            $this->jsonError($e->getMessage(), $e->getCode() ?: 422);
        }
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
    }

    public function mine(): void
    {
        $user = Security::requireJsonRole(["freelance"]);
<<<<<<< HEAD

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
=======
        $status = $this->statusFilter();

        if ($status !== false) {
            $this->json(
                $this->candidatures->listForFreelanceUser(
                    (int) $user["id"],
                    $status,
                ),
            );
        }
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
    }

    public function forMission(int $missionId): void
    {
        $user = Security::requireJsonRole(["admin", "manager"]);
<<<<<<< HEAD

=======
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        $status = $this->statusFilter();
        if ($status === false) {
            return;
        }

        $ownerId = $user["role"] === "manager" ? (int) $user["id"] : null;
<<<<<<< HEAD
        if (!$this->candidatures->missionExists($missionId, $ownerId)) {
            $this->json(["error" => "Mission introuvable."], 404);

            return;
=======

        if (!$this->candidatures->missionExists($missionId, $ownerId)) {
            $this->jsonError("Mission introuvable.", 404);
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        }

        $this->json($this->candidatures->listForMission($missionId, $status));
    }

    private function statusFilter(): string|false|null
    {
        $status = $_GET["status"] ?? null;
<<<<<<< HEAD

=======
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        if ($status === null || $status === "") {
            return null;
        }

        if (!in_array($status, self::STATUSES, true)) {
<<<<<<< HEAD
            $this->json(
                [
                    "error" =>
                        "status doit valoir pending, accepted ou rejected.",
                ],
                422,
            );

=======
            $this->jsonError(
                "status doit valoir pending, accepted ou rejected.",
                422,
            );
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
            return false;
        }

        return $status;
    }
<<<<<<< HEAD

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
=======
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
}
