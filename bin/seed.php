<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use App\Core\Database;

function insert(PDO $pdo, string $table, array $row): int
{
    $columns = array_keys($row);
    $sql = sprintf(
        "INSERT INTO %s (%s) VALUES (%s)",
        $table,
        implode(", ", $columns),
        implode(", ", array_map(fn(string $c) => ":" . $c, $columns)),
    );
    $pdo->prepare($sql)->execute($row);

    return (int) $pdo->lastInsertId();
}

function day(int $offset): string
{
    return new DateTimeImmutable("today")
        ->modify(sprintf("%+d days", $offset))
        ->format("Y-m-d");
}

try {
    $pdo = Database::getConnection();
} catch (PDOException $e) {
    exit("Error while connecting to database : " . $e->getMessage() . "\n");
}

$schemaSql = <<<SQL
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reviews, favorites, applications, missions, managers, freelances, users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('admin', 'manager', 'freelance') NOT NULL,
  is_active     TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE freelances (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id      INT UNSIGNED NOT NULL UNIQUE,
  first_name   VARCHAR(100) NOT NULL,
  last_name    VARCHAR(100) NOT NULL,
  title        VARCHAR(150),
  bio          TEXT,
  daily_rate   DECIMAL(8,2),
  availability ENUM('available', 'busy') NOT NULL DEFAULT 'available',
  location     VARCHAR(100),
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_freelances_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE managers (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED NOT NULL UNIQUE,
  first_name VARCHAR(100) NOT NULL,
  last_name  VARCHAR(100) NOT NULL,
  department VARCHAR(100),
  phone      VARCHAR(30),
  CONSTRAINT fk_managers_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE missions (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  manager_id  INT UNSIGNED NOT NULL,
  title       VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  budget      DECIMAL(10,2),
  daily_rate  DECIMAL(8,2),
  start_date  DATE,
  end_date    DATE,
  location    VARCHAR(100),
  status      ENUM('draft', 'open', 'in_progress', 'closed') NOT NULL DEFAULT 'open',
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_missions_status (status),
  CONSTRAINT fk_missions_manager FOREIGN KEY (manager_id) REFERENCES managers (id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE applications (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  mission_id    INT UNSIGNED NOT NULL,
  freelance_id  INT UNSIGNED NOT NULL,
  message       TEXT,
  proposed_rate DECIMAL(8,2),
  status        ENUM('pending', 'accepted', 'rejected') NOT NULL DEFAULT 'pending',
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_application (mission_id, freelance_id),
  CONSTRAINT fk_applications_mission   FOREIGN KEY (mission_id)   REFERENCES missions (id)   ON DELETE CASCADE,
  CONSTRAINT fk_applications_freelance FOREIGN KEY (freelance_id) REFERENCES freelances (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE favorites (
  freelance_id INT UNSIGNED NOT NULL,
  mission_id   INT UNSIGNED NOT NULL,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (freelance_id, mission_id),
  CONSTRAINT fk_favorites_freelance FOREIGN KEY (freelance_id) REFERENCES freelances (id) ON DELETE CASCADE,
  CONSTRAINT fk_favorites_mission   FOREIGN KEY (mission_id)   REFERENCES missions (id)   ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE reviews (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  freelance_id INT UNSIGNED NOT NULL,
  manager_id   INT UNSIGNED NOT NULL,
  mission_id   INT UNSIGNED NOT NULL,
  rating       TINYINT UNSIGNED NOT NULL,
  comment      TEXT,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_review (freelance_id, mission_id),
  CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5),
  CONSTRAINT fk_reviews_freelance FOREIGN KEY (freelance_id) REFERENCES freelances (id) ON DELETE CASCADE,
  CONSTRAINT fk_reviews_manager   FOREIGN KEY (manager_id)   REFERENCES managers (id)   ON DELETE CASCADE,
  CONSTRAINT fk_reviews_mission   FOREIGN KEY (mission_id)   REFERENCES missions (id)   ON DELETE CASCADE
) ENGINE=InnoDB;
SQL;

try {
    $pdo->exec($schemaSql);
} catch (PDOException $e) {
    exit("Error while creating schema : " . $e->getMessage() . "\n");
}

$passwordHash = password_hash("Password123!", PASSWORD_DEFAULT);

$pdo->beginTransaction();

try {
    insert($pdo, "users", [
        "email" => "admin@viacesi.fr",
        "password_hash" => $passwordHash,
        "role" => "admin",
    ]);

    $manager1UserId = insert($pdo, "users", [
        "email" => "manager@viacesi.fr",
        "password_hash" => $passwordHash,
        "role" => "manager",
    ]);
    $manager1Id = insert($pdo, "managers", [
        "user_id" => $manager1UserId,
        "first_name" => "Test",
        "last_name" => "Test",
        "department" => "CESI",
        "phone" => "0601020304",
    ]);

    $manager2UserId = insert($pdo, "users", [
        "email" => "j.dupont@viacesi.fr",
        "password_hash" => $passwordHash,
        "role" => "manager",
    ]);
    $manager2Id = insert($pdo, "managers", [
        "user_id" => $manager2UserId,
        "first_name" => "Jean",
        "last_name" => "Dupont",
        "department" => "Marketing",
        "phone" => "0699887766",
    ]);

    $freelance1UserId = insert($pdo, "users", [
        "email" => "freelance@viacesi.fr",
        "password_hash" => $passwordHash,
        "role" => "freelance",
    ]);
    $freelance1Id = insert($pdo, "freelances", [
        "user_id" => $freelance1UserId,
        "first_name" => "Alice",
        "last_name" => "Martin",
        "title" => "Développeuse Web Fullstack",
        "bio" =>
            "Spécialiste PHP/Symfony et architecture MVC avec 5 ans d'expérience.",
        "daily_rate" => 450.0,
        "availability" => "available",
        "location" => "Paris",
    ]);

    $freelance2UserId = insert($pdo, "users", [
        "email" => "b.dubois@freelance.fr",
        "password_hash" => $passwordHash,
        "role" => "freelance",
    ]);
    $freelance2Id = insert($pdo, "freelances", [
        "user_id" => $freelance2UserId,
        "first_name" => "Bob",
        "last_name" => "Dubois",
        "title" => "Expert DevOps & Sécurité",
        "bio" =>
            "Mise en place de pipelines CI/CD, audits de sécurité et conteneurisation.",
        "daily_rate" => 600.0,
        "availability" => "busy",
        "location" => "Lyon",
    ]);

    $freelance3UserId = insert($pdo, "users", [
        "email" => "c.lemaire@freelance.fr",
        "password_hash" => $passwordHash,
        "role" => "freelance",
    ]);
    $freelance3Id = insert($pdo, "freelances", [
        "user_id" => $freelance3UserId,
        "first_name" => "Clara",
        "last_name" => "Lemaire",
        "title" => "Intégratrice & UI/UX Designer",
        "bio" =>
            "Création de maquettes Figma et intégration web pixel-perfect.",
        "daily_rate" => 380.0,
        "availability" => "available",
        "location" => "Bordeaux",
    ]);

    $mission1Id = insert($pdo, "missions", [
        "manager_id" => $manager1Id,
        "title" => "Refonte de l'API Interne (PHP 8)",
        "description" =>
            "Modernisation de notre API historique vers une architecture REST propre en PHP 8.",
        "budget" => 12000.0,
        "daily_rate" => 450.0,
        "start_date" => day(15),
        "end_date" => day(45),
        "location" => "Télétravail",
        "status" => "open",
    ]);

    $mission2Id = insert($pdo, "missions", [
        "manager_id" => $manager2Id,
        "title" => "Création d'une Landing Page dynamique",
        "description" =>
            "Design et intégration d'une page promotionnelle pour notre nouveau produit.",
        "budget" => 3000.0,
        "daily_rate" => 400.0,
        "start_date" => day(-5),
        "end_date" => day(10),
        "location" => "Paris",
        "status" => "in_progress",
    ]);

    $mission3Id = insert($pdo, "missions", [
        "manager_id" => $manager1Id,
        "title" => "Audit de sécurité infrastructure",
        "description" =>
            "Analyse complète des failles potentielles sur nos serveurs de production.",
        "budget" => 8000.0,
        "daily_rate" => 650.0,
        "start_date" => day(-60),
        "end_date" => day(-30),
        "location" => "Lyon",
        "status" => "closed",
    ]);

    insert($pdo, "applications", [
        "mission_id" => $mission1Id,
        "freelance_id" => $freelance1Id,
        "message" =>
            "Bonjour, experte en PHP, je suis très intéressée par la refonte de votre API.",
        "proposed_rate" => 450.0,
        "status" => "pending",
    ]);

    insert($pdo, "applications", [
        "mission_id" => $mission2Id,
        "freelance_id" => $freelance3Id,
        "message" => "Je peux m'occuper du design et de l'intégration web !",
        "proposed_rate" => 380.0,
        "status" => "accepted",
    ]);

    insert($pdo, "applications", [
        "mission_id" => $mission3Id,
        "freelance_id" => $freelance2Id,
        "message" => "Je suis disponible immédiatement pour l'audit complet.",
        "proposed_rate" => 600.0,
        "status" => "accepted",
    ]);

    insert($pdo, "favorites", [
        "freelance_id" => $freelance1Id,
        "mission_id" => $mission2Id,
    ]);

    insert($pdo, "favorites", [
        "freelance_id" => $freelance3Id,
        "mission_id" => $mission1Id,
    ]);

    insert($pdo, "reviews", [
        "freelance_id" => $freelance2Id,
        "manager_id" => $manager1Id,
        "mission_id" => $mission3Id,
        "rating" => 5,
        "comment" =>
            "Excellent travail, rapport très détaillé et sécurisation optimale du serveur.",
    ]);

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    exit(
        "Error while seeding (no data has been injected) : " .
            $e->getMessage() .
            "\n"
    );
}
