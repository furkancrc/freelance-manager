<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractPageController;
use App\Core\Security;
use App\Models\Candidature;
use App\Models\Favorite;
use App\Models\Mission;

final class MissionPageController extends AbstractPageController
{
    private const STATUSES = ["draft", "open", "in_progress", "closed"];

    public function __construct(
        private Mission $missions,
        private Candidature $candidatures,
        private Favorite $favorites,
    ) {}

    /** GET /missions — SF7 recherche, SF11 statistiques, SF19 pagination. */
    public function index(): void
    {
        Security::requireAuth();
        $user = Security::currentUser();

        $filters = [
            "q" => $this->query("q"),
            "status" => $this->query("status"),
            "location" => $this->query("location"),
            "manager_user_id" =>
                $user["role"] === "manager" && $this->query("mine") !== null
                    ? $user["id"]
                    : null,
        ];

        $total = $this->missions->count($filters);
        $pages = $this->pageCount($total);
        $page = min($this->currentPage(), $pages);
        $missions = $this->missions->search(
            $filters,
            self::PER_PAGE,
            ($page - 1) * self::PER_PAGE,
        );
        $stats = $this->missions->statistics();
        $statuses = self::STATUSES;

        require __DIR__ . "/../Views/mission/index.php";
    }

    /** GET /missions/{id} */
    public function show(int $id): void
    {
        Security::requireAuth();
        $user = Security::currentUser();

        $mission = $this->missions->find($id);
        if ($mission === null) {
            $this->notFound();
        }

        $canEdit =
            $user["role"] === "admin" ||
            ($user["role"] === "manager" &&
                $this->missions->isOwnedBy($id, (int) $user["id"]));

        // SF15 : le propriétaire et l'admin voient les candidatures reçues.
        $applications = $canEdit
            ? $this->candidatures->listForMission($id)
            : [];

        $isFavorite = false;
        $myApplication = null;

        if ($user["role"] === "freelance") {
            $favorites = $this->favorites->listForFreelanceUser(
                (int) $user["id"],
            );
            $isFavorite = in_array($id, array_column($favorites, "id"));

            foreach (
                $this->candidatures->listForFreelanceUser((int) $user["id"])
                as $application
            ) {
                if ((int) $application["mission_id"] === $id) {
                    $myApplication = $application;
                }
            }
        }

        require __DIR__ . "/../Views/mission/show.php";
    }

    /** GET /missions/nouvelle — SF8 */
    public function create(): void
    {
        Security::requireRole(["manager"]);

        $mission = ["status" => "open"];
        $statuses = self::STATUSES;

        require __DIR__ . "/../Views/mission/form.php";
    }

    /** GET /missions/{id}/modifier — SF9 */
    public function edit(int $id): void
    {
        Security::requireRole(["admin", "manager"]);
        $user = Security::currentUser();

        $mission = $this->missions->find($id);
        if ($mission === null) {
            $this->notFound();
        }

        if (
            $user["role"] === "manager" &&
            !$this->missions->isOwnedBy($id, (int) $user["id"])
        ) {
            $this->forbidden();
        }

        $statuses = self::STATUSES;

        require __DIR__ . "/../Views/mission/form.php";
    }
}
