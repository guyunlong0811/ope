<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.0.2
Version: 1.5.4
Author: KeenThemes
Website: http://www.keenthemes.com/
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title><?php echo (L("admin_title")); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="MobileOptimized" content="320">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/zettaiOpe/Public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="/zettaiOpe/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/zettaiOpe/Public/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN THEME STYLES -->
    <link href="/zettaiOpe/Public/css/style-metronic.css" rel="stylesheet" type="text/css"/>
    <link href="/zettaiOpe/Public/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="/zettaiOpe/Public/css/style-responsive.css" rel="stylesheet" type="text/css"/>
    <link href="/zettaiOpe/Public/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="/zettaiOpe/Public/css/pages/tasks.css" rel="stylesheet" type="text/css"/>
    <link href="/zettaiOpe/Public/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="/zettaiOpe/Public/css/custom.css" rel="stylesheet" type="text/css"/>

    <!-- BEGIN DATE ONLINE COUNT STYLES -->

    <!-- END DATE ONLINE COUNT STYLES -->
    <link rel="stylesheet" type="text/css" href="/zettaiOpe/Public/plugins/bootstrap-datepicker/css/datepicker.css" />
    <!-- END THEME STYLES -->
    <!--<link rel="shortcut icon" href="favicon.ico" />-->
    <link rel="shortcut icon" href="/zettaiOpe/Public/img/picOTLShuijing.png" />

    <script src="/zettaiOpe/Public/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top">
<!-- BEGIN TOP NAVIGATION BAR -->
<div class="header-inner">
<!-- BEGIN LOGO -->
<a class="navbar-brand" href="/zettaiOpe/admin.php?s=">
    <img src="/zettaiOpe/Public/img/picOTLBiaoti.png" width="160" style="margin-top:-11px;" alt="logo" class="img-responsive" />
</a>
<p style="float: left; color: #FFFFFF; line-height: 40px; font-size: 22px; font-weight: bold;"><?php echo ($vApp); ?></p>
<!-- END LOGO -->
<!-- BEGIN RESPONSIVE MENU TOGGLER -->
<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
    <img src="/zettaiOpe/Public/img/menu-toggler.png" alt="" />
</a>
<!-- END RESPONSIVE MENU TOGGLER -->
<!-- BEGIN TOP NAVIGATION MENU -->
<ul class="nav navbar-nav pull-right">

<!-- BEGIN USER LOGIN DROPDOWN -->
<li class="dropdown user">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img alt="" src="/zettaiOpe/Public/img/avatar.png" width="29" height="29" />
        <span class="username"><?php echo ($vNickname); ?></span>
        <i class="icon-angle-down"></i>
    </a>
    <ul class="dropdown-menu">
        <li><a href="/zettaiOpe/admin.php?s=/Home/Home/logout"><i class="fa fa-sign-out"></i> 登出</a></li>
    </ul>
</li>
<!-- END USER LOGIN DROPDOWN -->
</ul>
<!-- END TOP NAVIGATION MENU -->
</div>
<!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<div class="clearfix"></div>
<!-- BEGIN CONTAINER -->
<div class="page-container">

<!-- BEGIN SIDEBAR -->
<div class="page-sidebar navbar-collapse collapse">

    <!-- BEGIN SIDEBAR MENU -->
    <ul class="page-sidebar-menu">

        <li>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <div class="sidebar-toggler hidden-phone"></div>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        </li>

        <li>

        </li>

        <li class="start <?php if(($vIcon) == "home"): ?>active<?php endif; ?>">
            <a href="/zettaiOpe/admin.php?s=/Home">
                <i class="fa fa-home"></i>
                <span class="title">主页</span>
                <span class="selected"></span>
            </a>
        </li>

        <?php if(is_array($vFunction)): foreach($vFunction as $key=>$value): if(($vIcon) == $value["icon"]): ?><li class="selected">
            <li class="active open">
        <?php else: ?>
            <li ><?php endif; ?>

            <a href="javascript:;">
                <i class="fa fa-<?php echo ($value["icon"]); ?>"></i>
                <span class="title"><?php echo ($value["name"]); ?></span>
                <span class="selected"></span>
                <?php if(($vIcon) == $value["icon"]): ?><span class="arrow open"></span>
                <?php else: ?>
                    <span class="arrow"></span><?php endif; ?>
            </a>

            <ul class="sub-menu">
                <?php if(is_array($value["list"])): foreach($value["list"] as $key=>$val): if(($val["display"]) != "false"): if(($vController) == $val["controller"]): ?><li class="active">
                        <?php else: ?>
                            <li><?php endif; ?>
                        <a href="/zettaiOpe/admin.php?s=/Home/<?php echo ($val["controller"]); ?>"><?php echo ($val["name"]); ?></a>
                        </li><?php endif; endforeach; endif; ?>
            </ul>

        </li><?php endforeach; endif; ?>

    </ul>
    <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR-->

<!-- BEGIN PAGE -->
<div class="page-content" style="min-height:648px !important">

    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">

        <div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption"><i class="fa fa-table"></i><?php echo (L("$vTitle")); ?></div>
        <div class="tools">

        <a href="/zettaiOpe/admin.php?s=/Home/GMExchangeType/add&p=<?php echo ($pg); ?>" style="color:whitesmoke;"><?php echo (L("gm_exchange_type_add")); ?></a>
                    </div>
        </div>
        <div class="portlet-body">

        <table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
            <thead>
                <tr>
                    <th><?php echo (L("exchange_type_id")); ?></th>
                    <th><?php echo (L("exchange_type_name")); ?></th>
                    <th><?php echo (L("exchange_type_type")); ?></th>
                    <th><?php echo (L("exchange_type_mail")); ?></th>
                    <th><?php echo (L("exchange_type_act")); ?></th>
                    <th><?php echo (L("exchange_type_total")); ?></th>
                    <th><?php echo (L("status")); ?></th>
                    <th colspan="5"><?php echo (L("operation")); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($list)): ?><tr>
                    <td colspan="100%" align="center"><?php echo (L("no_data")); ?></td>
                </tr><?php endif; ?>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($value["id"]); ?></td>
                    <td><?php echo ($value["name"]); ?></td>
                    <td><?php echo ($value["type"]); ?></td>
                    <td><?php echo ($value["goods"]); ?></td>
                    <td><?php echo ($value["act"]); ?></td>
                    <td><?php echo ($value["total"]); ?></td>
                    <td><?php echo ($value["status"]); ?><a href="/zettaiOpe/admin.php?s=/Home/GMExchangeType/status&id=<?php echo ($value["id"]); ?>&p=<?php echo ($pg); ?>">[<?php echo (L("change")); ?>]</a></td>
                    <td><a href="javascript:;" id="click_detail_<?php echo ($value["id"]); ?>"><?php echo (L("detail")); ?></a></td>
                    <td><a href="/zettaiOpe/admin.php?s=/Home/GMExchangeType/edit&id=<?php echo ($value["id"]); ?>&p=<?php echo ($pg); ?>"><?php echo (L("edit")); ?></a></td>
                    <td><a href="javascript:;" id="click_create_<?php echo ($value["id"]); ?>"><?php echo (L("create")); ?></a></td>
                    <td><a href="javascript:;" id="click_download_<?php echo ($value["id"]); ?>"><?php echo (L("download")); ?></a></td>
                    <td><a href="/zettaiOpe/admin.php?s=/Home/GMExchangeType/remove&id=<?php echo ($value["id"]); ?>&p=<?php echo ($pg); ?>"><?php echo (L("delete")); ?></a></td>

                </tr>
                <tr id="detail_<?php echo ($value["id"]); ?>" style="display:none;">
                    <td class="details" colspan="100%">
                        <table class="table table-bordered table-hover table-full-width">
                            <tbody>
                                <tr>
                                    <td width="200"><?php echo (L("exchange_type_server")); ?></td>
                                    <td><?php echo ($value["server"]); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo (L("exchange_type_channel")); ?></td>
                                    <td><?php echo ($value["channel"]); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo (L("exchange_type_use_count")); ?></td>
                                    <td><?php echo ($value["use_count"]); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo (L("exchange_type_use_count_diff")); ?></td>
                                    <td><?php echo ($value["use_count_diff"]); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo (L("exchange_type_starttime")); ?></td>
                                    <td><?php echo ($value["starttime"]); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo (L("exchange_type_endtime")); ?></td>
                                    <td><?php echo ($value["endtime"]); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo (L("exchange_type_lifetime")); ?></td>
                                    <td><?php echo ($value["lifetime"]); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo (L("exchange_type_create_count")); ?></td>
                                    <td><?php echo ($value["create_count"]); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr id="create_<?php echo ($value["id"]); ?>" style="display:none;">
                    <td class="creates" colspan="100%">
                        <table class="table table-bordered table-hover table-full-width">
                            <tbody>
                                <tr>
                                    <td width="400"><?php echo (L("exchange_code_prefix")); ?></td>
                                    <td><input type="text" class="form-control input-small" id="prefix_<?php echo ($value["id"]); ?>" name="prefix_<?php echo ($value["id"]); ?>" value="3" ></td>
                                </tr>
                                <tr>
                                    <td><?php echo (L("exchange_create_count")); ?></td>
                                    <td><input type="text" class="form-control input-small" id="create_count_<?php echo ($value["id"]); ?>" name="create_count_<?php echo ($value["id"]); ?>" value="0" ></td>
                                </tr>
                                <tr>
                                    <td><?php echo (L("exchange_create_code")); ?></td>
                                    <td><input type="text" class="form-control input-medium" id="create_code_<?php echo ($value["id"]); ?>" name="create_code_<?php echo ($value["id"]); ?>" value="" ></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><a id="submit_create_<?php echo ($value["id"]); ?>" href="javascript:;"><button type="button" class="btn blue"><?php echo (L("create")); ?></button></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr id="download_<?php echo ($value["id"]); ?>" style="display:none;">
                    <td class="downloads" colspan="100%">
                        <table class="table">
                            <tbody>
                            <?php if(!empty($download[$value[id]])): if(is_array($download[$value[id]])): $i = 0; $__LIST__ = $download[$value[id]];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                                <td><a href="/zettaiOpe/admin.php?s=/Home/Base/download&file=<?php echo ($val["name"]); ?>&url=<?php echo ($val["url"]); ?>" target="_blank"><?php echo ($val["name"]); ?></a></td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            <?php else: ?>
                            <tr>
                                <td align="center"><?php echo (L("no_data")); ?></td>
                            </tr><?php endif; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <input type="hidden" id="switch_detail_<?php echo ($value["id"]); ?>" value="0" />
                <input type="hidden" id="switch_create_<?php echo ($value["id"]); ?>" value="0" />
                <input type="hidden" id="switch_download_<?php echo ($value["id"]); ?>" value="0" />
                <script type="text/javascript">
                $(document).ready(function(){

                    $("#prefix_<?php echo ($value["id"]); ?>").val(3);
                    $("#create_count_<?php echo ($value["id"]); ?>").val(0);
                    $("#create_code_<?php echo ($value["id"]); ?>").val('');
                    $("#prefix_<?php echo ($value["id"]); ?>").attr('disabled', false);
                    $("#create_count_<?php echo ($value["id"]); ?>").attr('disabled', false);

                    //详情
                    $("#click_detail_<?php echo ($value["id"]); ?>").click(function(){
                        if($("#switch_detail_<?php echo ($value["id"]); ?>").val() == '0'){
                            $("#click_detail_<?php echo ($value["id"]); ?>").html('<?php echo (L("close")); ?>');
                            $("#switch_detail_<?php echo ($value["id"]); ?>").val('1');
                            $("#detail_<?php echo ($value["id"]); ?>").show('fast');
                        }else{
                            $("#click_detail_<?php echo ($value["id"]); ?>").html('<?php echo (L("detail")); ?>');
                            $("#switch_detail_<?php echo ($value["id"]); ?>").val('0');
                            $("#detail_<?php echo ($value["id"]); ?>").hide('fast');
                        }
                    });

                    //生成
                    $("#click_create_<?php echo ($value["id"]); ?>").click(function(){
                        if($("#switch_create_<?php echo ($value["id"]); ?>").val() == '0'){
                            $("#click_create_<?php echo ($value["id"]); ?>").html('<?php echo (L("close")); ?>');
                            $("#switch_create_<?php echo ($value["id"]); ?>").val('1');
                            $("#create_<?php echo ($value["id"]); ?>").show('fast');
                        }else{
                            $("#click_create_<?php echo ($value["id"]); ?>").html('<?php echo (L("create")); ?>');
                            $("#switch_create_<?php echo ($value["id"]); ?>").val('0');
                            $("#create_<?php echo ($value["id"]); ?>").hide('fast');
                        }
                    });

                    //下载
                    $("#click_download_<?php echo ($value["id"]); ?>").click(function(){
                        if($("#switch_download_<?php echo ($value["id"]); ?>").val() == '0'){
                            $("#click_download_<?php echo ($value["id"]); ?>").html('<?php echo (L("close")); ?>');
                            $("#switch_download_<?php echo ($value["id"]); ?>").val('1');
                            $("#download_<?php echo ($value["id"]); ?>").show('fast');
                        }else{
                            $("#click_download_<?php echo ($value["id"]); ?>").html('<?php echo (L("download")); ?>');
                            $("#switch_download_<?php echo ($value["id"]); ?>").val('0');
                            $("#download_<?php echo ($value["id"]); ?>").hide('fast');
                        }
                    });

                    //指定兑换码生成
                    $("#create_code_<?php echo ($value["id"]); ?>").blur(function(){
                        if($("#create_code_<?php echo ($value["id"]); ?>").val() != ''){
                            $("#prefix_<?php echo ($value["id"]); ?>").val(3);
                            $("#create_count_<?php echo ($value["id"]); ?>").val(0);
                            $("#prefix_<?php echo ($value["id"]); ?>").attr('disabled', true);
                            $("#create_count_<?php echo ($value["id"]); ?>").attr('disabled', true);
                        }else{
                            $("#prefix_<?php echo ($value["id"]); ?>").attr('disabled', false);
                            $("#create_count_<?php echo ($value["id"]); ?>").attr('disabled', false);
                        }
                    });

                    //生成逻辑
                    $("#prefix_<?php echo ($value["id"]); ?>").val(3);
                    $("#create_count_<?php echo ($value["id"]); ?>").val(0);

                    $("#prefix_<?php echo ($value["id"]); ?>").blur(function(){
                        if(!($("#prefix_<?php echo ($value["id"]); ?>").val() > 1)){
                            $("#prefix_<?php echo ($value["id"]); ?>").val(2);
                        }else{
                            var num = parseInt($("#prefix_<?php echo ($value["id"]); ?>").val());
                            $("#prefix_<?php echo ($value["id"]); ?>").val(num);
                        }
                    });

                    $("#create_count_<?php echo ($value["id"]); ?>").blur(function(){
                        if(!($("#create_count_<?php echo ($value["id"]); ?>").val() > 0)){
                            $("#create_count_<?php echo ($value["id"]); ?>").val(0);
                        }else{
                            var num = parseInt($("#create_count_<?php echo ($value["id"]); ?>").val());
                            $("#create_count_<?php echo ($value["id"]); ?>").val(num);
                        }
                    });

                    $("#submit_create_<?php echo ($value["id"]); ?>").click(function(){

                        var prefix = parseInt($("#prefix_<?php echo ($value["id"]); ?>").val());
                        var count = parseInt($("#create_count_<?php echo ($value["id"]); ?>").val());
                        var code = $("#create_code_<?php echo ($value["id"]); ?>").val();

                        if(code == ''){

                            if(!(count > 0)){
                                $("#create_count_<?php echo ($value["id"]); ?>").val(0);
                                alert('<?php echo (L("exchange_type_create_count_input")); ?>');
                                return false;
                            }

                            if($("#create_count_<?php echo ($value["id"]); ?>").val() > 10000){
                                $("#create_count_<?php echo ($value["id"]); ?>").val(10000);
                                alert('<?php echo (L("exchange_type_create_count_max")); ?>');
                                return false;
                            }

                        }

                        $("#submit_create_<?php echo ($value["id"]); ?>").attr('href',"/zettaiOpe/admin.php?s=/Home/GMExchangeType/create&id=<?php echo ($value["id"]); ?>&prefix="+prefix+"&count="+count+"&code="+code);
                        $("#submit_create_<?php echo ($value["id"]); ?>").attr('target','_blank');

                    });

                });
                </script><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>

        <?php echo ($vPageBar); ?>
            </div>
</div>

    </div>

</div>

</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="footer">
    <div class="footer-inner" style="margin-left:40%;">
        2014 &copy; Forever Game Network Technology Co., Ltd.
    </div>
    <div class="footer-tools">
			<span class="go-top">
			<i class="fa fa-angle-up"></i>
			</span>
    </div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/zettaiOpe/Public/plugins/respond.min.js"></script>
<script src="/zettaiOpe/Public/plugins/excanvas.min.js"></script>
<![endif]-->


<script src="/zettaiOpe/Public/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/zettaiOpe/Public/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
<script src="/zettaiOpe/Public/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/jquery.cookie.min.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN DATA ONLINE COUNT PLUGINS -->
<script src="/zettaiOpe/Public/plugins/flot/jquery.flot.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/flot/jquery.flot.resize.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/gritter/js/jquery.gritter.js" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
<script src="/zettaiOpe/Public/plugins/fullcalendar/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- END DATA ONLINE COUNT  PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/zettaiOpe/Public/scripts/app.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/scripts/index.js" type="text/javascript"></script>
<script src="/zettaiOpe/Public/scripts/tasks.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script type="text/javascript" src="/zettaiOpe/Public/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
    jQuery(document).ready(function() {
        App.init(); // initlayout and core plugins
    });

    if('<?php echo ($alert); ?>' != ''){
        alert('<?php echo (L("$alert")); ?>');
    }
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>