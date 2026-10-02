<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Security;
use App\Models\Freelance;
use App\Models\Review;
use DomainException;
use PDOException;

final class FreelanceController
{
    private Freelance $freelances;
    private Review $reviews;

    public function __construct()
    {
        $pdo = Database::connection();
        $this->freelances = new Freelance($pdo);
        $this->reviews = new Review($pdo);
    }

    /** GET /freelances — SF2 : recherche de freelances (réservé admin/manager). */
    public function index(): void
    {
        Security::requireJsonRole(['admin', 'manager']);

        $results = $this->freelances->search([
            'q' => $_GET['q'] ?? null,
            'availability' => $_GET['availability'] ?? null,
            'location' => $_GET['location'] ?? null,
            'min_rate' => $_GET['min_rate'] ?? null,
            'max_rate' => $_GET['max_rate'] ?? null,
        ]);

        $this->json($results);
    }

    /** GET /freelances/{id} */
    public function show(int $id): void
    {
        Security::requireJsonRole(['admin', 'manager']);

        $freelance = $this->freelances->find($id);
        if ($freelance === null) {
            $this->json(['error' => 'Freelance introuvable.'], 404);

            return;
        }

        $this->json($freelance);
    }

    /** POST /freelances — SF3 : création d'un freelance (réservé admin). */
    public function store(): void
    {
        Security::requireJsonRole(['admin']);

        $data = $this->readJsonBody();
        $errors = $this->validateCreate($data);
        if ($errors !== []) {
            $this->json(['errors' => $errors], 422);

            return;
        }

        try {
            $id = $this->freelances->create($data);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->json(['error' => 'Cet email est déjà utilisé.'], 409);

                return;
            }

            throw $e;
        }

        $this->json($this->freelances->find($id), 201);
    }

    /** PUT /freelances/{id} — SF4 : modification (admin, ou le freelance lui-même). */
    public function update(int $id): void
    {
        $user = Security::requireJsonRole(['admin', 'freelance']);

        $freelance = $this->freelances->find($id);
        if ($freelance === null) {
            $this->json(['error' => 'Freelance introuvable.'], 404);

            return;
        }

        if ($user['role'] === 'freelance' && (int) $freelance['user_id'] !== (int) $user['id']) {
            $this->json(['error' => 'Vous ne pouvez modifier que votre propre profil.'], 403);

            return;
        }

        $data = $this->readJsonBody();
        $errors = $this->validateUpdate($data);
        if ($errors !== []) {
            $this->json(['errors' => $errors], 422);

            return;
        }

        $this->freelances->update($id, $data);
        $this->json($this->freelances->find($id));
    }

    /** DELETE /freelances/{id} — SF5 : suppression (réservé admin). */
    public function destroy(int $id): void
    {
        Security::requireJsonRole(['admin']);

        if ($this->freelances->find($id) === null) {
            $this->json(['error' => 'Freelance introuvable.'], 404);

            return;
        }

        $this->freelances->delete($id);
        http_response_code(204);
    }

    /** POST /freelances/{id}/reviews — SF6 : évaluation par un manager. */
    public function addReview(int $id): void
    {
        $user = Security::requireJsonRole(['manager']);

        $data = $this->readJsonBody();

        if (empty($data['mission_id']) || !isset($data['rating'])) {
            $this->json(['error' => 'mission_id et rating sont requis.'], 422);

            return;
        }

        try {
            $reviewId = $this->reviews->create(
                (int) $user['id'],
                $id,
                (int) $data['mission_id'],
                (int) $data['rating'],
                $data['comment'] ?? null,
            );
        } catch (DomainException $e) {
            $this->json(['error' => $e->getMessage()], 422);

            return;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->json(['error' => 'Ce freelance a déjà été évalué pour cette mission.'], 409);

                return;
            }

            throw $e;
        }

        $this->json(['id' => $reviewId], 201);
    }

    /** @return array<string, mixed> */
    private function readJsonBody(): array
    {
        $data = json_decode((string) file_get_contents('php://input'), true);

        return is_array($data) ? $data : [];
    }

    /** @return array<string, string> */
    private function validateCreate(array $data): array
    {
        $errors = [];

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email invalide.';
        }

        if (empty($data['password']) || strlen((string) $data['password']) < 8) {
            $errors['password'] = 'Mot de passe requis (8 caractères minimum).';
        }

        if (empty($data['first_name'])) {
            $errors['first_name'] = 'Prénom requis.';
        }

        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Nom requis.';
        }

        if (isset($data['availability']) && !in_array($data['availability'], ['available', 'busy'], true)) {
            $errors['availability'] = 'Doit être "available" ou "busy".';
        }

        return $errors;
    }

    /** @return array<string, string> */
    private function validateUpdate(array $data): array
    {
        $errors = [];

        if (isset($data['availability']) && !in_array($data['availability'], ['available', 'busy'], true)) {
            $errors['availability'] = 'Doit être "available" ou "busy".';
        }

        if (isset($data['daily_rate']) && !is_numeric($data['daily_rate'])) {
            $errors['daily_rate'] = 'Doit être numérique.';
        }

        return $errors;
    }

    private function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
