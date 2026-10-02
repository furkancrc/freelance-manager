<?php

declare(strict_types=1);

namespace App\Core;

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
/**
 * Session, authentification et protection CSRF.
 *
 * L'utilisateur connecté est stocké dans $_SESSION['user'] sous la forme
 * ['id' => int, 'email' => string, 'role' => string].
 */
<<<<<<< HEAD
=======
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
>>>>>>> dccf881 (feat: clean auth and seed)
final class Security
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
=======
                "cookie_httponly" => true,
                "cookie_samesite" => "Lax",
>>>>>>> dccf881 (feat: clean auth and seed)
=======
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
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
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
<<<<<<< HEAD
=======
=======
>>>>>>> dccf881 (feat: clean auth and seed)
        $_SESSION["user"] = [
            "id" => $user["id"],
            "email" => $user["email"],
            "role" => $user["role"],
<<<<<<< HEAD
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
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
<<<<<<< HEAD
<<<<<<< HEAD
        return $_SESSION['user'] ?? null;
=======
        return $_SESSION["user"] ?? null;
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        return $_SESSION['user'] ?? null;
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
        return $_SESSION["user"] ?? null;
>>>>>>> dccf881 (feat: clean auth and seed)
    }

    public static function isLoggedIn(): bool
    {
        return self::currentUser() !== null;
    }

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
    /** Redirige vers /login si l'utilisateur n'est pas connecté. */
    public static function requireAuth(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit;
<<<<<<< HEAD
=======
=======
>>>>>>> dccf881 (feat: clean auth and seed)
    public static function requireAuth(): void
    {
        if (!self::isLoggedIn()) {
            header("Location: /login");
            exit();
<<<<<<< HEAD
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
>>>>>>> dccf881 (feat: clean auth and seed)
        }
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
     * Vérifie que l'utilisateur est connecté ET a l'un des rôles autorisés.
     *
=======
>>>>>>> dccf881 (feat: clean auth and seed)
=======
     * Vérifie que l'utilisateur est connecté ET a l'un des rôles autorisés.
     *
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
>>>>>>> dccf881 (feat: clean auth and seed)
     * @param string[] $roles
     */
    public static function requireRole(array $roles): void
    {
        self::requireAuth();

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
        if (!in_array(self::currentUser()['role'], $roles, true)) {
            http_response_code(403);
            exit('Accès refusé : rôle insuffisant.');
        }
    }

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
    /** Variante JSON de requireAuth : renvoie 401 au lieu de rediriger. */
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
>>>>>>> f8e10eb (fix: freelance controller)
    public static function requireJsonAuth(): array
    {
        $user = self::currentUser();
        if ($user === null) {
            self::jsonError(401, 'Authentification requise.');
        }

        return $user;
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
=======
     * Variante JSON de requireRole : renvoie 401/403 au lieu de rediriger.
     *
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
=======
>>>>>>> f8e10eb (fix: freelance controller)
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

<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
>>>>>>> 92aaf3a (feat(freelance): add freelance CRUD, search and reviews (F01, #1))
    /** Génère (ou réutilise) le jeton CSRF de la session courante. */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
<<<<<<< HEAD
=======
=======
>>>>>>> dccf881 (feat: clean auth and seed)
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
<<<<<<< HEAD
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
>>>>>>> dccf881 (feat: clean auth and seed)
    }

    public static function verifyCsrf(?string $token): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        return $token !== null
            && !empty($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
=======
        return $token !== null &&
            !empty($_SESSION["csrf_token"]) &&
            hash_equals($_SESSION["csrf_token"], $token);
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        return $token !== null
            && !empty($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
        return $token !== null &&
            !empty($_SESSION["csrf_token"]) &&
            hash_equals($_SESSION["csrf_token"], $token);
>>>>>>> dccf881 (feat: clean auth and seed)
    }
}
