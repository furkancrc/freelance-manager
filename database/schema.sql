-- Schéma de la base : plateforme de gestion des missions freelance.
-- Usage : mysql -u root < database/schema.sql   (puis : php bin/seed.php)

CREATE DATABASE IF NOT EXISTS freelance_manager
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE freelance_manager;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reviews, favorites, applications, missions, managers, freelances, users;
SET FOREIGN_KEY_CHECKS = 1;

-- Comptes et authentification (SF1)
CREATE TABLE users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('admin', 'manager', 'freelance') NOT NULL,
  is_active     TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Profil freelance (SF2 à SF5)
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

-- Profil manager (SF12)
CREATE TABLE managers (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED NOT NULL UNIQUE,
  first_name VARCHAR(100) NOT NULL,
  last_name  VARCHAR(100) NOT NULL,
  department VARCHAR(100),
  phone      VARCHAR(30),
  CONSTRAINT fk_managers_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Missions (SF7 à SF11)
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

-- Candidatures (SF13 à SF15) : un freelance ne postule qu'une fois par mission
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

-- Favoris (SF16 à SF18)
CREATE TABLE favorites (
  freelance_id INT UNSIGNED NOT NULL,
  mission_id   INT UNSIGNED NOT NULL,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (freelance_id, mission_id),
  CONSTRAINT fk_favorites_freelance FOREIGN KEY (freelance_id) REFERENCES freelances (id) ON DELETE CASCADE,
  CONSTRAINT fk_favorites_mission   FOREIGN KEY (mission_id)   REFERENCES missions (id)   ON DELETE CASCADE
) ENGINE=InnoDB;

-- Évaluation d'un freelance par un manager, pour une mission (SF6)
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
