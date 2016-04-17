/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : sys

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-10-26 21:55:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('admin', '1', '管理员', null, null, null, null);
INSERT INTO `auth_item` VALUES ('auth/ajax-link-auth', '2', 'ajax关联权限', null, null, null, null);
INSERT INTO `auth_item` VALUES ('auth/assign-auth', '2', '分配权限', null, null, null, null);
INSERT INTO `auth_item` VALUES ('auth/create', '2', '添加权限', null, null, null, null);
INSERT INTO `auth_item` VALUES ('auth/delete', '2', '删除权限', null, null, null, null);
INSERT INTO `auth_item` VALUES ('auth/index', '2', '资源', null, null, null, null);
INSERT INTO `auth_item` VALUES ('auth/update', '2', '修改权限', null, null, null, null);
INSERT INTO `auth_item` VALUES ('auth/view', '2', '浏览权限', null, null, null, null);
INSERT INTO `auth_item` VALUES ('auth_manage', '1', '权限管理', null, null, null, null);
INSERT INTO `auth_item` VALUES ('backend_manage', '1', '后台管理员', null, null, null, null);
INSERT INTO `auth_item` VALUES ('menu/ajax-get-childen-menu', '2', 'ajax获取子菜单', null, null, null, null);
INSERT INTO `auth_item` VALUES ('menu/ajax-menu-display', '2', 'ajax菜单显示/隐藏', null, null, null, null);
INSERT INTO `auth_item` VALUES ('menu/create', '2', '添加菜单', null, null, null, null);
INSERT INTO `auth_item` VALUES ('menu/delete', '2', '删除菜单', null, null, null, null);
INSERT INTO `auth_item` VALUES ('menu/index', '2', '菜单管理', null, null, null, null);
INSERT INTO `auth_item` VALUES ('menu/update', '2', '更新菜单', null, null, null, null);
INSERT INTO `auth_item` VALUES ('menu/view', '2', '查看菜单', null, null, null, null);
INSERT INTO `auth_item` VALUES ('menu_manage', '1', '菜单管理', null, null, null, null);
INSERT INTO `auth_item` VALUES ('site/index', '2', '首页', null, null, null, null);
INSERT INTO `auth_item` VALUES ('test/index', '2', '测试', null, null, null, null);
INSERT INTO `auth_item` VALUES ('user/add-assignment', '2', '分配权限', null, null, null, null);
INSERT INTO `auth_item` VALUES ('user/create', '2', '添加用户', null, '', null, null);
INSERT INTO `auth_item` VALUES ('user/delete', '2', '删除用户', null, null, null, null);
INSERT INTO `auth_item` VALUES ('user/index', '2', '用户管理', null, null, null, null);
INSERT INTO `auth_item` VALUES ('user/remove-all', '2', '批量删除用户', null, null, null, null);
INSERT INTO `auth_item` VALUES ('user/remove-assignment', '2', '移除权限', null, null, null, null);
INSERT INTO `auth_item` VALUES ('user/update', '2', '更新用户', null, null, null, null);
INSERT INTO `auth_item` VALUES ('user/view', '2', '查看用户', null, '', null, null);
INSERT INTO `auth_item` VALUES ('user_manage', '1', '用户管理', null, null, null, null);
