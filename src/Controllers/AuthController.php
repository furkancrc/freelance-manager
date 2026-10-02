<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Security;
use App\Models\User;

final class AuthController
{
    private User $users;

    public function __construct()
    {
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        $this->users = new User(Database::connection());
=======
        $this->users = new User(Database::getConnection());
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        $this->users = new User(Database::connection());
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
        $this->users = new User(Database::getConnection());
>>>>>>> dccf881 (feat: clean auth and seed)
    }

    public function showLogin(): void
    {
        if (Security::isLoggedIn()) {
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
            header('Location: /');
            exit;
=======
            header("Location: /");
            exit();
>>>>>>> dccf881 (feat: clean auth and seed)
=======
            header('Location: /');
            exit;
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
            header("Location: /");
            exit();
>>>>>>> dccf881 (feat: clean auth and seed)
        }

        $this->renderLogin();
    }

    public function login(): void
    {
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            $this->renderLogin('Requête invalide, merci de réessayer.');
=======
        if (!Security::verifyCsrf($_POST["csrf_token"] ?? null)) {
            http_response_code(400);
            $this->renderLogin("Requête invalide, merci de réessayer.");
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            $this->renderLogin('Requête invalide, merci de réessayer.');
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
        if (!Security::verifyCsrf($_POST["csrf_token"] ?? null)) {
            http_response_code(400);
            $this->renderLogin("Requête invalide, merci de réessayer.");
>>>>>>> dccf881 (feat: clean auth and seed)

            return;
        }

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $user = $email === '' ? null : $this->users->findByEmail($email);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            $this->renderLogin('Email ou mot de passe incorrect.');
<<<<<<< HEAD
=======
=======
>>>>>>> dccf881 (feat: clean auth and seed)
        $email = trim((string) ($_POST["email"] ?? ""));
        $password = (string) ($_POST["password"] ?? "");

        $user = $email === "" ? null : $this->users->findByEmail($email);

        if (
            $user === null ||
            !password_verify($password, $user["password_hash"])
        ) {
            $this->renderLogin("Email ou mot de passe incorrect.");
<<<<<<< HEAD
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
>>>>>>> dccf881 (feat: clean auth and seed)

            return;
        }

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        if ((int) $user['is_active'] === 0) {
            $this->renderLogin('Ce compte est désactivé.');
=======
        if ((int) $user["is_active"] === 0) {
            $this->renderLogin("Ce compte est désactivé.");
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        if ((int) $user['is_active'] === 0) {
            $this->renderLogin('Ce compte est désactivé.');
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
        if ((int) $user["is_active"] === 0) {
            $this->renderLogin("Ce compte est désactivé.");
>>>>>>> dccf881 (feat: clean auth and seed)

            return;
        }

        Security::login($user);
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        header('Location: /');
=======
        header("Location: /");
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        header('Location: /');
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
        header("Location: /");
>>>>>>> dccf881 (feat: clean auth and seed)
    }

    public function logout(): void
    {
        Security::logout();
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        header('Location: /login');
=======
        header("Location: /login");
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        header('Location: /login');
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
        header("Location: /login");
>>>>>>> dccf881 (feat: clean auth and seed)
    }

    private function renderLogin(?string $error = null): void
    {
        $csrfToken = Security::csrfToken();
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        require __DIR__ . '/../Views/auth/login.php';
=======
        require __DIR__ . "/../Views/auth/login.php";
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        require __DIR__ . '/../Views/auth/login.php';
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
        require __DIR__ . "/../Views/auth/login.php";
>>>>>>> dccf881 (feat: clean auth and seed)
    }
}
