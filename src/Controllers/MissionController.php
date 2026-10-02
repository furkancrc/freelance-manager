<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractApiController;
use App\Core\Security;
use App\Models\Mission;
use DateTimeImmutable;
use DomainException;

final class MissionController extends AbstractApiController
{
    private const STATUSES = ["draft", "open", "in_progress", "closed"];

    public function __construct(private Mission $missions) {}

    public function index(): void
    {
        Security::requireJsonAuth();

        $this->json(
            $this->missions->search([
                "q" => $_GET["q"] ?? null,
                "status" => $_GET["status"] ?? null,
                "location" => $_GET["location"] ?? null,
            ]),
        );
    }

    public function show(int $id): void
    {
        Security::requireJsonAuth();

        $mission = $this->missions->find($id);
        if ($mission === null) {
            $this->jsonError("Mission introuvable.", 404);
        }

        $this->json($mission);
    }

    public function store(): void
    {
        $user = Security::requireJsonRole(["manager"]);
        $data = $this->readJsonBody();

        $errors = $this->validateCreate($data);
        if ($errors !== []) {
            $this->jsonError($errors, 422);
        }

        try {
            $id = $this->missions->create((int) $user["id"], $data);
            $this->json($this->missions->find($id), 201);
        } catch (DomainException $e) {
            $this->jsonError($e->getMessage(), $e->getCode() ?: 422);
        }
    }

    public function update(int $id): void
    {
        $user = Security::requireJsonRole(["admin", "manager"]);
        $mission = $this->findEditable($id, $user);

        $data = $this->readJsonBody();
        $errors = $this->validateUpdate($data, $mission);
        if ($errors !== []) {
            $this->jsonError($errors, 422);
        }

        $success = $this->missions->update($id, $data);

        if (!$success) {
            $this->jsonError("Aucune modification effectuée.", 422);
        }

        $this->json($this->missions->find($id));
    }

    public function destroy(int $id): void
    {
        $user = Security::requireJsonRole(["admin", "manager"]);
        $this->findEditable($id, $user);

        $this->missions->delete($id);
        http_response_code(204);
    }

    public function stats(): void
    {
        Security::requireJsonAuth();

        $this->json($this->missions->statistics());
    }

    private function findEditable(int $id, array $user): array
    {
        $mission = $this->missions->find($id);
        if ($mission === null) {
            $this->jsonError("Mission introuvable.", 404);
        }

        if (
            $user["role"] === "manager" &&
            !$this->missions->isOwnedBy($id, (int) $user["id"])
        ) {
            $this->jsonError(
                "Vous ne pouvez modifier que vos propres missions.",
                403,
            );
        }

        return $mission;
    }

    private function validateCreate(array $data): array
    {
        $errors = [];
        if (empty($data["title"])) {
            $errors["title"] = "Le titre est requis.";
        }
        if (empty($data["description"])) {
            $errors["description"] = "La description est requise.";
        }
        if (empty($data["budget"])) {
            $errors["budget"] = "Le budget est requis.";
        }
        if (empty($data["daily_rate"])) {
            $errors["daily_rate"] = "Le taux journalier est requis.";
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

        return $errors + $this->validateUpdate($data, []);
    }

    private function validateUpdate(array $data, array $mission): array
    {
        $errors = [];
        if (array_key_exists("title", $data) && empty($data["title"])) {
            $errors["title"] = "Le titre ne peut pas être vide.";
        }
        if (
            array_key_exists("description", $data) &&
            empty($data["description"])
        ) {
            $errors["description"] = "La description ne peut pas être vide.";
        }
        if (array_key_exists("location", $data) && empty($data["location"])) {
            $errors["location"] = "La localisation ne peut pas être vide.";
        }
        if (array_key_exists("budget", $data) && !is_numeric($data["budget"])) {
            $errors["budget"] = "Le budget doit être numérique.";
        }
        if (
            array_key_exists("daily_rate", $data) &&
            !is_numeric($data["daily_rate"])
        ) {
            $errors["daily_rate"] = "Le taux journalier doit être numérique.";
        }
        if (
            array_key_exists("start_date", $data) &&
            !$this->isDate($data["start_date"])
        ) {
            $errors["start_date"] = "Date invalide (format AAAA-MM-JJ).";
        }
        if (
            array_key_exists("end_date", $data) &&
            !$this->isDate($data["end_date"])
        ) {
            $errors["end_date"] = "Date invalide (format AAAA-MM-JJ).";
        }
        if (
            array_key_exists("status", $data) &&
            !in_array($data["status"], self::STATUSES, true)
        ) {
            $errors["status"] =
                "Doit valoir draft, open, in_progress ou closed.";
        }

        $start = $data["start_date"] ?? ($mission["start_date"] ?? null);
        $end = $data["end_date"] ?? ($mission["end_date"] ?? null);

        if (
            $errors === [] &&
            $start !== null &&
            $end !== null &&
            $end < $start
        ) {
            $errors["end_date"] =
                "La date de fin doit être après la date de début.";
        }

        return $errors;
    }

    private function isDate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $date = DateTimeImmutable::createFromFormat("Y-m-d", $value);

        return $date !== false && $date->format("Y-m-d") === $value;
    }
}
