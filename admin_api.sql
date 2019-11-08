/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : admin_api

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2019-11-08 18:17:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `banner`
-- ----------------------------
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT 'Banner名称，通常作为标识',
  `description` varchar(255) DEFAULT NULL COMMENT 'Banner描述',
  `img` text,
  `url` text,
  `sort` int(11) DEFAULT NULL,
  `is_disabled` int(2) DEFAULT '1',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COMMENT='banner管理表';

-- ----------------------------
-- Records of banner
-- ----------------------------
INSERT INTO `banner` VALUES ('1', '首页置顶', '首页轮播图', '/uploads/20191106/2b28fb3cd663ef7917ab57cb6f4feec1.jpg', '', null, '1', '1559203982', '1572839714', null);
INSERT INTO `banner` VALUES ('2', '111', '111des', '/uploads/20191104/2b28fb3cd663ef7917ab57cb6f4feec1.jpg', '', null, '1', '1559203982', '1573117205', '1573117205');
INSERT INTO `banner` VALUES ('3', '2gg', 'ggdesgg11', '', null, null, '1', '1559203982', '1572839760', '1572839760');
INSERT INTO `banner` VALUES ('4', '222', '221des', '', null, null, '1', '1571996595', '1572839760', '1572839760');
INSERT INTO `banner` VALUES ('5', '333', null, '', null, null, '1', '1571996398', '1572839678', '1572839678');
INSERT INTO `banner` VALUES ('6', '4444', null, null, null, null, '1', '1571996398', '1572839678', '1572839678');
INSERT INTO `banner` VALUES ('7', '555', null, null, null, null, '1', '1571996398', '1572839678', '1572839678');
INSERT INTO `banner` VALUES ('8', '666', null, null, null, null, '1', '1571996398', '1572839673', '1572839673');
INSERT INTO `banner` VALUES ('9', '777', null, null, null, null, '1', '1571996398', '1572839673', '1572839673');
INSERT INTO `banner` VALUES ('10', '888', null, null, null, null, '1', '1571996398', '1572839673', '1572839673');
INSERT INTO `banner` VALUES ('11', '999', null, null, null, null, '1', '1571996398', '1572839673', '1572839673');
INSERT INTO `banner` VALUES ('12', '112312', null, null, null, null, '1', '1571996398', '1572839673', '1572839673');
INSERT INTO `banner` VALUES ('13', '55234', null, '', null, null, '1', '1571996398', '1572839673', '1572839673');
INSERT INTO `banner` VALUES ('14', '个地方官', null, '', null, null, '1', '1559203982', '1572839673', '1572839673');
INSERT INTO `banner` VALUES ('15', '5552268', null, '', null, null, '1', '1559203982', '1572839673', '1572839673');
INSERT INTO `banner` VALUES ('16', 'test', '', '/uploads/20191104/2b28fb3cd663ef7917ab57cb6f4feec1.jpg', '', null, '1', '1572834272', '1572839673', '1572839673');
INSERT INTO `banner` VALUES ('17', 'hhh1', 'sdf', '/uploads/20191104/2b28fb3cd663ef7917ab57cb6f4feec1.jpg', '', null, '1', '1572835461', '1572839674', '1572839674');
INSERT INTO `banner` VALUES ('18', 'test', '', '/uploads/20191106/2b28fb3cd663ef7917ab57cb6f4feec1.jpg', '', null, '1', '1572947065', '1573117208', '1573117208');
INSERT INTO `banner` VALUES ('19', 'test188123', '<p><strong>tes</strong></p>', '', 'testssss', null, '0', '1573027968', '1573027968', null);
INSERT INTO `banner` VALUES ('20', '123', '', '', '123', null, '1', '1573099397', '1573099402', '1573099402');
INSERT INTO `banner` VALUES ('21', '123123t', '<p>test</p>', '/uploads/20191108/2b28fb3cd663ef7917ab57cb6f4feec1.jpg', '', null, '1', '1573114891', '1573114891', null);

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `scope` varchar(32) DEFAULT NULL,
  `avatar` text,
  `email` varchar(32) DEFAULT NULL,
  `mobile` varchar(32) DEFAULT NULL,
  `salt` varchar(32) DEFAULT '' COMMENT 'token局部随机盐，当账号被黑是改变此值强制登出',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', 'admin', 'd779fa52b55d856d871ec5f4bcbe996f', 'carolsail', '32', '/uploads/20191108/2b28fb3cd663ef7917ab57cb6f4feec1.jpg', 'admin@test.com', '12345678', '0cYxOz', null, '1573204008', null);
INSERT INTO `user` VALUES ('2', 'editor', 'editor', 'd779fa52b55d856d871ec5f4bcbe996f', 'editor', '16', null, null, null, 'GizQ87', null, '1572946735', null);
