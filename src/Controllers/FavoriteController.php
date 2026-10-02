<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractApiController;
use App\Core\Security;
use App\Models\Favorite;
use DomainException;

final class FavoriteController extends AbstractApiController
{
    public function __construct(private Favorite $favorites) {}

    /** POST /missions/{id}/favorites — SF16 : ajouter aux favoris (réservé freelance). */
    public function add(int $missionId): void
    {
        $user = Security::requireJsonRole(["freelance"]);

        try {
            $this->favorites->add((int) $user["id"], $missionId);
        } catch (DomainException $e) {
            $this->jsonError($e->getMessage(), $e->getCode() ?: 422);
        }

        $this->json(["mission_id" => $missionId], 201);
    }

    /** DELETE /missions/{id}/favorites — SF17 : retirer des favoris (réservé freelance). */
    public function remove(int $missionId): void
    {
        $user = Security::requireJsonRole(["freelance"]);

        try {
            $this->favorites->remove((int) $user["id"], $missionId);
        } catch (DomainException $e) {
            $this->jsonError($e->getMessage(), $e->getCode() ?: 422);
        }

        http_response_code(204);
    }

    /** GET /favorites — SF18 : voir ses favoris (réservé freelance). */
    public function index(): void
    {
        $user = Security::requireJsonRole(["freelance"]);

        try {
            $this->json($this->favorites->listForFreelanceUser((int) $user["id"]));
        } catch (DomainException $e) {
            $this->jsonError($e->getMessage(), $e->getCode() ?: 422);
        }
    }
}
