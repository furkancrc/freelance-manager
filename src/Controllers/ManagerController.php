<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractApiController;
use App\Core\Security;
use App\Models\Manager;

final class ManagerController extends AbstractApiController
{
    public function __construct(private Manager $managers) {}

    public function show(int $userId): void
    {
        Security::requireJsonRole(["admin", "manager"]);

        $manager = $this->managers->findByUserId($userId);
        if ($manager === null) {
            $this->jsonError("Manager introuvable.", 404);
        }

        $this->json($manager);
    }

    public function update(int $userId): void
    {
        $user = Security::requireJsonRole(["admin", "manager"]);

        if ($user["role"] === "manager" && (int) $user["id"] !== $userId) {
            $this->jsonError(
                "Vous ne pouvez modifier que votre propre profil.",
                403,
            );
        }

        $data = $this->readJsonBody();
        $success = $this->managers->update($userId, $data);

        if (!$success) {
            $this->jsonError("Aucune modification effectuée.", 422);
        }

        $this->json($this->managers->findByUserId($userId));
    }

    public function destroy(int $userId): void
    {
        Security::requireJsonRole(["admin"]);

        if ($this->managers->findByUserId($userId) === null) {
            $this->jsonError("Manager introuvable.", 404);
        }

        $this->managers->delete($userId);
        http_response_code(204);
    }
}
