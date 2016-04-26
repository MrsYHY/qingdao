/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50710
Source Host           : localhost:3306
Source Database       : luck_draw

Target Server Type    : MYSQL
Target Server Version : 50710
File Encoding         : 65001

Date: 2016-04-26 12:42:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for zones
-- ----------------------------
DROP TABLE IF EXISTS `zones`;
CREATE TABLE `zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '大区名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zones
-- ----------------------------
INSERT INTO `zones` VALUES ('2', '湖里一区');
INSERT INTO `zones` VALUES ('3', '湖里二区');
INSERT INTO `zones` VALUES ('4', '思明一区');
