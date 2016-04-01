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

<script type="text/javascript">
    $(document).ready(function(){
        $("#mainform").submit(function(){
            if($("#partner_group_id").val() == 0 && $("#team_id").val() == ''){
                alert('<?php echo (L("one_condition_least")); ?>');
                return false;
            }
            return true;
        });
    });
</script>

<!-- BEGIN PAGE -->
<div class="page-content" style="min-height:648px !important">

    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">

        <div class="portlet box yellow">
    <div class="portlet-title">
        <div class="caption"><i class="fa fa-search"></i><?php echo (L("$vTitle")); ?></div>
    </div>
    <div class="portlet-body">
        <form id="mainform" role="form" action="" method="get">
            <input type="hidden" name="s" value="<?php echo ($s); ?>">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group input">

        <script type="application/javascript">
    $(document).ready(function(){
        if('<?php echo ($vGet["server_id"]); ?>' != ''){$("#server_id").val('<?php echo ($vGet["server_id"]); ?>');}
    });
</script>
<select style="float:left; margin-left:10px;" class="form-control input-medium" id="server_id" name="server_id">
    <?php if(is_array($vServer)): foreach($vServer as $key=>$value): ?><option value=<?php echo ($key); ?>><?php echo ($value["server_name"]); ?></option><?php endforeach; endif; ?>
</select>
        <script type="text/javascript">
    $(document).ready(function(){
        if('<?php echo ($vGet["team_id"]); ?>' != ''){$("#team_id").val('<?php echo ($vGet["team_id"]); ?>');}
    });
</script>
<input type="text" style="float:left; margin-left:10px;" class="form-control input-small" placeholder="<?php echo (L("team_id_input")); ?>" id="team_id" name="team_id">
        <script type="text/javascript">
    $(document).ready(function(){
        if('<?php echo ($vGet["partner_group_id"]); ?>' != ''){$("#partner_group_id").val('<?php echo ($vGet["partner_group_id"]); ?>');}
    });
</script>
<select style="float:left; margin-left:10px;" class="form-control input-medium" id = "partner_group_id" name = "partner_group_id">
    <option value="0"><?php echo (L("all_partner")); ?></option>;
    <?php if(is_array($vPartnerConfig)): foreach($vPartnerConfig as $key=>$value): ?><option value="<?php echo ($key); ?>"><?php echo ($value); ?></option>;<?php endforeach; endif; ?>
</select>
        <button class="btn red" type="submit"><i class="fa fa-search"></i>&nbsp;<?php echo (L("search")); ?></button>

                            </div>
                </div>
            </div>
        </form>
    </div>
</div>

        <?php if(!empty($vGet)): if(empty($vTable)): ?><div class="alert alert-danger">
    <strong><?php echo (L("error")); ?>!</strong> <?php echo (L("no_data")); ?>
</div>
<?php else: ?>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption"><i class="fa fa-table"></i><?php echo (L("$vTitle")); ?></div>
        <div class="tools">

                        </div>
        </div>
        <div class="portlet-body">

            <table class="table table-striped table-bordered table-hover table-full-width">
                <thead>
                <tr>
                    <th><?php echo (L("tid")); ?></th>
                    <th><?php echo (L("partner")); ?></th>
                    <th><?php echo (L("partner_quality")); ?></th>
                    <th><?php echo (L("level")); ?></th>
                    <th><?php echo (L("exp")); ?></th>
                    <th><?php echo (L("favour")); ?></th>
                    <th><?php echo (L("soul")); ?></th>
                    <th><?php echo (L("skill_point")); ?></th>
                    <th><?php echo (L("partner_force")); ?></th>
                    <th colspan="3"><?php echo (L("operation")); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($vTable)): $i = 0; $__LIST__ = $vTable;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><input type="hidden" id="switch<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>" value="0" />
                    <tr>
                        <td><?php echo ($value["tid"]); ?></td>
                        <td><?php echo ($value["partner_name"]); ?></td>
                        <td><?php echo ($value["index"]); ?></td>
                        <td><?php echo ($value["level"]); ?></td>
                        <td><?php echo ($value["exp"]); ?></td>
                        <td><?php echo ($value["favour"]); ?></td>
                        <td><?php echo ($value["soul"]); ?></td>
                        <td><?php echo ($value["skill_point"]); ?></td>
                        <td><?php echo ($value["force"]); ?></td>
                        <td><a href="javascript:;" id="click<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>"><?php echo (L("detail")); ?></a></td>
                        <td><a href="/zettaiOpe/admin.php?s=/Home/UserPartnerEquip/index&server_id=<?php echo ($vGet["server_id"]); ?>&team_id=<?php echo ($value["tid"]); ?>&partner_group_id=<?php echo ($value["group"]); ?>"><?php echo (L("equip")); ?></a></td>
                        <td><a href="/zettaiOpe/admin.php?s=/Home/UserPartnerEmblem/index&server_id=<?php echo ($vGet["server_id"]); ?>&team_id=<?php echo ($value["tid"]); ?>&partner_group_id=<?php echo ($value["group"]); ?>"><?php echo (L("emblem")); ?></a>
                    </tr>
                    <tr id="detail<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>" style="display:none;">
                        <td class="details" colspan="100%">
                            <table class="table table-bordered table-hover table-full-width">
                                <tbody>
                                    <tr>
                                        <td width="200"><?php echo (L("skill_level")); ?></td>
                                        <td><?php echo ($value["skill_1_level"]); ?>|<?php echo ($value["skill_2_level"]); ?>|<?php echo ($value["skill_3_level"]); ?>|<?php echo ($value["skill_4_level"]); ?>|<?php echo ($value["skill_5_level"]); ?>|<?php echo ($value["skill_6_level"]); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo (L("ctime")); ?></td>
                                        <td><?php echo ($value["ctime"]); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $("#click<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>").click(function(){
                                if($("#switch<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>").val() == '0'){
                                    $("#click<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>").html('<?php echo (L("close")); ?>');
                                    $("#switch<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>").val('1');
                                    $("#detail<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>").show('fast');
                                }else{
                                    $("#click<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>").html('<?php echo (L("detail")); ?>');
                                    $("#switch<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>").val('0');
                                    $("#detail<?php echo ($value["tid"]); ?>_<?php echo ($value["group"]); ?>").hide('fast');
                                }
                            });
                        });
                    </script><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>

            <?php echo ($vPageBar); ?>
                    </div>
    </div><?php endif; endif; ?>

    </div>
    <!-- END PAGE CONTAINER-->

</div>
<!-- END PAGE -->

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