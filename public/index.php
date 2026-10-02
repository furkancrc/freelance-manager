<?php

declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AuthController;
use App\Controllers\CandidatureController;
use App\Controllers\FreelanceController;
use App\Core\Database;
use App\Core\Router;
use App\Core\Security;
use App\Models\Candidature;
use App\Models\Freelance;
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

$router->get("/mentions-legales", function (): void {
    require __DIR__ . "/../src/Views/legals/index.php";
});

$router->dispatch($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
