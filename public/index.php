<?php

declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AuthController;
use App\Controllers\CandidatureController;
use App\Controllers\FavoriteController;
use App\Controllers\FreelanceController;
use App\Controllers\ManagerController;
use App\Controllers\MissionController;
use App\Core\Database;
use App\Core\Router;
use App\Core\Security;
use App\Models\Candidature;
use App\Models\Favorite;
use App\Models\Freelance;
use App\Models\Manager;
use App\Models\Review;
use App\Models\User;

Security::startSession();

$router = new Router();

$pdo = Database::getConnection();

$router->get("/", function (): void {
    Security::requireAuth();
    $user = Security::currentUser();
    require __DIR__ . "/../src/Views/home/index.php";
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

$router->get("/freelances", function () use ($pdo): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->index();
});

$router->post("/freelances", function () use ($pdo): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->store();
});

$router->get("/freelances/{id}", function (string $id) use ($pdo): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->show(
        (int) $id,
    );
});

$router->put("/freelances/{id}", function (string $id) use ($pdo): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->update(
        (int) $id,
    );
});

$router->delete("/freelances/{id}", function (string $id) use ($pdo): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->destroy(
        (int) $id,
    );
});

$router->post("/freelances/{id}/reviews", function (string $id) use (
    $pdo,
): void {
    new FreelanceController(new Freelance($pdo), new Review($pdo))->addReview(
        (int) $id,
    );
});

$router->get("/managers/{id}", function (string $id) use ($pdo): void {
    new ManagerController(new Manager($pdo))->show((int) $id);
});

$router->put("/managers/{id}", function (string $id) use ($pdo): void {
    new ManagerController(new Manager($pdo))->update((int) $id);
});

$router->delete("/managers/{id}", function (string $id) use ($pdo): void {
    new ManagerController(new Manager($pdo))->destroy((int) $id);
});

// SF7 : Liste des missions
$router->get("/missions", function (): void {
    $dbConnection = Database::getConnection();
    (new MissionController($dbConnection))->index();
});

// SF8 : Création d'une mission
$router->post("/missions", function (): void {
    $dbConnection = Database::getConnection();
    (new MissionController($dbConnection))->store();
});

// SF7 : Afficher une mission spécifique
$router->get("/missions/{id}", function (string $id): void {
    $dbConnection = Database::getConnection();
    (new MissionController($dbConnection))->show((int) $id);
});

// SF9 : Mettre à jour une mission
$router->put("/missions/{id}", function (string $id): void {
    $dbConnection = Database::getConnection();
    (new MissionController($dbConnection))->update((int) $id);
});

// SF10 : Supprimer une mission
$router->delete("/missions/{id}", function (string $id): void {
    $dbConnection = Database::getConnection();
    (new MissionController($dbConnection))->destroy((int) $id);
});

// SF11 : Statistiques
$router->get("/missions/stats", function (): void {
    $dbConnection = Database::getConnection();
    (new MissionController($dbConnection))->stats();
});

// Route de test rapide pour les missions
$router->get("/test-mission", function (): void {
    $dbConnection = Database::getConnection();
    (new MissionController($dbConnection))->testCrud();
});

$router->post("/missions/{id}/applications", function (string $id) use (
    $pdo,
): void {
    new CandidatureController(new Candidature($pdo))->store((int) $id);
});

$router->get("/applications/me", function () use ($pdo): void {
    new CandidatureController(new Candidature($pdo))->mine();
});

$router->get("/missions/{id}/applications", function (string $id) use (
    $pdo,
): void {
    new CandidatureController(new Candidature($pdo))->forMission((int) $id);
});

$router->post("/missions/{id}/favorites", function (string $id) use (
    $pdo,
): void {
    new FavoriteController(new Favorite($pdo))->add((int) $id);
});

$router->delete("/missions/{id}/favorites", function (string $id) use (
    $pdo,
): void {
    new FavoriteController(new Favorite($pdo))->remove((int) $id);
});

$router->get("/favorites", function () use ($pdo): void {
    new FavoriteController(new Favorite($pdo))->index();
});

$router->get("/mentions-legales", function (): void {
    require __DIR__ . "/../src/Views/legals/index.php";
});

$router->dispatch($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
