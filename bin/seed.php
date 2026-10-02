<?php

declare(strict_types=1);

/**
 * Jeu de données de démonstration.
 *
 * Usage : php bin/seed.php
 *
 * Vide toutes les tables puis les remplit avec des données fictives
 * (1 admin, 3 managers, 10 freelances, 12 missions, candidatures, favoris, avis).
 * Connexion configurable par variables d'environnement : DB_HOST, DB_PORT,
 * DB_NAME, DB_USER, DB_PASS (valeurs par défaut = compose.yaml).
 */

if (PHP_SAPI !== 'cli') {
    exit("Ce script se lance en ligne de commande uniquement.\n");
}

if ((getenv('APP_ENV') ?: 'dev') === 'production') {
    exit("Refus : le seed vide la base, il est interdit en production.\n");
}

// Mot de passe commun à tous les comptes de démo (stocké haché en base).
const DEMO_PASSWORD = 'Password123!';

function env(string $key, string $default): string
{
    $value = getenv($key);

    return $value === false ? $default : $value;
}

function connect(): PDO
{
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        env('DB_HOST', '127.0.0.1'),
        env('DB_PORT', '3306'),
        env('DB_NAME', 'freelance_manager'),
    );

    return new PDO($dsn, env('DB_USER', 'root'), env('DB_PASS', ''), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}

/** Insère une ligne et retourne son id auto-incrémenté. */
function insert(PDO $pdo, string $table, array $row): int
{
    $columns = array_keys($row);
    $sql = sprintf(
        'INSERT INTO %s (%s) VALUES (%s)',
        $table,
        implode(', ', $columns),
        implode(', ', array_map(fn (string $c) => ':' . $c, $columns)),
    );
    $pdo->prepare($sql)->execute($row);

    return (int) $pdo->lastInsertId();
}

function day(int $offset): string
{
    return (new DateTimeImmutable('today'))->modify(sprintf('%+d days', $offset))->format('Y-m-d');
}

// ---------------------------------------------------------------- données

$managers = [
    ['claire.martin@entreprise.test', 'Claire', 'Martin', 'Direction technique', '01 23 45 67 01'],
    ['thomas.bernard@entreprise.test', 'Thomas', 'Bernard', 'Marketing digital', '01 23 45 67 02'],
    ['sophie.leroy@entreprise.test', 'Sophie', 'Leroy', 'Data & Sécurité', '01 23 45 67 03'],
];

$freelances = [
    ['lucas.moreau@freelance.test', 'Lucas', 'Moreau', 'Développeur web full-stack', 'Développeur PHP/JS depuis 8 ans, spécialisé dans les applications métier.', 480.00, 'available', 'Lyon'],
    ['emma.petit@freelance.test', 'Emma', 'Petit', 'Développeuse mobile', 'Applications iOS et Android, de la conception à la publication.', 520.00, 'available', 'Paris'],
    ['hugo.roux@freelance.test', 'Hugo', 'Roux', 'Expert cybersécurité', 'Audits, tests d\'intrusion et sensibilisation des équipes.', 750.00, 'busy', 'Toulouse'],
    ['lea.garcia@freelance.test', 'Léa', 'Garcia', 'Data analyst', 'Tableaux de bord, SQL et modélisation de données.', 450.00, 'available', 'Bordeaux'],
    ['nathan.faure@freelance.test', 'Nathan', 'Faure', 'Designer UX/UI', 'Maquettes, design systems et tests utilisateurs.', 420.00, 'available', 'Nantes'],
    ['chloe.david@freelance.test', 'Chloé', 'David', 'Ingénieure DevOps', 'CI/CD, conteneurs et supervision d\'infrastructures.', 650.00, 'available', 'Lille'],
    ['maxime.girard@freelance.test', 'Maxime', 'Girard', 'Pentester', 'Tests d\'intrusion web et infrastructure.', 700.00, 'available', 'Strasbourg'],
    ['camille.andre@freelance.test', 'Camille', 'André', 'Développeuse back-end', 'API REST, PHP orienté objet et bases de données.', 500.00, 'busy', 'Rennes'],
    ['antoine.lambert@freelance.test', 'Antoine', 'Lambert', 'Intégrateur front-end', 'HTML/CSS/JS, accessibilité et approche mobile first.', 380.00, 'available', 'Marseille'],
    ['manon.fontaine@freelance.test', 'Manon', 'Fontaine', 'Consultante SEO', 'Référencement naturel, performances et analyse de trafic.', 400.00, 'available', 'Montpellier'],
];

// [index manager, titre, description, budget, tjm, début (j), fin (j), lieu, statut]
$missions = [
    [0, 'Refonte du site vitrine', 'Refonte complète du site institutionnel en HTML/CSS/JS, mobile first.', 12000, 450, 15, 60, 'Télétravail', 'open'],
    [0, 'Audit de sécurité applicative', 'Audit du portail client : injections SQL, XSS, CSRF et gestion des sessions.', 9000, 700, -20, 10, 'Lyon', 'in_progress'],
    [0, 'Migration vers PostgreSQL', 'Migration de la base MySQL historique vers PostgreSQL avec reprise des données.', 15000, 600, -120, -60, 'Lyon', 'closed'],
    [1, 'Application mobile de suivi de livraisons', 'Application mobile permettant aux clients de suivre leurs livraisons en temps réel.', 30000, 550, 30, 120, 'Paris', 'open'],
    [1, 'Tableau de bord de pilotage commercial', 'Conception d\'un tableau de bord de suivi des ventes à partir de l\'entrepôt de données.', 8000, 450, -90, -45, 'Télétravail', 'closed'],
    [1, 'Design system et maquettes', 'Création d\'un design system et des maquettes de l\'extranet.', 10000, 420, 10, 50, 'Paris', 'open'],
    [1, 'API REST de facturation', 'Spécification et développement d\'une API de facturation (PHP, MySQL).', 14000, 500, 45, 100, 'Télétravail', 'draft'],
    [2, 'Pipeline de données ETL', 'Mise en place d\'un pipeline d\'import et de transformation de données.', 18000, 520, 20, 90, 'Télétravail', 'open'],
    [2, 'Tests d\'intrusion infrastructure', 'Tests d\'intrusion interne et externe du système d\'information.', 11000, 750, -100, -70, 'Toulouse', 'closed'],
    [2, 'Intégration continue GitLab CI', 'Mise en place d\'une chaîne CI/CD avec tests automatisés et déploiement.', 7000, 650, -15, 15, 'Lille', 'in_progress'],
    [2, 'Chatbot de support interne', 'Prototype de chatbot pour répondre aux questions fréquentes des collaborateurs.', 9500, 500, 25, 70, 'Télétravail', 'open'],
    [0, 'Optimisation SEO et performances', 'Audit SEO, optimisation des balises, sitemap et temps de chargement.', 5000, 400, 5, 35, 'Télétravail', 'open'],
];

// [index mission, index freelance, statut, message, tjm proposé]
$applications = [
    [2, 0, 'accepted', 'Je maîtrise les migrations MySQL vers PostgreSQL, disponible immédiatement.', 590],
    [2, 7, 'rejected', 'Intéressée par la mission, disponibilité partielle.', 500],
    [4, 3, 'accepted', 'Spécialiste des tableaux de bord décisionnels.', 450],
    [4, 9, 'rejected', 'Profil orienté SEO mais curieuse de la data.', 400],
    [8, 2, 'accepted', 'Plus de dix tests d\'intrusion réalisés en environnement similaire.', 750],
    [8, 6, 'rejected', 'Disponible dès le lancement de la mission.', 700],
    [1, 6, 'accepted', 'Expérience solide sur les audits OWASP Top 10.', 700],
    [1, 2, 'rejected', 'Intéressé mais indisponible sur la période.', 750],
    [9, 5, 'accepted', 'Je mets en place des chaînes CI/CD depuis plusieurs années.', 650],
    [9, 0, 'rejected', 'Compétences DevOps de base, ouvert à monter en charge.', 480],
    [0, 8, 'pending', 'Intégrateur mobile first, je peux commencer rapidement.', 380],
    [0, 0, 'pending', 'Je peux prendre en charge l\'intégration et le back-end.', 450],
    [0, 4, 'pending', 'Je peux apporter la partie UX en complément.', 420],
    [3, 1, 'pending', 'Développeuse mobile expérimentée, portfolio disponible.', 520],
    [3, 7, 'pending', 'Je peux concevoir l\'API associée à l\'application.', 500],
    [5, 4, 'pending', 'Création de design systems pour plusieurs entreprises.', 420],
    [5, 8, 'pending', 'Je peux assurer l\'intégration du design system.', 380],
    [7, 3, 'pending', 'Très à l\'aise avec SQL et la transformation de données.', 450],
    [7, 5, 'pending', 'Je peux industrialiser le pipeline avec de la CI/CD.', 650],
    [10, 0, 'pending', 'Intéressé par ce prototype, je propose un POC rapide.', 480],
    [11, 9, 'pending', 'Le SEO est mon cœur de métier.', 400],
    [11, 8, 'pending', 'Je peux traiter les performances front-end.', 380],
];

// [index mission, index freelance, note, commentaire] : uniquement pour les missions terminées.
$reviews = [
    [2, 0, 5, 'Migration réalisée sans perte de données, communication exemplaire.'],
    [4, 3, 4, 'Tableaux de bord clairs et livrés dans les temps.'],
    [8, 2, 5, 'Rapport détaillé et recommandations très concrètes.'],
];

// [index freelance, index mission]
$favorites = [
    [0, 0], [0, 7], [0, 10],
    [1, 3],
    [3, 7], [3, 5],
    [4, 5], [4, 0],
    [5, 7], [5, 9],
    [8, 0], [8, 11],
    [9, 11],
];

// ---------------------------------------------------------------- exécution

try {
    $pdo = connect();
} catch (PDOException $e) {
    exit('Connexion impossible : ' . $e->getMessage() . "\n");
}

// TRUNCATE provoque un commit implicite : on vide donc avant la transaction.
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
foreach (['reviews', 'favorites', 'applications', 'missions', 'managers', 'freelances', 'users'] as $table) {
    $pdo->exec("TRUNCATE TABLE $table");
}
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$passwordHash = password_hash(DEMO_PASSWORD, PASSWORD_DEFAULT);

$pdo->beginTransaction();

try {
    $newUser = fn (string $email, string $role): int => insert($pdo, 'users', [
        'email' => $email,
        'password_hash' => $passwordHash,
        'role' => $role,
        'is_active' => 1,
    ]);

    $newUser('admin@entreprise.test', 'admin');

    $managerIds = [];
    foreach ($managers as [$email, $first, $last, $department, $phone]) {
        $managerIds[] = insert($pdo, 'managers', [
            'user_id' => $newUser($email, 'manager'),
            'first_name' => $first,
            'last_name' => $last,
            'department' => $department,
            'phone' => $phone,
        ]);
    }

    $freelanceIds = [];
    foreach ($freelances as [$email, $first, $last, $title, $bio, $rate, $availability, $location]) {
        $freelanceIds[] = insert($pdo, 'freelances', [
            'user_id' => $newUser($email, 'freelance'),
            'first_name' => $first,
            'last_name' => $last,
            'title' => $title,
            'bio' => $bio,
            'daily_rate' => $rate,
            'availability' => $availability,
            'location' => $location,
        ]);
    }

    $missionIds = [];
    foreach ($missions as [$manager, $title, $description, $budget, $rate, $start, $end, $location, $status]) {
        $missionIds[] = insert($pdo, 'missions', [
            'manager_id' => $managerIds[$manager],
            'title' => $title,
            'description' => $description,
            'budget' => $budget,
            'daily_rate' => $rate,
            'start_date' => day($start),
            'end_date' => day($end),
            'location' => $location,
            'status' => $status,
        ]);
    }

    foreach ($applications as [$mission, $freelance, $status, $message, $rate]) {
        insert($pdo, 'applications', [
            'mission_id' => $missionIds[$mission],
            'freelance_id' => $freelanceIds[$freelance],
            'message' => $message,
            'proposed_rate' => $rate,
            'status' => $status,
        ]);
    }

    foreach ($reviews as [$mission, $freelance, $rating, $comment]) {
        insert($pdo, 'reviews', [
            'freelance_id' => $freelanceIds[$freelance],
            'manager_id' => $managerIds[$missions[$mission][0]],
            'mission_id' => $missionIds[$mission],
            'rating' => $rating,
            'comment' => $comment,
        ]);
    }

    foreach ($favorites as [$freelance, $mission]) {
        insert($pdo, 'favorites', [
            'freelance_id' => $freelanceIds[$freelance],
            'mission_id' => $missionIds[$mission],
        ]);
    }

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    exit('Échec du seed (annulé) : ' . $e->getMessage() . "\n");
}

printf(
    "Seed terminé : 1 admin, %d managers, %d freelances, %d missions, %d candidatures, %d avis, %d favoris.\n",
    count($managers),
    count($freelances),
    count($missions),
    count($applications),
    count($reviews),
    count($favorites),
);
echo "Comptes de démo : admin@entreprise.test, claire.martin@entreprise.test, lucas.moreau@freelance.test (mot de passe : DEMO_PASSWORD dans ce fichier)\n";
