<?php

declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AuthController;
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

$router->dispatch($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
