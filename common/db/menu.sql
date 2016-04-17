/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : yoyosys

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-10-26 21:55:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `route` varchar(80) DEFAULT '' COMMENT '菜单地址',
  `name` varchar(20) DEFAULT '' COMMENT '菜单名',
  `desc` tinytext COMMENT '菜单描述',
  `display` tinyint(1) DEFAULT '1',
  `icon` varchar(30) DEFAULT '',
  `parent` mediumint(9) NOT NULL DEFAULT '0',
  `index` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('4', 'javascript:;', '系统设置', '', '1', 'icon-settings', '0', '19');
INSERT INTO `menu` VALUES ('5', 'auth/index', '权限管理', '', '1', 'glyphicon glyphicon-tasks', '0', '15');
INSERT INTO `menu` VALUES ('8', 'site/index', '控制面板', '控制面板', '1', 'icon-home', '0', '0');
INSERT INTO `menu` VALUES ('9', 'menu/index', '菜单管理', '', '1', null, '4', '15');
INSERT INTO `menu` VALUES ('10', 'javascript:;', '用户管理', '', '1', 'icon-users', '0', '12');
INSERT INTO `menu` VALUES ('23', 'user/index', '用户列表', '', '1', '', '10', '20');
