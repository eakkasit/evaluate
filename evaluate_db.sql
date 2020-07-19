/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50542
 Source Host           : localhost
 Source Database       : evaluate_db

 Target Server Type    : MySQL
 Target Server Version : 50542
 File Encoding         : utf-8

 Date: 07/19/2020 16:52:52 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `evaluate_log`
-- ----------------------------
DROP TABLE IF EXISTS `evaluate_log`;
CREATE TABLE `evaluate_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสการประชุม',
  `log_action` varchar(255) NOT NULL COMMENT 'ลักษณะกิจกรรม',
  `user_id` int(11) NOT NULL COMMENT 'รหัสผู้ใช้ระบบประชุม',
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่สร้าง',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `evaluate_log`
-- ----------------------------
BEGIN;
INSERT INTO `evaluate_log` VALUES ('1', 'login', '1', '2020-07-18 15:10:14'), ('2', 'login', '1', '2020-07-19 14:56:48'), ('3', 'login', '1', '2020-07-19 15:08:03'), ('4', 'logout', '1', '2020-07-19 15:31:10'), ('5', 'login', '1', '2020-07-19 15:33:04'), ('6', 'logout', '1', '2020-07-19 15:36:17'), ('7', 'login', '1', '2020-07-19 15:37:11');
COMMIT;

-- ----------------------------
--  Table structure for `evaluate_user`
-- ----------------------------
DROP TABLE IF EXISTS `evaluate_user`;
CREATE TABLE `evaluate_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ใช้ระบบประชุม',
  `citizen_id` varchar(13) DEFAULT NULL COMMENT 'เลขบัตรประชาชน',
  `prename` varchar(100) NOT NULL COMMENT 'คำนำหน้าชื่อ',
  `name` varchar(100) NOT NULL COMMENT 'ชื่อ',
  `surname` varchar(100) NOT NULL COMMENT 'นามสกุล',
  `position_code` varchar(100) DEFAULT NULL COMMENT 'ตำแหน่ง',
  `level_code` varchar(100) DEFAULT NULL COMMENT 'ระดับ',
  `gender` enum('male','female') DEFAULT NULL COMMENT 'เพศ',
  `department` varchar(255) DEFAULT NULL COMMENT 'สังกัด',
  `email` varchar(100) DEFAULT NULL COMMENT 'อีเมล์',
  `telephone` varchar(100) DEFAULT NULL COMMENT 'หมายเลขโทรศัพท์',
  `user_status` enum('active','invoke') DEFAULT 'active' COMMENT 'สถานะผู้ใช้ระบบประชุม',
  `user_type` enum('employee','temporary') DEFAULT 'employee' COMMENT 'ประเภทผู้ใช้งาน',
  `profile_picture` varchar(100) DEFAULT NULL COMMENT 'ชื่อรูปโปรไฟล์',
  `create_date` datetime DEFAULT NULL COMMENT 'สร้างเมื่อว.ด.ป.',
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'ปรับปรุงเมื่อว.ด.ป.',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `userid_idx` (`user_id`) USING BTREE,
  KEY `czid_idx` (`citizen_id`) USING BTREE,
  KEY `name_idx` (`name`) USING BTREE,
  KEY `surname_idx` (`surname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='ตารางข้อมูลผู้ใช้การประชุม';

-- ----------------------------
--  Records of `evaluate_user`
-- ----------------------------
BEGIN;
INSERT INTO `evaluate_user` VALUES ('1', '1234567890123', '3', 'ธิดารัตน์', 'ยินดี', 'พยาบาลวิชาชีพ', 'ปฏิบัติงาน', 'female', 'กองการเจ้าหน้าที่', 'email@hotmail.com', '0812345678', 'active', 'employee', '12345678901238.jpg', '2020-05-01 13:48:33', '2020-05-31 20:58:32'), ('2', '3500500247501', '1', 'กอ', 'กินกล้วย', 'นักวิชาการ', 'ชำนาญการ', 'male', 'สำนักทะเบียน', 'mail@gmail.com', '0814721133', 'invoke', 'employee', '35005002475011.jpg', '2020-05-01 13:48:33', '2020-05-31 20:58:32'), ('3', '1209600012021', '1', 'อรรถ', 'อัตตะ', 'เจ้าพนักงานธุรการ', 'ปฏิบัติงาน', 'male', 'สำนักทะเบียน', 'Attapon@mail.com', '0811234567', 'active', 'employee', '12096000120211.jpg', '2020-05-01 13:48:33', '2020-05-31 20:58:32'), ('4', '3200400455512', '3', 'กาญจณาพร', 'นิมิตร', 'นักวิชาการคลัง', 'ชำนาญการ', 'female', 'สำนักยุทธศาสตร์และงบประมาณ', 'Sathaya@mail.com', '0953674215', 'active', 'employee', '32004004555121.jpg', '2020-05-01 13:48:33', '2020-05-31 20:58:32'), ('5', '3331000854029', '1', 'ชัยวัฒน์', 'พันธ์พราย', 'นักบริหารงานทั่วไป', '', 'male', 'กองการเจ้าหน้าที่', 'Chalermchai@mail.com', '0967458154', 'active', 'employee', '33310008540291.jpg', '2020-05-01 13:48:33', '2020-05-31 20:58:32'), ('6', '3760500957272', '1', 'นราธิป', 'ผลกิจการ', 'นักบริหารงานทั่วไป', '', 'male', 'สำนักยุทธศาสตร์และงบประมาณ', 'Nara@mail.com', '0975136547', 'active', 'employee', '37605009572721.jpg', '2020-05-01 13:48:33', '2020-05-31 20:58:32'), ('7', '3900200040975', '2', 'ภัสสร', 'ทิพย์กอง', 'นักวิเคราะห์นโยบายฯ', 'ชำนาญการ', 'female', 'สำนักทะเบียน', 'Nopphatsorn@mail.com', '0995879431', 'active', 'employee', '39002000409751.jpg', '2020-05-01 13:48:33', '2020-05-31 20:58:32'), ('8', '9868994007110', '2', 'กัญจน์', 'จันทรโสภากุล', 'พนักงาน', '-', 'female', '-', 'kann@gmail.com', '0985241254', 'active', 'employee', '12356874215361.jpg', '2020-05-16 13:23:55', '2020-05-31 20:58:32'), ('9', '8425574483655', '3', 'อวสร', 'ภัททกิจโภคิน', 'พนักงาน', '-', 'female', '-', 'avasorn@gmail.com', '0975486752', 'active', 'employee', '12345678912343.jpg', '2020-05-16 13:27:06', '2020-05-31 20:58:32'), ('10', '6721878409746', '2', 'อนณ', 'ศิริกิจวัชรโชติ', 'พนักงาน', '-', 'female', '-', 'anon@gmail.com', '0879845687', 'active', 'employee', '12345678912342.jpg', '2020-05-16 13:28:14', '2020-05-31 20:58:32'), ('11', '7944121421506', '1', 'สพล', 'ธนะปรีดากุล', 'พนักงาน', 'ปฏิบัติการ', 'male', '', 'saponl@hotmail.com', '0987456587', 'active', 'employee', '112115684312520.jpg', '2020-05-16 13:31:16', '2020-05-31 20:58:32'), ('12', '4234145326297', '2', 'วรมน', 'ภัทรโสภณภักดี', 'พนักงาน', '', 'female', '', 'voramon@gmail.com', '0254125412', 'active', 'employee', '4234145326297.jpg', '2020-05-16 15:05:10', '2020-05-31 20:58:32'), ('13', '8532705667882', '1', 'อรรถนนท์', 'สิทธิโชควรสกุล', 'ผู้จัดการแผนกการเงิน', '', 'male', '', 'eat@gmail.com', '0894564756', 'active', 'employee', '1254874521557.jpg', '2020-05-18 10:27:16', '2020-05-31 20:58:32'), ('14', '1776989200431', '1', 'วรชน', 'ธนทรัพย์ปรีชา', 'ผู้จัดการขายภาค', '', 'male', '', 'vorachonkonnarak@hotmail.com', '0945485157', 'active', 'employee', '112115684312519.jpg', '2020-05-18 11:28:03', '2020-05-31 20:58:32'), ('15', '3305062295221', '1', 'รมย์', 'ธนทรัพย์ปรีชา', 'ผู้จัดการทีมขาย', '', 'male', '', 'rom@hotmail.com', '0678451238', 'active', 'employee', '3305062295221.jpg', '2020-05-18 11:28:34', '2020-05-31 20:58:32'), ('16', '4511611038161', '2', 'กณิกา', 'ภักดีวัชรสกุล', 'นักบัญชี', '', 'female', '', 'email@hotmail.com', '0565231456', 'active', 'employee', '112115684312512.jpg', '2020-05-18 11:35:36', '2020-05-31 20:58:32'), ('17', '1640600298425', '3', 'กัญญารัตน์', 'โสภณวรภัทรกุล', 'SA', '', 'female', '', 'gypso@gmail.com', '0971560206', 'active', 'employee', '1640600298425.jpg', '2020-05-18 11:36:19', '2020-05-31 20:58:32'), ('18', '4761209118307', '1', 'กฤตมุข', 'ปัญจรักษ์โภคิน', 'ผู้จัดการแผนก QA', '', 'male', '', 'kitti@hotmail.com', '0864259784', 'active', 'employee', '4761209118307.jpg', '2020-05-18 11:37:01', '2020-05-31 20:58:32'), ('19', '1121156843125', '2', 'ผ่องนภา', 'ผิวจันทร์', 'พนักงานปฏิบัติการ', 'ระดับผู้เชี่ยวชาญ', 'female', '', 'pongnapa@hotmail.com', '0921483649', 'invoke', 'employee', '112115684312517.jpg', '2020-05-18 11:38:57', '2020-05-31 20:58:32'), ('20', '1121156843125', '1', 'รัฐการ', 'วุฒิศักดิ์', 'นักบริหารงานทั่วไป', 'พนักงานปฏิบัติการ', 'male', 'สำนักทะเบียน', 'zxlove_346@gmail.com', '0533493698', 'active', 'employee', '112115684312516.jpg', '2020-05-18 11:39:34', '2020-05-31 20:58:32'), ('21', '1121156843125', '1', 'ณัฐชัย', 'มนตรีสร', 'นักวิชาการ', 'ระดับผู้เชี่ยวชาญ', 'male', '', 'nathachai@gmail.com', '0984573468', 'active', 'employee', '112115684312513.jpg', '2020-05-18 11:40:10', '2020-05-31 20:58:32'), ('22', '1121156843125', '3', 'นารีรัตน์', 'บุตรทอง', 'นักวิชาการเงินและบัญชี', 'พนักงานปฏิบัติการ', 'female', '', 'nareerut@gmail.com', '0872583647', 'active', 'employee', '112115684312515.jpg', '2020-05-18 11:40:36', '2020-05-31 20:58:32'), ('23', '7342513640629', '1', 'หนึ่งเดียว', 'ขจรพงษ์สกุล', 'ผู้ช่วยผู้จัดการแผนกขาย', '', 'male', '', 'onemillio@gmail.com', '0565231456', 'active', 'employee', '7342513640629.jpg', '2020-05-18 12:17:20', '2020-05-31 20:58:32'), ('24', '1992212915144', '1', 'ต้องครรลอง', 'เกียรติขจรพงษ์', 'ผู้จัดการฝ่ายขาย', '', 'male', '', 'tongkanlong.1994@testmail.com', '0987654321', 'active', 'employee', '1992212915144.jpg', '2020-05-26 12:04:55', '2020-05-31 20:58:32'), ('25', '1509909994693', '1', 'ปิยชนม์', 'คำเป็ง', 'นักวิชาการคอมพิวเตอร์', 'ชำนาญการ', 'male', 'กระทรวงกลาโหม', 'piyachon@email.co.th', '0956831149', 'active', 'employee', '1509909994693.jpg', '2020-05-26 14:12:01', '2020-05-31 20:58:32'), ('26', '1509901234567', '3', 'แสนสวย', 'มากมาย', 'นักชิวาการ', 'ปฎิบัติการ', 'female', 'จุฬาลงกรณ์มหาวิทยาลัย', 'test@meeting.com', '0123456789', 'active', 'temporary', '', '2020-06-07 23:54:27', '2020-06-08 13:24:54'), ('27', '', '3', 'สุดสวย', 'มากมี', 'นักวิชาการ', 'ปฎิบัติการ', 'female', 'จุฬาลงกรณ์มหาวิทยาลัย', 'test2@meeting.com', '0987654321', 'active', 'temporary', '', '2020-06-07 23:54:27', '2020-06-08 13:24:54');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
