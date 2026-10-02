<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractApiController;
use App\Core\Security;
use App\Models\Mission;
use DomainException;

final class MissionController extends AbstractApiController
{
    public function __construct(private Mission $missions) {}

    public function index(): void
    {
        $this->json($this->missions->all());
    }

    public function show(int $id): void
    {
        $mission = $this->missions->find($id);
        if ($mission === null) {
            $this->jsonError("Mission introuvable.", 404);
        }

        $this->json($mission);
    }

    public function store(): void
    {
        $user = Security::requireJsonRole(["admin", "manager"]);
        $data = $this->readJsonBody();

        $errors = $this->validate($data);
        if ($errors !== []) {
            $this->jsonError($errors, 422);
        }

        try {
            $id = $this->missions->create((int) $user["id"], $data);
            $this->json($this->missions->find($id), 201);
        } catch (DomainException $e) {
            $this->jsonError($e->getMessage(), 422);
        }
    }

    public function update(int $id): void
    {
        Security::requireJsonRole(["admin", "manager"]);

        $mission = $this->missions->find($id);
        if ($mission === null) {
            $this->jsonError("Mission introuvable.", 404);
        }

        $data = $this->readJsonBody();
        $success = $this->missions->update($id, $data);

        if (!$success) {
            $this->jsonError("Aucune modification effectuée.", 422);
        }

        $this->json($this->missions->find($id));
    }

    public function destroy(int $id): void
    {
        Security::requireJsonRole(["admin", "manager"]);

        if ($this->missions->find($id) === null) {
            $this->jsonError("Mission introuvable.", 404);
        }

        $this->missions->delete($id);
        http_response_code(204);
    }

    public function stats(): void
    {
        $this->json($this->missions->statistics());
    }

    private function validate(array $data): array
    {
        $errors = [];
        if (empty($data["title"])) {
            $errors["title"] = "Le titre est requis.";
        }
        if (empty($data["budget"]) || !is_numeric($data["budget"])) {
            $errors["budget"] = "Le budget doit être numérique.";
        }
        if (empty($data["daily_rate"]) || !is_numeric($data["daily_rate"])) {
            $errors["daily_rate"] = "Le taux journalier doit être numérique.";
        }
        if (empty($data["start_date"])) {
            $errors["start_date"] = "La date de début est requise.";
        }
        if (empty($data["end_date"])) {
            $errors["end_date"] = "La date de fin est requise.";
        }
        if (empty($data["location"])) {
            $errors["location"] = "La localisation est requise.";
        }

        return $errors;
    }
}
