-- Adminer 4.7.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(64) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categorie` (`id`, `nom`, `description`) VALUES
(1,	'bio',	'sandwichs ingrédients bio et locaux'),
(2,	'végétarien',	'sandwichs végétariens - peuvent contenir des produits laitiers'),
(3,	'traditionnel',	'sandwichs traditionnels : jambon, pâté, poulet etc ..'),
(4,	'chaud',	'sandwichs chauds : américain, burger, '),
(5,	'veggie',	'100% Veggie'),
(16,	'world',	'Tacos, nems, burritos, nos sandwichs du monde entier'),
(17,	'maroc',	'categorie des sandwich marocains');

DROP TABLE IF EXISTS `sand2cat`;
CREATE TABLE `sand2cat` (
  `sand_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sand2cat` (`sand_id`, `cat_id`) VALUES
(4,	3),
(4,	4),
(5,	3),
(5,	1),
(6,	4),
(6,	16),
(12,	3),
(12,	17);

DROP TABLE IF EXISTS `sandwich`;
CREATE TABLE `sandwich` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `type_pain` text NOT NULL,
  `img` text DEFAULT NULL,
  `prix` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sandwich` (`id`, `nom`, `description`, `type_pain`, `img`, `prix`) VALUES
(4,	'le bucheron',	'un sandwich de bucheron : frites, fromage, saucisse, steack, lard grillé, mayo',	'baguette campagne',	NULL,	6.00),
(5,	'jambon-beurre',	'le jambon-beurre traditionnel, avec des cornichons',	'baguette',	NULL,	5.25),
(6,	'fajitas poulet',	'fajitas au poulet avec ses tortillas de mais, comme à Puebla',	'tortillas',	NULL,	6.50),
(7,	'le forestier',	'un bon sandwich au gout de la forêt',	'pain complet',	NULL,	6.75),
(12,	'couscous',	'couscous maroc',	'baguette',	NULL,	13.50);

DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `staff` (`id`, `fullname`, `email`, `password`) VALUES
(1,	'mouad mounach',	'mouad@gmail.com',	'$2y$10$J0Ca5v0C2isyVuXE7MHGs.c.cABxuDgd/Cefc6cQPvxGAnXW2uli6');

-- 2019-03-09 21:47:30