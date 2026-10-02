<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Session, authentification et protection CSRF.
 *
 * L'utilisateur connecté est stocké dans $_SESSION['user'] sous la forme
 * ['id' => int, 'email' => string, 'role' => string].
 */
final class Security
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
            ]);
        }
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function currentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function isLoggedIn(): bool
    {
        return self::currentUser() !== null;
    }

    /** Redirige vers /login si l'utilisateur n'est pas connecté. */
    public static function requireAuth(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Vérifie que l'utilisateur est connecté ET a l'un des rôles autorisés.
     *
     * @param string[] $roles
     */
    public static function requireRole(array $roles): void
    {
        self::requireAuth();

        if (!in_array(self::currentUser()['role'], $roles, true)) {
            http_response_code(403);
            exit('Accès refusé : rôle insuffisant.');
        }
    }

    /** Génère (ou réutilise) le jeton CSRF de la session courante. */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf(?string $token): bool
    {
        return $token !== null
            && !empty($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }
}
