-- Adjacent Electrical & Mechanical LTD - database schema
-- Matches the connection settings used in index.php, projects.php,
-- admin/index.php and admin/admin.php.
--
-- Import (XAMPP):  mysql -u root < database.sql
-- or phpMyAdmin -> Import -> database.sql

CREATE DATABASE IF NOT EXISTS `id18698161_construction`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Application user expected by the PHP code. Replace CHANGE_ME with a strong
-- password and put the same value in config.local.php (DB_PASS).
CREATE USER IF NOT EXISTS 'id18698161_root'@'localhost' IDENTIFIED BY 'CHANGE_ME';
GRANT ALL PRIVILEGES ON `id18698161_construction`.* TO 'id18698161_root'@'localhost';
FLUSH PRIVILEGES;

USE `id18698161_construction`;

-- Projects shown on the home page carousel (latest 5) and projects page,
-- uploaded/deleted from the admin dashboard.
--   image       : file name inside /uploads
--   description : project title/description
--   date        : upload date (Y-m-d), set by admin/admin.php
CREATE TABLE IF NOT EXISTS `projects` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `image`       VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `date`        DATE NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Admin dashboard accounts. Passwords are stored as password_hash() hashes.
-- Create an account with:  php admin/create-admin.php <username> <password>
CREATE TABLE IF NOT EXISTS `admins` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(50)  NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admins_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Newsletter signups from the site footer (subscribe.php).
--   notified : 1 once the notification email reached COMPANY_EMAIL
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`      VARCHAR(254) NOT NULL,
  `notified`   TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_newsletter_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seed rows for the images already present in /uploads
INSERT INTO `projects` (`image`, `description`, `date`) VALUES
('slide1.jpg',                                 'Base Station (BTS) Installation',      '2022-04-22'),
('content-1.jpg',                              'Telecommunication Mast Erection',      '2022-04-22'),
('IMG-20220328-WA0023.jpg',                    'Tower Erection Project',               '2022-03-28'),
('IMG-20220328-WA0032.jpg',                    'Steel Work & Fabrication',             '2022-03-28'),
('104C61A0-E9A0-438F-B9C9-D53B5A89F749.jpeg',  'Microwave Dish & Radio Installation',  '2022-04-22'),
('4B703F90-CA36-47CD-B618-B3A9F386A50F.jpeg',  'Electrical Wiring Project',            '2022-04-22'),
('53CE185A-240A-43A1-ABEC-4752C9D97159.jpeg',  'Painting of Mast & Tower',             '2022-04-22'),
('A926D3B8-10FD-4430-B0A3-82514BA47773.jpeg',  'Cell Site Construction',               '2022-04-22');

-- Team members shown on team.php, managed from admin/team.php.
--   photo : file name inside /uploads (NULL shows a placeholder)
CREATE TABLE IF NOT EXISTS `team_members` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `position`   VARCHAR(100) NOT NULL,
  `photo`      VARCHAR(255) NULL,
  `facebook`   VARCHAR(255) NOT NULL DEFAULT '',
  `twitter`    VARCHAR(255) NOT NULL DEFAULT '',
  `instagram`  VARCHAR(255) NOT NULL DEFAULT '',
  `linkedin`   VARCHAR(255) NOT NULL DEFAULT '',
  `sort_order` INT          NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Client testimonials shown on testimonial.php and the home page,
-- managed from admin/testimonials.php. Only published rows are shown.
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name`  VARCHAR(100) NOT NULL,
  `client_title` VARCHAR(150) NOT NULL DEFAULT '',
  `message`      TEXT         NOT NULL,
  `rating`       TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `photo`        VARCHAR(255) NULL,
  `is_published` TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Editable company contact details (admin/contact-info.php), used by the
-- footer, contact page and newsletter notifications.
CREATE TABLE IF NOT EXISTS `site_settings` (
  `setting_key`   VARCHAR(50) NOT NULL,
  `setting_value` TEXT        NOT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('company_address', '30, Ismail Oladimeji Str, Islamic Estate, Kajola, Ogun State.'),
('company_email',   'ajacentconsultant@gmail.com'),
('phone_primary',   '08085059313'),
('phone_secondary', '09012903107'),
('whatsapp_number', '2348085059313'),
('contact_intro',   'Have a project in mind or need a quote? Send us a message and our team will get back to you promptly.'),
('map_location',    'Kajola, Ogun State, Nigeria');
