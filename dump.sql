CREATE DATABASE IF NOT EXISTS `enterra` DEFAULT CHARACTER SET utf8mb4;

USE `enterra`;

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `value` tinytext NOT NULL,
  `author_id` int(11) NOT NULL,
  `create_at` date NOT NULL,
  `update_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`,`first_name`,`last_name`,`role`) VALUES (1,'Eugeny','Samoilov','admin');