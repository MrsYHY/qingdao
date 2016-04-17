/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : sys

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-10-26 21:55:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`) USING BTREE,
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('auth_manage', 'auth/ajax-link-auth');
INSERT INTO `auth_item_child` VALUES ('auth_manage', 'auth/assign-auth');
INSERT INTO `auth_item_child` VALUES ('auth_manage', 'auth/create');
INSERT INTO `auth_item_child` VALUES ('auth_manage', 'auth/delete');
INSERT INTO `auth_item_child` VALUES ('auth_manage', 'auth/index');
INSERT INTO `auth_item_child` VALUES ('auth_manage', 'auth/update');
INSERT INTO `auth_item_child` VALUES ('auth_manage', 'auth/view');
INSERT INTO `auth_item_child` VALUES ('admin', 'auth_manage');
INSERT INTO `auth_item_child` VALUES ('admin', 'backend_manage');
INSERT INTO `auth_item_child` VALUES ('menu_manage', 'menu/ajax-get-childen-menu');
INSERT INTO `auth_item_child` VALUES ('menu_manage', 'menu/ajax-menu-display');
INSERT INTO `auth_item_child` VALUES ('menu_manage', 'menu/create');
INSERT INTO `auth_item_child` VALUES ('menu_manage', 'menu/delete');
INSERT INTO `auth_item_child` VALUES ('menu_manage', 'menu/index');
INSERT INTO `auth_item_child` VALUES ('menu_manage', 'menu/update');
INSERT INTO `auth_item_child` VALUES ('menu_manage', 'menu/view');
INSERT INTO `auth_item_child` VALUES ('admin', 'menu_manage');
INSERT INTO `auth_item_child` VALUES ('backend_manage', 'site/index');
INSERT INTO `auth_item_child` VALUES ('user_manage', 'user/add-assignment');
INSERT INTO `auth_item_child` VALUES ('user_manage', 'user/create');
INSERT INTO `auth_item_child` VALUES ('user_manage', 'user/delete');
INSERT INTO `auth_item_child` VALUES ('user_manage', 'user/index');
INSERT INTO `auth_item_child` VALUES ('user_manage', 'user/remove-all');
INSERT INTO `auth_item_child` VALUES ('user_manage', 'user/remove-assignment');
INSERT INTO `auth_item_child` VALUES ('user_manage', 'user/update');
INSERT INTO `auth_item_child` VALUES ('user_manage', 'user/view');
INSERT INTO `auth_item_child` VALUES ('admin', 'user_manage');
