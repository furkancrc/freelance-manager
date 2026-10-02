<?php

declare(strict_types=1);

/**
 * Configuration de l'application, lue depuis les variables d'environnement
 * (mêmes clés et valeurs par défaut que bin/seed.php et compose.yaml).
 */

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'freelance_manager',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
    ],
    'app' => [
        'env' => getenv('APP_ENV') ?: 'dev',
    ],
];
