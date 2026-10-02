<?php

declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AuthController;
use App\Controllers\CandidatureController;
use App\Controllers\FreelanceController;
use App\Core\Router;
use App\Core\Security;

Security::startSession();

$router = new Router();

$router->get("/", function (): void {
    Security::requireAuth();
    $user = Security::currentUser();
    require __DIR__ . "/../src/Views/home/index.php";
});

$router->get("/login", function (): void {
    new AuthController()->showLogin();
});

$router->post("/login", function (): void {
    new AuthController()->login();
});

$router->post("/logout", function (): void {
    new AuthController()->logout();
});

$router->get("/freelances", function (): void {
    new FreelanceController()->index();
});

$router->post("/freelances", function (): void {
    new FreelanceController()->store();
});

$router->get("/freelances/{id}", function (string $id): void {
    new FreelanceController()->show((int) $id);
});

$router->put("/freelances/{id}", function (string $id): void {
    new FreelanceController()->update((int) $id);
});

$router->delete("/freelances/{id}", function (string $id): void {
    new FreelanceController()->destroy((int) $id);
});

$router->post("/freelances/{id}/reviews", function (string $id): void {
    new FreelanceController()->addReview((int) $id);
});

$router->post("/missions/{id}/applications", function (string $id): void {
    new CandidatureController()->store((int) $id);
});

$router->get("/applications/me", function (): void {
    new CandidatureController()->mine();
});

$router->get("/missions/{id}/applications", function (string $id): void {
    new CandidatureController()->forMission((int) $id);
});

$router->dispatch($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
