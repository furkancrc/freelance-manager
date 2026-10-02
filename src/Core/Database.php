<?php

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Fournit une connexion PDO unique (partagée) à la base MySQL,
 * configurée via config/config.php.
 */
final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/config.php';
            $db = $config['db'];

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $db['host'],
                $db['port'],
                $db['name'],
            );

            self::$connection = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return self::$connection;
<<<<<<< HEAD
=======
=======
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = "database";
            $dbName = "freelance_manager";
            $user = "root";
            $password = "";

            $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $user, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("Error while connecting to database : " . $e->getMessage());
            }
        }

        return self::$instance;
<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
>>>>>>> dccf881 (feat: clean auth and seed)
=======
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
    }
}
