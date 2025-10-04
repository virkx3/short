
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(190) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `urls` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(32) NOT NULL UNIQUE,
  `long_url` TEXT NOT NULL,
  `user_id` INT NULL,
  `creator_ip` VARCHAR(45) DEFAULT NULL,
  `click_count` INT NOT NULL DEFAULT 0,
  `last_clicked_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `code_idx` (`code`),
  KEY `user_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `clicks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `url_id` INT NOT NULL,
  `ip` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(300) DEFAULT NULL,
  `referer` VARCHAR(500) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `url_idx` (`url_id`),
  KEY `ip_created_idx` (`ip`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `visits` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `ip` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(300) DEFAULT NULL,
  `path` VARCHAR(255) DEFAULT NULL,
  `referer` VARCHAR(500) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ip_created_idx` (`ip`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `action` VARCHAR(50) NOT NULL,
  `payload` TEXT,
  `ip` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `action_created_idx` (`action`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin (email/password: admin@allrcode.com / admin123)
INSERT IGNORE INTO users (name, email, password_hash, is_admin, created_at)
VALUES ('Super Admin','admin@allrcode.com','$2y$10$KIXMYCw5bOtWZjW3YHvf4uBjAa8L6Y8JtfFhS0VdVwO10pV2yB1C.',1,NOW());
