/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : yoyosys

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-10-26 21:55:58
*/

SET FOREIGN_KEY_CHECKS=0;

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
