<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class User
{
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    public function __construct(private PDO $pdo)
    {
    }
=======
    public function __construct(private PDO $pdo) {}
>>>>>>> dccf881 (feat: clean auth and seed)
=======
    public function __construct(private PDO $pdo)
    {
    }
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
    public function __construct(private PDO $pdo) {}
>>>>>>> dccf881 (feat: clean auth and seed)

    /** @return array{id: int, email: string, password_hash: string, role: string, is_active: int}|null */
    public function findByEmail(string $email): ?array
    {
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
=======
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(["email" => $email]);
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(["email" => $email]);
>>>>>>> dccf881 (feat: clean auth and seed)

=======
    public function __construct(private PDO $pdo) {}

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(["email" => $email]);
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    public function findById(int $id): ?array
    {
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
=======
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(["id" => $id]);
>>>>>>> dccf881 (feat: clean auth and seed)
=======
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(["id" => $id]);
>>>>>>> dccf881 (feat: clean auth and seed)

=======
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(["id" => $id]);
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }
}
