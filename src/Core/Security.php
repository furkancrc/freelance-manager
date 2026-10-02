<?php

declare(strict_types=1);

namespace App\Core;

<<<<<<< HEAD
/**
 * Session, authentification et protection CSRF.
 *
 * L'utilisateur connecté est stocké dans $_SESSION['user'] sous la forme
 * ['id' => int, 'email' => string, 'role' => string].
 */
=======
>>>>>>> dccf881 (feat: clean auth and seed)
final class Security
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
<<<<<<< HEAD
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
=======
                "cookie_httponly" => true,
                "cookie_samesite" => "Lax",
>>>>>>> dccf881 (feat: clean auth and seed)
            ]);
        }
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);
<<<<<<< HEAD
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
=======
        $_SESSION["user"] = [
            "id" => $user["id"],
            "email" => $user["email"],
            "role" => $user["role"],
>>>>>>> dccf881 (feat: clean auth and seed)
        ];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function currentUser(): ?array
    {
<<<<<<< HEAD
        return $_SESSION['user'] ?? null;
=======
        return $_SESSION["user"] ?? null;
>>>>>>> dccf881 (feat: clean auth and seed)
    }

    public static function isLoggedIn(): bool
    {
        return self::currentUser() !== null;
    }

<<<<<<< HEAD
    /** Redirige vers /login si l'utilisateur n'est pas connecté. */
    public static function requireAuth(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit;
=======
    public static function requireAuth(): void
    {
        if (!self::isLoggedIn()) {
            header("Location: /login");
            exit();
>>>>>>> dccf881 (feat: clean auth and seed)
        }
    }

    /**
<<<<<<< HEAD
     * Vérifie que l'utilisateur est connecté ET a l'un des rôles autorisés.
     *
=======
>>>>>>> dccf881 (feat: clean auth and seed)
     * @param string[] $roles
     */
    public static function requireRole(array $roles): void
    {
        self::requireAuth();

<<<<<<< HEAD
        if (!in_array(self::currentUser()['role'], $roles, true)) {
            http_response_code(403);
            exit('Accès refusé : rôle insuffisant.');
        }
    }

    public static function requireJsonAuth(): array
    {
        $user = self::currentUser();
        if ($user === null) {
            self::jsonError(401, 'Authentification requise.');
        }

        return $user;
    }

    /**
     * @param string[] $roles
     */
    public static function requireJsonRole(array $roles): array
    {
        $user = self::requireJsonAuth();

        if (!in_array($user['role'], $roles, true)) {
            self::jsonError(403, 'Rôle insuffisant.');
        }

        return $user;
    }

    private static function jsonError(int $status, string $message): never
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
        exit;
    }

    /** Génère (ou réutilise) le jeton CSRF de la session courante. */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
=======
        if (!in_array(self::currentUser()["role"], $roles, true)) {
            http_response_code(403);
            exit("Accès refusé : rôle insuffisant.");
        }
    }

    public static function csrfToken(): string
    {
        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }

        return $_SESSION["csrf_token"];
>>>>>>> dccf881 (feat: clean auth and seed)
    }

    public static function verifyCsrf(?string $token): bool
    {
<<<<<<< HEAD
        return $token !== null
            && !empty($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
=======
        return $token !== null &&
            !empty($_SESSION["csrf_token"]) &&
            hash_equals($_SESSION["csrf_token"], $token);
>>>>>>> dccf881 (feat: clean auth and seed)
    }
}
