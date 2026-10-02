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
        $this->users = new User(Database::connection());
    }

    public function showLogin(): void
    {
        if (Security::isLoggedIn()) {
            header('Location: /');
            exit;
        }

        $this->renderLogin();
    }

    public function login(): void
    {
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            $this->renderLogin('Requête invalide, merci de réessayer.');

            return;
        }

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $user = $email === '' ? null : $this->users->findByEmail($email);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            $this->renderLogin('Email ou mot de passe incorrect.');

            return;
        }

        if ((int) $user['is_active'] === 0) {
            $this->renderLogin('Ce compte est désactivé.');

            return;
        }

        Security::login($user);
        header('Location: /');
    }

    public function logout(): void
    {
        Security::logout();
        header('Location: /login');
    }

    private function renderLogin(?string $error = null): void
    {
        $csrfToken = Security::csrfToken();
        require __DIR__ . '/../Views/auth/login.php';
    }
}
