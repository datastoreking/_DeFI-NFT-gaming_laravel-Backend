-- MySQL dump 10.13  Distrib 5.7.37, for Linux (x86_64)
--
-- Host: localhost    Database: box_gamifly_co
-- ------------------------------------------------------
-- Server version	5.7.37-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `dl_ad`
--

DROP TABLE IF EXISTS `dl_ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL COMMENT '图片',
  `url` varchar(150) NOT NULL COMMENT '链接',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1轮播图 2个人中心',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='轮播图表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_ad`
--

LOCK TABLES `dl_ad` WRITE;
/*!40000 ALTER TABLE `dl_ad` DISABLE KEYS */;
INSERT INTO `dl_ad` VALUES (2,'/upload/images/20220307/1646614446785523.png','#',2,1646614453),(6,'/upload/images/20220418/1650279511227189.jpg','#',1,1650279517),(8,'/upload/images/20220418/1650289584284233.jpg','#',1,1650289589),(9,'/upload/images/20220418/1650289597920354.jpg','#',1,1650289601),(10,'/upload/images/20220418/165028961862774.jpg','#',1,1650289621),(11,'/upload/images/20220418/1650289629820623.jpg','#',1,1650289632);
/*!40000 ALTER TABLE `dl_ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_admin`
--

DROP TABLE IF EXISTS `dl_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_admin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `auth_ids` varchar(255) DEFAULT NULL COMMENT '角色权限ID',
  `head_img` varchar(255) DEFAULT NULL COMMENT '头像',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户登录名',
  `password` varchar(150) NOT NULL DEFAULT '' COMMENT '用户登录密码',
  `phone` varchar(16) DEFAULT NULL COMMENT '联系手机号',
  `remark` varchar(255) DEFAULT '' COMMENT '备注说明',
  `login_num` bigint(20) unsigned DEFAULT '0' COMMENT '登录次数',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用,)',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `agency_id` int(10) DEFAULT '0' COMMENT '用户ID',
  `ware_id` int(10) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE,
  KEY `phone` (`phone`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_admin`
--

LOCK TABLES `dl_admin` WRITE;
/*!40000 ALTER TABLE `dl_admin` DISABLE KEYS */;
INSERT INTO `dl_admin` VALUES (1,'1','/static/admin/images/head.jpg','admin','a816duypGBJ6bo8FwVb7nfxn9MLcitvcUlROQm8zDwmajio','15617661050','管理员',735,0,1,1600782083,1606785628,NULL,0,0);
/*!40000 ALTER TABLE `dl_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_admin_auth`
--

DROP TABLE IF EXISTS `dl_admin_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_admin_auth` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '权限名称',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态(1:禁用,2:启用)',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统权限表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_admin_auth`
--

LOCK TABLES `dl_admin_auth` WRITE;
/*!40000 ALTER TABLE `dl_admin_auth` DISABLE KEYS */;
INSERT INTO `dl_admin_auth` VALUES (1,'管理员',1,1,'测试管理员',1588921753,1589614331,NULL);
/*!40000 ALTER TABLE `dl_admin_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_admin_auth_node`
--

DROP TABLE IF EXISTS `dl_admin_auth_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_admin_auth_node` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `auth_id` bigint(20) unsigned DEFAULT NULL COMMENT '角色ID',
  `menu_id` bigint(20) DEFAULT NULL COMMENT '菜单ID',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `index_system_auth_auth` (`auth_id`) USING BTREE,
  KEY `index_system_auth_node` (`menu_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=300 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='角色与节点关系表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_admin_auth_node`
--

LOCK TABLES `dl_admin_auth_node` WRITE;
/*!40000 ALTER TABLE `dl_admin_auth_node` DISABLE KEYS */;
INSERT INTO `dl_admin_auth_node` VALUES (37,1,228),(38,1,234),(39,1,244),(40,1,245),(41,1,247),(42,1,249),(43,1,251),(44,1,255),(45,1,256),(46,1,257),(47,1,258),(48,1,259),(49,1,260),(50,1,261),(51,1,262),(52,1,263),(53,1,264),(54,1,265),(55,1,266),(94,8,257),(95,8,258),(96,8,268),(97,8,269),(98,8,271),(99,8,287),(100,8,352),(101,8,392),(102,8,393),(103,8,353),(104,8,394),(105,8,395),(106,8,274),(107,8,275),(108,8,323),(109,8,324),(110,8,326),(111,8,327),(112,8,377),(113,8,378),(114,8,379),(115,8,380),(116,8,381),(117,8,354),(118,8,382),(119,8,383),(120,8,384),(121,8,385),(122,8,355),(123,8,386),(124,8,387),(125,8,388),(126,8,356),(127,8,389),(128,8,390),(129,8,391),(130,8,276),(131,8,277),(132,8,328),(133,8,329),(134,8,330),(135,8,284),(136,8,285),(137,8,343),(138,8,344),(139,8,409),(140,8,410),(141,8,412),(142,8,414),(143,8,415),(144,8,416),(145,8,417),(146,8,418),(147,8,347),(148,8,411),(149,8,413),(150,8,365),(151,8,345),(152,8,346),(153,8,402),(154,8,404),(155,8,408),(156,8,348),(157,8,349),(158,8,405),(159,8,406),(160,8,407),(161,8,357),(162,8,358),(163,8,419),(164,8,421),(165,8,423),(166,8,424),(167,8,425),(168,8,426),(169,8,359),(170,8,420),(171,8,422),(172,8,360),(173,8,361),(174,8,431),(175,8,432),(176,8,433),(177,8,434),(178,8,370),(179,8,435),(180,8,436),(181,8,437),(182,8,364),(183,8,366),(184,8,438),(185,8,367),(186,8,439),(187,8,368),(188,8,440),(189,8,427),(190,8,428),(191,8,429),(192,8,430),(193,6,249),(194,6,251),(195,6,288),(196,6,290),(197,6,291),(198,6,257),(199,6,258),(200,6,268),(201,6,269),(202,6,271),(203,6,280),(204,6,281),(205,6,338),(206,6,339),(207,6,340),(208,6,396),(209,6,350),(210,6,351),(211,6,397),(212,6,398),(213,6,399),(214,6,343),(215,6,344),(216,6,409),(217,6,410),(218,6,412),(219,6,414),(220,6,415),(221,6,416),(222,6,417),(223,6,418),(224,6,347),(225,6,411),(226,6,413),(227,6,365),(228,2,228),(229,2,234),(230,2,296),(231,2,297),(232,2,299),(233,2,300),(234,2,301),(235,2,302),(236,2,376),(237,2,442),(238,2,244),(239,2,303),(240,2,304),(241,2,305),(242,2,306),(243,2,245),(244,2,307),(245,2,308),(246,2,309),(247,2,310),(248,2,311),(249,2,247),(250,2,333),(251,2,249),(252,2,251),(253,2,288),(254,2,289),(255,2,291),(256,2,255),(257,2,256),(258,2,292),(259,2,293),(260,2,294),(261,2,276),(262,2,277),(263,2,328),(264,2,329),(265,2,330),(266,2,278),(267,2,279),(268,2,331),(269,2,332),(270,2,284),(271,2,285),(272,2,444),(273,2,445),(274,2,446),(275,2,447),(276,2,448),(277,2,500),(278,2,501),(279,2,502),(280,2,503),(281,2,455),(282,2,456),(283,2,457),(284,2,458),(285,2,459),(286,2,474),(287,2,475),(288,2,476),(289,2,477),(290,2,481),(291,2,482),(292,2,483),(293,2,484),(294,2,485),(295,2,507),(296,2,508),(297,2,509),(298,2,510),(299,2,511);
/*!40000 ALTER TABLE `dl_admin_auth_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_admin_menu`
--

DROP TABLE IF EXISTS `dl_admin_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_admin_menu` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `icon` varchar(100) NOT NULL DEFAULT '0' COMMENT '菜单图标',
  `href` varchar(100) NOT NULL DEFAULT '' COMMENT '链接',
  `params` varchar(500) DEFAULT '' COMMENT '链接参数',
  `target` varchar(20) NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `sort` int(11) DEFAULT '0' COMMENT '菜单排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `remark` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `menu_status` tinyint(1) DEFAULT '1' COMMENT '是否是菜单列表 1 是  0 否',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `title` (`title`) USING BTREE,
  KEY `href` (`href`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=601 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统菜单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_admin_menu`
--

LOCK TABLES `dl_admin_menu` WRITE;
/*!40000 ALTER TABLE `dl_admin_menu` DISABLE KEYS */;
INSERT INTO `dl_admin_menu` VALUES (228,0,'系统管理','setup_fill','#','','_self',0,1,'',NULL,1617006047,NULL,1),(234,228,'菜单管理','fa fa-tree','admin/menu_index','','_self',10,1,'',NULL,1588228555,NULL,1),(244,228,'管理员管理','fa fa-user','admin/admin_index','','_self',12,1,'',1573185011,1608167926,NULL,1),(245,228,'角色管理','fa fa-bitbucket-square','admin/auth_index','','_self',11,1,'',1573435877,1608167968,NULL,1),(247,228,'配置管理','fa fa-asterisk','admin/config_index','','_self',8,1,'',1573457448,1608168069,NULL,1),(249,0,'用户管理','people_fill','#','','_self',100,1,'',1589439884,1628579842,NULL,1),(251,249,'用户列表','fa fa-list','admin/user_index','','_self',0,1,'',1589439931,1606801177,NULL,1),(255,0,'轮播图管理','picture_fill','#','','_self',95,1,NULL,1608186891,1628580002,NULL,1),(256,255,'轮播图列表','upload/category/20201217/1608186941783812.jpg','admin/ad_index','','_self',NULL,1,NULL,1608186942,NULL,NULL,1),(278,0,'协议管理','activity_fill','#','','_self',91,1,NULL,1611546588,1630391639,NULL,1),(279,278,'协议列表','0','admin/single_index','','_self',NULL,1,NULL,1611546626,NULL,NULL,1),(284,0,'控制面板','0','admin/index','','_self',0,1,NULL,1614332322,NULL,NULL,0),(285,284,'控制面板数据','0','admin/welcome','','_self',0,1,NULL,1614332351,NULL,NULL,0),(288,251,'列表数据','0','admin/user_list','','_self',0,1,NULL,1614332631,NULL,NULL,0),(289,251,'增加/编辑','0','admin/user_add','','_self',0,1,NULL,1614332697,1614332762,NULL,0),(291,251,'封禁','0','admin/user_state','','_self',0,1,NULL,1614332818,NULL,NULL,0),(292,256,'列表数据','0','admin/ad_list','','_self',0,1,NULL,1614332890,NULL,NULL,0),(293,256,'添加/编辑','0','admin/ad_add','','_self',0,1,NULL,1614332922,NULL,NULL,0),(294,256,'删除','0','admin/ad_del','','_self',0,1,NULL,1614332953,NULL,NULL,0),(296,234,'列表数据','0','admin/menu_list','','_self',0,1,NULL,1614333053,NULL,NULL,0),(297,234,'添加','0','admin/menu_add','','_self',0,1,NULL,1614333090,NULL,NULL,0),(299,234,'编辑','0','admin/menu_edit','','_self',0,1,NULL,1614333139,NULL,NULL,0),(300,234,'删除','0','admin/menu_del','','_self',0,1,NULL,1614333168,NULL,NULL,0),(301,234,'状态','0','admin/menu_status','','_self',0,1,NULL,1614333194,NULL,NULL,0),(302,234,'是否列表','0','admin/menu_list_status','','_self',0,1,NULL,1614333215,NULL,NULL,0),(303,244,'列表数据','0','admin/admin_list','','_self',0,1,NULL,1614333268,NULL,NULL,0),(304,244,'添加','0','admin/admin_add','','_self',0,1,NULL,1614333302,NULL,NULL,0),(305,244,'编辑','0','admin/admin_edit','','_self',0,1,NULL,1614333330,NULL,NULL,0),(306,244,'封号','0','admin/admin_status','','_self',0,1,NULL,1614333396,NULL,NULL,0),(307,245,'列表数据','0','admin/auth_list','','_self',0,1,NULL,1614333493,NULL,NULL,0),(308,245,'添加','0','admin/auth_add','','_self',0,1,NULL,1614333519,NULL,NULL,0),(309,245,'编辑','0','admin/auth_edit','','_self',0,1,NULL,1614333596,NULL,NULL,0),(310,245,'授权管理','0','admin/auth_node','','_self',0,1,NULL,1614333621,NULL,NULL,0),(311,245,'状态','0','admin/auth_state','','_self',0,1,NULL,1614333654,NULL,NULL,0),(331,279,'列表数据','0','admin/single_list','','_self',0,1,NULL,1614394172,NULL,NULL,0),(332,279,'添加/编辑','0','admin/single_add','','_self',0,1,NULL,1614394212,NULL,NULL,0),(333,247,'添加/编辑','0','admin/config_save','','_self',0,1,NULL,1614394291,NULL,NULL,0),(376,234,'排序','0','admin/menu_sort','','_self',0,1,NULL,1616742691,NULL,NULL,1),(442,234,'图标选择','0','admin/menu_icon','','_self',0,1,NULL,1617006887,NULL,NULL,0),(455,0,'盲盒管理','manage_fill .icon-manage_fill','#','','_self',93,1,NULL,1618986530,1637895034,NULL,1),(474,0,'订单管理','order .icon-order','#','','_self',99,1,NULL,1619492920,1649921664,NULL,1),(475,474,'订单列表','0','admin/order_index','','_self',1,1,NULL,1619492943,1647247961,NULL,1),(476,475,'列表数据','0','admin/order_list','','_self',NULL,1,NULL,1619492960,NULL,NULL,0),(481,0,'充值规则','redpacket_fill .icon-redpacket_fill','#','','_self',90,1,NULL,1619597773,1646361694,NULL,1),(482,481,'规则列表','0','admin/recharge_index','','_self',NULL,1,NULL,1619597834,1646361701,NULL,1),(483,482,'列表数据','0','admin/recharge_list','','_self',NULL,1,NULL,1619597863,1637744126,NULL,0),(484,482,'添加/修改','0','admin/recharge_add','','_self',NULL,1,NULL,1619597916,1637744132,NULL,0),(485,482,'删除','0','admin/recharge_del','','_self',NULL,1,NULL,1619597989,1637744137,NULL,0),(517,455,'普通赏列表','0','admin/box_one_index','','_self',2,1,NULL,1632468737,1646712027,NULL,1),(518,517,'列表数据','0','admin/box_one_list','','_self',NULL,1,NULL,1632468761,1646712090,NULL,0),(519,517,'添加/编辑','0','admin/box_one_add','','_self',NULL,1,NULL,1632468779,1646712049,NULL,0),(520,517,'删除','0','admin/box_one_del','','_self',NULL,1,NULL,1632468794,1646712118,NULL,0),(525,0,'潮玩券管理','coupons_fill .icon-coupons_fill','#','','_self',93,1,NULL,1637895076,1646794957,NULL,1),(526,525,'潮玩券列表','0','admin/coupon_index','','_self',NULL,1,NULL,1637895560,1646794964,NULL,1),(527,526,'列表数据','0','admin/coupon_list','','_self',NULL,1,NULL,1637895616,NULL,NULL,0),(528,526,'编辑','0','admin/coupon_add','','_self',NULL,1,NULL,1637895642,1648628091,NULL,0),(530,474,'赏品列表','0','admin/order_goods_index','','_self',NULL,1,NULL,1637918506,NULL,NULL,1),(531,530,'列表数据','0','admin/order_goods_list','','_self',NULL,1,NULL,1637918526,NULL,NULL,0),(533,455,'赏品列表','0','admin/goods_index','','_self',NULL,1,NULL,1638166673,1646365216,NULL,1),(534,533,'列表数据','0','admin/goods_list','','_self',NULL,1,NULL,1638166698,1646365233,NULL,0),(535,533,'添加/编辑','0','admin/goods_add','','_self',NULL,1,NULL,1638166716,1646365227,NULL,0),(536,533,'删除','0','admin/goods_del','','_self',NULL,1,NULL,1638166734,1646365221,NULL,0),(537,455,'赏品级别','0','admin/level_index','','_self',NULL,1,NULL,1646290035,NULL,NULL,1),(538,537,'列表数据','0','admin/level_list','','_self',NULL,1,NULL,1646290055,NULL,NULL,0),(539,537,'添加/编辑','0','admin/level_add','','_self',NULL,1,NULL,1646290077,NULL,NULL,0),(540,537,'删除','0','admin/level_del','','_self',NULL,1,NULL,1646290094,NULL,NULL,0),(541,517,'上架/下架','0','admin/box_one_state','','_self',NULL,1,NULL,1646365208,1646712110,NULL,0),(542,533,'选择','0','admin/goods_search','','_self',NULL,1,NULL,1646365261,NULL,NULL,0),(543,0,'分类管理','document_fill .icon-document_fill','#','','_self',94,1,NULL,1646708817,NULL,NULL,1),(544,543,'分类列表','0','admin/category_index','','_self',NULL,1,NULL,1646708839,1646788508,NULL,1),(545,544,'列表数据','0','admin/category_list','','_self',NULL,1,NULL,1646708859,NULL,NULL,0),(546,544,'添加/编辑','0','admin/category_add','','_self',NULL,1,NULL,1646708875,NULL,NULL,0),(547,544,'删除','0','admin/category_del','','_self',NULL,1,NULL,1646708890,NULL,NULL,0),(548,455,'竞技赏列表','0','admin/box_two_index','','_self',2,1,NULL,1646730997,NULL,NULL,1),(549,548,'列表数据','0','admin/box_two_list','','_self',NULL,1,NULL,1646731018,NULL,NULL,0),(550,548,'添加/编辑','0','admin/box_two_add','','_self',NULL,1,NULL,1646731039,NULL,NULL,0),(551,548,'删除','0','admin/box_two_del','','_self',NULL,1,NULL,1646731057,NULL,NULL,0),(552,548,'上架/下架','0','admin/box_two_state','','_self',NULL,1,NULL,1646731076,NULL,NULL,0),(553,455,'无限赏列表','0','admin/box_three_index','','_self',2,1,NULL,1646733858,NULL,NULL,1),(554,553,'列表数据','0','admin/box_three_list','','_self',NULL,1,NULL,1646733877,NULL,NULL,0),(555,553,'添加/编辑','0','admin/box_three_add','','_self',NULL,1,NULL,1646733894,NULL,NULL,0),(556,553,'删除','0','admin/box_three_del','','_self',NULL,1,NULL,1646733908,NULL,NULL,0),(557,553,'上架/下架','0','admin/box_three_state','','_self',NULL,1,NULL,1646733926,NULL,NULL,0),(558,553,'概率列表','0','admin/box_level_index','','_self',NULL,1,NULL,1646798421,NULL,NULL,1),(559,558,'列表数据','0','admin/box_level_list','','_self',NULL,1,NULL,1646798439,NULL,NULL,0),(560,558,'添加/编辑','0','admin/box_level_add','','_self',NULL,1,NULL,1646798462,NULL,NULL,0),(561,558,'删除','0','admin/box_level_del','','_self',NULL,1,NULL,1646798479,NULL,NULL,0),(562,474,'发货列表','0','admin/order_deliver_index','','_self',NULL,1,NULL,1647249647,NULL,NULL,1),(563,562,'列表数据','0','admin/order_deliver_list','','_self',NULL,1,NULL,1647249740,NULL,NULL,0),(564,562,'发货','0','admin/order_deliver','','_self',NULL,1,NULL,1647249757,NULL,NULL,0),(565,251,'充值/扣除','0','admin/user_conf','','_self',NULL,1,NULL,1648544672,NULL,NULL,0),(566,533,'竞技赏选择','0','admin/goods_three_search','','_self',NULL,1,NULL,1648713527,NULL,NULL,0),(567,533,'扭蛋机选择','0','admin/goods_egg_search','','_self',NULL,1,NULL,1648713546,NULL,NULL,0),(568,455,'扭蛋机列表','0','admin/box_egg_index','','_self',2,1,NULL,1648713590,NULL,NULL,1),(569,568,'列表数据','0','admin/box_egg_list','','_self',NULL,1,NULL,1648713621,NULL,NULL,0),(570,568,'添加/编辑','0','admin/box_egg_add','','_self',NULL,1,NULL,1648713644,NULL,NULL,0),(571,568,'删除','0','admin/box_egg_del','','_self',NULL,1,NULL,1648713670,NULL,NULL,0),(572,568,'上架/下架','0','admin/box_egg_state','','_self',NULL,1,NULL,1648713687,NULL,NULL,0),(573,0,'消费统计','barrage_fill .icon-barrage_fill','#','','_self',98,1,NULL,1649921751,NULL,NULL,1),(574,573,'消费日流水','0','admin/consume_day_index','','_self',NULL,1,NULL,1649921770,NULL,NULL,1),(575,574,'列表数据','0','admin/consume_day_list','','_self',NULL,1,NULL,1649921785,NULL,NULL,0),(576,573,'消费周流水','0','admin/consume_week_index','','_self',NULL,1,NULL,1649926479,NULL,NULL,1),(577,576,'列表数据','0','admin/consume_week_list','','_self',NULL,1,NULL,1649926531,NULL,NULL,0),(578,573,'消费月流水','0','admin/consume_month_index','','_self',NULL,1,NULL,1649926553,NULL,NULL,1),(579,578,'列表数据','0','admin/consume_month_list','','_self',NULL,1,NULL,1649926569,NULL,NULL,0),(580,573,'打拳日流水','0','admin/times_day_index','','_self',NULL,1,NULL,1649927500,1649927601,NULL,1),(581,580,'列表数据','0','admin/times_day_list','','_self',NULL,1,NULL,1649927515,NULL,NULL,0),(582,573,'打拳周流水','0','admin/times_week_index','','_self',NULL,1,NULL,1649927533,NULL,NULL,1),(583,582,'列表数据','0','admin/times_week_list','','_self',NULL,1,NULL,1649927550,NULL,NULL,0),(584,573,'打拳月流水','0','admin/times_month_index','','_self',NULL,1,NULL,1649927564,NULL,NULL,1),(585,584,'列表数据','0','admin/times_month_list','','_self',NULL,1,NULL,1649927578,NULL,NULL,0),(586,573,'无限日流水','0','admin/infinite_day_index','','_self',NULL,1,NULL,1649927875,NULL,NULL,1),(587,586,'列表数据','0','admin/infinite_day_list','','_self',NULL,1,NULL,1649927890,NULL,NULL,0),(588,573,'无限周流水','0','admin/infinite_week_index','','_self',NULL,1,NULL,1649927906,NULL,NULL,1),(589,588,'列表数据','0','admin/infinite_week_list','','_self',NULL,1,NULL,1649927922,NULL,NULL,0),(590,573,'无限月流水','0','admin/infinite_month_index','','_self',NULL,1,NULL,1649927938,NULL,NULL,1),(591,590,'列表数据','0','admin/infinite_month_list','','_self',NULL,1,NULL,1649927954,NULL,NULL,0),(592,526,'赠送','0','admin/coupon_give','','_self',NULL,1,NULL,1650012720,NULL,NULL,0),(594,553,'发赏','0','admin/box_three_prize','','_self',NULL,1,NULL,1650694120,NULL,NULL,0),(595,573,'日总流水','0','admin/consume_total_day_index','','_self',NULL,1,NULL,1650878255,NULL,NULL,1),(596,595,'列表数据','0','admin/consume_total_day_list','','_self',NULL,1,NULL,1650878269,NULL,NULL,0),(597,573,'周总流水','0','admin/consume_total_week_index','','_self',NULL,1,NULL,1650878292,NULL,NULL,1),(598,597,'列表数据','0','admin/consume_total_week_list','','_self',NULL,1,NULL,1650878314,NULL,NULL,0),(599,573,'月总流水','0','admin/consume_total_month_index','','_self',NULL,1,NULL,1650878333,NULL,NULL,1),(600,599,'列表数据','0','admin/consume_total_month_list','','_self',NULL,1,NULL,1650878349,NULL,NULL,0);
/*!40000 ALTER TABLE `dl_admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_box`
--

DROP TABLE IF EXISTS `dl_box`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_box` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_id` tinyint(3) DEFAULT '0' COMMENT '分类ID',
  `type` tinyint(3) NOT NULL COMMENT '1普通赏 2竞技赏 3无限赏',
  `name` varchar(150) NOT NULL COMMENT '名称',
  `image` varchar(150) NOT NULL COMMENT '图片',
  `cover_image` varchar(150) NOT NULL COMMENT '封面图',
  `price` float NOT NULL DEFAULT '0' COMMENT '价格',
  `num` int(10) NOT NULL DEFAULT '1' COMMENT '箱子数量',
  `sale` int(10) DEFAULT '0' COMMENT '卖出数量后可全收',
  `consume` decimal(10,2) DEFAULT '0.00' COMMENT '累计消费',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1上架 2下架',
  `sort` int(10) NOT NULL DEFAULT '1' COMMENT '排序值',
  `is_del` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  `hot` int(10) DEFAULT '0' COMMENT '抽赏热度',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='盲盒表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_box`
--

LOCK TABLES `dl_box` WRITE;
/*!40000 ALTER TABLE `dl_box` DISABLE KEYS */;
INSERT INTO `dl_box` VALUES (49,1,1,'S1','/upload/images/20220814/1660420256241691.jpg','/upload/images/20220814/1660420259839988.jpg',1,1,1,0.00,1,1,0,1660420369,0),(50,2,1,'A1','/upload/images/20220814/1660420434639426.png','/upload/images/20220814/1660420438673178.png',1,1,1,0.00,1,1,0,1660420443,0);
/*!40000 ALTER TABLE `dl_box` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_box_award`
--

DROP TABLE IF EXISTS `dl_box_award`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_box_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `box_id` int(10) unsigned NOT NULL COMMENT '盲盒ID',
  `number` int(10) NOT NULL COMMENT '编号',
  `is_award` tinyint(3) DEFAULT '0' COMMENT '是否发奖',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发奖记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_box_award`
--

LOCK TABLES `dl_box_award` WRITE;
/*!40000 ALTER TABLE `dl_box_award` DISABLE KEYS */;
/*!40000 ALTER TABLE `dl_box_award` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_box_level`
--

DROP TABLE IF EXISTS `dl_box_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_box_level` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `box_id` int(10) NOT NULL COMMENT '盲盒ID',
  `level` varchar(20) NOT NULL COMMENT '等级',
  `ratio` decimal(10,2) NOT NULL DEFAULT '0.01' COMMENT '概率',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='盲盒等级表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_box_level`
--

LOCK TABLES `dl_box_level` WRITE;
/*!40000 ALTER TABLE `dl_box_level` DISABLE KEYS */;
INSERT INTO `dl_box_level` VALUES (1,6,'SP',0.02,1653719221),(2,6,'A',0.03,1653719250),(3,6,'B',0.04,1653719258),(4,6,'C',0.05,1653719267),(5,6,'D',0.05,1653719274),(6,6,'E',0.05,1653719281),(7,6,'F',0.10,1653719292),(8,6,'G',0.10,1653719302),(9,6,'H',0.10,1653719310),(10,6,'I',0.15,1653719321),(11,6,'J',0.15,1653719329),(12,6,'K',0.15,1653719337),(13,6,'L',0.20,1653719348),(14,6,'M',0.20,1653719366),(15,6,'N',0.20,1653719372),(16,6,'O',98.41,1653719380),(17,7,'SP',0.03,1653719924),(18,7,'A',0.06,1653719930),(19,7,'B',1.70,1653719936),(20,7,'C',4.01,1653719943),(21,7,'D',31.40,1653719951),(22,7,'E',31.40,1653719961),(23,7,'F',31.40,1653719969);
/*!40000 ALTER TABLE `dl_box_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_card`
--

DROP TABLE IF EXISTS `dl_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `name` varchar(100) NOT NULL COMMENT '姓名',
  `qrcode` varchar(150) DEFAULT '' COMMENT '收款码',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='提现账号表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_card`
--

LOCK TABLES `dl_card` WRITE;
/*!40000 ALTER TABLE `dl_card` DISABLE KEYS */;
/*!40000 ALTER TABLE `dl_card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_cash`
--

DROP TABLE IF EXISTS `dl_cash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `service_ratio` varchar(10) NOT NULL COMMENT '提现费率',
  `service_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `real_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际到账金额',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0未审核 1已审核 2已拒绝',
  `card_id` int(10) unsigned NOT NULL COMMENT '提现账号ID',
  `create_time` int(10) unsigned NOT NULL COMMENT '申请时间',
  `admin_id` int(10) unsigned DEFAULT '0' COMMENT '审核人',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '审核时间',
  `remark` varchar(100) DEFAULT NULL COMMENT '审核说明',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='提现表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_cash`
--

LOCK TABLES `dl_cash` WRITE;
/*!40000 ALTER TABLE `dl_cash` DISABLE KEYS */;
/*!40000 ALTER TABLE `dl_cash` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_category`
--

DROP TABLE IF EXISTS `dl_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '类型名称',
  `type` tinyint(3) NOT NULL COMMENT '1一番赏 2无限赏',
  `is_del` tinyint(3) DEFAULT '0' COMMENT '是否删除',
  `create_time` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='盲盒分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_category`
--

LOCK TABLES `dl_category` WRITE;
/*!40000 ALTER TABLE `dl_category` DISABLE KEYS */;
INSERT INTO `dl_category` VALUES (1,'SINGLE',1,0,1646358303),(2,'ARENA',1,0,1646358316);
/*!40000 ALTER TABLE `dl_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_collection`
--

DROP TABLE IF EXISTS `dl_collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_collection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `box_id` int(10) NOT NULL COMMENT '盲盒ID',
  `type` tinyint(3) NOT NULL COMMENT '1普通赏 2竞技赏 3无限赏',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='收藏表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_collection`
--

LOCK TABLES `dl_collection` WRITE;
/*!40000 ALTER TABLE `dl_collection` DISABLE KEYS */;
INSERT INTO `dl_collection` VALUES (1,3,4,2,1653902476),(10,3,9,1,1658243595),(11,3,1,1,1658331504);
/*!40000 ALTER TABLE `dl_collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_config`
--

DROP TABLE IF EXISTS `dl_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '变量名',
  `value` text COMMENT '变量值',
  `remark` varchar(100) DEFAULT '' COMMENT '备注信息',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统配置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_config`
--

LOCK TABLES `dl_config` WRITE;
/*!40000 ALTER TABLE `dl_config` DISABLE KEYS */;
INSERT INTO `dl_config` VALUES (7,'site_name','Gamifly','网站名称'),(8,'site_logo','/images/logo.png','网站LOGO'),(12,'site_domain','http://box.gamifly.co/','网站域名'),(110,'site_bg','/images/bg.png','登录背景'),(121,'site_deliver','运费10元,满5件包邮（偏远地区除外）。预售商品到货后才可发货','发货说明'),(122,'site_freight','10','运费'),(126,'site_free_num','5','免邮费件数'),(129,'site_notice','开业准备中，帅Liu潮玩，祝大家一发入魂，客服在线时间12：00--24：00','系统公告'),(130,'box_free','0.2','特殊赏比例'),(131,'coupon_achieve','400','消费可得券'),(132,'site_group','/upload/images/20220415/1650036091105593.jpg','福利群'),(133,'site_share','/upload/images/20220406/1649239587639014.png','分享图片'),(134,'is_coupon','0','是否送券');
/*!40000 ALTER TABLE `dl_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_consume`
--

DROP TABLE IF EXISTS `dl_consume`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_consume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `year` varchar(20) NOT NULL COMMENT '年',
  `month` varchar(20) NOT NULL COMMENT '月',
  `day` varchar(20) NOT NULL COMMENT '日',
  `achieve` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '消费金额',
  `type` tinyint(3) NOT NULL COMMENT '1普通消费金额 2无限消费金额 3竞技打拳次数',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_consume`
--

LOCK TABLES `dl_consume` WRITE;
/*!40000 ALTER TABLE `dl_consume` DISABLE KEYS */;
INSERT INTO `dl_consume` VALUES (1,2,'2022','05','28',880.00,1,1653722051),(2,3,'2022','05','30',1.00,3,1653902739),(3,3,'2022','05','30',7940.00,1,1653902739),(4,1,'2022','05','30',1144.00,1,1653905444),(5,1,'2022','05','30',25.00,2,1653906042),(6,2,'2022','05','31',2506.00,1,1653965641),(7,4,'2022','05','31',616.00,1,1653983169),(8,1,'2022','05','31',880.00,1,1653985601),(9,3,'2022','06','01',1199.00,1,1654076279),(10,3,'2022','06','01',4.00,3,1654077486),(11,3,'2022','06','01',25.00,2,1654077653),(12,4,'2022','06','01',493.00,1,1654078272),(13,4,'2022','06','01',2.00,3,1654079742),(14,4,'2022','06','01',10.00,2,1654080136),(15,1,'2022','06','01',968.00,1,1654080267),(16,3,'2022','06','04',88.00,1,1654331859),(17,3,'2022','06','20',233.00,1,1655702899),(18,3,'2022','06','21',437.00,1,1655814489),(19,3,'2022','06','21',3.00,3,1655814505),(20,3,'2022','06','21',5.00,2,1655815706),(21,3,'2022','06','23',1.00,1,1655923240),(22,3,'2022','07','06',5.00,2,1657117457),(23,3,'2022','07','09',2.00,1,1657330617),(24,3,'2022','07','12',1.00,1,1657635005),(25,3,'2022','07','19',0.10,1,1658238316),(26,3,'2022','07','20',1.00,1,1658301554);
/*!40000 ALTER TABLE `dl_consume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_coupon`
--

DROP TABLE IF EXISTS `dl_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(50) NOT NULL COMMENT '级别',
  `image` varchar(150) NOT NULL COMMENT '图片',
  `image_merge` varchar(150) NOT NULL COMMENT '合并图片',
  `min_score` int(10) NOT NULL COMMENT '最少潮玩币',
  `max_score` int(10) NOT NULL COMMENT '最大潮玩币',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='优惠券表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_coupon`
--

LOCK TABLES `dl_coupon` WRITE;
/*!40000 ALTER TABLE `dl_coupon` DISABLE KEYS */;
INSERT INTO `dl_coupon` VALUES (1,'C','/upload/images/20220406/1649238519347876.png','/upload/images/20220406/1649238524466696.png',1,2,1650004636);
/*!40000 ALTER TABLE `dl_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_customer`
--

DROP TABLE IF EXISTS `dl_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '客服名称',
  `number` varchar(20) NOT NULL COMMENT '联系方式',
  `type` tinyint(3) NOT NULL COMMENT '1手机号 2微信号',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  `image` varchar(100) NOT NULL COMMENT '图标',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='客服表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_customer`
--

LOCK TABLES `dl_customer` WRITE;
/*!40000 ALTER TABLE `dl_customer` DISABLE KEYS */;
INSERT INTO `dl_customer` VALUES (1,'电话客服','4000000000',1,1631266261,'/upload/images/20211013/1634090752371351.png'),(2,'微信客服','JXT782766473',2,1631266261,'/upload/images/20211013/1634090739208870.png'),(3,'心想盲盒','在线联系',3,1634646180,'/upload/images/20211019/1634646176385474.png');
/*!40000 ALTER TABLE `dl_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_deliver`
--

DROP TABLE IF EXISTS `dl_deliver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_deliver` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oid` longtext NOT NULL COMMENT '赏品ID',
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1已申请 2已发货',
  `username` varchar(50) NOT NULL COMMENT '收货人',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `province` varchar(100) NOT NULL COMMENT '省',
  `city` varchar(100) NOT NULL COMMENT '市',
  `area` varchar(100) NOT NULL COMMENT '区',
  `address` varchar(150) NOT NULL COMMENT '详细地址',
  `remark` varchar(100) DEFAULT NULL COMMENT '备注',
  `express_id` int(10) DEFAULT NULL COMMENT '物流ID',
  `express_name` varchar(50) DEFAULT NULL COMMENT '物流方式',
  `express_number` varchar(50) DEFAULT NULL COMMENT '物流单号',
  `create_time` int(10) NOT NULL COMMENT '时间',
  `data` longtext NOT NULL COMMENT '数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='发货记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_deliver`
--

LOCK TABLES `dl_deliver` WRITE;
/*!40000 ALTER TABLE `dl_deliver` DISABLE KEYS */;
INSERT INTO `dl_deliver` VALUES (1,'11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,41,42,43,44,45,46,47,48,49,50,52,53,55,56,57,58,60,61,62,63,64,65,66,67,68,69,70,71,72,74,75,76,77,78,79,80,81,82,83,84,85,86,51,54,59,73,87,88,90',3,1,'778','17777888877','北京市','市辖区','西城区','古城一节',NULL,NULL,NULL,NULL,1653902855,'[{\"gid\":\"42\",\"num\":5},{\"gid\":\"38\",\"num\":1},{\"gid\":\"39\",\"num\":1},{\"gid\":\"40\",\"num\":1},{\"gid\":\"41\",\"num\":1},{\"gid\":\"11\",\"num\":62},{\"gid\":\"1\",\"num\":1},{\"gid\":\"5\",\"num\":1},{\"gid\":\"6\",\"num\":1},{\"gid\":\"7\",\"num\":1},{\"gid\":\"8\",\"num\":1},{\"gid\":\"10\",\"num\":1},{\"gid\":\"9\",\"num\":1}]'),(2,'40',3,1,'778','17777888877','北京市','市辖区','西城区','古城一节',NULL,NULL,NULL,NULL,1653902999,'[{\"gid\":\"3\",\"num\":1}]'),(3,'89',3,1,'778','17777888877','北京市','市辖区','西城区','古城一节',NULL,NULL,NULL,NULL,1653903072,'[{\"gid\":\"4\",\"num\":1}]'),(4,'92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,197,198,199,200,201,202,203,204,205,206,207,208,209,210,211',3,1,'778','17777888877','北京市','市辖区','西城区','古城一节',NULL,NULL,NULL,NULL,1653903918,'[{\"gid\":\"55\",\"num\":119}]'),(5,'212,213,214',1,1,'康逍遥','18749814010','河南省','郑州市','金水区','商务外环路王鼎国际1408',NULL,NULL,NULL,NULL,1653905539,'[{\"gid\":\"11\",\"num\":3}]'),(6,'1,2,3,4,5,6,7,8,9,10',2,1,'李基','13592611824','北京市','市辖区','西城区','王鼎国际1408',NULL,NULL,NULL,NULL,1653965585,'[{\"gid\":\"11\",\"num\":10}]'),(7,'230',2,1,'李基','13592611824','北京市','市辖区','西城区','王鼎国际1408',NULL,NULL,NULL,NULL,1653965654,'[{\"gid\":\"7\",\"num\":1}]'),(8,'231',2,1,'李基','13592611824','北京市','市辖区','西城区','王鼎国际1408',NULL,NULL,NULL,NULL,1653965910,'[{\"gid\":\"11\",\"num\":1}]'),(9,'223,225,226,227,228,229',1,1,'康逍遥','18749814010','河南省','郑州市','金水区','商务外环路王鼎国际1408',NULL,NULL,NULL,NULL,1653980764,'[{\"gid\":\"11\",\"num\":1},{\"gid\":\"121\",\"num\":5}]'),(10,'268,269,270,271,272',4,1,'刘','15236981852','北京市','市辖区','西城区','121561561',NULL,NULL,NULL,NULL,1653983599,'[{\"gid\":\"11\",\"num\":5}]'),(11,'273',4,1,'刘','15236981852','北京市','市辖区','西城区','121561561',NULL,NULL,NULL,NULL,1653983804,'[{\"gid\":\"11\",\"num\":1}]'),(12,'274',4,1,'刘','15236981852','北京市','市辖区','西城区','121561561',NULL,NULL,NULL,NULL,1653984739,'[{\"gid\":\"11\",\"num\":1}]'),(13,'275,276,277,278,279,281,283,284,280,282',1,1,'康逍遥','18749814010','河南省','郑州市','金水区','商务外环路王鼎国际1408',NULL,NULL,NULL,NULL,1653985614,'[{\"gid\":\"11\",\"num\":8},{\"gid\":\"5\",\"num\":1},{\"gid\":\"10\",\"num\":1}]');
/*!40000 ALTER TABLE `dl_deliver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_egg`
--

DROP TABLE IF EXISTS `dl_egg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_egg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `box_id` int(10) NOT NULL COMMENT '盲盒ID',
  `goods_id` int(10) DEFAULT NULL COMMENT '商品id',
  `sort` int(10) NOT NULL DEFAULT '1' COMMENT '排序值',
  `number` int(10) NOT NULL COMMENT '数量',
  `surplus` int(10) NOT NULL COMMENT '剩余',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  `ratio` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '概率',
  `is_special` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否隐藏款',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `box_id` (`box_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='盲盒商品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_egg`
--

LOCK TABLES `dl_egg` WRITE;
/*!40000 ALTER TABLE `dl_egg` DISABLE KEYS */;
/*!40000 ALTER TABLE `dl_egg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_express`
--

DROP TABLE IF EXISTS `dl_express`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_express` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '快递名称',
  `code` varchar(50) NOT NULL COMMENT '快递编码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='快递表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_express`
--

LOCK TABLES `dl_express` WRITE;
/*!40000 ALTER TABLE `dl_express` DISABLE KEYS */;
INSERT INTO `dl_express` VALUES (1,'中通快递','ZTO'),(2,'申通快递','STO'),(3,'圆通速递','YTO'),(4,'韵达速递','YD'),(5,'顺丰速运','SF'),(6,'百世快递','HTKY'),(7,'EMS','EMS');
/*!40000 ALTER TABLE `dl_express` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_goods`
--

DROP TABLE IF EXISTS `dl_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL COMMENT '商品名称',
  `image` varchar(150) NOT NULL COMMENT '图片',
  `price` float NOT NULL DEFAULT '0' COMMENT '售价',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '回收价',
  `sort` int(10) NOT NULL DEFAULT '1' COMMENT '排序值',
  `is_del` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  `is_book` tinyint(3) NOT NULL COMMENT '是否预售',
  `book_time` int(10) DEFAULT '0' COMMENT '预售时间',
  `content` text NOT NULL COMMENT '详情',
  `contract_address` varchar(50) DEFAULT NULL,
  `reward_type` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_goods`
--

LOCK TABLES `dl_goods` WRITE;
/*!40000 ALTER TABLE `dl_goods` DISABLE KEYS */;
INSERT INTO `dl_goods` VALUES (244,'B-nft-1','/upload/images/20220814/1660419490174283.jpg',1,30.00,1,1,1660419652,0,0,'<p>This is NFT item</p>','0xf9880797DBa08A05B9862C0e28CEe2fC76eFA8DA',0),(245,'B-nft-2','/upload/images/20220814/1660419672359463.jpg',1,30.00,1,1,1660419755,0,0,'<p>This is NFT item</p>','0xd36D5426733e53FC7ad2048041585FC6b39bB279',0),(246,'B-bitcoin-1','/upload/images/20220814/1660419791903833.jpg',0.0001,30.00,1,0,1660419821,0,0,'<p>This is Bitcoin item</p>',NULL,1),(247,'B-ether-1','/upload/images/20220814/1660419856104686.jpg',0.0001,30.00,1,0,1660419963,0,0,'<p>This is Ethereum item</p>',NULL,2),(248,'B-sol-1','/upload/images/20220814/166042002227131.jpg',0.001,30.00,1,0,1660420052,0,0,'<p>This is Sol item</p>',NULL,3),(249,'B-matic-1','/upload/images/20220814/1660420071212611.jpg',0.01,30.00,1,0,1660420125,0,1660406400,'<p>This is Matic item</p>',NULL,4),(250,'B-usdc-1','/upload/images/20220814/1660420142953123.png',0.01,30.00,1,0,1660420169,0,1660406400,'<p>This is USDC item</p>',NULL,5);
/*!40000 ALTER TABLE `dl_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_help`
--

DROP TABLE IF EXISTS `dl_help`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_help` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='客服问题表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_help`
--

LOCK TABLES `dl_help` WRITE;
/*!40000 ALTER TABLE `dl_help` DISABLE KEYS */;
INSERT INTO `dl_help` VALUES (1,'获取优惠券','购买186后，未获得店主身份的可获得店主身份，并且可获得800未用优惠券，已获得店主身份的可再次获得800未用优惠券。',1630315515),(2,'赠送好友','进入我的，点邀请好友，生成优惠券二维码图片，保存本地，发给亲友。对方扫码注册，下载享优汇APP，进入领取优惠券，充值成功即可。',1631007439),(3,'运营商选择','对应自己的手机号码选择，联通，移动，电信，不要选错，如果选错此次充值不成功，所支付金额会原路退回。',1631007652);
/*!40000 ALTER TABLE `dl_help` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_level`
--

DROP TABLE IF EXISTS `dl_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_level` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '等级名称',
  `sort` int(10) NOT NULL COMMENT '排序值',
  `level` varchar(50) NOT NULL COMMENT '级别',
  `create_time` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='翻赏等级表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_level`
--

LOCK TABLES `dl_level` WRITE;
/*!40000 ALTER TABLE `dl_level` DISABLE KEYS */;
INSERT INTO `dl_level` VALUES (2,'全局赏',2,'W',1646290186),(3,'First赏',3,'First',1646290474),(4,'Last赏',4,'Last',1646290474),(5,'SP赏',5,'SP',1646290562),(6,'A赏',6,'A',1646290577),(7,'B赏',7,'B',1646290595),(8,'C赏',8,'C',1646290617),(9,'D赏',9,'D',1646290633),(10,'E赏',10,'E',1646290648),(11,'F赏',11,'F',1646290661),(12,'G赏',12,'G',1646290661),(13,'战利品',2,'War',1646290661),(14,'挑战者',14,'Challenge',1646290661),(15,'H赏',14,'H',1646290661),(16,'I赏',15,'I',1648299211),(17,'J赏',16,'J',1648383732),(18,'K赏',17,'K',1650048481),(19,'L赏',19,'L',1650216019),(20,'M赏',20,'M',1650216035),(21,'N赏',21,'N',1650216044),(22,'O赏',22,'O',1650216057),(23,'P赏',23,'P',1650216067),(24,'Q赏',24,'Q',1650216085),(25,'R赏',25,'R',1650216098);
/*!40000 ALTER TABLE `dl_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_nft_returnList`
--

DROP TABLE IF EXISTS `dl_nft_returnList`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_nft_returnList` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `price` float NOT NULL DEFAULT '0',
  `img` tinytext NOT NULL,
  `name` varchar(50) NOT NULL,
  `returnTime` int(11) NOT NULL,
  `quantity` float NOT NULL DEFAULT '0',
  `integral` float NOT NULL DEFAULT '0',
  `description` tinytext NOT NULL,
  `level` varchar(50) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_nft_returnList`
--

LOCK TABLES `dl_nft_returnList` WRITE;
/*!40000 ALTER TABLE `dl_nft_returnList` DISABLE KEYS */;
INSERT INTO `dl_nft_returnList` VALUES (3,237,0.7,'/upload/images/20220812/1660236970869039.jpg','TestGood1',1660305373,1,0.7,'<p>This is NFT - 1 item</p>','A',28),(4,237,0.7,'/upload/images/20220812/1660236970869039.jpg','TestGood1',1660306366,1,0.7,'<p>This is NFT - 1 item</p>','A',28),(5,237,0.7,'/upload/images/20220812/1660236970869039.jpg','TestGood1',1660306366,1,0.7,'<p>This is NFT - 1 item</p>','A',28),(6,237,0.7,'/upload/images/20220812/1660236970869039.jpg','TestGood1',1660329918,1,0.7,'<p>This is NFT - 1 item</p>','A',28),(7,244,0.7,'/upload/images/20220814/1660419490174283.jpg','B-nft-1',1660421153,1,0.7,'<p>This is NFT item</p>','A',35);
/*!40000 ALTER TABLE `dl_nft_returnList` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_nft_withdrawList`
--

DROP TABLE IF EXISTS `dl_nft_withdrawList`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_nft_withdrawList` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `img` tinytext NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_nft_withdrawList`
--

LOCK TABLES `dl_nft_withdrawList` WRITE;
/*!40000 ALTER TABLE `dl_nft_withdrawList` DISABLE KEYS */;
INSERT INTO `dl_nft_withdrawList` VALUES (2,237,'/upload/images/20220812/1660236970869039.jpg','TestGood1','0xa8A86B82AeFbf31e9DFFB7C53dD1b3c2ad9A591f'),(3,244,'/upload/images/20220814/1660419490174283.jpg','B-nft-1','0x5Da6D29C69Ac99f99dB2da64b115e267F08C3bE4');
/*!40000 ALTER TABLE `dl_nft_withdrawList` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_order`
--

DROP TABLE IF EXISTS `dl_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `order_no` varchar(50) NOT NULL COMMENT '订单号',
  `type` tinyint(3) NOT NULL COMMENT '1普通盲盒 2邮费 3充值',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `pay_status` tinyint(3) DEFAULT '0' COMMENT '0未支付 1已支付',
  `pay_time` int(10) DEFAULT '0' COMMENT '支付时间',
  `create_time` int(10) NOT NULL COMMENT '支付时间',
  `box_id` varchar(10) DEFAULT '0' COMMENT '盲盒ID',
  `transaction_id` varchar(100) DEFAULT NULL COMMENT '第三方支付订单id',
  `deliver_id` varchar(50) DEFAULT '' COMMENT '待发货ID',
  `suit_id` int(10) DEFAULT NULL COMMENT '箱子ID',
  `num` int(10) NOT NULL COMMENT '数量',
  `is_prize` tinyint(3) NOT NULL DEFAULT '1' COMMENT '是否开奖',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_order`
--

LOCK TABLES `dl_order` WRITE;
/*!40000 ALTER TABLE `dl_order` DISABLE KEYS */;
INSERT INTO `dl_order` VALUES (1,2,'522051376151411',1,880.00,1,1653722051,1653722051,'1',NULL,'',1,10,1),(2,3,'5027393424172539',1,220.00,1,1653902739,1653902739,'4',NULL,'',251,5,1),(3,3,'502758103172558',1,88.00,1,1653902758,1653902758,'1',NULL,'',1,1,1),(4,3,'5027727586172612',1,880.00,1,1653902772,1653902772,'1',NULL,'',1,10,1),(5,3,'5027775931172617',1,880.00,1,1653902777,1653902777,'1',NULL,'',1,10,1),(6,3,'5027824014172622',1,880.00,1,1653902782,1653902782,'1',NULL,'',1,10,1),(7,3,'502788102172628',1,3432.00,1,1653902788,1653902788,'1',NULL,'',1,39,1),(8,3,'5032379579173357',1,130.00,1,1653903237,1653903237,'2',NULL,'',101,10,1),(9,3,'5032435962173403',1,130.00,1,1653903243,1653903243,'2',NULL,'',101,10,1),(10,3,'5032504922173410',1,130.00,1,1653903250,1653903250,'2',NULL,'',101,10,1),(11,3,'5032541009173414',1,130.00,1,1653903254,1653903254,'2',NULL,'',101,10,1),(12,3,'5032587105173418',1,130.00,1,1653903258,1653903258,'2',NULL,'',101,10,1),(13,3,'5032619955173421',1,130.00,1,1653903261,1653903261,'2',NULL,'',101,10,1),(14,3,'5032659066173425',1,130.00,1,1653903265,1653903265,'2',NULL,'',101,10,1),(15,3,'5032683450173428',1,130.00,1,1653903268,1653903268,'2',NULL,'',101,10,1),(16,3,'5032715076173431',1,130.00,1,1653903271,1653903271,'2',NULL,'',101,10,1),(17,3,'5032756799173435',1,130.00,1,1653903275,1653903275,'2',NULL,'',101,10,1),(18,3,'5032788442173438',1,130.00,1,1653903278,1653903278,'2',NULL,'',101,10,1),(19,3,'5032822958173442',1,130.00,1,1653903282,1653903282,'2',NULL,'',101,10,1),(20,1,'5054443476181044',1,88.00,1,1653905444,1653905444,'1',NULL,'',2,1,1),(21,1,'5054514370181051',1,88.00,1,1653905451,1653905451,'1',NULL,'',2,1,1),(22,1,'5054804491181120',1,88.00,1,1653905480,1653905480,'1',NULL,'',2,1,1),(23,1,'5057548333181554',1,880.00,1,1653905754,1653905754,'1',NULL,'',2,10,1),(24,1,'5060426165182042',1,25.00,1,1653906042,1653906042,'6',NULL,'',0,5,1),(25,2,'5656412288105401',1,88.00,1,1653965641,1653965641,'1',NULL,'',2,1,1),(26,2,'5658814457105801',1,88.00,1,1653965881,1653965881,'1',NULL,'',2,1,1),(27,2,'5659443100105904',1,440.00,1,1653965944,1653965944,'1',NULL,'',2,5,1),(28,2,'565959890105919',1,130.00,1,1653965959,1653965959,'2',NULL,'',101,10,1),(29,2,'5660238483110023',1,880.00,1,1653966023,1653966023,'1',NULL,'',2,10,1),(30,2,'5780558300142055',1,880.00,1,1653978055,1653978055,'1',NULL,'',2,10,1),(31,4,'5831685866154608',1,440.00,1,1653983168,1653983168,'1',NULL,'',2,5,1),(32,4,'5837914448155631',1,88.00,1,1653983791,1653983791,'1',NULL,'',2,1,1),(33,4,'5847291737161209',1,88.00,1,1653984729,1653984729,'1',NULL,'',2,1,1),(34,1,'58560114162641',1,880.00,1,1653985601,1653985601,'1',NULL,'',2,10,1),(35,3,'6762797955173759',1,440.00,1,1654076279,1654076279,'1',NULL,'',2,5,1),(36,3,'677486699175806',1,90.00,1,1654077486,1654077486,'5',NULL,'',451,10,1),(37,3,'6775115063175831',1,45.00,1,1654077511,1654077511,'5',NULL,'',451,5,1),(38,3,'6775178594175837',1,90.00,1,1654077517,1654077517,'5',NULL,'',451,10,1),(39,3,'6776201007180020',1,88.00,1,1654077620,1654077620,'4',NULL,'',252,2,1),(40,3,'6776531972180053',1,25.00,1,1654077653,1654077653,'6',NULL,'',0,5,1),(41,3,'6779121561180512',1,112.00,1,1654077912,1654077912,'3',NULL,'',151,2,1),(42,3,'6779218204180521',1,56.00,1,1654077921,1654077921,'3',NULL,'',151,1,1),(43,3,'6779971388180637',1,13.00,1,1654077997,1654077997,'2',NULL,'',101,1,1),(44,3,'6780397871180719',1,65.00,1,1654078039,1654078039,'2',NULL,'',101,5,1),(45,4,'6782729476181112',1,88.00,1,1654078272,1654078272,'1',NULL,'',2,1,1),(46,4,'6797429319183542',1,44.00,1,1654079742,1654079742,'4',NULL,'',252,1,1),(47,4,'6799316117183851',1,88.00,1,1654079931,1654079931,'1',NULL,'',2,1,1),(48,4,'679977685183937',1,88.00,1,1654079977,1654079977,'1',NULL,'',2,1,1),(49,4,'6800289591184028',1,9.00,1,1654080028,1654080028,'5',NULL,'',451,1,1),(50,4,'6801362041184216',1,5.00,1,1654080136,1654080136,'6',NULL,'',0,1,1),(51,4,'6801637832184243',1,88.00,1,1654080163,1654080163,'1',NULL,'',2,1,1),(52,4,'6802595968184419',1,88.00,1,1654080259,1654080259,'1',NULL,'',2,1,1),(53,1,'6802673966184427',1,440.00,1,1654080267,1654080267,'1',NULL,'',2,5,1),(54,1,'6803467796184546',1,440.00,1,1654080346,1654080346,'1',NULL,'',2,5,1),(55,1,'680437939184717',1,88.00,1,1654080437,1654080437,'1',NULL,'',2,1,1),(56,3,'680509155184829',1,88.00,1,1654080509,1654080509,'1',NULL,'',2,1,1),(57,4,'6805275787184847',1,5.00,1,1654080527,1654080527,'6',NULL,'',0,1,1),(58,3,'6865915395202951',1,112.00,1,1654086591,1654086591,'3',NULL,'',152,2,1),(59,3,'631859480163739',1,88.00,1,1654331859,1654331859,'1',NULL,'',2,1,1),(60,3,'6028993150132819',1,13.00,1,1655702899,1655702899,'2',NULL,'',141,1,1),(61,3,'6319295615213209',1,44.00,1,1655731929,1655731929,'4',NULL,'',252,1,1),(62,3,'6320123999213332',1,88.00,1,1655732012,1655732012,'1',NULL,'',3,1,1),(63,3,'6320429531213402',1,88.00,1,1655732042,1655732042,'1',NULL,'',3,1,1),(64,3,'6144893669202809',1,56.00,1,1655814489,1655814489,'3',NULL,'',152,1,1),(65,3,'61450594202825',1,56.00,1,1655814505,1655814505,'3',NULL,'',202,1,1),(66,3,'614511872202831',1,112.00,1,1655814511,1655814511,'3',NULL,'',202,2,1),(67,3,'6145812269202941',1,112.00,1,1655814581,1655814581,'3',NULL,'',211,2,1),(68,3,'6146878905203127',1,45.00,1,1655814687,1655814687,'5',NULL,'',451,5,1),(69,3,'6147255232203205',1,56.00,1,1655814725,1655814725,'3',NULL,'',153,1,1),(70,3,'6157061482204826',1,5.00,1,1655815706,1655815706,'6',NULL,'',0,1,1),(71,3,'6232404536024040',1,1.00,1,1655923240,1655923240,'8',NULL,'',551,1,1),(72,3,'7174578871222417',1,5.00,1,1657117457,1657117457,'6',NULL,'',0,1,1),(73,3,'7306179551093657',1,1.00,1,1657330617,1657330617,'8',NULL,'',552,1,1),(74,3,'7470428300141042',1,1.00,1,1657347042,1657347042,'8',NULL,'',553,1,1),(75,3,'735005143221005',1,1.00,1,1657635005,1657635005,'8',NULL,'',554,1,1),(76,3,'7383162574214516',1,0.10,1,1658238316,1658238316,'9',NULL,'',561,1,1),(77,3,'7015544082151914',1,1.00,1,1658301554,1658301554,'8',NULL,'',555,1,1);
/*!40000 ALTER TABLE `dl_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_order_address`
--

DROP TABLE IF EXISTS `dl_order_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_order_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `og_id` int(10) NOT NULL COMMENT '订单商品ID',
  `username` varchar(100) NOT NULL COMMENT '收货人',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `province` varchar(100) NOT NULL COMMENT '省',
  `city` varchar(100) NOT NULL COMMENT '市',
  `area` varchar(100) NOT NULL COMMENT '区',
  `address` varchar(150) NOT NULL COMMENT '详细地址',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单地址表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_order_address`
--

LOCK TABLES `dl_order_address` WRITE;
/*!40000 ALTER TABLE `dl_order_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `dl_order_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_order_goods`
--

DROP TABLE IF EXISTS `dl_order_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL COMMENT '订单ID',
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `gid` int(10) NOT NULL COMMENT '盲盒商品ID',
  `name` varchar(150) NOT NULL COMMENT '商品名称',
  `image` varchar(150) NOT NULL COMMENT '图片',
  `price` varchar(10) NOT NULL COMMENT '价格',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '回收价',
  `status` tinyint(3) NOT NULL COMMENT '1待操作 2保险柜 3待发货 4已发货 5已挂售',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  `box_id` int(10) DEFAULT NULL COMMENT '盲盒ID',
  `suit_id` int(10) DEFAULT NULL COMMENT '箱子ID',
  `level` varchar(50) DEFAULT '' COMMENT '等级',
  `level_name` varchar(100) DEFAULT NULL COMMENT '级别名称',
  `is_special` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否特殊赏',
  `number` int(10) NOT NULL COMMENT '次数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=386 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单商品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_order_goods`
--

LOCK TABLES `dl_order_goods` WRITE;
/*!40000 ALTER TABLE `dl_order_goods` DISABLE KEYS */;
INSERT INTO `dl_order_goods` VALUES (1,1,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653722051,1,1,'H','H赏',0,1),(2,1,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653722051,1,1,'H','H赏',0,2),(3,1,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653722051,1,1,'H','H赏',0,3),(4,1,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653722051,1,1,'H','H赏',0,4),(5,1,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653722051,1,1,'H','H赏',0,5),(6,1,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653722051,1,1,'H','H赏',0,6),(7,1,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653722051,1,1,'H','H赏',0,7),(8,1,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653722051,1,1,'H','H赏',0,8),(9,1,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653722051,1,1,'H','H赏',0,9),(10,1,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653722051,1,1,'H','H赏',0,10),(11,2,3,42,'帅LIU潮卷','/upload/images/20220416/1650047457838881.jpg','44.00',1.00,3,1653902739,4,251,'Challenge','挑战者',0,4),(12,2,3,42,'帅LIU潮卷','/upload/images/20220416/1650047457838881.jpg','44.00',1.00,3,1653902739,4,251,'Challenge','挑战者',0,5),(13,2,3,42,'帅LIU潮卷','/upload/images/20220416/1650047457838881.jpg','44.00',1.00,3,1653902739,4,251,'Challenge','挑战者',0,3),(14,2,3,42,'帅LIU潮卷','/upload/images/20220416/1650047457838881.jpg','44.00',1.00,3,1653902739,4,251,'Challenge','挑战者',0,1),(15,2,3,42,'帅LIU潮卷','/upload/images/20220416/1650047457838881.jpg','44.00',1.00,3,1653902739,4,251,'Challenge','挑战者',0,2),(16,2,3,38,'雕像香薰蜡烛','/upload/images/20220416/1650047394907810.png','44.00',85.00,3,1653902739,4,251,'War','战利品',1,6),(17,2,3,39,'香薰蜡烛','/upload/images/20220416/1650047411323103.png','44.00',55.00,3,1653902739,4,251,'War','战利品',1,7),(18,2,3,40,'蜡烛无烟熏香','/upload/images/20220416/1650047428220155.png','44.00',35.00,3,1653902739,4,251,'War','战利品',1,8),(19,2,3,41,'浪漫无烟蜡烛灯','/upload/images/20220416/1650047442603558.png','44.00',20.00,3,1653902739,4,251,'War','战利品',1,9),(20,3,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902758,1,1,'H','H赏',0,11),(21,4,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902772,1,1,'H','H赏',0,12),(22,4,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902772,1,1,'H','H赏',0,13),(23,4,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902772,1,1,'H','H赏',0,14),(24,4,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902772,1,1,'H','H赏',0,15),(25,4,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902772,1,1,'H','H赏',0,16),(26,4,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902772,1,1,'H','H赏',0,17),(27,4,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902772,1,1,'H','H赏',0,18),(28,4,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902772,1,1,'H','H赏',0,19),(29,4,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902772,1,1,'H','H赏',0,20),(30,4,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902772,1,1,'H','H赏',0,21),(31,5,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902777,1,1,'H','H赏',0,22),(32,5,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902777,1,1,'H','H赏',0,23),(33,5,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902777,1,1,'H','H赏',0,24),(34,5,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902777,1,1,'H','H赏',0,25),(35,5,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902777,1,1,'H','H赏',0,26),(36,5,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902777,1,1,'H','H赏',0,27),(37,5,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902777,1,1,'H','H赏',0,28),(38,5,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902777,1,1,'H','H赏',0,29),(39,5,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902777,1,1,'H','H赏',0,30),(40,5,3,3,'popwa乔巴','/upload/images/20220416/1650039390951194.jpg','88.00',888.00,3,1653902777,1,1,'SP','SP赏',0,31),(41,6,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902782,1,1,'H','H赏',0,32),(42,6,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902782,1,1,'H','H赏',0,33),(43,6,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902782,1,1,'H','H赏',0,34),(44,6,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902782,1,1,'H','H赏',0,35),(45,6,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902782,1,1,'H','H赏',0,36),(46,6,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902782,1,1,'H','H赏',0,37),(47,6,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902782,1,1,'H','H赏',0,38),(48,6,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902782,1,1,'H','H赏',0,39),(49,6,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902782,1,1,'H','H赏',0,40),(50,6,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902782,1,1,'H','H赏',0,41),(51,5,3,1,'popmax老沙','/upload/images/20220416/1650039333687705.jpg','88.00',1599.00,3,1653902782,1,1,'First','First赏',1,81),(52,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,42),(53,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,43),(54,7,3,5,'zero黄猿','/upload/images/20220416/1650039441976891.png','88.00',450.00,3,1653902788,1,1,'B','B赏',0,44),(55,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,45),(56,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,46),(57,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,47),(58,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,48),(59,7,3,6,'zero赤犬','/upload/images/20220416/1650039458682457.jpg','88.00',400.00,3,1653902788,1,1,'C','C赏',0,49),(60,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,50),(61,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,51),(62,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,52),(63,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,53),(64,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,54),(65,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,55),(66,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,56),(67,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,57),(68,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,58),(69,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,59),(70,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,60),(71,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,61),(72,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,62),(73,7,3,7,'zero青雉','/upload/images/20220416/1650039478232589.jpg','88.00',350.00,3,1653902788,1,1,'D','D赏',0,63),(74,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,64),(75,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,65),(76,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,66),(77,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,67),(78,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,68),(79,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,69),(80,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,70),(81,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,71),(82,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,72),(83,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,73),(84,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,74),(85,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,75),(86,7,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653902788,1,1,'H','H赏',0,76),(87,7,3,8,'zero罗杰','/upload/images/20220416/165003951511349.jpg','88.00',320.00,3,1653902788,1,1,'E','E赏',0,77),(88,7,3,10,'女帝景品','/upload/images/20220416/1650039551128762.jpg','88.00',100.00,3,1653902788,1,1,'G','G赏',0,78),(89,7,3,4,'zero女帝芳香脚','/upload/images/20220416/1650039411266671.jpg','88.00',700.00,3,1653902788,1,1,'A','A赏',0,79),(90,7,3,9,'zero御田','/upload/images/20220416/1650039534611656.png','88.00',220.00,3,1653902788,1,1,'F','F赏',0,80),(91,7,3,2,'popwa罗','/upload/images/20220416/1650039365546039.jpg','88.00',1299.00,2,1653902788,1,1,'Last','Last赏',1,82),(92,8,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903237,2,101,'J','J赏',0,1),(93,8,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903237,2,101,'J','J赏',0,2),(94,8,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903237,2,101,'J','J赏',0,3),(95,8,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903237,2,101,'J','J赏',0,4),(96,8,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903237,2,101,'J','J赏',0,5),(97,8,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903237,2,101,'J','J赏',0,6),(98,8,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903237,2,101,'J','J赏',0,7),(99,8,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903237,2,101,'J','J赏',0,8),(100,8,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903237,2,101,'J','J赏',0,9),(101,8,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903237,2,101,'J','J赏',0,10),(102,9,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903243,2,101,'J','J赏',0,11),(103,9,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903243,2,101,'J','J赏',0,12),(104,9,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903243,2,101,'J','J赏',0,13),(105,9,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903243,2,101,'J','J赏',0,14),(106,9,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903243,2,101,'J','J赏',0,15),(107,9,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903243,2,101,'J','J赏',0,16),(108,9,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903243,2,101,'J','J赏',0,17),(109,9,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903243,2,101,'J','J赏',0,18),(110,9,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903243,2,101,'J','J赏',0,19),(111,9,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903243,2,101,'J','J赏',0,20),(112,10,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903250,2,101,'J','J赏',0,21),(113,10,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903250,2,101,'J','J赏',0,22),(114,10,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903250,2,101,'J','J赏',0,23),(115,10,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903250,2,101,'J','J赏',0,24),(116,10,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903250,2,101,'J','J赏',0,25),(117,10,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903250,2,101,'J','J赏',0,26),(118,10,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903250,2,101,'J','J赏',0,27),(119,10,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903250,2,101,'J','J赏',0,28),(120,10,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903250,2,101,'J','J赏',0,29),(121,10,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903250,2,101,'J','J赏',0,30),(122,11,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903254,2,101,'J','J赏',0,31),(123,11,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903254,2,101,'J','J赏',0,32),(124,11,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903254,2,101,'J','J赏',0,33),(125,11,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903254,2,101,'J','J赏',0,34),(126,11,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903254,2,101,'J','J赏',0,35),(127,11,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903254,2,101,'J','J赏',0,36),(128,11,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903254,2,101,'J','J赏',0,37),(129,11,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903254,2,101,'J','J赏',0,38),(130,11,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903254,2,101,'J','J赏',0,39),(131,11,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903254,2,101,'J','J赏',0,40),(132,12,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903258,2,101,'J','J赏',0,41),(133,12,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903258,2,101,'J','J赏',0,42),(134,12,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903258,2,101,'J','J赏',0,43),(135,12,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903258,2,101,'J','J赏',0,44),(136,12,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903258,2,101,'J','J赏',0,45),(137,12,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903258,2,101,'J','J赏',0,46),(138,12,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903258,2,101,'J','J赏',0,47),(139,12,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903258,2,101,'J','J赏',0,48),(140,12,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903258,2,101,'J','J赏',0,49),(141,12,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903258,2,101,'J','J赏',0,50),(142,13,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903261,2,101,'J','J赏',0,51),(143,13,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903261,2,101,'J','J赏',0,52),(144,13,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903261,2,101,'J','J赏',0,53),(145,13,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903261,2,101,'J','J赏',0,54),(146,13,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903261,2,101,'J','J赏',0,55),(147,13,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903261,2,101,'J','J赏',0,56),(148,13,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903261,2,101,'J','J赏',0,57),(149,13,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903261,2,101,'J','J赏',0,58),(150,13,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903261,2,101,'J','J赏',0,59),(151,13,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903261,2,101,'J','J赏',0,60),(152,14,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903265,2,101,'J','J赏',0,61),(153,14,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903265,2,101,'J','J赏',0,62),(154,14,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903265,2,101,'J','J赏',0,63),(155,14,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903265,2,101,'J','J赏',0,64),(156,14,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903265,2,101,'J','J赏',0,65),(157,14,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903265,2,101,'J','J赏',0,66),(158,14,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903265,2,101,'J','J赏',0,67),(159,14,3,49,'布罗利gk','/upload/images/20220416/1650048037924962.png','13.00',550.00,2,1653903265,2,101,'D','D赏',0,68),(160,14,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903265,2,101,'J','J赏',0,69),(161,14,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903265,2,101,'J','J赏',0,70),(162,15,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903268,2,101,'J','J赏',0,71),(163,15,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903268,2,101,'J','J赏',0,72),(164,15,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903268,2,101,'J','J赏',0,73),(165,15,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903268,2,101,'J','J赏',0,74),(166,15,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903268,2,101,'J','J赏',0,75),(167,15,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903268,2,101,'J','J赏',0,76),(168,15,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903268,2,101,'J','J赏',0,77),(169,15,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903268,2,101,'J','J赏',0,78),(170,15,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903268,2,101,'J','J赏',0,79),(171,15,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903268,2,101,'J','J赏',0,80),(172,16,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903271,2,101,'J','J赏',0,81),(173,16,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903271,2,101,'J','J赏',0,82),(174,16,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903271,2,101,'J','J赏',0,83),(175,16,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903271,2,101,'J','J赏',0,84),(176,16,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903271,2,101,'J','J赏',0,85),(177,16,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903271,2,101,'J','J赏',0,86),(178,16,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903271,2,101,'J','J赏',0,87),(179,16,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903271,2,101,'J','J赏',0,88),(180,16,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903271,2,101,'J','J赏',0,89),(181,16,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903271,2,101,'J','J赏',0,90),(182,17,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903275,2,101,'J','J赏',0,91),(183,17,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903275,2,101,'J','J赏',0,92),(184,17,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903275,2,101,'J','J赏',0,93),(185,17,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903275,2,101,'J','J赏',0,94),(186,17,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903275,2,101,'J','J赏',0,95),(187,17,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903275,2,101,'J','J赏',0,96),(188,17,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903275,2,101,'J','J赏',0,97),(189,17,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903275,2,101,'J','J赏',0,98),(190,17,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903275,2,101,'J','J赏',0,99),(191,17,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903275,2,101,'J','J赏',0,100),(192,18,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903278,2,101,'J','J赏',0,101),(193,18,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903278,2,101,'J','J赏',0,102),(194,18,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903278,2,101,'J','J赏',0,103),(195,18,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903278,2,101,'J','J赏',0,104),(196,18,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903278,2,101,'J','J赏',0,105),(197,18,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903278,2,101,'J','J赏',0,106),(198,18,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903278,2,101,'J','J赏',0,107),(199,18,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903278,2,101,'J','J赏',0,108),(200,18,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903278,2,101,'J','J赏',0,109),(201,18,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903278,2,101,'J','J赏',0,110),(202,19,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903282,2,101,'J','J赏',0,111),(203,19,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903282,2,101,'J','J赏',0,112),(204,19,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903282,2,101,'J','J赏',0,113),(205,19,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903282,2,101,'J','J赏',0,114),(206,19,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903282,2,101,'J','J赏',0,115),(207,19,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903282,2,101,'J','J赏',0,116),(208,19,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903282,2,101,'J','J赏',0,117),(209,19,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903282,2,101,'J','J赏',0,118),(210,19,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903282,2,101,'J','J赏',0,119),(211,19,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,3,1653903282,2,101,'J','J赏',0,120),(212,20,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653905444,1,2,'H','H赏',0,1),(213,21,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653905451,1,2,'H','H赏',0,2),(214,22,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653905480,1,2,'H','H赏',0,3),(215,23,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,5,1653905754,1,2,'H','H赏',0,4),(216,23,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,5,1653905754,1,2,'H','H赏',0,5),(217,23,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,5,1653905754,1,2,'H','H赏',0,6),(218,23,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,5,1653905754,1,2,'H','H赏',0,7),(219,23,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,5,1653905754,1,2,'H','H赏',0,8),(220,23,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,5,1653905754,1,2,'H','H赏',0,9),(221,23,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,5,1653905754,1,2,'H','H赏',0,10),(222,23,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,5,1653905754,1,2,'H','H赏',0,11),(223,23,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653905754,1,2,'H','H赏',0,12),(224,23,1,9,'zero御田','/upload/images/20220416/1650039534611656.png','88.00',220.00,5,1653905754,1,2,'F','F赏',0,13),(225,24,1,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,3,1653906042,6,0,'O','O赏',0,1),(226,24,1,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,3,1653906042,6,0,'O','O赏',0,2),(227,24,1,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,3,1653906042,6,0,'O','O赏',0,3),(228,24,1,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,3,1653906042,6,0,'O','O赏',0,4),(229,24,1,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,3,1653906042,6,0,'O','O赏',0,5),(230,25,2,7,'zero青雉','/upload/images/20220416/1650039478232589.jpg','88.00',350.00,3,1653965641,1,2,'D','D赏',0,14),(231,26,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653965881,1,2,'H','H赏',0,15),(232,27,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653965945,1,2,'H','H赏',0,16),(233,27,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653965945,1,2,'H','H赏',0,17),(234,27,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653965945,1,2,'H','H赏',0,18),(235,27,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653965945,1,2,'H','H赏',0,19),(236,27,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653965945,1,2,'H','H赏',0,20),(237,28,2,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1653965959,2,101,'J','J赏',0,121),(238,28,2,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1653965959,2,101,'J','J赏',0,122),(239,28,2,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1653965959,2,101,'J','J赏',0,123),(240,28,2,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1653965959,2,101,'J','J赏',0,124),(241,28,2,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1653965959,2,101,'J','J赏',0,125),(242,28,2,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1653965959,2,101,'J','J赏',0,126),(243,28,2,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1653965959,2,101,'J','J赏',0,127),(244,28,2,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1653965959,2,101,'J','J赏',0,128),(245,28,2,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1653965959,2,101,'J','J赏',0,129),(246,28,2,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1653965959,2,101,'J','J赏',0,130),(247,29,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653966023,1,2,'H','H赏',0,21),(248,29,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653966023,1,2,'H','H赏',0,22),(249,29,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653966023,1,2,'H','H赏',0,23),(250,29,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653966023,1,2,'H','H赏',0,24),(251,29,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653966023,1,2,'H','H赏',0,25),(252,29,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653966023,1,2,'H','H赏',0,26),(253,29,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653966023,1,2,'H','H赏',0,27),(254,29,2,3,'popwa乔巴','/upload/images/20220416/1650039390951194.jpg','88.00',888.00,1,1653966023,1,2,'SP','SP赏',0,28),(255,29,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653966023,1,2,'H','H赏',0,29),(256,29,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653966023,1,2,'H','H赏',0,30),(257,30,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653978055,1,2,'H','H赏',0,31),(258,30,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653978055,1,2,'H','H赏',0,32),(259,30,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653978055,1,2,'H','H赏',0,33),(260,30,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653978055,1,2,'H','H赏',0,34),(261,30,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653978055,1,2,'H','H赏',0,35),(262,30,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653978055,1,2,'H','H赏',0,36),(263,30,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653978055,1,2,'H','H赏',0,37),(264,30,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653978055,1,2,'H','H赏',0,38),(265,30,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653978055,1,2,'H','H赏',0,39),(266,30,2,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1653978055,1,2,'H','H赏',0,40),(267,29,2,1,'popmax老沙','/upload/images/20220416/1650039333687705.jpg','88.00',1599.00,1,1653978055,1,2,'First','First赏',1,81),(268,31,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653983168,1,2,'H','H赏',0,41),(269,31,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653983168,1,2,'H','H赏',0,42),(270,31,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653983168,1,2,'H','H赏',0,43),(271,31,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653983168,1,2,'H','H赏',0,44),(272,31,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653983168,1,2,'H','H赏',0,45),(273,32,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653983791,1,2,'H','H赏',0,46),(274,33,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653984729,1,2,'H','H赏',0,47),(275,34,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653985601,1,2,'H','H赏',0,48),(276,34,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653985601,1,2,'H','H赏',0,49),(277,34,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653985601,1,2,'H','H赏',0,50),(278,34,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653985601,1,2,'H','H赏',0,51),(279,34,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653985601,1,2,'H','H赏',0,52),(280,34,1,5,'zero黄猿','/upload/images/20220416/1650039441976891.png','88.00',450.00,3,1653985601,1,2,'B','B赏',0,53),(281,34,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653985601,1,2,'H','H赏',0,54),(282,34,1,10,'女帝景品','/upload/images/20220416/1650039551128762.jpg','88.00',100.00,3,1653985601,1,2,'G','G赏',0,55),(283,34,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653985601,1,2,'H','H赏',0,56),(284,34,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,3,1653985601,1,2,'H','H赏',0,57),(285,35,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,2,1654076279,1,2,'H','H赏',0,58),(286,35,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,2,1654076279,1,2,'H','H赏',0,59),(287,35,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,2,1654076279,1,2,'H','H赏',0,60),(288,35,3,6,'zero赤犬','/upload/images/20220416/1650039458682457.jpg','88.00',400.00,2,1654076279,1,2,'C','C赏',0,61),(289,35,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,2,1654076279,1,2,'H','H赏',0,62),(290,36,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077486,5,451,'Challenge','挑战者',0,50),(291,36,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077486,5,451,'Challenge','挑战者',0,72),(292,36,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077486,5,451,'Challenge','挑战者',0,51),(293,36,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077486,5,451,'Challenge','挑战者',0,78),(294,36,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077486,5,451,'Challenge','挑战者',0,76),(295,36,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077486,5,451,'Challenge','挑战者',0,95),(296,36,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077486,5,451,'Challenge','挑战者',0,70),(297,36,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077486,5,451,'Challenge','挑战者',0,49),(298,36,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077486,5,451,'Challenge','挑战者',0,1),(299,36,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077486,5,451,'Challenge','挑战者',0,44),(300,37,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077511,5,451,'Challenge','挑战者',0,93),(301,37,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077511,5,451,'Challenge','挑战者',0,84),(302,37,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077511,5,451,'Challenge','挑战者',0,65),(303,37,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077511,5,451,'Challenge','挑战者',0,37),(304,37,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077511,5,451,'Challenge','挑战者',0,63),(305,38,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077517,5,451,'Challenge','挑战者',0,31),(306,38,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077517,5,451,'Challenge','挑战者',0,4),(307,38,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077517,5,451,'Challenge','挑战者',0,29),(308,38,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077517,5,451,'Challenge','挑战者',0,69),(309,38,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077517,5,451,'Challenge','挑战者',0,30),(310,38,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077517,5,451,'Challenge','挑战者',0,94),(311,38,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077517,5,451,'Challenge','挑战者',0,77),(312,38,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077517,5,451,'Challenge','挑战者',0,2),(313,38,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077517,5,451,'Challenge','挑战者',0,88),(314,38,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,2,1654077517,5,451,'Challenge','挑战者',0,9),(315,39,3,42,'帅LIU潮卷','/upload/images/20220416/1650047457838881.jpg','44.00',1.00,2,1654077620,4,252,'Challenge','挑战者',0,4),(316,39,3,42,'帅LIU潮卷','/upload/images/20220416/1650047457838881.jpg','44.00',1.00,2,1654077620,4,252,'Challenge','挑战者',0,1),(317,40,3,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,2,1654077653,6,0,'O','O赏',0,6),(318,40,3,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,2,1654077653,6,0,'O','O赏',0,7),(319,40,3,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,2,1654077653,6,0,'O','O赏',0,8),(320,40,3,119,'沙扎比','/upload/images/20220418/1650214941766925.png','5.00',40.00,2,1654077653,6,0,'N','N赏',0,9),(321,40,3,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,2,1654077653,6,0,'O','O赏',0,10),(322,41,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1654077912,3,151,'Challenge','挑战者',0,2),(323,41,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1654077912,3,151,'Challenge','挑战者',0,1),(324,42,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1654077921,3,151,'Challenge','挑战者',0,3),(325,41,3,23,'太空人钓星星','/upload/images/20220416/1650042874549382.jpg','56.00',85.00,1,1654077921,3,151,'War','战利品',1,4),(326,41,3,24,'星空太空人深思','/upload/images/20220416/1650042909443830.jpg','56.00',65.00,2,1654077921,3,151,'War','战利品',1,5),(327,43,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1654077997,2,101,'J','J赏',0,131),(328,44,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1654078039,2,101,'J','J赏',0,132),(329,44,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1654078039,2,101,'J','J赏',0,133),(330,44,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1654078039,2,101,'J','J赏',0,134),(331,44,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1654078039,2,101,'J','J赏',0,135),(332,44,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1654078039,2,101,'J','J赏',0,136),(333,45,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654078272,1,2,'H','H赏',0,63),(334,46,4,42,'帅LIU潮卷','/upload/images/20220416/1650047457838881.jpg','44.00',1.00,1,1654079742,4,252,'Challenge','挑战者',0,2),(335,47,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654079931,1,2,'H','H赏',0,64),(336,48,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654079977,1,2,'H','H赏',0,65),(337,49,4,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,1,1654080028,5,451,'Challenge','挑战者',0,26),(338,50,4,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,1,1654080136,6,0,'O','O赏',0,11),(339,51,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080163,1,2,'H','H赏',0,66),(340,52,4,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080259,1,2,'H','H赏',0,67),(341,53,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080267,1,2,'H','H赏',0,68),(342,53,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080267,1,2,'H','H赏',0,69),(343,53,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080267,1,2,'H','H赏',0,70),(344,53,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080267,1,2,'H','H赏',0,71),(345,53,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080267,1,2,'H','H赏',0,72),(346,54,1,4,'zero女帝芳香脚','/upload/images/20220416/1650039411266671.jpg','88.00',700.00,1,1654080346,1,2,'A','A赏',0,73),(347,54,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080346,1,2,'H','H赏',0,74),(348,54,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080346,1,2,'H','H赏',0,75),(349,54,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080346,1,2,'H','H赏',0,76),(350,54,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080346,1,2,'H','H赏',0,77),(351,55,1,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080437,1,2,'H','H赏',0,78),(352,56,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1654080509,1,2,'H','H赏',0,79),(353,57,4,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,1,1654080527,6,0,'O','O赏',0,12),(354,58,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1654086591,3,152,'Challenge','挑战者',0,1),(355,58,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1654086591,3,152,'Challenge','挑战者',0,2),(356,59,3,8,'zero罗杰','/upload/images/20220416/165003951511349.jpg','88.00',320.00,1,1654331859,1,2,'E','E赏',0,80),(357,45,4,2,'popwa罗','/upload/images/20220416/1650039365546039.jpg','88.00',1299.00,1,1654331859,1,2,'Last','Last赏',1,82),(358,60,3,55,'帅LIU潮卷','/upload/images/20220416/1650048147875118.jpg','13.00',1.00,1,1655702899,2,141,'J','J赏',0,1),(359,61,3,42,'帅LIU潮卷','/upload/images/20220416/1650047457838881.jpg','44.00',1.00,1,1655731929,4,252,'Challenge','挑战者',0,3),(360,62,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1655732012,1,3,'H','H赏',0,1),(361,63,3,11,'帅LIU潮卷','/upload/images/20220416/165003956756871.jpg','88.00',1.00,1,1655732042,1,3,'H','H赏',0,2),(362,64,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1655814489,3,152,'Challenge','挑战者',0,2),(363,64,3,23,'太空人钓星星','/upload/images/20220416/1650042874549382.jpg','56.00',85.00,1,1655814489,3,152,'War','战利品',1,4),(364,58,3,24,'星空太空人深思','/upload/images/20220416/1650042909443830.jpg','56.00',65.00,1,1655814489,3,152,'War','战利品',1,5),(365,65,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1655814505,3,202,'Challenge','挑战者',0,2),(366,66,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1655814511,3,202,'Challenge','挑战者',0,3),(367,66,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1655814511,3,202,'Challenge','挑战者',0,1),(368,65,3,23,'太空人钓星星','/upload/images/20220416/1650042874549382.jpg','56.00',85.00,1,1655814511,3,202,'War','战利品',1,4),(369,66,3,24,'星空太空人深思','/upload/images/20220416/1650042909443830.jpg','56.00',65.00,1,1655814511,3,202,'War','战利品',1,5),(370,67,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1655814581,3,211,'Challenge','挑战者',0,2),(371,67,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1655814581,3,211,'Challenge','挑战者',0,3),(372,68,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,1,1655814687,5,451,'Challenge','挑战者',0,52),(373,68,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,1,1655814687,5,451,'Challenge','挑战者',0,35),(374,68,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,1,1655814687,5,451,'Challenge','挑战者',0,95),(375,68,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,1,1655814687,5,451,'Challenge','挑战者',0,22),(376,68,3,83,'帅LIU潮卷','/upload/images/20220417/1650210391920032.jpg','9.00',1.00,1,1655814687,5,451,'Challenge','挑战者',0,92),(377,69,3,25,'帅LIU潮卷','/upload/images/20220416/1650042926262367.jpg','56.00',1.00,1,1655814725,3,153,'Challenge','挑战者',0,2),(378,70,3,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,1,1655815706,6,0,'O','O赏',0,13),(379,71,3,1,'popmax老沙','/upload/images/20220416/1650039333687705.jpg','88.00',1599.00,2,1655923240,8,551,'A','A赏',0,1),(380,72,3,121,'多啦A梦卡贴','/upload/images/20220418/1650215451379227.jpg','5.00',1.00,1,1657117457,6,0,'O','O赏',0,14),(381,73,3,1,'popmax老沙','/upload/images/20220416/1650039333687705.jpg','88.00',1599.00,2,1657330617,8,552,'A','A赏',0,1),(382,74,3,1,'popmax老沙','/upload/images/20220416/1650039333687705.jpg','88.00',1599.00,2,1657347042,8,553,'A','A赏',0,1),(383,75,3,1,'popmax老沙','/upload/images/20220416/1650039333687705.jpg','88.00',1599.00,2,1657635005,8,554,'A','A赏',0,1),(384,76,3,221,'限量弹珠一个','/upload/images/20220429/1651171753517563.jpg','5.00',1.00,2,1658238316,9,561,'A','A赏',0,1),(385,77,3,1,'popmax老沙','/upload/images/20220416/1650039333687705.jpg','88.00',1599.00,1,1658301554,8,555,'A','A赏',0,1);
/*!40000 ALTER TABLE `dl_order_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_order_pay`
--

DROP TABLE IF EXISTS `dl_order_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_order_pay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL COMMENT '订单ID',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `pay_name` varchar(20) NOT NULL COMMENT '支付方式（中文）',
  `pay_type` varchar(20) NOT NULL COMMENT '支付方式（字符串）',
  `create_time` int(10) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COMMENT='支付记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_order_pay`
--

LOCK TABLES `dl_order_pay` WRITE;
/*!40000 ALTER TABLE `dl_order_pay` DISABLE KEYS */;
INSERT INTO `dl_order_pay` VALUES (1,1,880.00,'余额','balance',1653722051),(2,2,220.00,'余额','balance',1653902739),(3,3,88.00,'余额','balance',1653902758),(4,4,880.00,'余额','balance',1653902772),(5,5,880.00,'余额','balance',1653902777),(6,6,880.00,'余额','balance',1653902782),(7,7,3432.00,'余额','balance',1653902788),(8,8,130.00,'余额','balance',1653903237),(9,9,130.00,'余额','balance',1653903243),(10,10,130.00,'余额','balance',1653903250),(11,11,130.00,'余额','balance',1653903254),(12,12,130.00,'余额','balance',1653903258),(13,13,130.00,'余额','balance',1653903261),(14,14,130.00,'余额','balance',1653903265),(15,15,130.00,'余额','balance',1653903268),(16,16,130.00,'余额','balance',1653903271),(17,17,130.00,'余额','balance',1653903275),(18,18,130.00,'余额','balance',1653903278),(19,19,130.00,'余额','balance',1653903282),(20,20,88.00,'潮玩币','score',1653905444),(21,21,88.00,'潮玩币','score',1653905451),(22,22,24.00,'潮玩币','score',1653905480),(23,22,64.00,'余额','balance',1653905480),(24,23,880.00,'余额','balance',1653905754),(25,24,25.00,'余额','balance',1653906042),(26,25,88.00,'潮玩币','score',1653965641),(27,26,88.00,'潮玩币','score',1653965881),(28,27,440.00,'潮玩币','score',1653965944),(29,28,130.00,'潮玩币','score',1653965959),(30,29,254.00,'潮玩币','score',1653966023),(31,29,626.00,'余额','balance',1653966023),(32,30,880.00,'余额','balance',1653978055),(33,31,440.00,'余额','balance',1653983168),(34,32,88.00,'余额','balance',1653983791),(35,33,88.00,'余额','balance',1653984729),(36,34,880.00,'余额','balance',1653985601),(37,35,440.00,'余额','balance',1654076279),(38,36,90.00,'余额','balance',1654077486),(39,37,45.00,'余额','balance',1654077511),(40,38,90.00,'余额','balance',1654077517),(41,39,88.00,'余额','balance',1654077620),(42,40,25.00,'余额','balance',1654077653),(43,41,112.00,'余额','balance',1654077912),(44,42,56.00,'余额','balance',1654077921),(45,43,13.00,'余额','balance',1654077997),(46,44,65.00,'余额','balance',1654078039),(47,45,88.00,'余额','balance',1654078272),(48,46,44.00,'余额','balance',1654079742),(49,47,88.00,'余额','balance',1654079931),(50,48,88.00,'余额','balance',1654079977),(51,49,9.00,'余额','balance',1654080028),(52,50,5.00,'余额','balance',1654080136),(53,51,88.00,'余额','balance',1654080163),(54,52,88.00,'余额','balance',1654080259),(55,53,440.00,'余额','balance',1654080267),(56,54,440.00,'余额','balance',1654080346),(57,55,88.00,'余额','balance',1654080437),(58,56,88.00,'余额','balance',1654080509),(59,57,5.00,'余额','balance',1654080527),(60,58,112.00,'余额','balance',1654086591),(61,59,88.00,'余额','balance',1654331859),(62,60,13.00,'余额','balance',1655702899),(63,61,44.00,'余额','balance',1655731929),(64,62,88.00,'余额','balance',1655732012),(65,63,88.00,'余额','balance',1655732042),(66,64,56.00,'余额','balance',1655814489),(67,65,56.00,'余额','balance',1655814505),(68,66,112.00,'余额','balance',1655814511),(69,67,112.00,'余额','balance',1655814581),(70,68,45.00,'余额','balance',1655814687),(71,69,56.00,'余额','balance',1655814725),(72,70,5.00,'余额','balance',1655815706),(73,71,1.00,'余额','balance',1655923240),(74,72,5.00,'余额','balance',1657117457),(75,73,1.00,'余额','balance',1657330617),(76,74,1.00,'余额','balance',1657347042),(77,75,1.00,'余额','balance',1657635005),(78,76,0.10,'余额','balance',1658238316),(79,77,1.00,'余额','balance',1658301554);
/*!40000 ALTER TABLE `dl_order_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_prize`
--

DROP TABLE IF EXISTS `dl_prize`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `box_id` int(10) NOT NULL COMMENT '盲盒ID',
  `goods_id` int(10) DEFAULT NULL COMMENT '商品id',
  `sort` int(10) NOT NULL DEFAULT '1' COMMENT '排序值',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  `ratio` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '概率',
  `level` varchar(20) NOT NULL COMMENT '等级',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `box_id` (`box_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='盲盒商品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_prize`
--

LOCK TABLES `dl_prize` WRITE;
/*!40000 ALTER TABLE `dl_prize` DISABLE KEYS */;
INSERT INTO `dl_prize` VALUES (1,6,120,1,1653719197,0.0100,'SP'),(2,6,106,1,1653719197,0.0200,'A'),(3,6,107,1,1653719197,0.0300,'B'),(4,6,108,1,1653719197,0.0400,'C'),(5,6,109,1,1653719197,0.0500,'D'),(6,6,110,1,1653719197,0.0500,'E'),(7,6,111,1,1653719197,0.1000,'F'),(8,6,112,1,1653719197,0.1000,'G'),(9,6,113,1,1653719197,0.1000,'H'),(10,6,114,1,1653719197,0.1000,'I'),(11,6,115,1,1653719197,0.1500,'J'),(12,6,116,1,1653719197,0.1500,'K'),(13,6,117,1,1653719197,0.1500,'L'),(14,6,118,1,1653719197,0.1500,'M'),(15,6,119,1,1653719197,0.2000,'N'),(16,6,121,1,1653719197,98.6000,'O'),(17,7,210,1,1653719885,0.0200,'SP'),(18,7,211,1,1653719886,0.0200,'SP'),(19,7,212,1,1653719886,0.0300,'A'),(20,7,213,1,1653719886,0.0400,'A'),(21,7,214,1,1653719886,0.0500,'A'),(22,7,215,1,1653719886,0.7000,'B'),(23,7,216,1,1653719886,0.8000,'B'),(24,7,217,1,1653719886,0.9000,'B'),(25,7,218,1,1653719886,4.0000,'C'),(26,7,219,1,1653719886,32.0000,'D'),(27,7,220,1,1653719886,32.0000,'E'),(28,7,221,1,1653719886,32.0000,'F');
/*!40000 ALTER TABLE `dl_prize` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_recharge`
--

DROP TABLE IF EXISTS `dl_recharge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `amount` varchar(10) NOT NULL DEFAULT '0' COMMENT '充值金额',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='充值金额表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_recharge`
--

LOCK TABLES `dl_recharge` WRITE;
/*!40000 ALTER TABLE `dl_recharge` DISABLE KEYS */;
INSERT INTO `dl_recharge` VALUES (1,'20',1647052404),(2,'50',1647052412),(3,'100',1647052419),(4,'300',1647052426),(5,'500',1647052432),(6,'1000',1647052438),(7,'2000',1647052443),(11,'3000',1650038625);
/*!40000 ALTER TABLE `dl_recharge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_sale`
--

DROP TABLE IF EXISTS `dl_sale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_sale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oid` longtext NOT NULL COMMENT '赏品ID',
  `data` longtext NOT NULL COMMENT '挂售数据',
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '挂售总价',
  `create_time` int(10) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_sale`
--

LOCK TABLES `dl_sale` WRITE;
/*!40000 ALTER TABLE `dl_sale` DISABLE KEYS */;
INSERT INTO `dl_sale` VALUES (1,'215,216,217,218,219,220,221,222,224','[{\"gid\":\"11\",\"num\":8},{\"gid\":\"9\",\"num\":1}]',1,228.00,1653905961);
/*!40000 ALTER TABLE `dl_sale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_single`
--

DROP TABLE IF EXISTS `dl_single`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_single` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='协议表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_single`
--

LOCK TABLES `dl_single` WRITE;
/*!40000 ALTER TABLE `dl_single` DISABLE KEYS */;
INSERT INTO `dl_single` VALUES (1,'用户协议','<p>尊敬的用户您好。</p><p><br/></p><p>本《用户购买协议》(以下统称本协议)是您与万载县沾组百货店(以下统称“帅Liu潮玩小程序(以下统称“&quot;&quot;)之间关于提供的购买服务的法律协议。您若注册帅Liu潮玩并购买和使用了本公司的商品或服务,即表示您认同并接受了本协议。您的使用受制于本协议,请仔细阅读。</p><p><br/></p><p>—、特别提示</p><p><br/></p><p>1、在此特别提醒,请您在使用服务前阅读并充分理解本协议特别是免除或者限制责任的相应条款,双方确认前述条款不属于《合同法》第40条规定“免除其责任、加重对方责任、排除对方主要权利之条款您认可其合法性及有效性。</p><p><br/></p><p>2、本公司可能因国家政策、发展规划、产品以及履行本协议的环境发生变化等因素,而对或本协议进行修改变更修改或变更的内容将于相关页面进行公告。若您不同意或本协议的前述修改或变更,您可停止使用服务。您使用服务即视为您接受并同意本协议所有条款,包括但不限于前述修改及变更请您在勾选同意本协议前谨慎阅读并理解相关内容如您勾选同意即视为您自此发生的交易均受本协议约束,包括但不限于前述修改及变更如违反本协议约定,有权随时中止或终止服务。</p><p><br/></p><p>3、如果您未满18周岁请在法定监护人的陪同下阅读本协议未成年人行使和履行本协议项下的权利和义务视为已获得了监护人的认可。</p><p><br/></p><p>4、由于您使用的软件版本、设备、操作系统等不同以及第三方原因可能导致您实际可使用的具体服务有差别,由此可能给您带来的不便您表示理解且不会因此向提出任何主张或追究木叶―番赏的任何责任。</p><p><br/></p><p>二、适用范围</p><p><br/></p><p>本协议适用于通过帅Liu潮玩销售的所有商品和服务(以下统称“商品”)。当您购买目前或将来提供的特殊品类商品(包括但不限于预售类商品、盲盒等)时,本协议未涉及的和该特殊品类商品展示页中另有规定的(“特殊条款&quot;),从其规定如果本协议与特殊条款有不—致之处以特殊条款为准</p><p><br/></p><p>三、交易条款</p><p><br/></p><p>1、关于商品信息调整</p><p><br/></p><p>商品名称、价格、数量、型号、规格、尺寸颜色、商品介绍、库存等商品信息随时都有可能发生变动,任何变动帅Liu潮玩不作特别通知请您至商品页面自行查看。会尽最大努力保证您所浏览的商品信息的准确性:但由于商品种类繁多、商信息量大以及技术因素等客观原因,商品信息页面显示可能存在一定滞后性或差错,您对此表示知悉和理解:此外,由于预售商品存在一定不确定性相应商品信息(包括但不限于赠品配比、规格尺寸等)可能会自动跟随官方信息修改,您对此表示知悉和理解。如用户不接受可以进行单个退款。</p><p><br/></p><p>2、关于订单信息</p><p><br/></p><p>在您提交订单时,请仔细确认所购商品的名称价格、数量、型号、规格、尺寸、颜色、收货人姓名、联系电话、收货地址等信息。若收货人并非您本人收货人的行为和意思表示将视为您的行为和意思表示,您应对收货人的行为及意思表示产生的法律后果承担连带责任您提交订单即表示对订单中所确认的订购商品、收货信息等内容的准确性负责。如果因为您填写的收货人姓名联系电话、收货地址等信息错误,导致延期配送、不能配送、或商品交付给非您本意的收货人,由此造成的损失需由您自行承担:因造成的任何损失或增加费用的,应由您完全独自承担。您同意并保证:为了更好的为您提供服务,龟仙人一番赏会记录您在选购商品过程中在线填写的所有信息;若有需要可提供给相关服务提供方。</p><p><br/></p><p>3、关于订单生效</p><p><br/></p><p>帅Liu潮玩展示的商品和价格等商品信息仅仅作为要约邀请。您下单时须写明订单信息内容,系统生成的订单信息是计算机信息系统根据您填写的内容和和操作自动生成的数据。作为您向帅Liu潮玩发出的合同要约。您付款后,即视为双方之间的合同成立您未能在指定时间完成付款的,有权取消订单。如果您在一份订单里订购了多种商品,但仅就部分商品支付价款,则木叶—番赏和之间仅就该部分商品成立合同关系“赠品兑换券类产品具有随机性的特点,对此您已充分了解。您应该对您消费行为的性质和后果自行判断,并对您在的一切消费行为完全负责。基于产品特点我们无法保证您收到的商品符合您对某—特定款式或型·号的预期对此。您不应以未收到特定款式的商品为由要求很款、赔偿或承担任何责任。木叶—番赏无法且不会对因前述问题而导致的任何损失或损害承担责任。如因系统故障或的过失导致显示信息明显不合理的情况(包括但不限于商品价格明显偏低、显示余量明显异常等)请勿进行相关后续操作并立即通知进行修改有权在法律允许的最大限度内取消相关不合理订单并及时通知您。</p><p><br/></p><p>4、关于商品缺货的处理</p><p><br/></p><p>由于国家政策、市场变化、系统问题及其他不可抗力等因素影响,您所提交的订单信息中的商品可能出现缺货情在此情形下将以有效方式(包括但不限于在发公告、发送邮件、向您提供的联系电话发送手机短信、向您的账号发送私信以及站内信信息等方式)通知您并提供解决方案(包括但不限于取消订单换货、调货等方式式)。请您在未收到商品之前留意通知若超过两次无法与您取得电话联系且您在收到番堂通知超过30天未主动联系在线客服将有权取消订单,由此给您带来的不便敬请谅解缺货商品是杏补货不再另行通知,如您对此商品感兴趣请您随时关注。</p><p><br/></p><p>四、配送条款</p><p><br/></p><p>用户付款并填写真实的收货人姓名、有效联系电话、收货地址是商家给用户发货的前提。</p><p><br/></p><p>1、关于发货时间您知悉并理解,上列出的发货时间为参考时间该参考时间可能根据库存状况、送货时间、送货地点、物流状况等客观因素存在误差,具体发货时间以实际发出时间为准。此外,您同样知悉预售类商品的具体发货时间会因受制作周期物流周期、质检返工、不可抗力等诸多因素影响存在误差,导致预售类商品的实际发货时间可能提早或推迟,您对上述情形表示同意及理解。</p><p><br/></p><p>2、所有预售类商品如后续需要支付运费或祺他费用将在补款时—并支付。</p><p><br/></p><p>3、商品的可配送区域为中国大陆地区(特殊偏远地区除外)收件地址在非可配送区域或区域不明确的,务必客服人员核实清楚后再下单本公司及对因此造成的一切纠纷和损失不承担责任。</p><p><br/></p><p>4、运费视配送地址不同可能不同,具体以订单支付页面显示的价格为准。</p><p><br/></p><p>5、在签收商品时,请您本人或您指定的收件人亲自在不拆封商品包装的情况下,在快递前当面验货,确认无误后再签收。若您或上述收件人委托他人签收商品或承运人已按您或收件人指示将商品置于指定地点的,视为本人签收。快递—旦签收视为该商品已交付给您。6、您无正当理由拒绝签收不支持七天无理由退换货的商品或性质不适宜拒签的商品的,商品返回后,需由您承担商品毁损灭失的风险及因此产生的费用。龟仙人一番赏可选择退款或将商品再次发回,若商品性质已不适宜发回或您再次拒绝签收的,订单损失由您承担。五、支付条款</p><p><br/></p><p>1、您在使用“抽一张”、“抽五张”、“抽十张”、“全收&quot;等支付功能时因商品可能存在多名用户同时抢购的情况,您应及时注意赠品余量情况,保证支付后购买顺利。</p><p><br/></p><p>2、您在使用微信支付购买时,如遇到赠品数量不足的情况,相应金额会自动退款至您的微信账户,退款时可能会有延迟情况,请耐心等待。若长时间未收到钱款请联系客服处理六、售后条款</p><p><br/></p><p>“—番赏为开赏类商品,不支持七天无理由退换货。您知悉并理解内商品难免存在轻微增色、溢色、气泡染色不均轻微划痕等涂装或原厂问题的瑕疵。上述均属于正常现象,番赏不接受因此提出的退换货申请,请您知悉并谨慎考虑后,理性购买。</p><p><br/></p><p>七、通知</p><p><br/></p><p>1、为更好地为您提供服务,您同意接受我们发送的信息,包括但不限于:在番赏发布公告、向您发送邮件、向您提供的联系电话发送手机短信、向您的账号发送私信以及站内信信息向您提供的联系地址邮寄书面通知等。如一番堂能够证明以上电子形式的信息已经发送给您或者已在相关页面公布,则视为您已收到相关信息以纸质载体发出的书面通知,按照提供联系地址交邮后的第五个工作日即视为送达2、保留对本小程序注册、购物用户发送订单信息、促销活动等告知服务的权利。如果您在注册、购物,表明您已默认同意接受此项服务。</p><p><br/></p><p>八、评价</p><p><br/></p><p>您有权在提供的评价系统中对与您达成交易的商品进行评价。您应当理解,您在的评价信息是公开的您的所有评价行为应遵守本协议,评价内容应当客观真实,不应含任何污言秽语政治敏感、色情低俗、广告信息及法律法规列明之其他禁止性信息;您不应以不正当方式利用评价权利对其他用户实施威胁、敲诈勒索。有权对您实施上述行为所产生的评价信息进行删除或屏蔽。</p><p><br/></p><p>九、其他</p><p><br/></p><p>1、除非另有证明,储存在服务器上的数据是您使用服务的唯有效证据。</p><p><br/></p><p>2、若您存在以下不正当行为,—经发现,有权采取包括但不限于暂停发货、取消订单、拦截已发货的订单、限制账户权限等措施:</p><p><br/></p><p>(1)、您利用平台进行非法套现、洗钱及其他违法犯罪行为。</p><p><br/></p><p>(2)、由于用户将其用户密码告知他人或与他人共享注册账号与密码,由此导致的任何个人信息的泄漏,或其他非因番赏原因导致的个人隐私信息的泄露。(3)、用户自行向第三方公开其个人隐私信息。</p><p><br/></p><p>(4)、用户与及合作单位之间就用户个人隐私信息的使用公开达成约定因此向合作单位公开用户个人隐私信息、任何由于黑客攻击、电脑病毒侵入及其他非因原因导致用户个人隐私信息的泄露。</p><p><br/></p><p>2、用户同意可在以下事项中免费使用用户的个人隐私信息:</p><p><br/></p><p>(1)、向用户及时发送重要通知,如应用更新、本协议条款的变更。</p><p><br/></p><p>(2)、内部进行审计、数据分析和研究等,以改进番赏的产品、服务和与用户之间的沟通:</p><p><br/></p><p>(3)、依本协议约定,管理、审查用户信息及进行处理措施。</p><p><br/></p><p>(4)、帅Liu潮玩可能将收集到的用户信息,用在其他功能或服务中向用户提供特定内容,包括但不限于展示广告、对用户阅读过的内容进行信息安全类提示、基于特征标签进行间接人群画像并提供更加精准和个性化的服务和内容等。</p><p><br/></p><p>(2)、适用法律法规规定的其他事项。</p><p><br/></p><p>(3)、根据相关法律法规及国家标佳,以下情形中,可能会收集、使用、共享、转让、公开披露用户的个人信息无需征求用户的授权同意:(1)、与国家安全、国防安全等国家利益直接相关的与公共安全、公共卫生、公众知情等重大公共利益直接相关的。</p><p><br/></p><p>(2)、与犯罪侦查、起诉、审判和判决执行等直接相关的。</p><p><br/></p><p>(3)、出于维护用户或其他个人的生命、财产声誉等重大合法权益但又很难得到本人同意的。</p><p><br/></p><p>(4)、所收集的个人信息是用户自行向社会公众公开的。</p><p><br/></p><p>(5)、从合法公开披露的信息中收集个人信息的,如合法的新闻报道、政府信息公开等渠道根据用户要求签订和履行合同所必需的。(6)、用于维护所提供的产品或服务的安全稳定运行所必需的,例如发现、处置产品或服务的故障为开展合法的新闻报道所必需的出于公共利益开展统计或学术研究所必要的。(7)、法律法规规定的其他情形。</p><p><br/></p><p>4、我们非常重视对未成年人个人信息的保护。根据相关法律法规的规定,若您是18周岁以下的未成年人,在使用服务前,应事先取得您的家长或法定监护人的书面同意。</p><p><br/></p><p>5、帅Liu潮玩将会尽其商业上的合理努力保障用户在本软件及服务中的数据存储安全,但是并不能就此提供完全保证,包括但不限于以下情形:</p><p><br/></p><p>(1)、不对用户在本软件及服务中相关数据的删除或储存失败负责。(2)、有权根据实际情况自行决定单个用户在本软件及服务中数据的最长储存期限,并在服务器上为其分配数据最大存储空间等。</p><p><br/></p><p>(3)、如果用户停止使用本软件及服务或服务被终止或取消,帅Liu潮玩可以从服务器上永久地删除用户数据。服务停止、终止或取消后,没有义务向用户返还任何数据。</p><p><br/></p><p>十一、免责事由</p><p><br/></p><p>1、您知悉并同意,不因下述任一情况而可能导致的任何损害赔偿承担责任,包括但不限于财产、收益、数据资料等方面的损失或其它无形损失:(1)、因台风、地震、海啸、洪水、停电、战争、恐怖袭击等不可抗力之因素导致系统或服务不能正常运行。</p><p><br/></p><p>(2)、由于黑客攻击、电信部门技术调整或故障、系统维护等原因而造成的系统服务中断或者延迟。</p><p><br/></p><p>(3)、由于政府命令、法律法规的变更、司法机关及行政机关的命令、裁定等原因而导致的系统服务中断、终止或延迟。</p><p><br/></p><p>(4)、由于您将账号、密码告知他人或未保管好自己的密码或与他人共享账号或任何其他非的过错,导致您的个人资料泄露。</p><p><br/></p><p>(5)、由于与链接或合作的其它网站所造成的银行账户信息身份信息泄露及由此而导致的任何法律争议和后果、您(包括未成年人用户)向提供错误、不完整、不实信息等,造成任何损失。如因系统维护或升级的需要而需暂停服务时,我们将尽可能事先进行通知。对于服务的中断或终止而给您造成的任何损失,我们无须对您或任何第三方承担任何责任。</p><p><br/></p><p>2、您理解对您的任何请求采取行动均需要合理时间,且应您请求而采取的行动可能无法避免或阻止侵害后果的形成或扩大除存在法定过错外,番赏不承担责任。</p><p><br/></p><p>3、您理解并同意,因您自身违反本协议或相关服务条款的规定,导致或产生第三方主张的任何索赔、要求或损失您应当独立承担责任:因此遭受损失的,您也应当并赔偿。</p><p><br/></p><p>十二、投诉指引</p><p><br/></p><p>一番赏—向重视知识产权以及用户权益的保护如您认为平台某些内容涉嫌侵犯了您的合法权益,您可以通过发送邮件至[763044293@qq.com】向进行投诉。投诉请求至少应包括以下内容:(1)投诉人的姓名(名称)、联系电话和通讯地址:(2)投诉的具体理由、要求及被投诉内容:(3构成侵权的初步证明材料。当收到投诉请求后,我们的工作人员会尽快依法为您处理。</p><p><br/></p><p>十三法律适用、争议解决及条款可分割性1、本协议的订立、效力、解释、执行及其下产生的任何争议的解决应适用并遵守中国法律。</p><p><br/></p><p>2、因本协议或其违约、终止或无效而产生的或与本协议或其违约、终止或无效有关的任何争议、争论或诉求(争议)应提交杭州国际经济贸易仲裁委员会根据提交争议时该会届时有效的仲裁规则进行仲裁。仲裁庭的仲裁裁决为终局仲裁,对双方均有约束力双方应尽其最大努力使得任何该等仲裁裁决及时得以执行,并就此提供任何必要的协助。</p><p><br/></p><p>3、本协议任条款被视为废止、无效或不可执行,该条款应视为可分割的不应影响其他条款或其任何部分的效力,您与我们仍应善意履行。</p><p><br/></p><p><br/></p><p><br/></p>',1632451653),(2,'隐私政策','<p>					</p><p>尊敬的用户您好。</p><p><br/></p><p>本《用户购买协议》(以下统称本协议)是您与万载县沾组百货店(以下统称“帅Liu潮玩小程序(以下统称“&quot;&quot;)之间关于提供的购买服务的法律协议。您若注册帅Liu潮玩并购买和使用了本公司的商品或服务,即表示您认同并接受了本协议。您的使用受制于本协议,请仔细阅读。</p><p><br/></p><p>—、特别提示</p><p><br/></p><p>1、在此特别提醒,请您在使用服务前阅读并充分理解本协议特别是免除或者限制责任的相应条款,双方确认前述条款不属于《合同法》第40条规定“免除其责任、加重对方责任、排除对方主要权利之条款您认可其合法性及有效性。</p><p><br/></p><p>2、本公司可能因国家政策、发展规划、产品以及履行本协议的环境发生变化等因素,而对或本协议进行修改变更修改或变更的内容将于相关页面进行公告。若您不同意或本协议的前述修改或变更,您可停止使用服务。您使用服务即视为您接受并同意本协议所有条款,包括但不限于前述修改及变更请您在勾选同意本协议前谨慎阅读并理解相关内容如您勾选同意即视为您自此发生的交易均受本协议约束,包括但不限于前述修改及变更如违反本协议约定,有权随时中止或终止服务。</p><p><br/></p><p>3、如果您未满18周岁请在法定监护人的陪同下阅读本协议未成年人行使和履行本协议项下的权利和义务视为已获得了监护人的认可。</p><p><br/></p><p>4、由于您使用的软件版本、设备、操作系统等不同以及第三方原因可能导致您实际可使用的具体服务有差别,由此可能给您带来的不便您表示理解且不会因此向提出任何主张或追究木叶―番赏的任何责任。</p><p><br/></p><p>二、适用范围</p><p><br/></p><p>本协议适用于通过帅Liu潮玩销售的所有商品和服务(以下统称“商品”)。当您购买目前或将来提供的特殊品类商品(包括但不限于预售类商品、盲盒等)时,本协议未涉及的和该特殊品类商品展示页中另有规定的(“特殊条款&quot;),从其规定如果本协议与特殊条款有不—致之处以特殊条款为准</p><p><br/></p><p>三、交易条款</p><p><br/></p><p>1、关于商品信息调整</p><p><br/></p><p>商品名称、价格、数量、型号、规格、尺寸颜色、商品介绍、库存等商品信息随时都有可能发生变动,任何变动帅Liu潮玩不作特别通知请您至商品页面自行查看。会尽最大努力保证您所浏览的商品信息的准确性:但由于商品种类繁多、商信息量大以及技术因素等客观原因,商品信息页面显示可能存在一定滞后性或差错,您对此表示知悉和理解:此外,由于预售商品存在一定不确定性相应商品信息(包括但不限于赠品配比、规格尺寸等)可能会自动跟随官方信息修改,您对此表示知悉和理解。如用户不接受可以进行单个退款。</p><p><br/></p><p>2、关于订单信息</p><p><br/></p><p>在您提交订单时,请仔细确认所购商品的名称价格、数量、型号、规格、尺寸、颜色、收货人姓名、联系电话、收货地址等信息。若收货人并非您本人收货人的行为和意思表示将视为您的行为和意思表示,您应对收货人的行为及意思表示产生的法律后果承担连带责任您提交订单即表示对订单中所确认的订购商品、收货信息等内容的准确性负责。如果因为您填写的收货人姓名联系电话、收货地址等信息错误,导致延期配送、不能配送、或商品交付给非您本意的收货人,由此造成的损失需由您自行承担:因造成的任何损失或增加费用的,应由您完全独自承担。您同意并保证:为了更好的为您提供服务,龟仙人一番赏会记录您在选购商品过程中在线填写的所有信息;若有需要可提供给相关服务提供方。</p><p><br/></p><p>3、关于订单生效</p><p><br/></p><p>帅Liu潮玩展示的商品和价格等商品信息仅仅作为要约邀请。您下单时须写明订单信息内容,系统生成的订单信息是计算机信息系统根据您填写的内容和和操作自动生成的数据。作为您向帅Liu潮玩发出的合同要约。您付款后,即视为双方之间的合同成立您未能在指定时间完成付款的,有权取消订单。如果您在一份订单里订购了多种商品,但仅就部分商品支付价款,则木叶—番赏和之间仅就该部分商品成立合同关系“赠品兑换券类产品具有随机性的特点,对此您已充分了解。您应该对您消费行为的性质和后果自行判断,并对您在的一切消费行为完全负责。基于产品特点我们无法保证您收到的商品符合您对某—特定款式或型·号的预期对此。您不应以未收到特定款式的商品为由要求很款、赔偿或承担任何责任。木叶—番赏无法且不会对因前述问题而导致的任何损失或损害承担责任。如因系统故障或的过失导致显示信息明显不合理的情况(包括但不限于商品价格明显偏低、显示余量明显异常等)请勿进行相关后续操作并立即通知进行修改有权在法律允许的最大限度内取消相关不合理订单并及时通知您。</p><p><br/></p><p>4、关于商品缺货的处理</p><p><br/></p><p>由于国家政策、市场变化、系统问题及其他不可抗力等因素影响,您所提交的订单信息中的商品可能出现缺货情在此情形下将以有效方式(包括但不限于在发公告、发送邮件、向您提供的联系电话发送手机短信、向您的账号发送私信以及站内信信息等方式)通知您并提供解决方案(包括但不限于取消订单换货、调货等方式式)。请您在未收到商品之前留意通知若超过两次无法与您取得电话联系且您在收到番堂通知超过30天未主动联系在线客服将有权取消订单,由此给您带来的不便敬请谅解缺货商品是杏补货不再另行通知,如您对此商品感兴趣请您随时关注。</p><p><br/></p><p>四、配送条款</p><p><br/></p><p>用户付款并填写真实的收货人姓名、有效联系电话、收货地址是商家给用户发货的前提。</p><p><br/></p><p>1、关于发货时间您知悉并理解,上列出的发货时间为参考时间该参考时间可能根据库存状况、送货时间、送货地点、物流状况等客观因素存在误差,具体发货时间以实际发出时间为准。此外,您同样知悉预售类商品的具体发货时间会因受制作周期物流周期、质检返工、不可抗力等诸多因素影响存在误差,导致预售类商品的实际发货时间可能提早或推迟,您对上述情形表示同意及理解。</p><p><br/></p><p>2、所有预售类商品如后续需要支付运费或祺他费用将在补款时—并支付。</p><p><br/></p><p>3、商品的可配送区域为中国大陆地区(特殊偏远地区除外)收件地址在非可配送区域或区域不明确的,务必客服人员核实清楚后再下单本公司及对因此造成的一切纠纷和损失不承担责任。</p><p><br/></p><p>4、运费视配送地址不同可能不同,具体以订单支付页面显示的价格为准。</p><p><br/></p><p>5、在签收商品时,请您本人或您指定的收件人亲自在不拆封商品包装的情况下,在快递前当面验货,确认无误后再签收。若您或上述收件人委托他人签收商品或承运人已按您或收件人指示将商品置于指定地点的,视为本人签收。快递—旦签收视为该商品已交付给您。6、您无正当理由拒绝签收不支持七天无理由退换货的商品或性质不适宜拒签的商品的,商品返回后,需由您承担商品毁损灭失的风险及因此产生的费用。龟仙人一番赏可选择退款或将商品再次发回,若商品性质已不适宜发回或您再次拒绝签收的,订单损失由您承担。五、支付条款</p><p><br/></p><p>1、您在使用“抽一张”、“抽五张”、“抽十张”、“全收&quot;等支付功能时因商品可能存在多名用户同时抢购的情况,您应及时注意赠品余量情况,保证支付后购买顺利。</p><p><br/></p><p>2、您在使用微信支付购买时,如遇到赠品数量不足的情况,相应金额会自动退款至您的微信账户,退款时可能会有延迟情况,请耐心等待。若长时间未收到钱款请联系客服处理六、售后条款</p><p><br/></p><p>“—番赏为开赏类商品,不支持七天无理由退换货。您知悉并理解内商品难免存在轻微增色、溢色、气泡染色不均轻微划痕等涂装或原厂问题的瑕疵。上述均属于正常现象,番赏不接受因此提出的退换货申请,请您知悉并谨慎考虑后,理性购买。</p><p><br/></p><p>七、通知</p><p><br/></p><p>1、为更好地为您提供服务,您同意接受我们发送的信息,包括但不限于:在番赏发布公告、向您发送邮件、向您提供的联系电话发送手机短信、向您的账号发送私信以及站内信信息向您提供的联系地址邮寄书面通知等。如一番堂能够证明以上电子形式的信息已经发送给您或者已在相关页面公布,则视为您已收到相关信息以纸质载体发出的书面通知,按照提供联系地址交邮后的第五个工作日即视为送达2、保留对本小程序注册、购物用户发送订单信息、促销活动等告知服务的权利。如果您在注册、购物,表明您已默认同意接受此项服务。</p><p><br/></p><p>八、评价</p><p><br/></p><p>您有权在提供的评价系统中对与您达成交易的商品进行评价。您应当理解,您在的评价信息是公开的您的所有评价行为应遵守本协议,评价内容应当客观真实,不应含任何污言秽语政治敏感、色情低俗、广告信息及法律法规列明之其他禁止性信息;您不应以不正当方式利用评价权利对其他用户实施威胁、敲诈勒索。有权对您实施上述行为所产生的评价信息进行删除或屏蔽。</p><p><br/></p><p>九、其他</p><p><br/></p><p>1、除非另有证明,储存在服务器上的数据是您使用服务的唯有效证据。</p><p><br/></p><p>2、若您存在以下不正当行为,—经发现,有权采取包括但不限于暂停发货、取消订单、拦截已发货的订单、限制账户权限等措施:</p><p><br/></p><p>(1)、您利用平台进行非法套现、洗钱及其他违法犯罪行为。</p><p><br/></p><p>(2)、由于用户将其用户密码告知他人或与他人共享注册账号与密码,由此导致的任何个人信息的泄漏,或其他非因番赏原因导致的个人隐私信息的泄露。(3)、用户自行向第三方公开其个人隐私信息。</p><p><br/></p><p>(4)、用户与及合作单位之间就用户个人隐私信息的使用公开达成约定因此向合作单位公开用户个人隐私信息、任何由于黑客攻击、电脑病毒侵入及其他非因原因导致用户个人隐私信息的泄露。</p><p><br/></p><p>2、用户同意可在以下事项中免费使用用户的个人隐私信息:</p><p><br/></p><p>(1)、向用户及时发送重要通知,如应用更新、本协议条款的变更。</p><p><br/></p><p>(2)、内部进行审计、数据分析和研究等,以改进番赏的产品、服务和与用户之间的沟通:</p><p><br/></p><p>(3)、依本协议约定,管理、审查用户信息及进行处理措施。</p><p><br/></p><p>(4)、帅Liu潮玩可能将收集到的用户信息,用在其他功能或服务中向用户提供特定内容,包括但不限于展示广告、对用户阅读过的内容进行信息安全类提示、基于特征标签进行间接人群画像并提供更加精准和个性化的服务和内容等。</p><p><br/></p><p>(2)、适用法律法规规定的其他事项。</p><p><br/></p><p>(3)、根据相关法律法规及国家标佳,以下情形中,可能会收集、使用、共享、转让、公开披露用户的个人信息无需征求用户的授权同意:(1)、与国家安全、国防安全等国家利益直接相关的与公共安全、公共卫生、公众知情等重大公共利益直接相关的。</p><p><br/></p><p>(2)、与犯罪侦查、起诉、审判和判决执行等直接相关的。</p><p><br/></p><p>(3)、出于维护用户或其他个人的生命、财产声誉等重大合法权益但又很难得到本人同意的。</p><p><br/></p><p>(4)、所收集的个人信息是用户自行向社会公众公开的。</p><p><br/></p><p>(5)、从合法公开披露的信息中收集个人信息的,如合法的新闻报道、政府信息公开等渠道根据用户要求签订和履行合同所必需的。(6)、用于维护所提供的产品或服务的安全稳定运行所必需的,例如发现、处置产品或服务的故障为开展合法的新闻报道所必需的出于公共利益开展统计或学术研究所必要的。(7)、法律法规规定的其他情形。</p><p><br/></p><p>4、我们非常重视对未成年人个人信息的保护。根据相关法律法规的规定,若您是18周岁以下的未成年人,在使用服务前,应事先取得您的家长或法定监护人的书面同意。</p><p><br/></p><p>5、帅Liu潮玩将会尽其商业上的合理努力保障用户在本软件及服务中的数据存储安全,但是并不能就此提供完全保证,包括但不限于以下情形:</p><p><br/></p><p>(1)、不对用户在本软件及服务中相关数据的删除或储存失败负责。(2)、有权根据实际情况自行决定单个用户在本软件及服务中数据的最长储存期限,并在服务器上为其分配数据最大存储空间等。</p><p><br/></p><p>(3)、如果用户停止使用本软件及服务或服务被终止或取消,帅Liu潮玩可以从服务器上永久地删除用户数据。服务停止、终止或取消后,没有义务向用户返还任何数据。</p><p><br/></p><p>十一、免责事由</p><p><br/></p><p>1、您知悉并同意,不因下述任一情况而可能导致的任何损害赔偿承担责任,包括但不限于财产、收益、数据资料等方面的损失或其它无形损失:(1)、因台风、地震、海啸、洪水、停电、战争、恐怖袭击等不可抗力之因素导致系统或服务不能正常运行。</p><p><br/></p><p>(2)、由于黑客攻击、电信部门技术调整或故障、系统维护等原因而造成的系统服务中断或者延迟。</p><p><br/></p><p>(3)、由于政府命令、法律法规的变更、司法机关及行政机关的命令、裁定等原因而导致的系统服务中断、终止或延迟。</p><p><br/></p><p>(4)、由于您将账号、密码告知他人或未保管好自己的密码或与他人共享账号或任何其他非的过错,导致您的个人资料泄露。</p><p><br/></p><p>(5)、由于与链接或合作的其它网站所造成的银行账户信息身份信息泄露及由此而导致的任何法律争议和后果、您(包括未成年人用户)向提供错误、不完整、不实信息等,造成任何损失。如因系统维护或升级的需要而需暂停服务时,我们将尽可能事先进行通知。对于服务的中断或终止而给您造成的任何损失,我们无须对您或任何第三方承担任何责任。</p><p><br/></p><p>2、您理解对您的任何请求采取行动均需要合理时间,且应您请求而采取的行动可能无法避免或阻止侵害后果的形成或扩大除存在法定过错外,番赏不承担责任。</p><p><br/></p><p>3、您理解并同意,因您自身违反本协议或相关服务条款的规定,导致或产生第三方主张的任何索赔、要求或损失您应当独立承担责任:因此遭受损失的,您也应当并赔偿。</p><p><br/></p><p>十二、投诉指引</p><p><br/></p><p>一番赏—向重视知识产权以及用户权益的保护如您认为平台某些内容涉嫌侵犯了您的合法权益,您可以通过发送邮件至[763044293@qq.com】向进行投诉。投诉请求至少应包括以下内容:(1)投诉人的姓名(名称)、联系电话和通讯地址:(2)投诉的具体理由、要求及被投诉内容:(3构成侵权的初步证明材料。当收到投诉请求后,我们的工作人员会尽快依法为您处理。</p><p><br/></p><p>十三法律适用、争议解决及条款可分割性1、本协议的订立、效力、解释、执行及其下产生的任何争议的解决应适用并遵守中国法律。</p><p><br/></p><p>2、因本协议或其违约、终止或无效而产生的或与本协议或其违约、终止或无效有关的任何争议、争论或诉求(争议)应提交杭州国际经济贸易仲裁委员会根据提交争议时该会届时有效的仲裁规则进行仲裁。仲裁庭的仲裁裁决为终局仲裁,对双方均有约束力双方应尽其最大努力使得任何该等仲裁裁决及时得以执行,并就此提供任何必要的协助。</p><p><br/></p><p>3、本协议任条款被视为废止、无效或不可执行,该条款应视为可分割的不应影响其他条款或其任何部分的效力,您与我们仍应善意履行。</p><p><br/></p><p>				</p>',1632451653),(3,'购买说明','<p style=\"white-space: normal;\">1、购买模式为开赏类商品，一经购买不支持无理由退货，请谨慎购买（未成年禁止购买）</p><p style=\"white-space: normal;\">2、购买规则有“一次”“五次”“十次”“全收”按钮获得商品<br/></p><p style=\"white-space: normal;\">3、双随机抽赏方式,FIRST为全局前半局随机赠送一位玩家，LAST为后半局随机赠送一位玩家。</p><p style=\"white-space: normal;\">4、全局赏类型，比如购买ABCDE赏后，全部赠品随机赠送每位客户，具体请查看赏池界面。</p><p style=\"white-space: normal;\">5、无限赏类型，为“一次”“三次”“五次”“十次”按钮获得商品，根据需求进行购买。</p><p><br/></p>',1632451653),(4,'潮玩券说明','<p style=\"white-space: normal;\">1、购买模式为开赏类商品，一经购买不支持无理由退货，请谨慎购买（未成年禁止购买）</p><p style=\"white-space: normal;\">2、购买规则有“一次”“五次”“十次”“全收”按钮获得商品<br/></p><p style=\"white-space: normal;\">3、双随机抽赏方式,FIRST为全局前半局随机赠送一位玩家，LAST为后半局随机赠送一位玩家。</p><p style=\"white-space: normal;\">4、全局赏类型，比如购买ABCDE赏后，全部赠品随机赠送每位客户，具体请查看赏池界面。</p><p style=\"white-space: normal;\">5、无限赏类型，为“一次”“三次”“五次”“十次”按钮获得商品，根据需求进行购买。</p><p><br/></p>',1632451653),(5,'用户服务协议条款','<p>尊敬的用户您好。</p><p><br/></p><p>本《用户购买协议》(以下统称本协议)是您与万载县沾组百货店(以下统称“帅Liu潮玩小程序(以下统称“&quot;&quot;)之间关于提供的购买服务的法律协议。您若注册帅Liu潮玩并购买和使用了本公司的商品或服务,即表示您认同并接受了本协议。您的使用受制于本协议,请仔细阅读。</p><p><br/></p><p>—、特别提示</p><p><br/></p><p>1、在此特别提醒,请您在使用服务前阅读并充分理解本协议特别是免除或者限制责任的相应条款,双方确认前述条款不属于《合同法》第40条规定“免除其责任、加重对方责任、排除对方主要权利之条款您认可其合法性及有效性。</p><p><br/></p><p>2、本公司可能因国家政策、发展规划、产品以及履行本协议的环境发生变化等因素,而对或本协议进行修改变更修改或变更的内容将于相关页面进行公告。若您不同意或本协议的前述修改或变更,您可停止使用服务。您使用服务即视为您接受并同意本协议所有条款,包括但不限于前述修改及变更请您在勾选同意本协议前谨慎阅读并理解相关内容如您勾选同意即视为您自此发生的交易均受本协议约束,包括但不限于前述修改及变更如违反本协议约定,有权随时中止或终止服务。</p><p><br/></p><p>3、如果您未满18周岁请在法定监护人的陪同下阅读本协议未成年人行使和履行本协议项下的权利和义务视为已获得了监护人的认可。</p><p><br/></p><p>4、由于您使用的软件版本、设备、操作系统等不同以及第三方原因可能导致您实际可使用的具体服务有差别,由此可能给您带来的不便您表示理解且不会因此向提出任何主张或追究木叶―番赏的任何责任。</p><p><br/></p><p>二、适用范围</p><p><br/></p><p>本协议适用于通过帅Liu潮玩销售的所有商品和服务(以下统称“商品”)。当您购买目前或将来提供的特殊品类商品(包括但不限于预售类商品、盲盒等)时,本协议未涉及的和该特殊品类商品展示页中另有规定的(“特殊条款&quot;),从其规定如果本协议与特殊条款有不—致之处以特殊条款为准</p><p><br/></p><p>三、交易条款</p><p><br/></p><p>1、关于商品信息调整</p><p><br/></p><p>商品名称、价格、数量、型号、规格、尺寸颜色、商品介绍、库存等商品信息随时都有可能发生变动,任何变动帅Liu潮玩不作特别通知请您至商品页面自行查看。会尽最大努力保证您所浏览的商品信息的准确性:但由于商品种类繁多、商信息量大以及技术因素等客观原因,商品信息页面显示可能存在一定滞后性或差错,您对此表示知悉和理解:此外,由于预售商品存在一定不确定性相应商品信息(包括但不限于赠品配比、规格尺寸等)可能会自动跟随官方信息修改,您对此表示知悉和理解。如用户不接受可以进行单个退款。</p><p><br/></p><p>2、关于订单信息</p><p><br/></p><p>在您提交订单时,请仔细确认所购商品的名称价格、数量、型号、规格、尺寸、颜色、收货人姓名、联系电话、收货地址等信息。若收货人并非您本人收货人的行为和意思表示将视为您的行为和意思表示,您应对收货人的行为及意思表示产生的法律后果承担连带责任您提交订单即表示对订单中所确认的订购商品、收货信息等内容的准确性负责。如果因为您填写的收货人姓名联系电话、收货地址等信息错误,导致延期配送、不能配送、或商品交付给非您本意的收货人,由此造成的损失需由您自行承担:因造成的任何损失或增加费用的,应由您完全独自承担。您同意并保证:为了更好的为您提供服务,龟仙人一番赏会记录您在选购商品过程中在线填写的所有信息;若有需要可提供给相关服务提供方。</p><p><br/></p><p>3、关于订单生效</p><p><br/></p><p>帅Liu潮玩展示的商品和价格等商品信息仅仅作为要约邀请。您下单时须写明订单信息内容,系统生成的订单信息是计算机信息系统根据您填写的内容和和操作自动生成的数据。作为您向帅Liu潮玩发出的合同要约。您付款后,即视为双方之间的合同成立您未能在指定时间完成付款的,有权取消订单。如果您在一份订单里订购了多种商品,但仅就部分商品支付价款,则木叶—番赏和之间仅就该部分商品成立合同关系“赠品兑换券类产品具有随机性的特点,对此您已充分了解。您应该对您消费行为的性质和后果自行判断,并对您在的一切消费行为完全负责。基于产品特点我们无法保证您收到的商品符合您对某—特定款式或型·号的预期对此。您不应以未收到特定款式的商品为由要求很款、赔偿或承担任何责任。木叶—番赏无法且不会对因前述问题而导致的任何损失或损害承担责任。如因系统故障或的过失导致显示信息明显不合理的情况(包括但不限于商品价格明显偏低、显示余量明显异常等)请勿进行相关后续操作并立即通知进行修改有权在法律允许的最大限度内取消相关不合理订单并及时通知您。</p><p><br/></p><p>4、关于商品缺货的处理</p><p><br/></p><p>由于国家政策、市场变化、系统问题及其他不可抗力等因素影响,您所提交的订单信息中的商品可能出现缺货情在此情形下将以有效方式(包括但不限于在发公告、发送邮件、向您提供的联系电话发送手机短信、向您的账号发送私信以及站内信信息等方式)通知您并提供解决方案(包括但不限于取消订单换货、调货等方式式)。请您在未收到商品之前留意通知若超过两次无法与您取得电话联系且您在收到番堂通知超过30天未主动联系在线客服将有权取消订单,由此给您带来的不便敬请谅解缺货商品是杏补货不再另行通知,如您对此商品感兴趣请您随时关注。</p><p><br/></p><p>四、配送条款</p><p><br/></p><p>用户付款并填写真实的收货人姓名、有效联系电话、收货地址是商家给用户发货的前提。</p><p><br/></p><p>1、关于发货时间您知悉并理解,上列出的发货时间为参考时间该参考时间可能根据库存状况、送货时间、送货地点、物流状况等客观因素存在误差,具体发货时间以实际发出时间为准。此外,您同样知悉预售类商品的具体发货时间会因受制作周期物流周期、质检返工、不可抗力等诸多因素影响存在误差,导致预售类商品的实际发货时间可能提早或推迟,您对上述情形表示同意及理解。</p><p><br/></p><p>2、所有预售类商品如后续需要支付运费或祺他费用将在补款时—并支付。</p><p><br/></p><p>3、商品的可配送区域为中国大陆地区(特殊偏远地区除外)收件地址在非可配送区域或区域不明确的,务必客服人员核实清楚后再下单本公司及对因此造成的一切纠纷和损失不承担责任。</p><p><br/></p><p>4、运费视配送地址不同可能不同,具体以订单支付页面显示的价格为准。</p><p><br/></p><p>5、在签收商品时,请您本人或您指定的收件人亲自在不拆封商品包装的情况下,在快递前当面验货,确认无误后再签收。若您或上述收件人委托他人签收商品或承运人已按您或收件人指示将商品置于指定地点的,视为本人签收。快递—旦签收视为该商品已交付给您。6、您无正当理由拒绝签收不支持七天无理由退换货的商品或性质不适宜拒签的商品的,商品返回后,需由您承担商品毁损灭失的风险及因此产生的费用。龟仙人一番赏可选择退款或将商品再次发回,若商品性质已不适宜发回或您再次拒绝签收的,订单损失由您承担。五、支付条款</p><p><br/></p><p>1、您在使用“抽一张”、“抽五张”、“抽十张”、“全收&quot;等支付功能时因商品可能存在多名用户同时抢购的情况,您应及时注意赠品余量情况,保证支付后购买顺利。</p><p><br/></p><p>2、您在使用微信支付购买时,如遇到赠品数量不足的情况,相应金额会自动退款至您的微信账户,退款时可能会有延迟情况,请耐心等待。若长时间未收到钱款请联系客服处理六、售后条款</p><p><br/></p><p>“—番赏为开赏类商品,不支持七天无理由退换货。您知悉并理解内商品难免存在轻微增色、溢色、气泡染色不均轻微划痕等涂装或原厂问题的瑕疵。上述均属于正常现象,番赏不接受因此提出的退换货申请,请您知悉并谨慎考虑后,理性购买。</p><p><br/></p><p>七、通知</p><p><br/></p><p>1、为更好地为您提供服务,您同意接受我们发送的信息,包括但不限于:在番赏发布公告、向您发送邮件、向您提供的联系电话发送手机短信、向您的账号发送私信以及站内信信息向您提供的联系地址邮寄书面通知等。如一番堂能够证明以上电子形式的信息已经发送给您或者已在相关页面公布,则视为您已收到相关信息以纸质载体发出的书面通知,按照提供联系地址交邮后的第五个工作日即视为送达2、保留对本小程序注册、购物用户发送订单信息、促销活动等告知服务的权利。如果您在注册、购物,表明您已默认同意接受此项服务。</p><p><br/></p><p>八、评价</p><p><br/></p><p>您有权在提供的评价系统中对与您达成交易的商品进行评价。您应当理解,您在的评价信息是公开的您的所有评价行为应遵守本协议,评价内容应当客观真实,不应含任何污言秽语政治敏感、色情低俗、广告信息及法律法规列明之其他禁止性信息;您不应以不正当方式利用评价权利对其他用户实施威胁、敲诈勒索。有权对您实施上述行为所产生的评价信息进行删除或屏蔽。</p><p><br/></p><p>九、其他</p><p><br/></p><p>1、除非另有证明,储存在服务器上的数据是您使用服务的唯有效证据。</p><p><br/></p><p>2、若您存在以下不正当行为,—经发现,有权采取包括但不限于暂停发货、取消订单、拦截已发货的订单、限制账户权限等措施:</p><p><br/></p><p>(1)、您利用平台进行非法套现、洗钱及其他违法犯罪行为。</p><p><br/></p><p>(2)、由于用户将其用户密码告知他人或与他人共享注册账号与密码,由此导致的任何个人信息的泄漏,或其他非因番赏原因导致的个人隐私信息的泄露。(3)、用户自行向第三方公开其个人隐私信息。</p><p><br/></p><p>(4)、用户与及合作单位之间就用户个人隐私信息的使用公开达成约定因此向合作单位公开用户个人隐私信息、任何由于黑客攻击、电脑病毒侵入及其他非因原因导致用户个人隐私信息的泄露。</p><p><br/></p><p>2、用户同意可在以下事项中免费使用用户的个人隐私信息:</p><p><br/></p><p>(1)、向用户及时发送重要通知,如应用更新、本协议条款的变更。</p><p><br/></p><p>(2)、内部进行审计、数据分析和研究等,以改进番赏的产品、服务和与用户之间的沟通:</p><p><br/></p><p>(3)、依本协议约定,管理、审查用户信息及进行处理措施。</p><p><br/></p><p>(4)、帅Liu潮玩可能将收集到的用户信息,用在其他功能或服务中向用户提供特定内容,包括但不限于展示广告、对用户阅读过的内容进行信息安全类提示、基于特征标签进行间接人群画像并提供更加精准和个性化的服务和内容等。</p><p><br/></p><p>(2)、适用法律法规规定的其他事项。</p><p><br/></p><p>(3)、根据相关法律法规及国家标佳,以下情形中,可能会收集、使用、共享、转让、公开披露用户的个人信息无需征求用户的授权同意:(1)、与国家安全、国防安全等国家利益直接相关的与公共安全、公共卫生、公众知情等重大公共利益直接相关的。</p><p><br/></p><p>(2)、与犯罪侦查、起诉、审判和判决执行等直接相关的。</p><p><br/></p><p>(3)、出于维护用户或其他个人的生命、财产声誉等重大合法权益但又很难得到本人同意的。</p><p><br/></p><p>(4)、所收集的个人信息是用户自行向社会公众公开的。</p><p><br/></p><p>(5)、从合法公开披露的信息中收集个人信息的,如合法的新闻报道、政府信息公开等渠道根据用户要求签订和履行合同所必需的。(6)、用于维护所提供的产品或服务的安全稳定运行所必需的,例如发现、处置产品或服务的故障为开展合法的新闻报道所必需的出于公共利益开展统计或学术研究所必要的。(7)、法律法规规定的其他情形。</p><p><br/></p><p>4、我们非常重视对未成年人个人信息的保护。根据相关法律法规的规定,若您是18周岁以下的未成年人,在使用服务前,应事先取得您的家长或法定监护人的书面同意。</p><p><br/></p><p>5、帅Liu潮玩将会尽其商业上的合理努力保障用户在本软件及服务中的数据存储安全,但是并不能就此提供完全保证,包括但不限于以下情形:</p><p><br/></p><p>(1)、不对用户在本软件及服务中相关数据的删除或储存失败负责。(2)、有权根据实际情况自行决定单个用户在本软件及服务中数据的最长储存期限,并在服务器上为其分配数据最大存储空间等。</p><p><br/></p><p>(3)、如果用户停止使用本软件及服务或服务被终止或取消,帅Liu潮玩可以从服务器上永久地删除用户数据。服务停止、终止或取消后,没有义务向用户返还任何数据。</p><p><br/></p><p>十一、免责事由</p><p><br/></p><p>1、您知悉并同意,不因下述任一情况而可能导致的任何损害赔偿承担责任,包括但不限于财产、收益、数据资料等方面的损失或其它无形损失:(1)、因台风、地震、海啸、洪水、停电、战争、恐怖袭击等不可抗力之因素导致系统或服务不能正常运行。</p><p><br/></p><p>(2)、由于黑客攻击、电信部门技术调整或故障、系统维护等原因而造成的系统服务中断或者延迟。</p><p><br/></p><p>(3)、由于政府命令、法律法规的变更、司法机关及行政机关的命令、裁定等原因而导致的系统服务中断、终止或延迟。</p><p><br/></p><p>(4)、由于您将账号、密码告知他人或未保管好自己的密码或与他人共享账号或任何其他非的过错,导致您的个人资料泄露。</p><p><br/></p><p>(5)、由于与链接或合作的其它网站所造成的银行账户信息身份信息泄露及由此而导致的任何法律争议和后果、您(包括未成年人用户)向提供错误、不完整、不实信息等,造成任何损失。如因系统维护或升级的需要而需暂停服务时,我们将尽可能事先进行通知。对于服务的中断或终止而给您造成的任何损失,我们无须对您或任何第三方承担任何责任。</p><p><br/></p><p>2、您理解对您的任何请求采取行动均需要合理时间,且应您请求而采取的行动可能无法避免或阻止侵害后果的形成或扩大除存在法定过错外,番赏不承担责任。</p><p><br/></p><p>3、您理解并同意,因您自身违反本协议或相关服务条款的规定,导致或产生第三方主张的任何索赔、要求或损失您应当独立承担责任:因此遭受损失的,您也应当并赔偿。</p><p><br/></p><p>十二、投诉指引</p><p><br/></p><p>一番赏—向重视知识产权以及用户权益的保护如您认为平台某些内容涉嫌侵犯了您的合法权益,您可以通过发送邮件至[763044293@qq.com】向进行投诉。投诉请求至少应包括以下内容:(1)投诉人的姓名(名称)、联系电话和通讯地址:(2)投诉的具体理由、要求及被投诉内容:(3构成侵权的初步证明材料。当收到投诉请求后,我们的工作人员会尽快依法为您处理。</p><p><br/></p><p>十三法律适用、争议解决及条款可分割性1、本协议的订立、效力、解释、执行及其下产生的任何争议的解决应适用并遵守中国法律。</p><p><br/></p><p>2、因本协议或其违约、终止或无效而产生的或与本协议或其违约、终止或无效有关的任何争议、争论或诉求(争议)应提交杭州国际经济贸易仲裁委员会根据提交争议时该会届时有效的仲裁规则进行仲裁。仲裁庭的仲裁裁决为终局仲裁,对双方均有约束力双方应尽其最大努力使得任何该等仲裁裁决及时得以执行,并就此提供任何必要的协助。</p><p><br/></p><p>3、本协议任条款被视为废止、无效或不可执行,该条款应视为可分割的不应影响其他条款或其任何部分的效力,您与我们仍应善意履行。</p><p><br/></p><p><br/></p><p><br/></p>',1632451653),(6,'无限赏购买说明','<p>1、购买模式为开赏类商品，一经购买不支持无理由退货，请谨慎购买（未成年禁止购买）</p><p>2、购买规则有“一次”“五次”“十次”“全收”按钮获得商品<br/></p><p>3、双随机抽赏方式,FIRST为全局前半局随机赠送一位玩家，LAST为后半局随机赠送一位玩家。</p><p>4、全局赏类型，比如购买ABCDE赏后，全部赠品随机赠送每位客户，具体请查看赏池界面。</p><p>5、无限赏类型，为“一次”“三次”“五次”“十次”按钮获得商品，根据需求进行购买。</p>',1632451653),(7,'竞技赏购买说明','<p>1、购买模式为开赏类商品，一经购买不支持无理由退货，请谨慎购买（未成年禁止购买）</p><p>2、购买规则有“一次”“五次”“十次”“全收”按钮获得商品<br/></p><p>3、双随机抽赏方式,FIRST为全局前半局随机赠送一位玩家，LAST为后半局随机赠送一位玩家。</p><p>4、全局赏类型，比如购买ABCDE赏后，全部赠品随机赠送每位客户，具体请查看赏池界面。</p><p><br/></p><p><br/></p>',1632451653),(8,'赏袋注意事项','<p>					</p><p><br/></p><p><br/></p><p><br/></p><p><span style=\"color: rgb(0, 176, 240);\"><strong>【打包发货】</strong></span><br/></p><p>用于邮寄玩家喜欢的物品，现货发货时间为3-15天内发货，预售发货则为发售后3-15天内发货，偏远地区不包邮。</p><p><span style=\"color: rgb(0, 176, 240);\"><strong>【存保险柜】</strong></span></p><p>用于保存玩家所需要的物品，以免错误发货。</p><p><br/></p><p><br/></p><p>				</p>',1632451653),(9,'使用规则','<p>1、购买模式为开赏类商品，一经购买不支持无理由退货，请谨慎购买（未成年禁止购买）</p><p>2、购买规则有“一次”“五次”“十次”“全收”按钮获得商品<br/></p><p>3、双随机抽赏方式,FIRST为全局前半局随机赠送一位玩家，LAST为后半局随机赠送一位玩家。</p><p>4、全局赏类型，比如购买ABCDE赏后，全部赠品随机赠送每位客户，具体请查看赏池界面。</p><p><br/></p><p><br/></p>',1632451653),(10,'扭蛋机规则','<p>扭蛋盲盒</p><p>1、玩法说明有1，3，5，10抽，可以根据自己的需求进行购买。</p><p>2、里面随机设置一款隐藏款</p><p>3、购买扭蛋盲盒越多，出赏及隐藏款概率越高。</p><p>4、未成年人禁止购买。</p>',1632451653),(11,'赠送说明','<p>1、购买模式为开赏类商品，一经购买不支持无理由退货，请谨慎购买（未成年禁止购买）</p><p>2、购买规则有“一次”“五次”“十次”“全收”按钮获得商品<br/></p><p>3、双随机抽赏方式,FIRST为全局前半局随机赠送一位玩家，LAST为后半局随机赠送一位玩家。</p><p>4、全局赏类型，比如购买ABCDE赏后，全部赠品随机赠送每位客户，具体请查看赏池界面。</p><p><br/></p><p><br/></p>',1632451653);
/*!40000 ALTER TABLE `dl_single` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_sms`
--

DROP TABLE IF EXISTS `dl_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(11) NOT NULL COMMENT '手机号',
  `event` varchar(11) NOT NULL COMMENT '事件',
  `code` varchar(6) NOT NULL COMMENT '验证码',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `expire_time` int(10) NOT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='验证码表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_sms`
--

LOCK TABLES `dl_sms` WRITE;
/*!40000 ALTER TABLE `dl_sms` DISABLE KEYS */;
INSERT INTO `dl_sms` VALUES (1,'18523004717','login','488589',1655458868,1655459468),(2,'15723065063','login','902114',1655469870,1655470470),(3,'15723065063','login','871305',1655469943,1655470543),(4,'15893633445','login','591510',1657781654,1657782254),(5,'15893633445','login','902370',1657783967,1657784567),(6,'15893633445','login','748451',1657784248,1657784848),(7,'15893633445','login','619375',1657785260,1657785860),(8,'15893633445','login','623824',1657785336,1657785936),(9,'15893633445','login','124359',1657785465,1657786065),(10,'15893633445','login','105489',1657785748,1657786348),(11,'15893633445','login','446964',1657785748,1657786348),(12,'15893633445','login','809791',1657785931,1657786531),(13,'15893633445','login','365182',1657786047,1657786647),(14,'15893633445','login','879358',1657786048,1657786648),(15,'15893633445','login','964044',1657786132,1657786732);
/*!40000 ALTER TABLE `dl_sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_suit`
--

DROP TABLE IF EXISTS `dl_suit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_suit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `box_id` int(10) NOT NULL COMMENT '盲盒ID',
  `is_end` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否售罄',
  `no_key` int(10) NOT NULL COMMENT '箱号',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `num` int(10) NOT NULL COMMENT '数量',
  `surplus` int(10) DEFAULT '0' COMMENT '剩余',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=739 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='盲盒套装表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_suit`
--

LOCK TABLES `dl_suit` WRITE;
/*!40000 ALTER TABLE `dl_suit` DISABLE KEYS */;
INSERT INTO `dl_suit` VALUES (737,49,0,1,1660420369,150,150),(738,50,0,1,1660420443,100,100);
/*!40000 ALTER TABLE `dl_suit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_suit_goods`
--

DROP TABLE IF EXISTS `dl_suit_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_suit_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `suit_id` int(10) NOT NULL COMMENT '箱子ID',
  `goods_id` int(10) NOT NULL COMMENT '商品ID',
  `num` int(10) NOT NULL COMMENT '数量',
  `ratio` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '概率',
  `level` varchar(10) NOT NULL COMMENT '等级',
  `sales` int(10) DEFAULT '0' COMMENT '销量',
  `surplus` int(10) NOT NULL DEFAULT '0' COMMENT '剩余',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  `is_special` tinyint(3) DEFAULT '0' COMMENT '是否特殊赏',
  `surplusNFTidArray` json DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4559 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='盲盒箱子赏品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_suit_goods`
--

LOCK TABLES `dl_suit_goods` WRITE;
/*!40000 ALTER TABLE `dl_suit_goods` DISABLE KEYS */;
INSERT INTO `dl_suit_goods` VALUES (4554,737,244,50,80.0000,'A',2,48,1660420369,0,'[4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 1]'),(4555,737,247,50,10.0000,'A',1,49,1660420369,0,NULL),(4556,737,250,50,10.0000,'A',0,50,1660420369,0,NULL),(4557,738,245,50,80.0000,'SP',0,50,1660420443,0,'[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50]'),(4558,738,248,50,20.0000,'A',0,50,1660420443,0,NULL);
/*!40000 ALTER TABLE `dl_suit_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_user`
--

DROP TABLE IF EXISTS `dl_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '头像图片',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `balance` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '积分',
  `token` text CHARACTER SET utf8 COMMENT '令牌',
  `create_time` int(10) NOT NULL COMMENT '注册时间',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1正常 2封号',
  `is_del` tinyint(3) DEFAULT '0' COMMENT '是否删除',
  `achieve` decimal(10,2) DEFAULT '0.00' COMMENT '消费',
  `platform_wallet` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `external_wallet_address` varchar(50) DEFAULT NULL,
  `bitcoin_address` varchar(50) DEFAULT NULL,
  `sol_address` varchar(50) DEFAULT NULL,
  `login_type` tinyint(4) DEFAULT NULL,
  `verified` tinyint(4) DEFAULT NULL,
  `referral_id` varchar(50) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_user`
--

LOCK TABLES `dl_user` WRITE;
/*!40000 ALTER TABLE `dl_user` DISABLE KEYS */;
INSERT INTO `dl_user` VALUES (28,'Jake Hiram','20220805164830.jpg','','',820.20,0.00,'ya29.A0AVA9y1uvUOia2Pfml5gH8EHakWvzUYTfZ4U1Q1JmQJrLPYdXvXCiNj6g1fDuw9m1zhM_jCB8eBw0GUFTLCG4az3u_ZCsq2UkuKnHAW4gS5qzvQjdQU6-x_G_YXGO8kkbNknaqoDEPHn9_rrNuaAJCivmFs9uKtoaCgYKATASATASFQE65dr8tby3gNjkxY1b6wi1Y_aTew0166',1660418590,0,0,0.00,'0xaE43aCac9FdeA2448fc62071aA5637D0Db98200d','jake@aylab.io','0xa8A86B82AeFbf31e9DFFB7C53dD1b3c2ad9A591f','tb1q9v7lr5kn8m79226q6jgk95kgavysqrlgu73k0w','FAQfHJSGowmtgGsEQZQiAEJCmxvhZi2YCw1tuwmPsZh2',0,0,'318','193.178.210.183'),(29,'Patryk Pietras','','','',198.03,0.00,'ya29.A0AVA9y1vxLw2O1AL2aCp97VAA43xPAvAtU_AQDHbBfrA5qq9xrF4rH4su7-ivbq40gQCQqVJ8kSbAuaZI8C6VGBq9yPgfK8kXr6Ute2mq32AQgdRadkO5mn-pJOSptZl2sQXak8ne6Gs9KSL9ch68Ru9FQEnKUAYUNnWUtBVEFTQVRBU0ZRRTY1ZHI4X1pseFc3YnlCaHBMT2Y2b1ZiQjBWQQ0165',1660418612,0,0,0.00,'0xdB7Dc0C66f5b212Bb7a252e51C660Fc00f3A9A6f','patrykpietrasfree@gmail.com','0xb1d9e45dB419B67e9f9239B98c1444f69CD8d1F0','mgtNWmG5NGvoBWjFAmY2KqzxyKWTRgjyxB','8Mhzxd8N8f49xrXSMUDtsr2qBm1t5sXnXKzoqTfDj4bC',0,0,'28',NULL),(30,'哆啦A梦鸭','20220720104903.jpeg','','',46.50,0.00,'decc3f0fcb6c8429d33ca68c1b0be42cd409e21b9544d80de509058238d3ad82',1660418628,0,0,0.00,'0x02f5d7425E63cf00abE400c47a9b4fd8ebCF52f8','','0x14da4Fc1abD3D749E62C1f5E1Cd219A6e31ecc06','msuAQ4xuE4T4CMjPTAHDXfSsYQ1p7FqRqJ','E5xeGcDBMdfhCpQrXrhHKRMwxogJwc8uvWHUQzbqh7yq',2,0,'35','193.178.210.183'),(31,'Kanshin','','','',101.02,0.00,'ya29.A0AVA9y1tpAx1TS9kG276XI3iwG3DVbEeUB5zcWY1iED15YqEahlv-LqNufwCAb7Mg1xHzdB3_lSL97RsRFm8Q66nxWdvt_-IT7QM0umJOgDZUYOV4pumtHijY8TgOcklZ0QQQkrAjEPOxf96eAZc3VJ64LodngN4YUNnWUtBVEFTQVRBU0ZRRTY1ZHI4aDFUODRfUU1zTEhfTjBSN2ZhRXFpdw0166',1660418637,0,0,0.00,'0x1F501BbCf702cc0eC37379BF0E735D9762731B68','robert@aylab.io','0xEd1EFd4a92276cB0c283f2b50091533FA4c9e95f','mgpxaoQQiVwqkCMP5M2g7Zy9f9pxobQXoQ','49WEXXWuwDEsU5h3Yu4zt6fpvvAQZyVuApSnry3mwP59',0,0,'31',NULL),(32,'Rik Adrit','','','',96.02,0.00,'ya29.a0ARrdaM8HvAB7OG3UXMgQ9z0X80JG51Ke0Q1AhN3MfMp4432VHm4Y4zr8ujCPr327JVVknbyALD-G0k5rGNmh7JiJeCGYTYe4WjQZS5mxWSpstgwmax4LgWLyruzBJFR7ohvuN81w5UoD-dniImAIJx5HuL9v',1660418646,0,0,0.00,'0xf3beCAD2c0539653C7dB219bD90D031eF5586582','rikadrit@gmail.com','0xcEc82D0D7e24d1DD896D3e06499DCc4d9E7b9a81','my967wRVAfb9spGxU279Q445SRdvnS6J8X','FZmftscP2Pj23im9XAUinkySXCFyRVxE35noZA4WAjGq',0,0,'29',NULL),(33,'Ronello Raganas','','','',100.01,0.00,'ya29.a0ARrdaM8sUTkqItnDIIiawuHo1ppTVGG4Af3IpIFbGzX8GHwZZo8e2cyCqUzbqfJrP4pXijnng-FOcdTKoHHGGASIt37b8yuhyjn2CHQwQG5CFbj6ybVovgZ-midh7-MwbHhQCofDHsxNDDx9xEjciq4LocRg',1660418655,0,0,0.00,'0xa6e7aE8668f416c4EC87dEEd263820E33ACeaD48','ragz100790@gmail.com','0xcf98331D6b98e1A51605FDd70829c0D2C9069a13','momaTx4PyjjiyXmRh2M7rmy4TB1L32zM7v','5JDSFTXq3GVqtJ5pp8qsgv94DfCEQHJz1JkvoMUE6fKs',0,0,'29',NULL),(34,'mrfishvlogs','','','',6.03,0.00,'f3c8537b7b5a008e9a219931736dd6c7655e4fc7a379e015f4c82c0ef184fa22',1660418662,0,0,0.00,'0x17d42fb8b803F143e9367b357CbE41D776c8113d','','0x1fAcd6893681AC539eA28a77a7B67d58843C0e9B','mvCdQwXyEQZNMvHsyphp6iV6mafGB3c1VY','BUaiSPY9KAJHySa8Ywy9yC6SbAyUN88ZhM5ea6Wd2QrM',2,0,'29',NULL),(35,'Jake Hiram','','','',931.78,0.00,'ya29.A0AVA9y1vNnzja20RxlGcd6SWS0ew2X0lVCu-7SZXxM42SZZXR1ZWYc_QdhmlzxG8BtYvZrpFRcspETHTO4Ywaf_E8oqfkdLIdsErNhj9xOjonjxJHFyBZSqobhwWR0EmF8nzNCmjR77aDMVd7kipx7-5TNl8WFWQYUNnWUtBVEFTQVRBU0ZRRTY1ZHI4UUQ2Z29ZdjhDSnRZcFU3WmxtaVpPZw0166',1660648686,0,0,0.00,'0xD046195cE42cBa98D9667DFB4f50df836817E4ef','hiramjake59@gmail.com','0x5Da6D29C69Ac99f99dB2da64b115e267F08C3bE4','n4QGLRgg9KnZXxuSqYBm4xiFiLNrM8Yied','6CfFRG7bX8Xskgq6ZrbSHtEbvhY1tgt5hLkTENk4YTJr',0,0,'29',NULL),(36,'Ivan Zieco','','','',100.02,0.00,'EAAKg6TyP5dMBAHkzwy1zV03m5ATYhAWnFGsKS09ZC90caA6qZAQTxMWpHGBNJGlD1ZCciD1QO5yVA9uhFDusyDik2fLOFV12c4OVwv7HuEVZCnOaEpl53ZBOLP83aRRfZAZBh1Vhmd5qpUyvD1gNZAq2cS2ISJ7eZCTwlWE3UWXVBCx8ymIcpZBaEGTZBoM5V26Qk0yDPY8G84euSxjdkh3l0ZBmarqZBdiQ7m5BFJIImxbYBTucrICgnzZCSC',1660418685,0,0,0.00,'0x944fb08dCb8a308411d343507b551934E2dcb220','ivanfree523@gmail.com','0xB98F30f3Fb057579736Bea71d5a9a5b4F3e72283','moxKNfumkiMZCTkcPDfYoDDM3v3BVMAmwQ','BcJcs38j59bJiLWhJRqv3VF5gq1urSCYye1Sw84XHCpY',1,0,'29',NULL),(37,'许愿','','','',100.01,0.00,'ya29.A0AVA9y1vcLQzoFko5vVrSWEmiRezqHNgh70rqTvnZBYlIpNEdOdvdjRjmV4VwdaL5rwTWRCNsfLReCTjlSKQmp3lm0_WecnrR5fQd4BDc0coiQnKTFi_omqy-Doc6CYYyr08_Bps5mCEbb4n1D41PYhvissXwgKwaCgYKATASATASFQE65dr83ln8hPNdIuZAHHAl5XSwSQ0166',1660418693,0,0,0.00,'0xC2Aa257e55c0EDD48093b54A8cf64a61312e46EF','damonxuyuan@gmail.com','0x2790Df539615D896D46DfEdc331f8e065d0B1731','mqGgqwSKDJfSdzq2Zb6YMPoRV4rUQRpbYM','A1SDhTLr3ErNpkKctjaRQPp1YDqKhfoSbE7g2pdjBmhj',0,0,NULL,NULL),(38,'','','','',100.02,0.00,'51c0b6ad6193c9cd92fc36e142a854b56a72c157e60a4280171e3967f30929e8',1660418702,0,0,0.00,'0x28826a5d05a12A8F19e862AFc5DF958d796Ce7c7','','0x8a72D3480EB61B985765ebD9823DbaC3E77A96DE','n2AswZonGJ1oStXtBFQXJzyCVC4c1fWH6n','F48mnwFFH4r9myybpda8HCGtEgo8QzLYiK6w2c9vHwuo',2,0,NULL,NULL);
/*!40000 ALTER TABLE `dl_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_user_address`
--

DROP TABLE IF EXISTS `dl_user_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_user_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `username` varchar(30) NOT NULL COMMENT '提货人',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `province` varchar(50) NOT NULL COMMENT '省',
  `city` varchar(50) NOT NULL COMMENT '市',
  `area` varchar(50) NOT NULL COMMENT '区/县',
  `address` varchar(150) NOT NULL COMMENT '详细地址',
  `is_default` tinyint(3) DEFAULT '0' COMMENT '是否默认',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户自提表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_user_address`
--

LOCK TABLES `dl_user_address` WRITE;
/*!40000 ALTER TABLE `dl_user_address` DISABLE KEYS */;
INSERT INTO `dl_user_address` VALUES (1,3,'778','17777888877','北京市','市辖区','西城区','古城一节',1,1653902583),(2,1,'康逍遥','18749814010','河南省','郑州市','金水区','商务外环路王鼎国际1408',0,1653905534),(3,1,'康逍遥','18749814010','香港','香港岛','香港岛','0000000',0,1653905945),(4,2,'李基','13592611824','北京市','市辖区','西城区','王鼎国际1408',1,1653965580),(5,4,'刘','15236981852','北京市','市辖区','西城区','121561561',1,1653983564);
/*!40000 ALTER TABLE `dl_user_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_user_bid`
--

DROP TABLE IF EXISTS `dl_user_bid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_user_bid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `box_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `buyNumber` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_user_bid`
--

LOCK TABLES `dl_user_bid` WRITE;
/*!40000 ALTER TABLE `dl_user_bid` DISABLE KEYS */;
/*!40000 ALTER TABLE `dl_user_bid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_user_box`
--

DROP TABLE IF EXISTS `dl_user_box`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_user_box` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `box_id` int(10) NOT NULL COMMENT '盲盒ID',
  `reward_rank` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_user_box`
--

LOCK TABLES `dl_user_box` WRITE;
/*!40000 ALTER TABLE `dl_user_box` DISABLE KEYS */;
INSERT INTO `dl_user_box` VALUES (5,28,36,8),(6,29,36,1),(7,31,36,1),(8,35,49,1);
/*!40000 ALTER TABLE `dl_user_box` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_user_coupon`
--

DROP TABLE IF EXISTS `dl_user_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_user_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `cid` int(10) NOT NULL COMMENT '优惠券ID',
  `level` varchar(20) NOT NULL COMMENT '级别',
  `image` varchar(150) NOT NULL COMMENT '图片',
  `min_score` int(10) NOT NULL COMMENT '最小可得',
  `max_score` int(10) NOT NULL COMMENT '最大可得',
  `oid` int(10) DEFAULT NULL COMMENT '订单ID',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0未使用 1已使用 2已合成',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  `is_merge` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否合并',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户优惠券表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_user_coupon`
--

LOCK TABLES `dl_user_coupon` WRITE;
/*!40000 ALTER TABLE `dl_user_coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `dl_user_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_user_coupon_log`
--

DROP TABLE IF EXISTS `dl_user_coupon_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_user_coupon_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL COMMENT '优惠券ID',
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `level` varchar(20) NOT NULL COMMENT '级别',
  `image` varchar(150) NOT NULL COMMENT '图片',
  `min_score` int(10) NOT NULL COMMENT '最少可得',
  `max_score` int(10) NOT NULL COMMENT '最大可得',
  `is_merge` tinyint(3) DEFAULT '0' COMMENT '是否合并',
  `state` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0分享中 1待开券 2已开券',
  `create_time` int(10) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='潮玩券记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_user_coupon_log`
--

LOCK TABLES `dl_user_coupon_log` WRITE;
/*!40000 ALTER TABLE `dl_user_coupon_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `dl_user_coupon_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_user_deal`
--

DROP TABLE IF EXISTS `dl_user_deal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_user_deal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `type` tinyint(3) NOT NULL COMMENT '1增加 2减少',
  `before_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '之前余额',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变动金额',
  `after_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '之后余额',
  `info` varchar(255) NOT NULL COMMENT '说明',
  `way` tinyint(3) NOT NULL COMMENT '1余额 2积分',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户交易记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_user_deal`
--

LOCK TABLES `dl_user_deal` WRITE;
/*!40000 ALTER TABLE `dl_user_deal` DISABLE KEYS */;
INSERT INTO `dl_user_deal` VALUES (1,2,2,10000.00,880.00,9120.00,'抽赏扣除余额',1,1653722051),(2,3,1,0.00,10000.00,10000.00,'管理员调整增加',1,1653902691),(3,3,2,10000.00,220.00,9780.00,'抽赏扣除余额',1,1653902739),(4,3,2,9780.00,88.00,9692.00,'抽赏扣除余额',1,1653902758),(5,3,2,9692.00,880.00,8812.00,'抽赏扣除余额',1,1653902772),(6,3,2,8812.00,880.00,7932.00,'抽赏扣除余额',1,1653902777),(7,3,2,7932.00,880.00,7052.00,'抽赏扣除余额',1,1653902782),(8,3,2,7052.00,3432.00,3620.00,'抽赏扣除余额',1,1653902788),(9,3,2,3620.00,10.00,3610.00,'发货扣除余额',1,1653902999),(10,3,2,3610.00,10.00,3600.00,'发货扣除余额',1,1653903001),(11,3,2,3600.00,10.00,3590.00,'发货扣除余额',1,1653903007),(12,3,2,3590.00,10.00,3580.00,'发货扣除余额',1,1653903010),(13,3,2,3580.00,10.00,3570.00,'发货扣除余额',1,1653903072),(14,3,2,3570.00,10.00,3560.00,'发货扣除余额',1,1653903075),(15,3,2,3560.00,130.00,3430.00,'抽赏扣除余额',1,1653903237),(16,3,2,3430.00,130.00,3300.00,'抽赏扣除余额',1,1653903243),(17,3,2,3300.00,130.00,3170.00,'抽赏扣除余额',1,1653903250),(18,3,2,3170.00,130.00,3040.00,'抽赏扣除余额',1,1653903254),(19,3,2,3040.00,130.00,2910.00,'抽赏扣除余额',1,1653903258),(20,3,2,2910.00,130.00,2780.00,'抽赏扣除余额',1,1653903261),(21,3,2,2780.00,130.00,2650.00,'抽赏扣除余额',1,1653903265),(22,3,2,2650.00,130.00,2520.00,'抽赏扣除余额',1,1653903268),(23,3,2,2520.00,130.00,2390.00,'抽赏扣除余额',1,1653903271),(24,3,2,2390.00,130.00,2260.00,'抽赏扣除余额',1,1653903275),(25,3,2,2260.00,130.00,2130.00,'抽赏扣除余额',1,1653903278),(26,3,2,2130.00,130.00,2000.00,'抽赏扣除余额',1,1653903282),(27,1,1,0.00,50.00,50.00,'管理员调整增加',1,1653905381),(28,1,1,0.00,200.00,200.00,'管理员调整增加',2,1653905389),(29,1,2,200.00,88.00,112.00,'抽赏抵扣88潮玩币',2,1653905444),(30,1,2,112.00,88.00,24.00,'抽赏抵扣88潮玩币',2,1653905451),(31,1,1,50.00,80.00,130.00,'管理员调整增加',1,1653905469),(32,1,2,24.00,24.00,0.00,'抽赏抵扣24潮玩币',2,1653905480),(33,1,2,130.00,64.00,66.00,'抽赏扣除余额',1,1653905480),(34,1,2,66.00,10.00,56.00,'发货扣除余额',1,1653905539),(35,1,1,56.00,20000.00,20056.00,'管理员调整增加',1,1653905740),(36,1,2,20056.00,880.00,19176.00,'抽赏扣除余额',1,1653905754),(37,1,1,19176.00,228.00,19404.00,'挂售余额收入',1,1653905961),(38,1,2,19404.00,25.00,19379.00,'抽赏扣除余额',1,1653906042),(39,2,2,1000.00,88.00,912.00,'抽赏抵扣88潮玩币',2,1653965641),(40,2,2,9120.00,10.00,9110.00,'发货扣除余额',1,1653965654),(41,2,2,912.00,88.00,824.00,'抽赏抵扣88潮玩币',2,1653965881),(42,2,2,9110.00,10.00,9100.00,'发货扣除余额',1,1653965910),(43,2,2,9100.00,10.00,9090.00,'发货扣除余额',1,1653965917),(44,2,2,824.00,440.00,384.00,'抽赏抵扣440潮玩币',2,1653965944),(45,2,2,384.00,130.00,254.00,'抽赏抵扣130潮玩币',2,1653965959),(46,2,2,254.00,254.00,0.00,'抽赏抵扣254潮玩币',2,1653966023),(47,2,2,9090.00,626.00,8464.00,'抽赏扣除余额',1,1653966023),(48,2,2,8464.00,880.00,7584.00,'抽赏扣除余额',1,1653978055),(49,4,2,10000.00,440.00,9560.00,'抽赏扣除余额',1,1653983168),(50,4,2,9560.00,88.00,9472.00,'抽赏扣除余额',1,1653983791),(51,4,2,9472.00,10.00,9462.00,'发货扣除余额',1,1653983804),(52,4,2,9462.00,88.00,9374.00,'抽赏扣除余额',1,1653984729),(53,4,2,9374.00,10.00,9364.00,'发货扣除余额',1,1653984739),(54,1,2,19379.00,880.00,18499.00,'抽赏扣除余额',1,1653985601),(55,3,2,2000.00,440.00,1560.00,'抽赏扣除余额',1,1654076279),(56,3,2,1560.00,90.00,1470.00,'抽赏扣除余额',1,1654077486),(57,3,2,1470.00,45.00,1425.00,'抽赏扣除余额',1,1654077511),(58,3,2,1425.00,90.00,1335.00,'抽赏扣除余额',1,1654077517),(59,3,2,1335.00,88.00,1247.00,'抽赏扣除余额',1,1654077620),(60,3,2,1247.00,25.00,1222.00,'抽赏扣除余额',1,1654077653),(61,3,2,1222.00,112.00,1110.00,'抽赏扣除余额',1,1654077912),(62,3,2,1110.00,56.00,1054.00,'抽赏扣除余额',1,1654077921),(63,3,2,1054.00,13.00,1041.00,'抽赏扣除余额',1,1654077997),(64,3,2,1041.00,65.00,976.00,'抽赏扣除余额',1,1654078039),(65,4,2,9364.00,88.00,9276.00,'抽赏扣除余额',1,1654078272),(66,4,2,9276.00,44.00,9232.00,'抽赏扣除余额',1,1654079742),(67,4,2,9232.00,88.00,9144.00,'抽赏扣除余额',1,1654079931),(68,4,2,9144.00,88.00,9056.00,'抽赏扣除余额',1,1654079977),(69,4,2,9056.00,9.00,9047.00,'抽赏扣除余额',1,1654080028),(70,4,2,9047.00,5.00,9042.00,'抽赏扣除余额',1,1654080136),(71,4,2,9042.00,88.00,8954.00,'抽赏扣除余额',1,1654080163),(72,4,2,8954.00,88.00,8866.00,'抽赏扣除余额',1,1654080259),(73,1,2,18499.00,440.00,18059.00,'抽赏扣除余额',1,1654080267),(74,1,2,18059.00,440.00,17619.00,'抽赏扣除余额',1,1654080346),(75,1,2,17619.00,88.00,17531.00,'抽赏扣除余额',1,1654080437),(76,3,2,976.00,88.00,888.00,'抽赏扣除余额',1,1654080509),(77,4,2,8866.00,5.00,8861.00,'抽赏扣除余额',1,1654080527),(78,3,2,888.00,112.00,776.00,'抽赏扣除余额',1,1654086591),(79,3,2,776.00,88.00,688.00,'抽赏扣除余额',1,1654331859),(80,3,2,688.00,13.00,675.00,'抽赏扣除余额',1,1655702899),(81,3,2,675.00,44.00,631.00,'抽赏扣除余额',1,1655731929),(82,3,2,631.00,88.00,543.00,'抽赏扣除余额',1,1655732012),(83,3,2,543.00,88.00,455.00,'抽赏扣除余额',1,1655732042),(84,3,2,455.00,56.00,399.00,'抽赏扣除余额',1,1655814489),(85,3,2,399.00,56.00,343.00,'抽赏扣除余额',1,1655814505),(86,3,2,343.00,112.00,231.00,'抽赏扣除余额',1,1655814511),(87,3,2,231.00,112.00,119.00,'抽赏扣除余额',1,1655814581),(88,3,2,119.00,45.00,74.00,'抽赏扣除余额',1,1655814687),(89,3,2,74.00,56.00,18.00,'抽赏扣除余额',1,1655814725),(90,3,2,18.00,5.00,13.00,'抽赏扣除余额',1,1655815706),(91,3,2,13.00,1.00,12.00,'抽赏扣除余额',1,1655923240),(92,3,2,12.00,5.00,7.00,'抽赏扣除余额',1,1657117457),(93,3,2,7.00,1.00,6.00,'抽赏扣除余额',1,1657330617),(94,3,2,6.00,1.00,5.00,'抽赏扣除余额',1,1657347042),(95,3,2,5.00,1.00,4.00,'抽赏扣除余额',1,1657635005),(96,3,2,4.00,0.10,3.90,'抽赏扣除余额',1,1658238316),(97,3,2,3.90,1.00,2.90,'抽赏扣除余额',1,1658301554);
/*!40000 ALTER TABLE `dl_user_deal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dl_user_nft`
--

DROP TABLE IF EXISTS `dl_user_nft`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dl_user_nft` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `box_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `NFTname` varchar(50) NOT NULL DEFAULT '',
  `level` varchar(50) NOT NULL DEFAULT '',
  `time` varchar(50) NOT NULL DEFAULT '',
  `token_id` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dl_user_nft`
--

LOCK TABLES `dl_user_nft` WRITE;
/*!40000 ALTER TABLE `dl_user_nft` DISABLE KEYS */;
INSERT INTO `dl_user_nft` VALUES (29,28,36,237,'TestGood1','A','1660327933','[2]'),(30,35,49,244,'B-nft-1','A','1660420872','[3]');
/*!40000 ALTER TABLE `dl_user_nft` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-08-16 11:26:57
