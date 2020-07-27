-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(250) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `detail` text,
  `status` int(11) DEFAULT '0',
  `project_name` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `project` (`id`, `year`, `create_dt`, `date_start`, `date_end`, `detail`, `status`, `project_name`) VALUES
(1,	'2563',	'2020-05-27 10:29:31',	'2020-04-01 00:00:00',	'2020-08-31 00:00:00',	'กองทุนเพื่อ SME ที่กำลังต้องการจัดหา ปรับปรุง สิ่งปลูกสร้าง (ไม่รวมการซื้อที่ดินว่างเปล่า) เครื่องจักร อุปกรณ์ พาหนะ ซอฟต์แวร์ และสิ่งอำนวยความสะดวกในกิจการ หรือกำลังมองหาเงินทุน/เงินทุนหมุนเวียน เพื่อเป็นค่าใช้จ่ายในการพัฒนากิจการ',	2,	'โครงการจัดการกองทุนพัฒนา');

-- 2020-07-03 07:57:07
