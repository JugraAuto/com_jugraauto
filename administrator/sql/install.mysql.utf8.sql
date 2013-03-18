INSERT INTO `#__menu_types` (`menutype`,`title`,`description`) VALUES
        ('com_jugraauto','Югра-Авто','Меню для компаний справочника Югра-Авто');

DROP TABLE IF EXISTS `#__jugraauto_companies`;
CREATE TABLE IF NOT EXISTS `#__jugraauto_companies` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`city_id` TEXT NOT NULL ,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`menu_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`alias` VARCHAR(255)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`street_type` VARCHAR(20)  NOT NULL ,
`street` VARCHAR(50)  NOT NULL ,
`house` VARCHAR(10)  NOT NULL ,
`address_else` VARCHAR(50)  NOT NULL ,
`email` VARCHAR(50)  NOT NULL ,
`fio` VARCHAR(50)  NOT NULL ,
`phone` VARCHAR(100)  NOT NULL ,
`fax` VARCHAR(100)  NOT NULL ,
`logo` VARCHAR(100)  NOT NULL ,
`desc` text  NOT NULL ,
`type` TINYINT(1)  NOT NULL DEFAULT '1',
`image` VARCHAR(100)  NOT NULL ,
`pointx` VARCHAR(20)  NOT NULL ,
`pointy` VARCHAR(20)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
KEY `name` (`name`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `#__jugraauto_cities`;
CREATE TABLE IF NOT EXISTS `#__jugraauto_cities` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`name` VARCHAR(255)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
KEY `name` (`name`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO `#__jugraauto_cities` (`id`,`name`) VALUES
        (1,'г. Сургут'),
        (2,'г. Нефтеюганск'),
        (3,'г. Нижневартовск'),
        (4,'г. Югорск'),
        (5,'г. Советский'),
        (6,'г. Нягань'),
        (7,'г. Урай'),
        (8,'г. Ханты-Мансийск'),
        (9,'г. Екатеринбург'),
        (10,'г. Челябинск');

DROP TABLE IF EXISTS `#__jugraauto_companies_categories`;
CREATE TABLE IF NOT EXISTS `#__jugraauto_companies_categories` (
`company_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`category_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`path` VARCHAR(255)  NOT NULL ,
KEY `company_id` (`company_id`),
KEY `category_id` (`category_id`)
) DEFAULT COLLATE=utf8_general_ci;

