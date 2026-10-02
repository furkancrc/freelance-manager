<?php

declare(strict_types=1);

<<<<<<< HEAD
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\FreelanceController;
<<<<<<< HEAD
=======
require __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AuthController;
>>>>>>> dccf881 (feat: clean auth and seed)
=======
use App\Controllers\ManagerController;
>>>>>>> d4d35fc (CRUD manager)
use App\Core\Router;
use App\Core\Security;
use App\Core\Database;


Security::startSession();

$router = new Router();

<<<<<<< HEAD
$router->get('/', function (): void {
    Security::requireAuth();
    $user = Security::currentUser();
    require __DIR__ . '/../src/Views/home/index.php';
});

$router->get('/login', function (): void {
    (new AuthController())->showLogin();
});

$router->post('/login', function (): void {
    (new AuthController())->login();
});

$router->post('/logout', function (): void {
    (new AuthController())->logout();
});

$router->get('/freelances', function (): void {
    (new FreelanceController())->index();
});

$router->post('/freelances', function (): void {
    (new FreelanceController())->store();
});

$router->get('/freelances/{id}', function (string $id): void {
    (new FreelanceController())->show((int) $id);
});

$router->put('/freelances/{id}', function (string $id): void {
    (new FreelanceController())->update((int) $id);
});

$router->delete('/freelances/{id}', function (string $id): void {
    (new FreelanceController())->destroy((int) $id);
});

$router->post('/freelances/{id}/reviews', function (string $id): void {
    (new FreelanceController())->addReview((int) $id);
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
=======
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

<<<<<<< HEAD
=======
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

$router->get("/test-manager", function () use ($dbConnection): void {
    $dbConnection = Database::getConnection();
    
    $controller = new \App\Controllers\ManagerController($dbConnection);
    $controller->testCreate();
});

>>>>>>> d4d35fc (CRUD manager)
$router->dispatch($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
>>>>>>> dccf881 (feat: clean auth and seed)
