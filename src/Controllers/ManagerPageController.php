<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractPageController;
use App\Core\Security;
use App\Models\Manager;

/** SF12 : gestion des managers, réservée à l'admin. */
final class ManagerPageController extends AbstractPageController
{
    public function __construct(private Manager $managers) {}

    /** GET /managers */
    public function index(): void
    {
        Security::requireRole(["admin"]);

        $managers = $this->managers->all();

        require __DIR__ . "/../Views/manager/index.php";
    }

    /** GET /managers/nouveau */
    public function create(): void
    {
        Security::requireRole(["admin"]);

        $manager = [];
        $isProfile = false;

        require __DIR__ . "/../Views/manager/form.php";
    }

    /** GET /managers/{id}/modifier ({id} = user_id, comme l'API). */
    public function edit(int $userId): void
    {
        Security::requireRole(["admin"]);

        $manager = $this->managers->findByUserId($userId);
        if ($manager === null) {
            $this->notFound();
        }

        $isProfile = false;

        require __DIR__ . "/../Views/manager/form.php";
    }
}
