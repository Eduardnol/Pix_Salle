SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `pixsalle`;
USE `pixsalle`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`
(
    `id`        INT                                                     NOT NULL AUTO_INCREMENT,
    `email`     VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `password`  VARCHAR(255)                                            NOT NULL,
    `createdAt` DATETIME                                                NOT NULL,
    `updatedAt` DATETIME                                                NOT NULL,
    `userName`  VARCHAR(255) DEFAULT NULL,
    `phone`     VARCHAR(255) DEFAULT NULL,
    `picture`   VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images`
(
    `id`        INT                                                     NOT NULL AUTO_INCREMENT,
    `userId`    INT                                                     NOT NULL,
    `imagePath` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `albumId`   INT                                                     NOT NULL,

    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

# DROP TABLE IF EXISTS `money`;
CREATE TABLE `money`
(
    `id`        INT      NOT NULL AUTO_INCREMENT,
    `userId`    INT      NOT NULL,
    `quantity`  INT      NOT NULL,
    `createdAt` DATETIME NOT NULL,
    `updatedAt` DATETIME NOT NULL,
    PRIMARY KEY (`id`, `userId`),
    FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

# Create a table to store the memberships of each user
DROP TABLE IF EXISTS `memberships`;
CREATE TABLE `memberships`
(
    `id`        INT      NOT NULL AUTO_INCREMENT,
    `userId`    INT      NOT NULL,
    `isActive`  BOOLEAN  NOT NULL,
    `createdAt` DATETIME NOT NULL,
    `updatedAt` DATETIME NOT NULL,
    PRIMARY KEY (`id`, `userId`),
    FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `portfolios`;
CREATE TABLE `portfolios`
(
    `id`        INT                                                     NOT NULL AUTO_INCREMENT,
    `userId`    INT                                                     NOT NULL,
    `title`     VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `createdAt` DATETIME                                                NOT NULL,
    `updatedAt` DATETIME                                                NOT NULL,
    PRIMARY KEY (`id`, `userId`),
    FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `album`;
CREATE TABLE `album`
(
    `id`          INT                                                     NOT NULL AUTO_INCREMENT,
    `portfolioId` INT                                                     NOT NULL,
    `title`       VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `createdAt`   DATETIME                                                NOT NULL,
    `updatedAt`   DATETIME                                                NOT NULL,
    PRIMARY KEY (`id`, `portfolioId`),
    FOREIGN KEY (`portfolioId`)
        REFERENCES `portfolios` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs`
(
    `id`        INT                                                     NOT NULL AUTO_INCREMENT,
    `title`     VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `userId`    INT                                                     NOT NULL,
    `content`   VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `createdAt` DATETIME                                                NOT NULL,
    `updatedAt` DATETIME                                                NOT NULL,
    PRIMARY KEY (`id`, `userId`),
    FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
