<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractApiController;
use App\Core\Security;
use App\Models\Manager;
use DomainException;

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

        if ($this->managers->findByUserId($userId) === null) {
            $this->jsonError("Manager introuvable.", 404);
        }

        $data = $this->readJsonBody();
        $errors = $this->validateUpdate($data);

        if ($errors !== []) {
            $this->jsonError($errors, 422);
        }

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

        try {
            $this->managers->delete($userId);
        } catch (DomainException $e) {
            $this->jsonError($e->getMessage(), $e->getCode() ?: 409);
        }

        http_response_code(204);
    }

    private function validateUpdate(array $data): array
    {
        $errors = [];
        if (
            array_key_exists("first_name", $data) &&
            empty($data["first_name"])
        ) {
            $errors["first_name"] = "Le prénom ne peut pas être vide.";
        }
        if (array_key_exists("last_name", $data) && empty($data["last_name"])) {
            $errors["last_name"] = "Le nom ne peut pas être vide.";
        }

        return $errors;
    }
}
