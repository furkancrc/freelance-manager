<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
require __DIR__ . '/../vendor/autoload.php';
=======
require __DIR__ . "/../vendor/autoload.php";
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31

use App\Controllers\AuthController;
use App\Controllers\CandidatureController;
use App\Controllers\FreelanceController;
<<<<<<< HEAD
<<<<<<< HEAD
=======
require __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AuthController;
<<<<<<< HEAD
>>>>>>> dccf881 (feat: clean auth and seed)
=======
use App\Controllers\ManagerController;
>>>>>>> d4d35fc (CRUD manager)
use App\Core\Router;
use App\Core\Security;
use App\Core\Database;

=======
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
=======
use App\Controllers\CandidatureController;
>>>>>>> f762781 (feat: cleanup candidature & legals)
use App\Controllers\FreelanceController;
use App\Core\Router;
use App\Core\Security;
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
require __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AuthController;
use App\Core\Router;
use App\Core\Security;
>>>>>>> dccf881 (feat: clean auth and seed)
=======
use App\Core\Database;
use App\Core\Router;
use App\Core\Security;
use App\Models\Candidature;
use App\Models\Freelance;
use App\Models\Review;
use App\Models\User;
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31

Security::startSession();

$router = new Router();

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
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

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
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
=======
>>>>>>> dccf881 (feat: clean auth and seed)
=======
$pdo = Database::getConnection();

>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
$router->get("/", function (): void {
    Security::requireAuth();
    $user = Security::currentUser();
    require __DIR__ . "/../src/Views/home/index.php";
});

<<<<<<< HEAD
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

<<<<<<< HEAD
<<<<<<< HEAD
$router->get("/test-manager", function () use ($dbConnection): void {
    $dbConnection = Database::getConnection();
    
    $controller = new \App\Controllers\ManagerController($dbConnection);
    $controller->testCreate();
});

>>>>>>> d4d35fc (CRUD manager)
=======
=======
>>>>>>> f762781 (feat: cleanup candidature & legals)
$router->post("/missions/{id}/applications", function (string $id): void {
    new CandidatureController()->store((int) $id);
});

$router->get("/applications/me", function (): void {
    new CandidatureController()->mine();
});

$router->get("/missions/{id}/applications", function (string $id): void {
    new CandidatureController()->forMission((int) $id);
=======
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
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
});

$router->get("/mentions-legales", function (): void {
    require __DIR__ . "/../src/Views/legals/index.php";
});

<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> f762781 (feat: cleanup candidature & legals)
=======
>>>>>>> f762781 (feat: cleanup candidature & legals)
$router->dispatch($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
>>>>>>> dccf881 (feat: clean auth and seed)
=======
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
$router->dispatch($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
>>>>>>> dccf881 (feat: clean auth and seed)
=======
$router->dispatch($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
