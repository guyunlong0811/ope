<?php
return array(
    'FUNCTION' => array(
        array(
            'name' => 'admin_manager',
            'icon' => 'user',
            'list' => array(
                array(
                    'name' => 'admin_model',
                    'controller' => 'Admin',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'admin_index',),
                        array('action' => 'status', 'name' => 'admin_change_status',),
                        array('action' => 'add', 'name' => 'admin_add',),
                        array('action' => 'edit', 'name' => 'admin_edit',),
                        array('action' => 'permission', 'name' => 'admin_permission',),
                        array('action' => 'server', 'name' => 'admin_server',),
                    ),
                ),
                array(
                    'name' => 'admin_modify',
                    'controller' => 'AdminInfo',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'admin_info_index',),
                    ),
                ),
                array(
                    'name' => 'admin_log',
                    'controller' => 'AdminLog',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'admin_log_index',),
                    ),
                ),
            ),
        ),

        array(
            'name' => 'server_manage',
            'icon' => 'cloud',
            'list' => array(

                array(
                    'name' => 'sm_channel',
                    'controller' => 'SMChannel',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'sm_channel_index',),
                        array('action' => 'add', 'name' => 'sm_channel_add',),
                        array('action' => 'edit', 'name' => 'sm_channel_edit',),
                        array('action' => 'status', 'name' => 'sm_channel_status',),
                    ),
                ),

                array(
                    'name' => 'sm_server',
                    'controller' => 'SMServer',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'sm_server_index',),
                        array('action' => 'status', 'name' => 'sm_server_status',),
                        array('action' => 'open', 'name' => 'sm_server_open',),
                        array('action' => 'merge', 'name' => 'sm_server_merge',),
                        array('action' => 'type', 'name' => 'sm_server_type',),
                        array('action' => 'channel', 'name' => 'sm_server_channel',),
                        array('action' => 'channelAdd', 'name' => 'sm_server_channel_add',),
                        array('action' => 'channelStatus', 'name' => 'sm_server_channel_status',),
                        array('action' => 'activation', 'name' => 'sm_server_activation',),
                        array('action' => 'maintainAllServer', 'name' => 'sm_server_maintain_all',),
                        array('action' => 'reopenAllServer', 'name' => 'sm_server_reopen_all',),
                        array('action' => 'clear', 'name' => 'sm_server_clear',),
                        array('action' => 'move', 'name' => 'sm_server_move',),
                        array('action' => 'robot', 'name' => 'sm_server_robot',),
                        array('action' => 'kick', 'name' => 'sm_server_kick',),
                        array('action' => 'channelOpenAll', 'name' => 'sm_server_open_all_channel',),
                        array('action' => 'channelCloseAll', 'name' => 'sm_server_close_all_channel',),
                        array('action' => 'channelChangeAll', 'name' => 'sm_server_change_all_channel_status',),
                    ),
                ),

                array(
                    'name' => 'sm_game',
                    'controller' => 'SMGame',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'sm_game_index',),
                        array('action' => 'add', 'name' => 'sm_game_add',),
                        array('action' => 'delete', 'name' => 'sm_game_delete',),
                        array('action' => 'status', 'name' => 'sm_game_status',),
                    ),
                ),

                array(
                    'name' => 'sm_script',
                    'controller' => 'SMScript',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'sm_script_index',),
                        array('action' => 'add', 'name' => 'sm_script_add',),
                        array('action' => 'delete', 'name' => 'sm_script_delete',),
                        array('action' => 'status', 'name' => 'sm_script_status',),
                    ),
                ),

                array(
                    'name' => 'sm_cache',
                    'controller' => 'SMCache',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'sm_cache_index',),
                    ),
                ),

            ),
        ),

        array(
            'name' => 'version_manager',
            'icon' => 'code-fork',
            'list' => array(
                array(
                    'name' => 'ver_resource_upload',
                    'controller' => 'VerResourceUpload',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'ver_resource_upload_index',),
                    ),
                ),
                array(
                    'name' => 'ver_release_version',
                    'controller' => 'VerRelease',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'ver_release_version_index',),
                    ),
                ),
                array(
                    'name' => 'ver_lua_update',
                    'controller' => 'VerLuaUpdate',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'ver_lua_update_index',),
                    ),
                ),
                array(
                    'name' => 'ver_db_update',
                    'controller' => 'VerDbUpdate',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'ver_db_update_index',),
                    ),
                ),
                array(
                    'name' => 'ver_list_release',
                    'controller' => 'VerListRelease',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'ver_list_release_index',),
                        array('action' => 'edit', 'name' => 'ver_list_release_edit',),
                        array('action' => 'release', 'name' => 'ver_list_release_release',),
                    ),
                ),
                array(
                    'name' => 'ver_list',
                    'controller' => 'VerList',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'ver_list_index',),
                        array('action' => 'edit', 'name' => 'ver_list_edit',),
                    ),
                ),
            ),
        ),

        array(
            'name' => 'data_statistics',
            'icon' => 'bar-chart-o',
            'list' => array(

                array(
                    'name' => 'data_real_time',
                    'controller' => 'DataRealTime',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_real_time_index',),
                    ),
//                    'display' => 'false',
                ),

                array(
                    'name' => 'data_statistics_index',
                    'controller' => 'DataStatisticsIndex',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_statistics_index_index',),
                    ),
                ),

                array(
                    'name' => 'data_statistics_asia',
                    'controller' => 'DataStatisticsAsia',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_statistics_asia_index',),
                    ),
                ),

                array(
                    'name' => 'data_statistics_daily',
                    'controller' => 'DataStatisticsDaily',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_statistics_daily_index',),
                    ),
                ),

                array(
                    'name' => 'data_statistics_monthly',
                    'controller' => 'DataStatisticsMonthly',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_statistics_monthly_index',),
                    ),
                ),

                array(
                    'name' => 'data_team_level',
                    'controller' => 'DataTeamLevel',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_team_level_index',),
                    ),
                ),
                array(
                    'name' => 'data_new_activation',
                    'controller' => 'DataNewActivation',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_new_activation_index',),
                    ),
                ),
                array(
                    'name' => 'data_recharge_distribution',
                    'controller' => 'DataRechargeDistribution',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_recharge_distribution_index',),
                    ),
                ),
                array(
                    'name' => 'data_nodes_monitor',
                    'controller' => 'DataNodesMonitor',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_nodes_monitor_index',),
                    ),
                ),

                array(
                    'name' => 'data_activation_statistics',
                    'controller' => 'DataActivationStatistics',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_activation_statistics_index',),
                    ),
                ),
//                array(
//                    'name' => 'data_login_statistics',
//                    'controller' => 'DataLoginStatistics',
//                    'permission' => array(
//                        array('action' => 'index', 'name' => 'data_login_statistics_index',),
//                    ),
//                ),
                array(
                    'name' => 'data_retention_statistics',
                    'controller' => 'DataRetentionStatistics',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_retention_statistics_index',),
                    ),
                ),
                array(
                    'name' => 'data_lost_statistics',
                    'controller' => 'DataLostStatistics',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_lost_statistics_index',),
                    ),
                ),


                array(
                    'name' => 'data_pay_level',
                    'controller' => 'DataPayLevel',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_pay_level_index',),
                    ),
                ),

                array(
                    'name' => 'data_pay_first_cash',
                    'controller' => 'DataPayFirstCash',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_pay_first_cash_index',),
                    ),
                ),

                array(
                    'name' => 'data_pay_first_level',
                    'controller' => 'DataPayFirstLevel',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_pay_first_level_index',),
                    ),
                ),

                array(
                    'name' => 'data_pay_rank_daily',
                    'controller' => 'DataPayRankDaily',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_pay_rank_daily_index',),
                    ),
                ),
                array(
                    'name' => 'data_pay_rank',
                    'controller' => 'DataPayRank',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_pay_rank_index',),
                    ),
                ),
                array(
                    'name' => 'data_pay_detail',
                    'controller' => 'DataPayDetail',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_pay_detail_index',),
                    ),
                ),

                array(
                    'name' => 'data_online_num',
                    'controller' => 'DataOnlineCount',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_online_count_index',),
                    ),
                ),

                array(
                    'name' => 'data_currency',
                    'controller' => 'DataCurrency',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_currency_index',),
                    ),
                ),

                array(
                    'name' => 'data_top_behave',
                    'controller' => 'DataTopBehave',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_top_behave_index',),
                    ),
                ),

                array(
                    'name' => 'data_lost_behave',
                    'controller' => 'DataLostBehave',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_lost_behave_index',),
                    ),
                ),

                array(
                    'name' => 'data_pray',
                    'controller' => 'DataPray',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'data_pray_index',),
                    ),
                ),

//                array(
//                    'name' => 'data_active_users',
//                    'controller' => 'DataActiveUsers',
//                    'permission' => array(
//                        array('action' => 'index', 'name' => 'data_active_users_index',),
//                    ),
//                ),
//                array(
//                    'name' => 'data_consume',
//                    'controller' => 'DataConsume',
//                    'permission' => array(
//                        array('action' => 'index', 'name' => 'data_consume_index',),
//                    ),
//                ),


            ),
        ),

        array(
            'name' => 'user_module',
            'icon' => 'group',
            'list' => array(

                array(
                    'name' => 'user_info',
                    'controller' => 'UserInfo',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_info_index',),
                        array('action' => 'detail', 'name' => 'user_info_detail',),
                        array('action' => 'skipGuide', 'name' => 'user_info_skip_guide',),
                        array('action' => 'kick', 'name' => 'user_info_kick',),
                    ),
                ),

                array(
                    'name' => 'user_partner',
                    'controller' => 'UserPartner',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_partner_index',),
                    ),
                ),

                array(
                    'name' => 'user_partner_equip',
                    'controller' => 'UserPartnerEquip',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_partner_equip_index',),
                    ),
                ),

                array(
                    'name' => 'user_partner_emblem',
                    'controller' => 'UserPartnerEmblem',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_partner_emblem_index',),
                    ),
                ),

                array(
                    'name' => 'user_item',
                    'controller' => 'UserItem',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_item_index',),
                    ),
                ),

                array(
                    'name' => 'user_mail',
                    'controller' => 'UserMail',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_mail_index',),
                    ),
                ),

                array(
                    'name' => 'user_mail_log',
                    'controller' => 'UserMailLog',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_mail_log_index',),
                    ),
                ),

                array(
                    'name' => 'user_quest',
                    'controller' => 'UserQuest',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_quest_index',),
                    ),
                ),

                array(
                    'name' => 'user_shop',
                    'controller' => 'UserShop',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_shop_index',),
                    ),
                ),

                array(
                    'name' => 'user_attr_change',
                    'controller' => 'UserAttrChange',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_attr_change_index',),
                    ),
                ),

                array(
                    'name' => 'user_item_change',
                    'controller' => 'UserItemChange',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_item_change_index',),
                    ),
                ),

                array(
                    'name' => 'user_order',
                    'controller' => 'UserOrder',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_order_index',),
                    ),
                ),

                array(
                    'name' => 'user_pray',
                    'controller' => 'UserPray',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'user_pray_index',),
                    ),
                ),

                array(
                    'name' => 'league_info',
                    'controller' => 'LeagueInfo',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'league_info_index',),
                        array('action' => 'detail', 'name' => 'league_info_detail',),
                        array('action' => 'edit', 'name' => 'league_info_edit',),
                    ),
                ),

            ),
        ),

        array(
            'name' => 'game_manage',
            'icon' => 'wrench',
            'list' => array(

                array(
                    'name' => 'gm_banned',
                    'controller' => 'GMBanned',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_banned_index',),
                        array('action' => 'add', 'name' => 'gm_banned_add',),
                        array('action' => 'edit', 'name' => 'gm_banned_edit',),
                    ),
                ),

                array(
                    'name' => 'gm_arena_rank',
                    'controller' => 'GMArenaRank',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_arena_rank_index',),
                        array('action' => 'add', 'name' => 'gm_arena_rank_replace',),
                    ),
                ),

                array(
                    'name' => 'gm_params',
                    'controller' => 'GMParams',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_params_index',),
                        array('action' => 'content', 'name' => 'gm_params_content',),
                    ),
                ),

                array(
                    'name' => 'gm_notice',
                    'controller' => 'GMNotice',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_notice_index',),
                        array('action' => 'status', 'name' => 'gm_notice_status',),
                        array('action' => 'add', 'name' => 'gm_notice_add',),
                        array('action' => 'edit', 'name' => 'gm_notice_edit',),
                        array('action' => 'delete', 'name' => 'gm_notice_delete',),
                    ),
                ),

                array(
                    'name' => 'gm_marquee',
                    'controller' => 'GMMarquee',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_marquee_index',),
                        array('action' => 'send', 'name' => 'gm_marquee_send',),
                        array('action' => 'resend', 'name' => 'gm_marquee_resend',),
                        array('action' => 'cancel', 'name' => 'gm_marquee_cancel',),
                    ),
                ),

                array(
                    'name' => 'gm_mail',
                    'controller' => 'GMMail',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_mail_index',),
                        array('action' => 'send', 'name' => 'gm_mail_send',),
                    ),
                ),

                array(
                    'name' => 'gm_operation',
                    'controller' => 'GMOperation',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_operation_index',),
                        array('action' => 'add', 'name' => 'gm_operation_add',),
                        array('action' => 'delete', 'name' => 'gm_operation_delete',),
                        array('action' => 'deleteAll', 'name' => 'gm_operation_delete_all',),
                    ),
                ),

                array(
                    'name' => 'gm_pay_consume',
                    'controller' => 'GMPayConsume',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_pay_consume_index',),
                        array('action' => 'groupIndex', 'name' => 'gm_pay_consume_group_index',),
                        array('action' => 'groupOpen', 'name' => 'gm_pay_consume_group_open',),
                        array('action' => 'groupClose', 'name' => 'gm_pay_consume_group_close',),
                        array('action' => 'status', 'name' => 'gm_pay_consume_status',),
                        array('action' => 'groupAdd', 'name' => 'gm_pay_consume_group_add',),
                        array('action' => 'add', 'name' => 'gm_pay_consume_add',),
                        array('action' => 'groupEdit', 'name' => 'gm_pay_consume_group_edit',),
                        array('action' => 'edit', 'name' => 'gm_pay_consume_edit',),
                        array('action' => 'groupDelete', 'name' => 'gm_pay_consume_group_delete',),
                        array('action' => 'delete', 'name' => 'gm_pay_consume_delete',),
                        array('action' => 'groupCopy', 'name' => 'gm_pay_consume_group_copy',),
                    ),
                ),

                array(
                    'name' => 'gm_activity_time',
                    'controller' => 'GMActivityTime',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_activity_time_index',),
                        array('action' => 'add', 'name' => 'gm_activity_time_add',),
                        array('action' => 'delete', 'name' => 'gm_activity_time_delete',),
                        array('action' => 'status', 'name' => 'gm_activity_time_status',),
                    ),
                ),

                array(
                    'name' => 'gm_pray_timed',
                    'controller' => 'GMPrayTimed',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_pray_timed_index',),
                        array('action' => 'status', 'name' => 'gm_pray_timed_status',),
                        array('action' => 'add', 'name' => 'gm_pray_timed_add',),
                        array('action' => 'edit', 'name' => 'gm_pray_timed_edit',),
                    ),
                ),

                array(
                    'name' => 'gm_vip',
                    'controller' => 'GMVip',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_vip_index',),
                        array('action' => 'send', 'name' => 'gm_vip_send',),
                    ),
                ),

                array(
                    'name' => 'gm_exchange_type',
                    'controller' => 'GMExchangeType',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_exchange_type_index',),
                        array('action' => 'add', 'name' => 'gm_exchange_type_add',),
                        array('action' => 'edit', 'name' => 'gm_exchange_type_edit',),
                        array('action' => 'status', 'name' => 'gm_exchange_type_change_status',),
                        array('action' => 'create', 'name' => 'gm_exchange_type_create',),
                    ),
                ),

                array(
                    'name' => 'gm_exchange_mail',
                    'controller' => 'GMExchangeMail',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_exchange_mail_index',),
                        array('action' => 'add', 'name' => 'gm_exchange_mail_add',),
                        array('action' => 'edit', 'name' => 'gm_exchange_mail_edit',),
                    ),
                ),

                array(
                    'name' => 'gm_exchange',
                    'controller' => 'GMExchange',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_exchange_index',),
                    ),
                ),

                array(
                    'name' => 'gm_push',
                    'controller' => 'GMPush',
                    'permission' => array(
                        array('action' => 'index', 'name' => 'gm_push_index',),
                        array('action' => 'send', 'name' => 'gm_push_send',),
                    ),
                ),

            ),
        ),

    ),
);