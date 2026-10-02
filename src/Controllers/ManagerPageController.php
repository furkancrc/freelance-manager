<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractPageController;
use App\Core\Security;
use App\Models\Manager;

final class ManagerPageController extends AbstractPageController
{
    public function __construct(private Manager $managers) {}

    public function index(): void
    {
        Security::requireRole(["admin"]);

        $managers = $this->managers->all();

        require __DIR__ . "/../Views/manager/index.php";
    }

    public function create(): void
    {
        Security::requireRole(["admin"]);

        $manager = [];
        $isProfile = false;

        require __DIR__ . "/../Views/manager/form.php";
    }

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
