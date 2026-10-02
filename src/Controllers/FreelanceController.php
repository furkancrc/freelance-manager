<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractApiController;
use App\Core\Security;
use App\Models\Freelance;
use App\Models\Review;
use DomainException;

final class FreelanceController extends AbstractApiController
{
    public function __construct(
        private Freelance $freelances,
        private Review $reviews,
    ) {}

    public function index(): void
    {
        Security::requireJsonRole(["admin", "manager"]);

        $this->json(
            $this->freelances->search([
                "q" => $_GET["q"] ?? null,
                "availability" => $_GET["availability"] ?? null,
                "location" => $_GET["location"] ?? null,
                "min_rate" => $_GET["min_rate"] ?? null,
                "max_rate" => $_GET["max_rate"] ?? null,
            ]),
        );
    }

    public function show(int $id): void
    {
        Security::requireJsonRole(["admin", "manager"]);

        $freelance = $this->freelances->find($id);
        if ($freelance === null) {
            $this->jsonError("Freelance introuvable.", 404);
        }

        $this->json($freelance);
    }

    public function store(): void
    {
        Security::requireJsonRole(["admin"]);

        $data = $this->readJsonBody();
        $errors = $this->validateCreate($data);

        if ($errors !== []) {
            $this->jsonError($errors, 422);
        }

        try {
            $id = $this->freelances->create($data);
            $this->json($this->freelances->find($id), 201);
        } catch (DomainException $e) {
            $this->jsonError($e->getMessage(), $e->getCode() ?: 409);
        }
    }

    public function update(int $id): void
    {
        $user = Security::requireJsonRole(["admin", "freelance"]);
        $freelance = $this->freelances->find($id);

        if ($freelance === null) {
            $this->jsonError("Freelance introuvable.", 404);
        }

        if (
            $user["role"] === "freelance" &&
            (int) $freelance["user_id"] !== (int) $user["id"]
        ) {
            $this->jsonError(
                "Vous ne pouvez modifier que votre propre profil.",
                403,
            );
        }

        $data = $this->readJsonBody();
        $errors = $this->validateUpdate($data);

        if ($errors !== []) {
            $this->jsonError($errors, 422);
        }

        $this->freelances->update($id, $data);
        $this->json($this->freelances->find($id));
    }

    public function destroy(int $id): void
    {
        Security::requireJsonRole(["admin"]);

        if ($this->freelances->find($id) === null) {
            $this->jsonError("Freelance introuvable.", 404);
        }

        $this->freelances->delete($id);
        http_response_code(204);
    }

    public function addReview(int $id): void
    {
        $user = Security::requireJsonRole(["manager"]);
        $data = $this->readJsonBody();

        if (empty($data["mission_id"]) || !isset($data["rating"])) {
            $this->jsonError("mission_id et rating sont requis.", 422);
        }

        if (isset($data["comment"]) && !is_string($data["comment"])) {
            $this->jsonError(["comment" => "Doit être un texte."], 422);
        }

        try {
            $reviewId = $this->reviews->create(
                (int) $user["id"],
                $id,
                (int) $data["mission_id"],
                (int) $data["rating"],
                $data["comment"] ?? null,
            );
            $this->json(["id" => $reviewId], 201);
        } catch (DomainException $e) {
            $this->jsonError($e->getMessage(), $e->getCode() ?: 422);
        }
    }

    private function validateCreate(array $data): array
    {
        $errors = [];
        if (
            empty($data["email"]) ||
            !filter_var($data["email"], FILTER_VALIDATE_EMAIL)
        ) {
            $errors["email"] = "Email invalide.";
        }
        if (
            empty($data["password"]) ||
            strlen((string) $data["password"]) < 8
        ) {
            $errors["password"] = "Mot de passe requis (8 caractères minimum).";
        }
        if (empty($data["first_name"])) {
            $errors["first_name"] = "Prénom requis.";
        }
        if (empty($data["last_name"])) {
            $errors["last_name"] = "Nom requis.";
        }
        if (
            isset($data["availability"]) &&
            !in_array($data["availability"], ["available", "busy"], true)
        ) {
            $errors["availability"] = 'Doit être "available" ou "busy".';
        }
        if (isset($data["daily_rate"]) && !is_numeric($data["daily_rate"])) {
            $errors["daily_rate"] = "Doit être numérique.";
        }

        return $errors;
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
        if (
            array_key_exists("availability", $data) &&
            !in_array($data["availability"], ["available", "busy"], true)
        ) {
            $errors["availability"] = 'Doit être "available" ou "busy".';
        }
        if (isset($data["daily_rate"]) && !is_numeric($data["daily_rate"])) {
            $errors["daily_rate"] = "Doit être numérique.";
        }

        return $errors;
    }
}
