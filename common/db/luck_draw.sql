/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50710
Source Host           : localhost:3306
Source Database       : luck_draw

Target Server Type    : MYSQL
Target Server Version : 50710
File Encoding         : 65001

Date: 2016-04-20 17:34:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for activitys
-- ----------------------------
DROP TABLE IF EXISTS `activitys`;
CREATE TABLE `activitys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '活动主题',
  `content` varchar(2000) DEFAULT NULL COMMENT '活动内容',
  `start_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `end_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of activitys
-- ----------------------------
INSERT INTO `activitys` VALUES ('1', '测试测试测试', '测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试', '2016-04-01 00:00:00', '2016-04-23 00:00:00');

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('admin', '1', null);
INSERT INTO `auth_assignment` VALUES ('auth_manage', '3', '1433035551');
INSERT INTO `auth_assignment` VALUES ('backend_manage', '2', '1433005674');
INSERT INTO `auth_assignment` VALUES ('menu_manage', '2', '1433005674');
INSERT INTO `auth_assignment` VALUES ('user_manage', '2', '1433035322');

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
INSERT INTO `auth_item` VALUES ('activity/create', '2', '活动创建', null, '', null, null);
INSERT INTO `auth_item` VALUES ('activity/delete', '2', '活动删除', null, '', null, null);
INSERT INTO `auth_item` VALUES ('activity/list', '2', '活动列表', null, '', null, null);
INSERT INTO `auth_item` VALUES ('activity/prize-create', '2', '奖品创建', null, '', null, null);
INSERT INTO `auth_item` VALUES ('activity/update', '2', '活动更新', null, '', null, null);
INSERT INTO `auth_item` VALUES ('activity/view', '2', '活动查看', null, '', null, null);
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
INSERT INTO `auth_item` VALUES ('prizes/create', '2', '奖品创建', null, '', null, null);
INSERT INTO `auth_item` VALUES ('prizes/delete', '2', '奖品删除', null, '', null, null);
INSERT INTO `auth_item` VALUES ('prizes/update', '2', '奖品更新', null, '', null, null);
INSERT INTO `auth_item` VALUES ('prizes/view', '2', '奖品查看', null, '', null, null);
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
INSERT INTO `auth_item_child` VALUES ('admin', 'activity/create');
INSERT INTO `auth_item_child` VALUES ('admin', 'activity/delete');
INSERT INTO `auth_item_child` VALUES ('admin', 'activity/list');
INSERT INTO `auth_item_child` VALUES ('admin', 'activity/prize-create');
INSERT INTO `auth_item_child` VALUES ('admin', 'activity/update');
INSERT INTO `auth_item_child` VALUES ('admin', 'activity/view');
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
INSERT INTO `auth_item_child` VALUES ('admin', 'prizes/create');
INSERT INTO `auth_item_child` VALUES ('admin', 'prizes/delete');
INSERT INTO `auth_item_child` VALUES ('admin', 'prizes/update');
INSERT INTO `auth_item_child` VALUES ('admin', 'prizes/view');
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

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for luck_draw_result
-- ----------------------------
DROP TABLE IF EXISTS `luck_draw_result`;
CREATE TABLE `luck_draw_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `result` tinyint(2) DEFAULT NULL COMMENT '中奖结果 0：未中奖 1：中奖',
  `activity_id` int(11) DEFAULT NULL COMMENT '所属活动',
  `prize_id` int(11) DEFAULT NULL COMMENT '所属奖品',
  `prize_level` tinyint(4) DEFAULT NULL COMMENT '奖品等级',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of luck_draw_result
-- ----------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('4', 'javascript:;', '系统设置', '', '1', 'icon-settings', '0', '19');
INSERT INTO `menu` VALUES ('5', 'auth/index', '权限管理', '', '1', 'glyphicon glyphicon-tasks', '4', '15');
INSERT INTO `menu` VALUES ('8', 'site/index', '控制面板', '控制面板', '1', 'icon-home', '0', '0');
INSERT INTO `menu` VALUES ('9', 'menu/index', '菜单管理', '', '1', null, '4', '15');
INSERT INTO `menu` VALUES ('10', 'javascript:;', '用户管理', '', '1', 'icon-users', '0', '12');
INSERT INTO `menu` VALUES ('23', 'user/index', '用户列表', '', '1', '', '10', '20');
INSERT INTO `menu` VALUES ('25', 'javascript:;', '活动管理', '', '1', '', '0', '3');
INSERT INTO `menu` VALUES ('26', 'activity/list', '活动列表', '', '1', '', '25', '1');

-- ----------------------------
-- Table structure for prizes
-- ----------------------------
DROP TABLE IF EXISTS `prizes`;
CREATE TABLE `prizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) DEFAULT NULL COMMENT '所属活动',
  `name` varchar(255) DEFAULT NULL COMMENT '奖品名称',
  `num` int(2) DEFAULT NULL COMMENT '奖品数量',
  `prize_level` tinyint(4) DEFAULT NULL COMMENT '奖品等级 0：特等奖 1：一等奖 2：二等奖 以此类推',
  `win_rate` float(5,3) DEFAULT NULL COMMENT '中奖率',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of prizes
-- ----------------------------
INSERT INTO `prizes` VALUES ('3', '1', '45678', '78', '0', '0.000');

-- ----------------------------
-- Table structure for terminal_user
-- ----------------------------
DROP TABLE IF EXISTS `terminal_user`;
CREATE TABLE `terminal_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `terminal_user_token` varchar(255) DEFAULT NULL COMMENT '终端用户标识',
  `role` tinyint(4) DEFAULT '0' COMMENT '0:消费者 1：促销员',
  `sign_in_num` int(2) DEFAULT NULL COMMENT '连续签到次数',
  `draw_luck_num` int(2) DEFAULT NULL COMMENT '抽奖机会数',
  `draw_luck_total` int(3) DEFAULT NULL COMMENT '总共抽奖次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of terminal_user
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1,本站注册2qq登陆',
  `oauth_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '密码重置密匙',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱',
  `role` smallint(6) NOT NULL DEFAULT '10' COMMENT '角色',
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT '状态',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  `updated_at` int(10) NOT NULL COMMENT '更新时间',
  `pre_login_time` int(10) DEFAULT NULL,
  `login_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_oauth` (`type`,`oauth_id`) USING BTREE,
  KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10003 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '1', null, '', 'admin', '', '$2y$10$W4Dd4MAiSD7wbxRjG49x/..CKDX7mWH2vgievWGpwq2gtj.8uVe.O', null, '', '10', '10', '1432994066', '1422976238', null, null);
INSERT INTO `user` VALUES ('2', '1', null, '', 'manage', 'vrIGTYAPo89hONyL6HQJVSFIVc9SwMp9', '$2y$13$A.WVLbwjVTHzCCMJtQdXQu6cxIWXvF1UTCqRKMmaTfpisnEBtRzm6', null, '25871588@qq.com', '10', '10', '1432994066', '1433035322', null, null);
INSERT INTO `user` VALUES ('3', '1', null, '', 'test2', 'fHIPkDFaSYBvpd9RtzUQkAOOEqkziPUT', '$2y$13$6Zltzis3pp7ZDUalExxKsuIiiYp6MiyuTb8mGdd2vEAg0wK/mZLDS', null, 'ss@ww.com', '10', '0', '1433035551', '1433035551', null, null);
INSERT INTO `user` VALUES ('10000', '1', null, '会员1', 'member1', 'fHIPkDFaSYBvpd9RtzUQkAOOEqkziPUT', '$2y$13$6Zltzis3pp7ZDUalExxKsuIiiYp6MiyuTb8mGdd2vEAg0wK/mZLDS', '', 'ss@ww.com', '0', '10', '1433035551', '1439130217', '1438700281', '1439130217');
INSERT INTO `user` VALUES ('10001', '1', null, '', 'member2', '', '', null, '82138625@qq.com', '10', '10', '1438482462', '1438482462', null, null);
INSERT INTO `user` VALUES ('10002', '1', null, '', 'member3', 'kgA9d9gfcALeOlDES-hTCNZmZVW7seEw', '$2y$13$yfv3oV.xCHJikRfNLdTHf.FBsRrgl7GAT2fXVw0NaB6yr6exO9JaC', null, '82138625@qq.com', '10', '10', '1438482766', '1438482766', null, null);
