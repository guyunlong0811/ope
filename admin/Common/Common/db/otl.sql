SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `g_achievement`
-- ----------------------------
DROP TABLE IF EXISTS `g_achievement`;
CREATE TABLE `g_achievement` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `achieve` int(10) unsigned NOT NULL COMMENT '完成成就ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '登录时间',
  PRIMARY KEY (`tid`,`achieve`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_arena`
-- ----------------------------
DROP TABLE IF EXISTS `g_arena`;
CREATE TABLE `g_arena` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `rank` int(10) unsigned NOT NULL COMMENT '当前排名',
  `honour` int(10) unsigned NOT NULL COMMENT '荣誉值',
  `partner` varchar(64) NOT NULL COMMENT '防御伙伴组(json)',
  `rand_list` varchar(128) NOT NULL COMMENT '随机出的挑战排名',
  `win` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '当前连胜场次',
  `rank_change` tinyint(1) unsigned NOT NULL COMMENT '是否有排名变化',
  `last_refresh_time` int(10) unsigned NOT NULL COMMENT '最后刷新列表时间',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`tid`),
  KEY `rank` (`rank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_babel`
-- ----------------------------
DROP TABLE IF EXISTS `g_babel`;
CREATE TABLE `g_babel` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `floor` tinyint(3) unsigned NOT NULL COMMENT '层数',
  `partner` text NOT NULL COMMENT '已经阵亡的伙伴组ID列表',
  `max` tinyint(3) unsigned NOT NULL COMMENT '历史最大挑战楼层',
  `max_sweep` tinyint(3) unsigned NOT NULL COMMENT '最大可扫荡层数',
  `sweep_starttime` int(10) unsigned NOT NULL COMMENT '开始扫荡时间',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态(0:战斗;1:通关等待重置;2:boss层胜利;3:扫荡中;)',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_count`
-- ----------------------------
DROP TABLE IF EXISTS `g_count`;
CREATE TABLE `g_count` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `achievement` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成就点',
  `diamond_total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '累计获得水晶',
  `gold_total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '累计获得金币',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最高连击数',
  `arena` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '竞技场参加总次数',
  `arena_win` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '竞技场胜利次数',
  `arena_win_continuous` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '竞技场连胜次数',
  `arena_rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '竞技场最高排名',
  `abyss` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '深渊之战挑战次数',
  `abyss_kill` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '深渊之战击杀次数',
  `abyss_rank` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '深渊之战最高排名',
  `league` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '加入联盟次数',
  `league_donate` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '联盟捐献次数',
  `league_boss` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会BOSS参战次数',
  `league_boss_kill` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会BOSS胜利击杀次数',
  `league_fight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '联盟战参与次数',
  `league_fight_win` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '联盟战胜利次数',
  `league_arena` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'PVP公会战参与次数',
  `league_arena_win` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'PVP公会战胜利次数',
  `login` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `login_continuous` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '连续登录天数',
  `star` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '获得总星数',
  `star_reset` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '星灵系统重置次数',
  `force` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '战队总战力',
  `force_top` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最强小队战力',
  `quest_daily` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '日常任务完成数',
  `gold_box_use` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金色宝箱使用次数',
  `silver_box_use` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '银色宝箱使用次数',
  `emblem_combine` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '合成纹章次数',
  `share` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '分享次数',
  `quit_scene` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '退出场景ID',
  `mini_game` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最新小游戏版本号',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_daily_register`
-- ----------------------------
DROP TABLE IF EXISTS `g_daily_register`;
CREATE TABLE `g_daily_register` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `day` tinyint(2) unsigned NOT NULL COMMENT '登录天数(非第几天)',
  `pay` tinyint(1) unsigned NOT NULL COMMENT '是否是付费领取(0:免费;1:付费;)',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '奖品领取状态(1:单倍;2:双倍;)',
  PRIMARY KEY (`tid`,`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_device`
-- ----------------------------
DROP TABLE IF EXISTS `g_device`;
CREATE TABLE `g_device` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `name` varchar(24) NOT NULL COMMENT '设备名称',
  `system` tinyint(1) unsigned NOT NULL COMMENT '系统(1:ios;2:android;3:windows phone;)',
  `version` varchar(16) NOT NULL COMMENT '系统版本号',
  `token` text NOT NULL COMMENT '推送token',
  `utime` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem`;
CREATE TABLE `g_emblem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `index` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `tid` int(10) unsigned NOT NULL COMMENT '所属战队',
  `partner` int(10) unsigned NOT NULL COMMENT '装备伙伴组ID',
  `slot` tinyint(3) unsigned NOT NULL COMMENT '所属孔位(0:未装备;)',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_0`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_0`;
CREATE TABLE `g_emblem_0` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '数量',
  `total` smallint(5) unsigned NOT NULL COMMENT '总数量',
  PRIMARY KEY (`tid`,`emblem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_1`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_1`;
CREATE TABLE `g_emblem_1` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '数量',
  `total` smallint(5) unsigned NOT NULL COMMENT '总数量',
  PRIMARY KEY (`tid`,`emblem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_2`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_2`;
CREATE TABLE `g_emblem_2` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '数量',
  `total` smallint(5) unsigned NOT NULL COMMENT '总数量',
  PRIMARY KEY (`tid`,`emblem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_3`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_3`;
CREATE TABLE `g_emblem_3` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '数量',
  `total` smallint(5) unsigned NOT NULL COMMENT '总数量',
  PRIMARY KEY (`tid`,`emblem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_4`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_4`;
CREATE TABLE `g_emblem_4` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '数量',
  `total` smallint(5) unsigned NOT NULL COMMENT '总数量',
  PRIMARY KEY (`tid`,`emblem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_5`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_5`;
CREATE TABLE `g_emblem_5` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '数量',
  `total` smallint(5) unsigned NOT NULL COMMENT '总数量',
  PRIMARY KEY (`tid`,`emblem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_6`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_6`;
CREATE TABLE `g_emblem_6` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '数量',
  `total` smallint(5) unsigned NOT NULL COMMENT '总数量',
  PRIMARY KEY (`tid`,`emblem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_7`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_7`;
CREATE TABLE `g_emblem_7` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '数量',
  `total` smallint(5) unsigned NOT NULL COMMENT '总数量',
  PRIMARY KEY (`tid`,`emblem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_8`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_8`;
CREATE TABLE `g_emblem_8` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '数量',
  `total` smallint(5) unsigned NOT NULL COMMENT '总数量',
  PRIMARY KEY (`tid`,`emblem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_9`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_9`;
CREATE TABLE `g_emblem_9` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '数量',
  `total` smallint(5) unsigned NOT NULL COMMENT '总数量',
  PRIMARY KEY (`tid`,`emblem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_emblem_equip`
-- ----------------------------
DROP TABLE IF EXISTS `g_emblem_equip`;
CREATE TABLE `g_emblem_equip` (
  `tid` int(10) unsigned NOT NULL COMMENT '所属战队',
  `partner` int(10) unsigned NOT NULL COMMENT '装备伙伴组ID',
  `slot` tinyint(3) unsigned NOT NULL COMMENT '所属孔位',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  PRIMARY KEY (`tid`,`partner`,`slot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_equip_0`
-- ----------------------------
DROP TABLE IF EXISTS `g_equip_0`;
CREATE TABLE `g_equip_0` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组',
  `index` int(10) unsigned NOT NULL COMMENT '装备ID',
  `partner_group` int(10) unsigned NOT NULL COMMENT '归属伙伴组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '装备等级',
  `extra_1_type` int(10) unsigned NOT NULL COMMENT '附加属性类型(0:无;)',
  `extra_1_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_1_value` smallint(5) unsigned NOT NULL COMMENT '附加属性值',
  `extra_1_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_2_type` int(10) unsigned NOT NULL,
  `extra_2_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_2_value` smallint(5) unsigned NOT NULL,
  `extra_2_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_3_type` int(10) unsigned NOT NULL,
  `extra_3_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_3_value` smallint(5) unsigned NOT NULL,
  `extra_3_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_4_type` int(10) unsigned NOT NULL,
  `extra_4_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_4_value` smallint(5) unsigned NOT NULL,
  `extra_4_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  PRIMARY KEY (`tid`,`group`),
  KEY `tid_group` (`tid`,`partner_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_equip_1`
-- ----------------------------
DROP TABLE IF EXISTS `g_equip_1`;
CREATE TABLE `g_equip_1` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组',
  `index` int(10) unsigned NOT NULL COMMENT '装备ID',
  `partner_group` int(10) unsigned NOT NULL COMMENT '归属伙伴组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '装备等级',
  `extra_1_type` int(10) unsigned NOT NULL COMMENT '附加属性类型(0:无;)',
  `extra_1_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_1_value` smallint(5) unsigned NOT NULL COMMENT '附加属性值',
  `extra_1_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_2_type` int(10) unsigned NOT NULL,
  `extra_2_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_2_value` smallint(5) unsigned NOT NULL,
  `extra_2_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_3_type` int(10) unsigned NOT NULL,
  `extra_3_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_3_value` smallint(5) unsigned NOT NULL,
  `extra_3_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_4_type` int(10) unsigned NOT NULL,
  `extra_4_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_4_value` smallint(5) unsigned NOT NULL,
  `extra_4_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  PRIMARY KEY (`tid`,`group`),
  KEY `tid_group` (`tid`,`partner_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_equip_2`
-- ----------------------------
DROP TABLE IF EXISTS `g_equip_2`;
CREATE TABLE `g_equip_2` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组',
  `index` int(10) unsigned NOT NULL COMMENT '装备ID',
  `partner_group` int(10) unsigned NOT NULL COMMENT '归属伙伴组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '装备等级',
  `extra_1_type` int(10) unsigned NOT NULL COMMENT '附加属性类型(0:无;)',
  `extra_1_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_1_value` smallint(5) unsigned NOT NULL COMMENT '附加属性值',
  `extra_1_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_2_type` int(10) unsigned NOT NULL,
  `extra_2_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_2_value` smallint(5) unsigned NOT NULL,
  `extra_2_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_3_type` int(10) unsigned NOT NULL,
  `extra_3_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_3_value` smallint(5) unsigned NOT NULL,
  `extra_3_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_4_type` int(10) unsigned NOT NULL,
  `extra_4_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_4_value` smallint(5) unsigned NOT NULL,
  `extra_4_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  PRIMARY KEY (`tid`,`group`),
  KEY `tid_group` (`tid`,`partner_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_equip_3`
-- ----------------------------
DROP TABLE IF EXISTS `g_equip_3`;
CREATE TABLE `g_equip_3` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组',
  `index` int(10) unsigned NOT NULL COMMENT '装备ID',
  `partner_group` int(10) unsigned NOT NULL COMMENT '归属伙伴组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '装备等级',
  `extra_1_type` int(10) unsigned NOT NULL COMMENT '附加属性类型(0:无;)',
  `extra_1_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_1_value` smallint(5) unsigned NOT NULL COMMENT '附加属性值',
  `extra_1_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_2_type` int(10) unsigned NOT NULL,
  `extra_2_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_2_value` smallint(5) unsigned NOT NULL,
  `extra_2_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_3_type` int(10) unsigned NOT NULL,
  `extra_3_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_3_value` smallint(5) unsigned NOT NULL,
  `extra_3_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_4_type` int(10) unsigned NOT NULL,
  `extra_4_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_4_value` smallint(5) unsigned NOT NULL,
  `extra_4_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  PRIMARY KEY (`tid`,`group`),
  KEY `tid_group` (`tid`,`partner_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_equip_4`
-- ----------------------------
DROP TABLE IF EXISTS `g_equip_4`;
CREATE TABLE `g_equip_4` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组',
  `index` int(10) unsigned NOT NULL COMMENT '装备ID',
  `partner_group` int(10) unsigned NOT NULL COMMENT '归属伙伴组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '装备等级',
  `extra_1_type` int(10) unsigned NOT NULL COMMENT '附加属性类型(0:无;)',
  `extra_1_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_1_value` smallint(5) unsigned NOT NULL COMMENT '附加属性值',
  `extra_1_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_2_type` int(10) unsigned NOT NULL,
  `extra_2_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_2_value` smallint(5) unsigned NOT NULL,
  `extra_2_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_3_type` int(10) unsigned NOT NULL,
  `extra_3_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_3_value` smallint(5) unsigned NOT NULL,
  `extra_3_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_4_type` int(10) unsigned NOT NULL,
  `extra_4_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_4_value` smallint(5) unsigned NOT NULL,
  `extra_4_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  PRIMARY KEY (`tid`,`group`),
  KEY `tid_group` (`tid`,`partner_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_equip_5`
-- ----------------------------
DROP TABLE IF EXISTS `g_equip_5`;
CREATE TABLE `g_equip_5` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组',
  `index` int(10) unsigned NOT NULL COMMENT '装备ID',
  `partner_group` int(10) unsigned NOT NULL COMMENT '归属伙伴组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '装备等级',
  `extra_1_type` int(10) unsigned NOT NULL COMMENT '附加属性类型(0:无;)',
  `extra_1_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_1_value` smallint(5) unsigned NOT NULL COMMENT '附加属性值',
  `extra_1_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_2_type` int(10) unsigned NOT NULL,
  `extra_2_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_2_value` smallint(5) unsigned NOT NULL,
  `extra_2_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_3_type` int(10) unsigned NOT NULL,
  `extra_3_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_3_value` smallint(5) unsigned NOT NULL,
  `extra_3_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_4_type` int(10) unsigned NOT NULL,
  `extra_4_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_4_value` smallint(5) unsigned NOT NULL,
  `extra_4_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  PRIMARY KEY (`tid`,`group`),
  KEY `tid_group` (`tid`,`partner_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_equip_6`
-- ----------------------------
DROP TABLE IF EXISTS `g_equip_6`;
CREATE TABLE `g_equip_6` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组',
  `index` int(10) unsigned NOT NULL COMMENT '装备ID',
  `partner_group` int(10) unsigned NOT NULL COMMENT '归属伙伴组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '装备等级',
  `extra_1_type` int(10) unsigned NOT NULL COMMENT '附加属性类型(0:无;)',
  `extra_1_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_1_value` smallint(5) unsigned NOT NULL COMMENT '附加属性值',
  `extra_1_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_2_type` int(10) unsigned NOT NULL,
  `extra_2_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_2_value` smallint(5) unsigned NOT NULL,
  `extra_2_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_3_type` int(10) unsigned NOT NULL,
  `extra_3_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_3_value` smallint(5) unsigned NOT NULL,
  `extra_3_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_4_type` int(10) unsigned NOT NULL,
  `extra_4_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_4_value` smallint(5) unsigned NOT NULL,
  `extra_4_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  PRIMARY KEY (`tid`,`group`),
  KEY `tid_group` (`tid`,`partner_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_equip_7`
-- ----------------------------
DROP TABLE IF EXISTS `g_equip_7`;
CREATE TABLE `g_equip_7` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组',
  `index` int(10) unsigned NOT NULL COMMENT '装备ID',
  `partner_group` int(10) unsigned NOT NULL COMMENT '归属伙伴组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '装备等级',
  `extra_1_type` int(10) unsigned NOT NULL COMMENT '附加属性类型(0:无;)',
  `extra_1_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_1_value` smallint(5) unsigned NOT NULL COMMENT '附加属性值',
  `extra_1_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_2_type` int(10) unsigned NOT NULL,
  `extra_2_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_2_value` smallint(5) unsigned NOT NULL,
  `extra_2_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_3_type` int(10) unsigned NOT NULL,
  `extra_3_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_3_value` smallint(5) unsigned NOT NULL,
  `extra_3_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_4_type` int(10) unsigned NOT NULL,
  `extra_4_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_4_value` smallint(5) unsigned NOT NULL,
  `extra_4_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  PRIMARY KEY (`tid`,`group`),
  KEY `tid_group` (`tid`,`partner_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_equip_8`
-- ----------------------------
DROP TABLE IF EXISTS `g_equip_8`;
CREATE TABLE `g_equip_8` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组',
  `index` int(10) unsigned NOT NULL COMMENT '装备ID',
  `partner_group` int(10) unsigned NOT NULL COMMENT '归属伙伴组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '装备等级',
  `extra_1_type` int(10) unsigned NOT NULL COMMENT '附加属性类型(0:无;)',
  `extra_1_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_1_value` smallint(5) unsigned NOT NULL COMMENT '附加属性值',
  `extra_1_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_2_type` int(10) unsigned NOT NULL,
  `extra_2_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_2_value` smallint(5) unsigned NOT NULL,
  `extra_2_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_3_type` int(10) unsigned NOT NULL,
  `extra_3_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_3_value` smallint(5) unsigned NOT NULL,
  `extra_3_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_4_type` int(10) unsigned NOT NULL,
  `extra_4_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_4_value` smallint(5) unsigned NOT NULL,
  `extra_4_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  PRIMARY KEY (`tid`,`group`),
  KEY `tid_group` (`tid`,`partner_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_equip_9`
-- ----------------------------
DROP TABLE IF EXISTS `g_equip_9`;
CREATE TABLE `g_equip_9` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组',
  `index` int(10) unsigned NOT NULL COMMENT '装备ID',
  `partner_group` int(10) unsigned NOT NULL COMMENT '归属伙伴组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '装备等级',
  `extra_1_type` int(10) unsigned NOT NULL COMMENT '附加属性类型(0:无;)',
  `extra_1_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_1_value` smallint(5) unsigned NOT NULL COMMENT '附加属性值',
  `extra_1_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_2_type` int(10) unsigned NOT NULL,
  `extra_2_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_2_value` smallint(5) unsigned NOT NULL,
  `extra_2_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_3_type` int(10) unsigned NOT NULL,
  `extra_3_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_3_value` smallint(5) unsigned NOT NULL,
  `extra_3_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  `extra_4_type` int(10) unsigned NOT NULL,
  `extra_4_id` int(10) unsigned NOT NULL COMMENT '附加属性ID(0:无;)',
  `extra_4_value` smallint(5) unsigned NOT NULL,
  `extra_4_lock` tinyint(3) unsigned NOT NULL COMMENT '属性是否锁闭',
  PRIMARY KEY (`tid`,`group`),
  KEY `tid_group` (`tid`,`partner_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_event`
-- ----------------------------
DROP TABLE IF EXISTS `g_event`;
CREATE TABLE `g_event` (
  `id` int(10) unsigned NOT NULL COMMENT '活动在总表中的ID',
  `group` int(10) unsigned NOT NULL COMMENT '活动ID',
  `status` tinyint(1) unsigned NOT NULL COMMENT '活动状态(0:关闭;1:开启;2:未结算;)',
  `ps` varchar(255) NOT NULL COMMENT '备注信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_fate`
-- ----------------------------
DROP TABLE IF EXISTS `g_fate`;
CREATE TABLE `g_fate` (
  `tid` int(10) unsigned NOT NULL COMMENT '玩家战队ID',
  `fate` smallint(5) unsigned NOT NULL COMMENT '当前所进行到的',
  `bonus` int(10) unsigned NOT NULL,
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`tid`,`fate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_friend`
-- ----------------------------
DROP TABLE IF EXISTS `g_friend`;
CREATE TABLE `g_friend` (
  `tid_1` int(10) unsigned NOT NULL COMMENT '战队1ID(通常为发起者)',
  `tid_2` int(10) unsigned NOT NULL COMMENT '战队2ID(通常为接受者)',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态(1:成为好友;2:发起请求等待回复;)',
  PRIMARY KEY (`tid_1`,`tid_2`),
  KEY `tid_2` (`tid_2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_fund`
-- ----------------------------
DROP TABLE IF EXISTS `g_fund`;
CREATE TABLE `g_fund` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `fund` tinyint(3) unsigned NOT NULL COMMENT '基金编号',
  `ctime` int(10) unsigned NOT NULL COMMENT '领取时间',
  PRIMARY KEY (`tid`,`fund`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_guide`
-- ----------------------------
DROP TABLE IF EXISTS `g_guide`;
CREATE TABLE `g_guide` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `step1` int(10) unsigned NOT NULL COMMENT '大步骤',
  `step2` int(10) unsigned NOT NULL COMMENT '小步骤',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`tid`,`step1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_instance_0`
-- ----------------------------
DROP TABLE IF EXISTS `g_instance_0`;
CREATE TABLE `g_instance_0` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过关最高星数',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大combo数',
  `combo_time` int(10) unsigned NOT NULL COMMENT '打出最高连击时间',
  PRIMARY KEY (`tid`,`instance`),
  KEY `tid_group` (`tid`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_instance_1`
-- ----------------------------
DROP TABLE IF EXISTS `g_instance_1`;
CREATE TABLE `g_instance_1` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过关最高星数',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大combo数',
  `combo_time` int(10) unsigned NOT NULL COMMENT '打出最高连击时间',
  PRIMARY KEY (`tid`,`instance`),
  KEY `tid_group` (`tid`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_instance_2`
-- ----------------------------
DROP TABLE IF EXISTS `g_instance_2`;
CREATE TABLE `g_instance_2` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过关最高星数',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大combo数',
  `combo_time` int(10) unsigned NOT NULL COMMENT '打出最高连击时间',
  PRIMARY KEY (`tid`,`instance`),
  KEY `tid_group` (`tid`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_instance_3`
-- ----------------------------
DROP TABLE IF EXISTS `g_instance_3`;
CREATE TABLE `g_instance_3` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过关最高星数',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大combo数',
  `combo_time` int(10) unsigned NOT NULL COMMENT '打出最高连击时间',
  PRIMARY KEY (`tid`,`instance`),
  KEY `tid_group` (`tid`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_instance_4`
-- ----------------------------
DROP TABLE IF EXISTS `g_instance_4`;
CREATE TABLE `g_instance_4` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过关最高星数',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大combo数',
  `combo_time` int(10) unsigned NOT NULL COMMENT '打出最高连击时间',
  PRIMARY KEY (`tid`,`instance`),
  KEY `tid_group` (`tid`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_instance_5`
-- ----------------------------
DROP TABLE IF EXISTS `g_instance_5`;
CREATE TABLE `g_instance_5` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过关最高星数',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大combo数',
  `combo_time` int(10) unsigned NOT NULL COMMENT '打出最高连击时间',
  PRIMARY KEY (`tid`,`instance`),
  KEY `tid_group` (`tid`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_instance_6`
-- ----------------------------
DROP TABLE IF EXISTS `g_instance_6`;
CREATE TABLE `g_instance_6` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过关最高星数',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大combo数',
  `combo_time` int(10) unsigned NOT NULL COMMENT '打出最高连击时间',
  PRIMARY KEY (`tid`,`instance`),
  KEY `tid_group` (`tid`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_instance_7`
-- ----------------------------
DROP TABLE IF EXISTS `g_instance_7`;
CREATE TABLE `g_instance_7` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过关最高星数',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大combo数',
  `combo_time` int(10) unsigned NOT NULL COMMENT '打出最高连击时间',
  PRIMARY KEY (`tid`,`instance`),
  KEY `tid_group` (`tid`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_instance_8`
-- ----------------------------
DROP TABLE IF EXISTS `g_instance_8`;
CREATE TABLE `g_instance_8` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过关最高星数',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大combo数',
  `combo_time` int(10) unsigned NOT NULL COMMENT '打出最高连击时间',
  PRIMARY KEY (`tid`,`instance`),
  KEY `tid_group` (`tid`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_instance_9`
-- ----------------------------
DROP TABLE IF EXISTS `g_instance_9`;
CREATE TABLE `g_instance_9` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过关最高星数',
  `combo` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大combo数',
  `combo_time` int(10) unsigned NOT NULL COMMENT '打出最高连击时间',
  PRIMARY KEY (`tid`,`instance`),
  KEY `tid_group` (`tid`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_item_0`
-- ----------------------------
DROP TABLE IF EXISTS `g_item_0`;
CREATE TABLE `g_item_0` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '道具数量',
  PRIMARY KEY (`tid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_item_1`
-- ----------------------------
DROP TABLE IF EXISTS `g_item_1`;
CREATE TABLE `g_item_1` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '道具数量',
  PRIMARY KEY (`tid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_item_2`
-- ----------------------------
DROP TABLE IF EXISTS `g_item_2`;
CREATE TABLE `g_item_2` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '道具数量',
  PRIMARY KEY (`tid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_item_3`
-- ----------------------------
DROP TABLE IF EXISTS `g_item_3`;
CREATE TABLE `g_item_3` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '道具数量',
  PRIMARY KEY (`tid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_item_4`
-- ----------------------------
DROP TABLE IF EXISTS `g_item_4`;
CREATE TABLE `g_item_4` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '道具数量',
  PRIMARY KEY (`tid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_item_5`
-- ----------------------------
DROP TABLE IF EXISTS `g_item_5`;
CREATE TABLE `g_item_5` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '道具数量',
  PRIMARY KEY (`tid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_item_6`
-- ----------------------------
DROP TABLE IF EXISTS `g_item_6`;
CREATE TABLE `g_item_6` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '道具数量',
  PRIMARY KEY (`tid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_item_7`
-- ----------------------------
DROP TABLE IF EXISTS `g_item_7`;
CREATE TABLE `g_item_7` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '道具数量',
  PRIMARY KEY (`tid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_item_8`
-- ----------------------------
DROP TABLE IF EXISTS `g_item_8`;
CREATE TABLE `g_item_8` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '道具数量',
  PRIMARY KEY (`tid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_item_9`
-- ----------------------------
DROP TABLE IF EXISTS `g_item_9`;
CREATE TABLE `g_item_9` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '道具数量',
  PRIMARY KEY (`tid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_league`
-- ----------------------------
DROP TABLE IF EXISTS `g_league`;
CREATE TABLE `g_league` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '联盟编号',
  `name` varchar(30) NOT NULL COMMENT '联盟名称',
  `president_tid` int(10) unsigned NOT NULL COMMENT '盟主战队ID',
  `fund` int(10) unsigned NOT NULL COMMENT '联盟资金',
  `notice` varchar(255) NOT NULL COMMENT '联盟公告',
  `center_level` tinyint(3) unsigned NOT NULL COMMENT '联盟大厅等级',
  `shop_level` tinyint(3) unsigned NOT NULL COMMENT '联盟商店等级',
  `food_level` tinyint(3) unsigned NOT NULL COMMENT '联盟食堂等级',
  `attribute_level` tinyint(3) unsigned NOT NULL COMMENT '联盟神像等级',
  `boss_level` tinyint(3) unsigned NOT NULL COMMENT '公会boss等级',
  `activity` int(10) unsigned NOT NULL COMMENT '公会活跃度',
  `point` int(10) unsigned NOT NULL COMMENT '联盟积分',
  `record` char(5) NOT NULL COMMENT '联盟战近5场战绩(0:失败;1:胜利;2:平局;)',
  `recommend` int(10) unsigned NOT NULL COMMENT '推荐到期时间',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_league_arena`
-- ----------------------------
DROP TABLE IF EXISTS `g_league_arena`;
CREATE TABLE `g_league_arena` (
  `league_id` int(10) unsigned NOT NULL COMMENT '报名公会',
  `area` int(10) unsigned NOT NULL COMMENT '区域ID',
  `reg_tid` int(10) unsigned NOT NULL COMMENT '报名玩家tid',
  `count` int(10) unsigned NOT NULL COMMENT '已报名次数',
  `point` int(10) unsigned NOT NULL COMMENT '公会战积分',
  `ctime` int(10) unsigned NOT NULL COMMENT '报名时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更改时间',
  PRIMARY KEY (`league_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_league_arena_rank`
-- ----------------------------
DROP TABLE IF EXISTS `g_league_arena_rank`;
CREATE TABLE `g_league_arena_rank` (
  `league_id` int(10) unsigned NOT NULL COMMENT '公会ID',
  `league_name` varchar(30) NOT NULL COMMENT '公会名称',
  `point` int(10) unsigned NOT NULL COMMENT '积分',
  `area` int(10) unsigned NOT NULL COMMENT '区域ID',
  PRIMARY KEY (`league_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_league_arena_team`
-- ----------------------------
DROP TABLE IF EXISTS `g_league_arena_team`;
CREATE TABLE `g_league_arena_team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '队伍ID',
  `tid` int(10) unsigned NOT NULL COMMENT '报名玩家tid',
  `league_id` int(10) unsigned NOT NULL COMMENT '公会ID',
  `partner` char(32) NOT NULL COMMENT '报名队伍',
  `opponent` int(10) unsigned NOT NULL COMMENT '对手ID',
  `status` tinyint(3) unsigned NOT NULL COMMENT '队伍状态(0:失败;1:胜利;2:未战斗;3:战斗中;)',
  `ctime` int(10) unsigned NOT NULL COMMENT '报名时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_league_rank`
-- ----------------------------
DROP TABLE IF EXISTS `g_league_rank`;
CREATE TABLE `g_league_rank` (
  `league_id` int(10) unsigned NOT NULL COMMENT '联盟ID',
  `league_name` varchar(30) NOT NULL COMMENT '联盟名称',
  `president_tid` int(10) unsigned NOT NULL COMMENT '会长战队ID',
  `president_nickname` varchar(24) NOT NULL COMMENT '会长昵称',
  `center_level` tinyint(3) unsigned NOT NULL COMMENT '联盟大厅等级',
  `count` smallint(5) unsigned NOT NULL COMMENT '联盟当前人数',
  `point` int(10) unsigned NOT NULL COMMENT '联盟积分',
  PRIMARY KEY (`league_id`),
  UNIQUE KEY `league_id` (`league_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_league_team`
-- ----------------------------
DROP TABLE IF EXISTS `g_league_team`;
CREATE TABLE `g_league_team` (
  `league_id` int(10) unsigned NOT NULL COMMENT '联盟ID',
  `tid` int(10) unsigned NOT NULL COMMENT '成员战队ID',
  `position` tinyint(3) unsigned NOT NULL COMMENT '联盟职位(0:普通帮众;1:会长;2:副会长;3:精英;)',
  `contribution` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '贡献度',
  `boss_buff` smallint(5) unsigned NOT NULL COMMENT '公会boss剩余buff次数',
  `quest_list` varchar(255) NOT NULL COMMENT '联盟任务列表(json)',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`tid`,`league_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_level_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `g_level_bonus`;
CREATE TABLE `g_level_bonus` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `bonus` tinyint(3) unsigned NOT NULL COMMENT '礼包ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '领取时间',
  PRIMARY KEY (`tid`,`bonus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_life_death_battle`
-- ----------------------------
DROP TABLE IF EXISTS `g_life_death_battle`;
CREATE TABLE `g_life_death_battle` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `floor` tinyint(3) unsigned NOT NULL COMMENT '层数',
  `opponent` text NOT NULL COMMENT '对手战队信息',
  `reward` text NOT NULL COMMENT '所有奖励物品',
  `reward_last` text NOT NULL COMMENT '上层奖励物品',
  `reward_next` text NOT NULL COMMENT '下层奖励物品',
  `max` tinyint(3) unsigned NOT NULL COMMENT '历史最大挑战楼层',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '结束时间',
  `status` tinyint(1) NOT NULL COMMENT '当前状态(-1:战败;0:准备战斗;1:通关;2:战胜;3:等待重置;)',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_mail`
-- ----------------------------
DROP TABLE IF EXISTS `g_mail`;
CREATE TABLE `g_mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `type` tinyint(1) unsigned NOT NULL COMMENT '邮件类型(1:无附件;2:有附件;)',
  `title` varchar(255) NOT NULL COMMENT '邮件标题',
  `from` varchar(255) NOT NULL COMMENT '发件人',
  `des` text NOT NULL COMMENT '邮件内容',
  `item_1_type` int(10) unsigned NOT NULL COMMENT '物品类型',
  `item_1_value_1` int(10) unsigned NOT NULL COMMENT '道具参数1',
  `item_1_value_2` int(10) unsigned NOT NULL COMMENT '道具参数2',
  `item_2_type` int(10) unsigned NOT NULL COMMENT '物品类型',
  `item_2_value_1` int(10) unsigned NOT NULL COMMENT '道具参数1',
  `item_2_value_2` int(10) unsigned NOT NULL COMMENT '道具参数2',
  `item_3_type` int(10) unsigned NOT NULL COMMENT '物品类型',
  `item_3_value_1` int(10) unsigned NOT NULL COMMENT '道具参数1',
  `item_3_value_2` int(10) unsigned NOT NULL COMMENT '道具参数2',
  `item_4_type` int(10) unsigned NOT NULL COMMENT '物品类型',
  `item_4_value_1` int(10) unsigned NOT NULL COMMENT '道具参数1',
  `item_4_value_2` int(10) unsigned NOT NULL COMMENT '道具参数2',
  `open_script` varchar(255) NOT NULL COMMENT '邮件开启脚本',
  `behave` mediumint(6) unsigned NOT NULL COMMENT '邮件行为',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `dtime` int(10) unsigned NOT NULL COMMENT '邮件销毁时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '邮件状态(0:未查看;1:已查看邮件;)',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_map_star_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `g_map_star_bonus`;
CREATE TABLE `g_map_star_bonus` (
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `bonus` int(10) NOT NULL,
  `ctime` int(10) unsigned NOT NULL COMMENT '登录时间',
  PRIMARY KEY (`tid`,`bonus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_member`
-- ----------------------------
DROP TABLE IF EXISTS `g_member`;
CREATE TABLE `g_member` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `type` int(10) unsigned NOT NULL COMMENT '会员类型',
  `count` smallint(5) unsigned NOT NULL COMMENT '购买次数',
  `last_receive_date` date NOT NULL COMMENT '最近领取日期',
  `receive` smallint(5) unsigned NOT NULL COMMENT '购买次数',
  `expire` date NOT NULL COMMENT '会员结束时间(0:无限制;)',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`tid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_novice_login`
-- ----------------------------
DROP TABLE IF EXISTS `g_novice_login`;
CREATE TABLE `g_novice_login` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `day` tinyint(2) unsigned NOT NULL COMMENT '登录天数',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`tid`,`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_order`
-- ----------------------------
DROP TABLE IF EXISTS `g_order`;
CREATE TABLE `g_order` (
  `order_id` char(22) NOT NULL COMMENT '游戏内部订单号',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `cash_id` int(10) unsigned NOT NULL COMMENT '渠道ID',
  `price` int(10) unsigned NOT NULL COMMENT '价格',
  `channel_id` smallint(5) unsigned NOT NULL COMMENT '渠道ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_params`
-- ----------------------------
DROP TABLE IF EXISTS `g_params`;
CREATE TABLE `g_params` (
  `index` varchar(255) NOT NULL COMMENT '游戏参数键',
  `value` text NOT NULL COMMENT '游戏参数值',
  PRIMARY KEY (`index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `g_params`
-- ----------------------------
BEGIN;
INSERT INTO `g_params` VALUES ('ENABLE_EVENT', '[101,201,401,501,801,901,1001,1002,1401,1501,1601,1701,1801, 1901, 2001]'), ('ENABLE_MEMBER', '[10001,20001]'), ('EVENT_ICON_1', '{\"home\":[],\"list\":[]}'), ('EVENT_ICON_2', '[111,112]'), ('FATE_OPEN_TIME', '[{\"starttime\":\"2015-12-15 11:00:00\",\"endtime\":\"2015-12-21 23:59:59\"}]'), ('FIRST_PAY_RESET_TIME', '2014-08-17 15:52:31'), ('FUND_CLOSE', '2015-12-23 11:00:00'), ('FUND_RATE', '0.3'), ('GAME_SERVICE', 'http://os.sdo.com/default.aspx?gm=791000183&amp;source=040002&amp;user=@xx_s@'), ('LEAGUE_ARENA_TOP', '[]'), ('MAINTAIN_TIPS', ''), ('MAIN_ICON_1', '[101,102,103,104,105,106]'), ('MAIN_ICON_2', '[107,108,109,110]'), ('NEW_SERVER_BONUS', '2015-12-21 23:59:59'), ('PRAY_TIMED', '[{\"starttime\": \"2015-09-13 11:00:00\",\"endtime\": \"2015-09-20 11:00:00\"},{\"starttime\": \"2015-10-01 11:00:00\",\"endtime\": \"2015-10-07 11:00:00\"}]'), ('PRE_DOWNLOAD_BONUS', '{\"mail_id\":\"7004\",\"endtime\":\"2014-10-25 11:00:00\"}'), ('TEAM_MAX_LEVEL', '60');
COMMIT;

-- ----------------------------
--  Table structure for `g_partner`
-- ----------------------------
DROP TABLE IF EXISTS `g_partner`;
CREATE TABLE `g_partner` (
  `tid` int(10) unsigned NOT NULL COMMENT '所属战队ID',
  `group` int(10) unsigned NOT NULL COMMENT '伙伴组',
  `index` int(10) unsigned NOT NULL COMMENT '伙伴ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '伙伴当前等级',
  `exp` int(10) unsigned NOT NULL COMMENT '伙伴当前经验值',
  `favour` int(10) unsigned NOT NULL COMMENT '伙伴与主角的好感度经验',
  `soul` smallint(5) unsigned NOT NULL COMMENT '魂石数量',
  `skill_1_level` tinyint(3) unsigned NOT NULL COMMENT '技能等级',
  `skill_2_level` tinyint(3) unsigned NOT NULL COMMENT '技能等级',
  `skill_3_level` tinyint(3) unsigned NOT NULL COMMENT '技能等级',
  `skill_4_level` tinyint(3) unsigned NOT NULL COMMENT '技能等级',
  `skill_5_level` tinyint(3) unsigned NOT NULL COMMENT '技能等级',
  `skill_6_level` tinyint(3) unsigned NOT NULL COMMENT '技能等级',
  `force` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '伙伴战力',
  `ctime` int(10) unsigned NOT NULL COMMENT '获得伙伴时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最近更新时间',
  PRIMARY KEY (`tid`,`group`),
  UNIQUE KEY `tid_index` (`tid`,`index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_partner_quest`
-- ----------------------------
DROP TABLE IF EXISTS `g_partner_quest`;
CREATE TABLE `g_partner_quest` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `quest` int(10) unsigned NOT NULL COMMENT '任务ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '完成任务时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态(0:未完成;1:已完成;)',
  PRIMARY KEY (`tid`,`quest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_pay`
-- ----------------------------
DROP TABLE IF EXISTS `g_pay`;
CREATE TABLE `g_pay` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `cash_id` int(10) unsigned NOT NULL COMMENT '购买商品',
  `count` int(10) unsigned NOT NULL COMMENT '购买次数',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`tid`,`cash_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_pray`
-- ----------------------------
DROP TABLE IF EXISTS `g_pray`;
CREATE TABLE `g_pray` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `pray_id` smallint(5) unsigned NOT NULL COMMENT '祈愿ID',
  `is_free` tinyint(1) unsigned NOT NULL COMMENT '是否是免费',
  `count` int(10) unsigned NOT NULL COMMENT '抽奖次数',
  `ctime` int(10) unsigned NOT NULL COMMENT '第一次收取时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最近抽取时间',
  PRIMARY KEY (`tid`,`pray_id`,`is_free`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_pray_timed`
-- ----------------------------
DROP TABLE IF EXISTS `g_pray_timed`;
CREATE TABLE `g_pray_timed` (
  `tid` int(10) unsigned NOT NULL COMMENT '玩家战队ID',
  `dyn_id` int(10) unsigned NOT NULL COMMENT '对应运营活动配置ID',
  `point` int(10) unsigned NOT NULL COMMENT '玩家积分',
  `free_1` int(10) unsigned NOT NULL COMMENT '免费单抽次数',
  `free_10` int(10) unsigned NOT NULL COMMENT '免费10连抽次数',
  `pay_1` int(10) unsigned NOT NULL COMMENT '付费单抽次数',
  `pay_10` int(10) unsigned NOT NULL COMMENT '付费10连抽次数',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`tid`,`dyn_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_quest`
-- ----------------------------
DROP TABLE IF EXISTS `g_quest`;
CREATE TABLE `g_quest` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `quest` int(10) unsigned NOT NULL COMMENT '任务ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '完成任务时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态(0:玩家未浏览;1:玩家已完成;2:玩家已浏览;)',
  PRIMARY KEY (`tid`,`quest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_shop`
-- ----------------------------
DROP TABLE IF EXISTS `g_shop`;
CREATE TABLE `g_shop` (
  `type` int(10) unsigned NOT NULL COMMENT '商店ID',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID(填0则为所有人都可打开)',
  `ctime` int(10) unsigned NOT NULL COMMENT '商店开启时间',
  `dtime` int(10) unsigned NOT NULL COMMENT '商店关闭时间',
  PRIMARY KEY (`type`,`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_shop_arena`
-- ----------------------------
DROP TABLE IF EXISTS `g_shop_arena`;
CREATE TABLE `g_shop_arena` (
  `tid` int(10) unsigned NOT NULL COMMENT '队伍ID',
  `refresh_time` int(10) unsigned NOT NULL COMMENT '最近刷新商店时间',
  `goods_1` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_2` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_3` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_4` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_5` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_6` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_7` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_8` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_9` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_10` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_11` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_12` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_shop_hero`
-- ----------------------------
DROP TABLE IF EXISTS `g_shop_hero`;
CREATE TABLE `g_shop_hero` (
  `tid` int(10) unsigned NOT NULL COMMENT '队伍ID',
  `refresh_time` int(10) unsigned NOT NULL COMMENT '最近刷新商店时间',
  `goods_1` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_2` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_3` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_4` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_5` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_6` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_7` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_8` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_9` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_10` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_11` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_12` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_shop_league`
-- ----------------------------
DROP TABLE IF EXISTS `g_shop_league`;
CREATE TABLE `g_shop_league` (
  `tid` int(10) unsigned NOT NULL COMMENT '队伍ID',
  `refresh_time` int(10) unsigned NOT NULL COMMENT '最近刷新商店时间',
  `goods_1` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_2` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_3` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_4` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_5` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_6` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_7` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_8` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_9` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_10` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_11` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_12` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_13` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_14` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_15` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_16` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_17` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_18` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_shop_mystery`
-- ----------------------------
DROP TABLE IF EXISTS `g_shop_mystery`;
CREATE TABLE `g_shop_mystery` (
  `tid` int(10) unsigned NOT NULL COMMENT '队伍ID',
  `refresh_time` int(10) unsigned NOT NULL COMMENT '最近刷新商店时间',
  `goods_1` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_2` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_3` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_4` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_5` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_6` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_7` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_8` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_9` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_10` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_11` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_12` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_shop_normal`
-- ----------------------------
DROP TABLE IF EXISTS `g_shop_normal`;
CREATE TABLE `g_shop_normal` (
  `tid` int(10) unsigned NOT NULL COMMENT '队伍ID',
  `refresh_time` int(10) unsigned NOT NULL COMMENT '最近刷新商店时间',
  `goods_1` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_2` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_3` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_4` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_5` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_6` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_shop_vip`
-- ----------------------------
DROP TABLE IF EXISTS `g_shop_vip`;
CREATE TABLE `g_shop_vip` (
  `tid` int(10) unsigned NOT NULL COMMENT '队伍ID',
  `refresh_time` int(10) unsigned NOT NULL COMMENT '最近刷新商店时间',
  `goods_1` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_2` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_3` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_4` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_5` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_6` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_7` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_8` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_9` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_10` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_11` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_12` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_13` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_14` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_15` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_16` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_17` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_18` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_shop_vip_daily`
-- ----------------------------
DROP TABLE IF EXISTS `g_shop_vip_daily`;
CREATE TABLE `g_shop_vip_daily` (
  `tid` int(10) unsigned NOT NULL COMMENT '队伍ID',
  `refresh_time` int(10) unsigned NOT NULL COMMENT '最近刷新商店时间',
  `goods_1` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_2` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_3` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_4` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_5` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_6` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_7` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_8` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_9` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_10` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_11` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_12` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_13` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_14` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_15` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_16` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_17` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  `goods_18` varchar(80) NOT NULL COMMENT '商品类型(1:道具;2:属性;3:魂石;)#商品具体ID#商品数量#货币类型(1:金币;2:钻石;3:联盟贡献度;)#单价#限制类型#限制参数#是否被购买',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_star`
-- ----------------------------
DROP TABLE IF EXISTS `g_star`;
CREATE TABLE `g_star` (
  `tid` int(10) unsigned NOT NULL COMMENT '所属战队ID',
  `position` tinyint(3) unsigned NOT NULL COMMENT '星位',
  `level` tinyint(3) NOT NULL,
  `partner` int(10) unsigned NOT NULL COMMENT '伙伴',
  `attr1` tinyint(3) unsigned NOT NULL COMMENT '属性1洗炼百分比',
  `attr2` tinyint(3) unsigned NOT NULL COMMENT '属性2洗炼百分比',
  `cache_attr1` tinyint(3) unsigned NOT NULL COMMENT '属性1洗炼百分比',
  `cache_attr2` tinyint(3) unsigned NOT NULL COMMENT '属性2洗炼百分比',
  `gold_count` int(10) unsigned NOT NULL COMMENT '金币洗炼次数',
  `diamond_count` int(10) unsigned NOT NULL COMMENT '水晶洗炼次数',
  PRIMARY KEY (`tid`,`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_team`
-- ----------------------------
DROP TABLE IF EXISTS `g_team`;
CREATE TABLE `g_team` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '战队ID',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `role_id` varchar(64) DEFAULT NULL COMMENT '平台分配ID',
  `nickname` char(32) NOT NULL COMMENT '角色昵称',
  `icon` varchar(255) NOT NULL COMMENT '头像',
  `level` tinyint(3) unsigned NOT NULL COMMENT '等级',
  `exp` int(10) unsigned NOT NULL COMMENT '角色经验',
  `diamond_pay` int(10) unsigned NOT NULL COMMENT '宝石/点券',
  `diamond_free` int(10) unsigned NOT NULL COMMENT '免费钻石',
  `gold` int(10) unsigned NOT NULL COMMENT '游戏币',
  `star` smallint(5) unsigned NOT NULL COMMENT '当前已经使用星星数',
  `material_score` int(10) unsigned NOT NULL COMMENT '附魔材料积分',
  `vality` smallint(5) unsigned NOT NULL COMMENT '当前体力值',
  `vality_utime` int(10) unsigned NOT NULL COMMENT '体力最后更新时间(自动增加)',
  `energy` smallint(5) unsigned NOT NULL COMMENT '当前气力值',
  `energy_utime` int(10) unsigned NOT NULL COMMENT '气力最后更新时间(自动增加)',
  `skill_point` int(10) unsigned NOT NULL COMMENT '总技能点',
  `skill_point_utime` int(10) unsigned NOT NULL COMMENT '气力最后更新时间(自动增加)',
  `fund` tinyint(1) unsigned NOT NULL COMMENT '是否购买成长基金(1:已购买;0:未购买;)',
  `guide_skip` tinyint(1) unsigned NOT NULL COMMENT '是否跳过新手引导(1:已跳过;0:未跳过;)',
  `channel_id` smallint(5) unsigned NOT NULL COMMENT '渠道ID',
  `login_continuous` smallint(5) unsigned NOT NULL COMMENT '最近连续登录天数',
  `last_login_time` int(10) unsigned NOT NULL COMMENT '最近登录时间',
  `ctime` int(10) unsigned NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`tid`),
  KEY `uid` (`uid`),
  KEY `role_id` (`role_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_vip`
-- ----------------------------
DROP TABLE IF EXISTS `g_vip`;
CREATE TABLE `g_vip` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `index` int(10) unsigned NOT NULL COMMENT '玩家VIP',
  `score` int(10) unsigned NOT NULL COMMENT 'VIP积分',
  `pay_valid` int(10) unsigned NOT NULL COMMENT '有效充值',
  `pay` int(10) unsigned NOT NULL COMMENT '总充值数',
  `pay_count` int(10) unsigned NOT NULL COMMENT '充值次数',
  `first_pay` int(10) unsigned NOT NULL COMMENT '首次充值物品',
  `first_pay_level` tinyint(3) unsigned NOT NULL COMMENT '首次充值等级',
  `first_pay_time` int(10) unsigned NOT NULL COMMENT '首次充值时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `g_vip_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `g_vip_bonus`;
CREATE TABLE `g_vip_bonus` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `vip` int(10) unsigned NOT NULL COMMENT '会员类型',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`tid`,`vip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_abyss`
-- ----------------------------
DROP TABLE IF EXISTS `l_abyss`;
CREATE TABLE `l_abyss` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `battle_id` tinyint(3) unsigned NOT NULL COMMENT '活动副本ID',
  `rank_list` text NOT NULL COMMENT '排名情况',
  `last_tid` int(10) unsigned NOT NULL COMMENT '最后击杀战队ID',
  `drop` varchar(255) NOT NULL COMMENT '掉落情况',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_activity_complete`
-- ----------------------------
DROP TABLE IF EXISTS `l_activity_complete`;
CREATE TABLE `l_activity_complete` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `activity` int(10) unsigned NOT NULL COMMENT '活动ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '活动完成时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_arena`
-- ----------------------------
DROP TABLE IF EXISTS `l_arena`;
CREATE TABLE `l_arena` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `attr` char(16) NOT NULL COMMENT '改变属性',
  `value` int(10) NOT NULL COMMENT '改变值',
  `before` int(10) unsigned NOT NULL COMMENT '修改前数值',
  `after` int(10) unsigned NOT NULL COMMENT '修改后数值',
  `behave` mediumint(6) unsigned NOT NULL COMMENT '改变原因',
  `ctime` int(10) unsigned NOT NULL COMMENT '改变时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_arena_battle`
-- ----------------------------
DROP TABLE IF EXISTS `l_arena_battle`;
CREATE TABLE `l_arena_battle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `tid1` int(10) unsigned NOT NULL COMMENT '挑战者战队ID',
  `nickname1` varchar(32) NOT NULL COMMENT '挑战者昵称',
  `icon1` int(10) unsigned NOT NULL COMMENT '挑战者头像',
  `level1` tinyint(3) unsigned NOT NULL COMMENT '挑战者等级',
  `tid2` int(10) unsigned NOT NULL COMMENT '被挑战者战队ID',
  `nickname2` varchar(32) NOT NULL COMMENT '被挑战者昵称',
  `icon2` int(10) unsigned NOT NULL COMMENT '被挑战者头像',
  `level2` tinyint(3) unsigned NOT NULL COMMENT '被挑战者等级',
  `result` tinyint(1) unsigned NOT NULL COMMENT '胜负(1:胜;0:负;)',
  `change` int(10) unsigned NOT NULL COMMENT '排名变化',
  `ctime` int(10) unsigned NOT NULL COMMENT '挑战时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_cheat`
-- ----------------------------
DROP TABLE IF EXISTS `l_cheat`;
CREATE TABLE `l_cheat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `type` int(10) unsigned NOT NULL COMMENT '作弊类型',
  `value` varchar(255) NOT NULL COMMENT '作弊值',
  `normal` varchar(255) NOT NULL COMMENT '正常值',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_dynamic`
-- ----------------------------
DROP TABLE IF EXISTS `l_dynamic`;
CREATE TABLE `l_dynamic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `module` char(16) NOT NULL COMMENT '战斗模块',
  `dynamic` int(10) unsigned NOT NULL COMMENT '层数',
  `partner` varchar(64) NOT NULL COMMENT '参战伙伴',
  `target_tid` int(10) unsigned NOT NULL COMMENT '对手排名',
  `target_team` text NOT NULL,
  `result` tinyint(1) NOT NULL COMMENT '状态(-1:异常;0:战败;1:战胜;)',
  `starttime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '结束时间',
  PRIMARY KEY (`id`),
  KEY `tid_module` (`tid`,`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_emblem`
-- ----------------------------
DROP TABLE IF EXISTS `l_emblem`;
CREATE TABLE `l_emblem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'LOGID',
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `emblem` int(10) unsigned NOT NULL COMMENT '纹章ID',
  `count` smallint(5) NOT NULL COMMENT '改变数量',
  `behave` mediumint(6) unsigned NOT NULL COMMENT '获取或消耗方式',
  `ctime` int(10) unsigned NOT NULL COMMENT '记录时间戳',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_equip_strengthen`
-- ----------------------------
DROP TABLE IF EXISTS `l_equip_strengthen`;
CREATE TABLE `l_equip_strengthen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'LOGID',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `group` int(10) unsigned NOT NULL COMMENT '装备组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '强化等级',
  `after` tinyint(3) unsigned NOT NULL COMMENT '升级后等级',
  `ctime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_equip_upgrade`
-- ----------------------------
DROP TABLE IF EXISTS `l_equip_upgrade`;
CREATE TABLE `l_equip_upgrade` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'LOGID',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `group` int(10) unsigned NOT NULL COMMENT '升阶后的装备index',
  `index` int(10) unsigned NOT NULL COMMENT '升阶后index',
  `ctime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_iap`
-- ----------------------------
DROP TABLE IF EXISTS `l_iap`;
CREATE TABLE `l_iap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `transaction_id` text NOT NULL COMMENT '苹果商店订单号',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt` (`transaction_id`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_instance`
-- ----------------------------
DROP TABLE IF EXISTS `l_instance`;
CREATE TABLE `l_instance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录编号',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `module` char(16) NOT NULL COMMENT '战斗模块',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `group` int(10) unsigned NOT NULL COMMENT '副本组ID',
  `difficulty` tinyint(3) unsigned NOT NULL COMMENT '副本难度',
  `partner` varchar(64) NOT NULL COMMENT '参战伙伴',
  `drop` varchar(255) NOT NULL COMMENT '副本怪物掉落情况',
  `result` tinyint(2) NOT NULL COMMENT '战斗结果(-2:未完成;-1:作弊;0:失败;1:胜利;)',
  `starttime` int(10) unsigned NOT NULL COMMENT '副本开始时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '副本结束时间',
  `is_sweep` tinyint(1) unsigned NOT NULL COMMENT '是否是扫荡',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_item`
-- ----------------------------
DROP TABLE IF EXISTS `l_item`;
CREATE TABLE `l_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'LOGID',
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `item` int(10) unsigned NOT NULL COMMENT '道具ID',
  `count` smallint(5) NOT NULL COMMENT '改变数量',
  `behave` mediumint(6) unsigned NOT NULL COMMENT '获取或消耗方式',
  `ctime` int(10) unsigned NOT NULL COMMENT '记录时间戳',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_league`
-- ----------------------------
DROP TABLE IF EXISTS `l_league`;
CREATE TABLE `l_league` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `league_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `attr` char(16) NOT NULL COMMENT '改变属性',
  `value` int(10) NOT NULL COMMENT '改变值',
  `before` int(10) unsigned NOT NULL COMMENT '修改前数值',
  `after` int(10) unsigned NOT NULL COMMENT '修改后数值',
  `behave` mediumint(6) unsigned NOT NULL COMMENT '改变原因',
  `ctime` int(10) unsigned NOT NULL COMMENT '改变时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_league_battle`
-- ----------------------------
DROP TABLE IF EXISTS `l_league_battle`;
CREATE TABLE `l_league_battle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `tid` int(10) unsigned NOT NULL COMMENT '挑战玩家ID',
  `league_id` int(10) unsigned NOT NULL COMMENT '玩家所属联盟ID',
  `instance` int(10) unsigned NOT NULL COMMENT '副本ID',
  `partner` varchar(64) NOT NULL COMMENT '参战伙伴',
  `result` tinyint(2) NOT NULL COMMENT '战斗结果(-2:未完成;-1:作弊;0:失败;1:胜利;)',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_league_battle_result`
-- ----------------------------
DROP TABLE IF EXISTS `l_league_battle_result`;
CREATE TABLE `l_league_battle_result` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `league_id` int(10) unsigned NOT NULL COMMENT '胜利联盟ID(0:所有联盟挑战失败)',
  `league_name` varchar(64) NOT NULL COMMENT '联盟名称',
  `idol_tid` int(10) unsigned NOT NULL COMMENT '购买神像玩家战队ID(0:神像尚未激活;)',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间(激活神像时间)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_league_boss`
-- ----------------------------
DROP TABLE IF EXISTS `l_league_boss`;
CREATE TABLE `l_league_boss` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `league_id` int(10) unsigned NOT NULL COMMENT '活动副本ID',
  `site` tinyint(3) unsigned NOT NULL COMMENT '槽位',
  `last_tid` int(10) unsigned NOT NULL COMMENT '最后击杀战队ID',
  `rank_list` text NOT NULL COMMENT '排名情况',
  `drop` varchar(255) NOT NULL COMMENT '掉落情况',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_league_dismiss`
-- ----------------------------
DROP TABLE IF EXISTS `l_league_dismiss`;
CREATE TABLE `l_league_dismiss` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '联盟ID',
  `name` varchar(30) NOT NULL COMMENT '联盟名称',
  `president_tid` int(10) unsigned NOT NULL COMMENT '盟主战队ID',
  `fund` int(10) unsigned NOT NULL COMMENT '联盟资金',
  `notice` varchar(255) NOT NULL,
  `center_level` tinyint(1) unsigned NOT NULL COMMENT '联盟大厅等级',
  `shop_level` tinyint(1) unsigned NOT NULL COMMENT '联盟商店等级',
  `food_level` tinyint(1) unsigned NOT NULL COMMENT '联盟食堂等级',
  `attribute_level` tinyint(1) unsigned NOT NULL COMMENT '联盟神像等级',
  `battle_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是联盟战第一名(0:失败者;1:胜利者;2:胜利并开启神像功能;)',
  `recommend` int(10) unsigned NOT NULL COMMENT '推荐到期时间',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `dtime` int(10) unsigned NOT NULL COMMENT '解散时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_league_donate`
-- ----------------------------
DROP TABLE IF EXISTS `l_league_donate`;
CREATE TABLE `l_league_donate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `league_id` int(10) unsigned NOT NULL COMMENT '受捐赠联盟ID',
  `tid` int(10) unsigned NOT NULL COMMENT '捐赠战队ID',
  `donate_type` tinyint(3) unsigned NOT NULL COMMENT '捐赠类型',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `league_id` (`league_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_league_fight`
-- ----------------------------
DROP TABLE IF EXISTS `l_league_fight`;
CREATE TABLE `l_league_fight` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `league_id` int(10) unsigned NOT NULL COMMENT '公会1',
  `target_league_id` int(10) unsigned NOT NULL COMMENT '公会2',
  `hold` tinyint(1) unsigned NOT NULL COMMENT '占领据点数',
  `result` tinyint(3) unsigned NOT NULL COMMENT '结果(0:负;1:胜;2:平;)',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_league_food`
-- ----------------------------
DROP TABLE IF EXISTS `l_league_food`;
CREATE TABLE `l_league_food` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `tid` int(10) unsigned NOT NULL COMMENT '挑战玩家ID',
  `league_id` int(10) unsigned NOT NULL COMMENT '玩家所属联盟ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_league_team`
-- ----------------------------
DROP TABLE IF EXISTS `l_league_team`;
CREATE TABLE `l_league_team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `league_id` int(10) unsigned NOT NULL COMMENT '联盟ID',
  `attr` char(16) NOT NULL COMMENT '改变属性',
  `value` int(10) NOT NULL COMMENT '改变值',
  `before` int(10) unsigned NOT NULL COMMENT '修改前数值',
  `after` int(10) unsigned NOT NULL COMMENT '修改后数值',
  `behave` mediumint(6) unsigned NOT NULL COMMENT '改变原因',
  `ctime` int(10) unsigned NOT NULL COMMENT '改变时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_league_team_member`
-- ----------------------------
DROP TABLE IF EXISTS `l_league_team_member`;
CREATE TABLE `l_league_team_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `league_id` int(10) unsigned NOT NULL COMMENT '联盟ID',
  `tid` int(10) unsigned NOT NULL COMMENT '成员战队ID',
  `action_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0,none, 1: apply 2:join 3:left;4:declined;5:fire;',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_login`
-- ----------------------------
DROP TABLE IF EXISTS `l_login`;
CREATE TABLE `l_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_mail`
-- ----------------------------
DROP TABLE IF EXISTS `l_mail`;
CREATE TABLE `l_mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `type` tinyint(1) unsigned NOT NULL COMMENT '邮件类型(1:无附件;2:有附件;)',
  `title` varchar(255) NOT NULL COMMENT '邮件标题',
  `from` varchar(255) NOT NULL COMMENT '发件人',
  `des` text NOT NULL COMMENT '邮件内容',
  `item_1_type` int(10) unsigned NOT NULL COMMENT '物品类型',
  `item_1_value_1` int(10) unsigned NOT NULL COMMENT '道具参数1',
  `item_1_value_2` int(10) unsigned NOT NULL COMMENT '道具参数2',
  `item_2_type` int(10) unsigned NOT NULL COMMENT '物品类型',
  `item_2_value_1` int(10) unsigned NOT NULL COMMENT '道具参数1',
  `item_2_value_2` int(10) unsigned NOT NULL COMMENT '道具参数2',
  `item_3_type` int(10) unsigned NOT NULL COMMENT '物品类型',
  `item_3_value_1` int(10) unsigned NOT NULL COMMENT '道具参数1',
  `item_3_value_2` int(10) unsigned NOT NULL COMMENT '道具参数2',
  `item_4_type` int(10) unsigned NOT NULL COMMENT '物品类型',
  `item_4_value_1` int(10) unsigned NOT NULL COMMENT '道具参数1',
  `item_4_value_2` int(10) unsigned NOT NULL COMMENT '道具参数2',
  `open_script` varchar(255) NOT NULL COMMENT '邮件开启脚本',
  `behave` mediumint(6) unsigned NOT NULL COMMENT '邮件行为',
  `ctime` int(10) unsigned NOT NULL COMMENT '邮件创建时间',
  `dtime` int(10) unsigned NOT NULL COMMENT '邮件销毁时间',
  `create_time` int(10) unsigned NOT NULL COMMENT '日志创建时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '邮件状态(1:玩家操作;2:过期自动删除;3:管理员删除;)',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_member`
-- ----------------------------
DROP TABLE IF EXISTS `l_member`;
CREATE TABLE `l_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `type` int(10) unsigned NOT NULL COMMENT '会员类型',
  `ctime` int(10) unsigned NOT NULL COMMENT '领取时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_order`
-- ----------------------------
DROP TABLE IF EXISTS `l_order`;
CREATE TABLE `l_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `cash_id` int(10) unsigned NOT NULL COMMENT '渠道ID',
  `price` int(10) unsigned NOT NULL COMMENT '价格',
  `channel_id` smallint(5) unsigned NOT NULL COMMENT '渠道ID',
  `order_id` char(32) NOT NULL COMMENT '游戏内部订单号',
  `platform_order_id` varchar(128) NOT NULL COMMENT '第三方订单号',
  `verify` text NOT NULL COMMENT '验证信息',
  `level` tinyint(3) unsigned NOT NULL COMMENT '充值等级',
  `starttime` int(10) unsigned NOT NULL COMMENT '订单创建时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '订单结束时间',
  `status` tinyint(3) NOT NULL COMMENT '订单状态(1:成功;0:订单失败;-1:订单取消;-2:订单过期;-3:验证失败;-4:重复凭证;-5:商品增加失败;-6:价格有误;)',
  `comment` varchar(255) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_partner`
-- ----------------------------
DROP TABLE IF EXISTS `l_partner`;
CREATE TABLE `l_partner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `tid` int(10) unsigned NOT NULL COMMENT '伙伴',
  `group` int(10) unsigned NOT NULL COMMENT '伙伴组ID',
  `attr` char(16) NOT NULL COMMENT '改变属性',
  `value` int(10) NOT NULL COMMENT '改变值',
  `before` int(10) NOT NULL COMMENT '修改前数值',
  `after` int(10) NOT NULL COMMENT '修改后数值',
  `behave` mediumint(6) unsigned NOT NULL COMMENT '改变原因',
  `ctime` int(10) unsigned NOT NULL COMMENT '改变时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_pray`
-- ----------------------------
DROP TABLE IF EXISTS `l_pray`;
CREATE TABLE `l_pray` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `pray_id` smallint(5) unsigned NOT NULL COMMENT '祈愿ID',
  `bonus` text NOT NULL COMMENT '获得物品',
  `is_free` tinyint(1) unsigned NOT NULL COMMENT '类型(0:付费;1:免费;)',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `tid_pray` (`tid`,`pray_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_share`
-- ----------------------------
DROP TABLE IF EXISTS `l_share`;
CREATE TABLE `l_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '分享时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_shop`
-- ----------------------------
DROP TABLE IF EXISTS `l_shop`;
CREATE TABLE `l_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志编号',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID(填0则为所有人都可打开)',
  `type` int(10) unsigned NOT NULL COMMENT '商店ID',
  `starttime` int(10) unsigned NOT NULL COMMENT '商店开启时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '商店关闭时间',
  `ctime` int(10) unsigned NOT NULL COMMENT '商店开启时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_star`
-- ----------------------------
DROP TABLE IF EXISTS `l_star`;
CREATE TABLE `l_star` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'LOGID',
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `position` int(10) unsigned NOT NULL COMMENT '装备组ID',
  `level` tinyint(3) unsigned NOT NULL COMMENT '强化等级',
  `after` tinyint(3) unsigned NOT NULL COMMENT '升级后等级',
  `ctime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_team`
-- ----------------------------
DROP TABLE IF EXISTS `l_team`;
CREATE TABLE `l_team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `attr` char(16) NOT NULL COMMENT '改变属性',
  `value` int(10) NOT NULL COMMENT '改变值',
  `before` int(10) unsigned NOT NULL COMMENT '修改前数值',
  `after` int(10) unsigned NOT NULL COMMENT '修改后数值',
  `level` tinyint(3) unsigned NOT NULL COMMENT '操作等级',
  `behave` mediumint(6) unsigned NOT NULL COMMENT '改变原因',
  `ctime` int(10) unsigned NOT NULL COMMENT '改变时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `l_vip`
-- ----------------------------
DROP TABLE IF EXISTS `l_vip`;
CREATE TABLE `l_vip` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `tid` int(10) unsigned NOT NULL COMMENT '角色ID',
  `attr` char(16) NOT NULL COMMENT '改变属性',
  `value` int(10) NOT NULL COMMENT '改变值',
  `before` int(10) unsigned NOT NULL COMMENT '修改前数值',
  `after` int(10) unsigned NOT NULL COMMENT '修改后数值',
  `behave` mediumint(6) unsigned NOT NULL COMMENT '改变原因',
  `ctime` int(10) unsigned NOT NULL COMMENT '改变时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `t_daily_activity_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `t_daily_activity_bonus`;
CREATE TABLE `t_daily_activity_bonus` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `bonus` int(10) unsigned NOT NULL COMMENT '奖励ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '领取时间',
  PRIMARY KEY (`tid`,`bonus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `t_daily_count`
-- ----------------------------
DROP TABLE IF EXISTS `t_daily_count`;
CREATE TABLE `t_daily_count` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `type` smallint(5) unsigned NOT NULL COMMENT '类型',
  `count` smallint(5) unsigned NOT NULL COMMENT '次数',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`tid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `t_daily_event`
-- ----------------------------
DROP TABLE IF EXISTS `t_daily_event`;
CREATE TABLE `t_daily_event` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `event_id` smallint(5) unsigned NOT NULL COMMENT '活动ID',
  `type` smallint(5) unsigned NOT NULL COMMENT '类型(1:使用次数;2:购买次数;3:增加次数;4:附加功能使用次数;)',
  `group` mediumint(8) unsigned NOT NULL COMMENT '活动组ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '次数',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`tid`,`event_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `t_daily_instance`
-- ----------------------------
DROP TABLE IF EXISTS `t_daily_instance`;
CREATE TABLE `t_daily_instance` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `instance_id` int(10) unsigned NOT NULL COMMENT '副本ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '次数',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`tid`,`instance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `t_daily_league`
-- ----------------------------
DROP TABLE IF EXISTS `t_daily_league`;
CREATE TABLE `t_daily_league` (
  `league_id` int(10) unsigned NOT NULL COMMENT '联盟ID',
  `league_name` varchar(30) NOT NULL COMMENT '联盟名称',
  `president_tid` int(10) unsigned NOT NULL COMMENT '会长战队ID',
  `president_nickname` varchar(24) NOT NULL,
  `center_level` tinyint(3) unsigned NOT NULL COMMENT '联盟大厅等级',
  `count` smallint(5) unsigned NOT NULL COMMENT '联盟当前人数',
  PRIMARY KEY (`league_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `t_daily_online_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `t_daily_online_bonus`;
CREATE TABLE `t_daily_online_bonus` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `last_receive_bonus` tinyint(3) unsigned NOT NULL COMMENT '当日领奖次数',
  `last_receive_time` int(10) unsigned NOT NULL COMMENT '最近一次领取奖励时间',
  `second` mediumint(8) unsigned NOT NULL COMMENT '当前奖励时段在线时间',
  `cache` mediumint(8) unsigned NOT NULL COMMENT '缓存时间',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `t_daily_quest`
-- ----------------------------
DROP TABLE IF EXISTS `t_daily_quest`;
CREATE TABLE `t_daily_quest` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `quest` int(10) unsigned NOT NULL COMMENT '任务ID',
  `count` int(11) NOT NULL,
  `ctime` int(10) unsigned NOT NULL COMMENT '完成任务时间',
  `utime` int(10) NOT NULL,
  PRIMARY KEY (`tid`,`quest`),
  KEY `tid_quest` (`tid`,`quest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `t_daily_shop`
-- ----------------------------
DROP TABLE IF EXISTS `t_daily_shop`;
CREATE TABLE `t_daily_shop` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `type` smallint(5) unsigned NOT NULL COMMENT '商店类型',
  `count` smallint(5) unsigned NOT NULL COMMENT '次数',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`tid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `t_specify_event`
-- ----------------------------
DROP TABLE IF EXISTS `t_specify_event`;
CREATE TABLE `t_specify_event` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `event_id` smallint(5) unsigned NOT NULL COMMENT '活动ID',
  `type` smallint(5) unsigned NOT NULL COMMENT '类型(1:使用次数;2:购买次数;3:增加次数;4:附加功能使用次数;)',
  `group` mediumint(8) unsigned NOT NULL COMMENT '活动组ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '次数',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`tid`,`event_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `t_weekly_event`
-- ----------------------------
DROP TABLE IF EXISTS `t_weekly_event`;
CREATE TABLE `t_weekly_event` (
  `tid` int(10) unsigned NOT NULL COMMENT '战队ID',
  `event_id` smallint(5) unsigned NOT NULL COMMENT '活动ID',
  `type` smallint(5) unsigned NOT NULL COMMENT '类型(1:使用次数;2:购买次数;3:增加次数;4:附加功能使用次数;)',
  `group` mediumint(8) unsigned NOT NULL COMMENT '活动组ID',
  `count` smallint(5) unsigned NOT NULL COMMENT '次数',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`tid`,`event_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

SET FOREIGN_KEY_CHECKS = 1;
