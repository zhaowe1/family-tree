/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : family_tree

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 22/05/2021 13:41:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sex` tinyint(4) NOT NULL DEFAULT 1,
  `birthday` date NOT NULL,
  `f_id` int(11) NOT NULL DEFAULT 0,
  `m_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, '王大锤', 1, '1990-01-01', 3, 4);
INSERT INTO `user` VALUES (2, '王尼美', 2, '1990-02-01', 3, 4);
INSERT INTO `user` VALUES (3, '王建国', 1, '1970-03-03', 0, 0);
INSERT INTO `user` VALUES (4, '李秀英', 2, '1970-03-03', 0, 0);
INSERT INTO `user` VALUES (5, '赵铁柱', 1, '2010-04-04', 0, 2);
INSERT INTO `user` VALUES (6, '王小明', 1, '2010-05-05', 1, 0);

SET FOREIGN_KEY_CHECKS = 1;
