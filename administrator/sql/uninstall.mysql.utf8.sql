DELETE FROM `#__menu_types` WHERE `menutype` = 'com_jugraauto';
DELETE FROM `#__menu` WHERE `menutype` = 'com_jugraauto';
DROP TABLE IF EXISTS `#__jugraauto_companies`;
DROP TABLE IF EXISTS `#__jugraauto_cities`;
