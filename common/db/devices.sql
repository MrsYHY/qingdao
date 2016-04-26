/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50710
Source Host           : localhost:3306
Source Database       : luck_draw

Target Server Type    : MYSQL
Target Server Version : 50710
File Encoding         : 65001

Date: 2016-04-26 12:42:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for devices
-- ----------------------------
DROP TABLE IF EXISTS `devices`;
CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_name` varchar(255) DEFAULT NULL COMMENT '设备名称',
  `device_keyword` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shake_num` int(5) DEFAULT NULL,
  `sale_name` varchar(255) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL COMMENT '大区id',
  PRIMARY KEY (`id`,`device_keyword`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of devices
-- ----------------------------
INSERT INTO `devices` VALUES ('3', '设备1', '5512251', '0', '0', '小胡', '2');
