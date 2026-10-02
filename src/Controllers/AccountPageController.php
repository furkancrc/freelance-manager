<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractPageController;
use App\Core\Security;
use App\Models\Candidature;
use App\Models\Favorite;
use App\Models\Freelance;
use App\Models\Manager;

final class AccountPageController extends AbstractPageController
{
    private const APPLICATION_STATUSES = ["pending", "accepted", "rejected"];

    public function __construct(
        private Candidature $candidatures,
        private Favorite $favorites,
        private Freelance $freelances,
        private Manager $managers,
    ) {}

    public function applications(): void
    {
        Security::requireRole(["freelance"]);
        $user = Security::currentUser();

        $status = $this->query("status");
        if (!in_array($status, self::APPLICATION_STATUSES, true)) {
            $status = null;
        }

        $applications = $this->candidatures->listForFreelanceUser(
            (int) $user["id"],
            $status,
        );
        $statuses = self::APPLICATION_STATUSES;

        require __DIR__ . "/../Views/account/applications.php";
    }

    public function favorites(): void
    {
        Security::requireRole(["freelance"]);
        $user = Security::currentUser();

        $favorites = $this->favorites->listForFreelanceUser((int) $user["id"]);

        require __DIR__ . "/../Views/account/favorites.php";
    }

    public function profile(): void
    {
        Security::requireRole(["freelance", "manager"]);
        $user = Security::currentUser();
        $isProfile = true;

        if ($user["role"] === "freelance") {
            $freelance = $this->freelances->findByUserId((int) $user["id"]);
            if ($freelance === null) {
                $this->notFound();
            }

            $availabilities = ["available", "busy"];

            require __DIR__ . "/../Views/freelance/form.php";
            return;
        }

        $manager = $this->managers->findByUserId((int) $user["id"]);
        if ($manager === null) {
            $this->notFound();
        }

        require __DIR__ . "/../Views/manager/form.php";
    }
}
