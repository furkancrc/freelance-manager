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
        $this->users = new User(Database::connection());
=======
        $this->users = new User(Database::getConnection());
>>>>>>> dccf881 (feat: clean auth and seed)
    }

    public function showLogin(): void
    {
        if (Security::isLoggedIn()) {
<<<<<<< HEAD
            header('Location: /');
            exit;
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
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            $this->renderLogin('Requête invalide, merci de réessayer.');
=======
        if (!Security::verifyCsrf($_POST["csrf_token"] ?? null)) {
            http_response_code(400);
            $this->renderLogin("Requête invalide, merci de réessayer.");
>>>>>>> dccf881 (feat: clean auth and seed)

            return;
        }

<<<<<<< HEAD
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $user = $email === '' ? null : $this->users->findByEmail($email);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            $this->renderLogin('Email ou mot de passe incorrect.');
=======
        $email = trim((string) ($_POST["email"] ?? ""));
        $password = (string) ($_POST["password"] ?? "");

        $user = $email === "" ? null : $this->users->findByEmail($email);

        if (
            $user === null ||
            !password_verify($password, $user["password_hash"])
        ) {
            $this->renderLogin("Email ou mot de passe incorrect.");
>>>>>>> dccf881 (feat: clean auth and seed)

            return;
        }

<<<<<<< HEAD
        if ((int) $user['is_active'] === 0) {
            $this->renderLogin('Ce compte est désactivé.');
=======
        if ((int) $user["is_active"] === 0) {
            $this->renderLogin("Ce compte est désactivé.");
>>>>>>> dccf881 (feat: clean auth and seed)

            return;
        }

        Security::login($user);
<<<<<<< HEAD
        header('Location: /');
=======
        header("Location: /");
>>>>>>> dccf881 (feat: clean auth and seed)
    }

    public function logout(): void
    {
        Security::logout();
<<<<<<< HEAD
        header('Location: /login');
=======
        header("Location: /login");
>>>>>>> dccf881 (feat: clean auth and seed)
    }

    private function renderLogin(?string $error = null): void
    {
        $csrfToken = Security::csrfToken();
<<<<<<< HEAD
        require __DIR__ . '/../Views/auth/login.php';
=======
        require __DIR__ . "/../Views/auth/login.php";
>>>>>>> dccf881 (feat: clean auth and seed)
    }
}
