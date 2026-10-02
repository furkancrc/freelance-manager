<?php

declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AccountPageController;
use App\Controllers\AuthController;
use App\Controllers\CandidatureController;
use App\Controllers\FavoriteController;
use App\Controllers\FreelanceController;
use App\Controllers\FreelancePageController;
use App\Controllers\ManagerController;
use App\Controllers\ManagerPageController;
use App\Controllers\MissionController;
use App\Controllers\MissionPageController;
use App\Core\Database;
use App\Core\Router;
use App\Core\Security;
use App\Models\Candidature;
use App\Models\Favorite;
use App\Models\Freelance;
use App\Models\Manager;
use App\Models\Mission;
use App\Models\Review;
use App\Models\User;

Security::startSession();

$router = new Router();
$pdo = Database::getConnection();

$router->get("/", function (): void {
    Security::requireAuth();
    header("Location: /missions");
});

$router->get("/login", function () use ($pdo): void {
    new AuthController(new User($pdo))->showLogin();
});

$router->post("/login", function () use ($pdo): void {
    new AuthController(new User($pdo))->login();
});

$router->post("/logout", function () use ($pdo): void {
    new AuthController(new User($pdo))->logout();
});

$router->get("/mentions-legales", function (): void {
    require __DIR__ . "/../src/Views/legals/index.php";
});

$router->get("/missions", function () use ($pdo): void {
    new MissionPageController(
        new Mission($pdo),
        new Candidature($pdo),
        new Favorite($pdo),
    )->index();
});

$router->get("/missions/nouvelle", function () use ($pdo): void {
    new MissionPageController(
        new Mission($pdo),
        new Candidature($pdo),
        new Favorite($pdo),
    )->create();
});

$router->get("/missions/{id}", function (string $id) use ($pdo): void {
    new MissionPageController(
        new Mission($pdo),
        new Candidature($pdo),
        new Favorite($pdo),
    )->show((int) $id);
});

$router->get("/missions/{id}/modifier", function (string $id) use ($pdo): void {
    new MissionPageController(
        new Mission($pdo),
        new Candidature($pdo),
        new Favorite($pdo),
    )->edit((int) $id);
});

$router->get("/freelances", function () use ($pdo): void {
    new FreelancePageController(
        new Freelance($pdo),
        new Review($pdo),
        new Mission($pdo),
    )->index();
});

$router->get("/freelances/nouveau", function () use ($pdo): void {
    new FreelancePageController(
        new Freelance($pdo),
        new Review($pdo),
        new Mission($pdo),
    )->create();
});

$router->get("/freelances/{id}", function (string $id) use ($pdo): void {
    new FreelancePageController(
        new Freelance($pdo),
        new Review($pdo),
        new Mission($pdo),
    )->show((int) $id);
});

$router->get("/freelances/{id}/modifier", function (string $id) use (
    $pdo,
): void {
    new FreelancePageController(
        new Freelance($pdo),
        new Review($pdo),
        new Mission($pdo),
    )->edit((int) $id);
});

$router->get("/managers", function () use ($pdo): void {
    new ManagerPageController(new Manager($pdo))->index();
});

$router->get("/managers/nouveau", function () use ($pdo): void {
    new ManagerPageController(new Manager($pdo))->create();
});

$router->get("/managers/{id}/modifier", function (string $id) use ($pdo): void {
    new ManagerPageController(new Manager($pdo))->edit((int) $id);
});

$router->get("/candidatures", function () use ($pdo): void {
    new AccountPageController(
        new Candidature($pdo),
        new Favorite($pdo),
        new Freelance($pdo),
        new Manager($pdo),
    )->applications();
});

$router->get("/favoris", function () use ($pdo): void {
    new AccountPageController(
        new Candidature($pdo),
        new Favorite($pdo),
        new Freelance($pdo),
        new Manager($pdo),
    )->favorites();
});

$router->get("/profil", function () use ($pdo): void {
    new AccountPageController(
        new Candidature($pdo),
        new Favorite($pdo),
        new Freelance($pdo),
        new Manager($pdo),
    )->profile();
});

$router->get("/api/freelances", function () use ($pdo): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->index();
});

$router->post("/api/freelances", function () use ($pdo): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->store();
});

$router->get("/api/freelances/{id}", function (string $id) use ($pdo): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->show(
        (int) $id,
    );
});

$router->put("/api/freelances/{id}", function (string $id) use ($pdo): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->update(
        (int) $id,
    );
});

$router->delete("/api/freelances/{id}", function (string $id) use ($pdo): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->destroy(
        (int) $id,
    );
});

$router->post("/api/freelances/{id}/reviews", function (string $id) use (
    $pdo,
): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->addReview(
        (int) $id,
    );
});

$router->get("/api/managers", function () use ($pdo): void {
    new ManagerController(new Manager($pdo))->index();
});

$router->post("/api/managers", function () use ($pdo): void {
    new ManagerController(new Manager($pdo))->store();
});

$router->get("/api/managers/{id}", function (string $id) use ($pdo): void {
    new ManagerController(new Manager($pdo))->show((int) $id);
});

$router->put("/api/managers/{id}", function (string $id) use ($pdo): void {
    new ManagerController(new Manager($pdo))->update((int) $id);
});

$router->delete("/api/managers/{id}", function (string $id) use ($pdo): void {
    new ManagerController(new Manager($pdo))->destroy((int) $id);
});

$router->get("/api/missions", function () use ($pdo): void {
    new MissionController(new Mission($pdo))->index();
});

$router->post("/api/missions", function () use ($pdo): void {
    new MissionController(new Mission($pdo))->store();
});

$router->get("/api/missions/stats", function () use ($pdo): void {
    new MissionController(new Mission($pdo))->stats();
});

$router->get("/api/missions/{id}", function (string $id) use ($pdo): void {
    new MissionController(new Mission($pdo))->show((int) $id);
});

$router->put("/api/missions/{id}", function (string $id) use ($pdo): void {
    new MissionController(new Mission($pdo))->update((int) $id);
});

$router->delete("/api/missions/{id}", function (string $id) use ($pdo): void {
    new MissionController(new Mission($pdo))->destroy((int) $id);
});

$router->post("/api/missions/{id}/applications", function (string $id) use (
    $pdo,
): void {
    new CandidatureController(new Candidature($pdo))->store((int) $id);
});

$router->put("/api/applications/{id}", function (string $id) use ($pdo): void {
    new CandidatureController(new Candidature($pdo))->updateStatus((int) $id);
});

$router->delete("/api/applications/{id}", function (string $id) use (
    $pdo,
): void {
    new CandidatureController(new Candidature($pdo))->destroy((int) $id);
});

$router->get("/api/applications/me", function () use ($pdo): void {
    new CandidatureController(new Candidature($pdo))->mine();
});

$router->get("/api/missions/{id}/applications", function (string $id) use (
    $pdo,
): void {
    new CandidatureController(new Candidature($pdo))->forMission((int) $id);
});

$router->post("/api/missions/{id}/favorites", function (string $id) use (
    $pdo,
): void {
    new FavoriteController(new Favorite($pdo))->add((int) $id);
});

$router->delete("/api/missions/{id}/favorites", function (string $id) use (
    $pdo,
): void {
    new FavoriteController(new Favorite($pdo))->remove((int) $id);
});

$router->get("/api/favorites", function () use ($pdo): void {
    new FavoriteController(new Favorite($pdo))->index();
});

$router->dispatch($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
