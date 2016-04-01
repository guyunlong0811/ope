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
        if('<?php echo ($vGet["user_id"]); ?>' != ''){$("#user_id").val('<?php echo ($vGet["user_id"]); ?>');}
        if('<?php echo ($vGet["channel_uid"]); ?>' != ''){$("#channel_uid").val('<?php echo ($vGet["channel_uid"]); ?>');}
        if('<?php echo ($vGet["nickname"]); ?>' != ''){$("#nickname").val('<?php echo ($vGet["nickname"]); ?>');}
    });
</script>

<div class="page-content" style="min-height:648px !important">

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
        or
        <input type="text" class="form-control input-small" placeholder="<?php echo (L("channel_uid_input")); ?>" id="channel_uid" name="channel_uid">
        or
        <input type="text" class="form-control input-small" placeholder="<?php echo (L("user_id_input")); ?>" id="user_id" name="user_id">
        or
        <input type="text" class="form-control input-small" placeholder="<?php echo (L("user_nickname_input")); ?>" id="nickname" name="nickname">
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

            <table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
                <thead>
                <tr>
                    <th><?php echo (L("tid")); ?></th>
                    <th><?php echo (L("uid")); ?></th>
                    <th><?php echo (L("nickname")); ?></th>
                    <th><?php echo (L("level")); ?></th>
                    <th><?php echo (L("diamond_pay")); ?></th>
                    <th><?php echo (L("diamond_free")); ?></th>
                    <th><?php echo (L("gold")); ?></th>
                    <th><?php echo (L("vality")); ?></th>
                    <th><?php echo (L("ctime")); ?></th>
                    <th><?php echo (L("last_login_time")); ?></th>
                    <th colspan="3"><?php echo (L("operation")); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($vTable)): $i = 0; $__LIST__ = $vTable;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($value["tid"]); ?></td>
                        <td><?php echo ($value["uid"]); ?></td>
                        <td><?php echo ($value["nickname"]); ?></td>
                        <td><?php echo ($value["level"]); ?></td>
                        <td><?php echo ($value["diamond_pay"]); ?></td>
                        <td><?php echo ($value["diamond_free"]); ?></td>
                        <td><?php echo ($value["gold"]); ?></td>
                        <td><?php echo ($value["vality"]); ?></td>
                        <td><?php echo ($value["ctime"]); ?></td>
                        <td><?php echo ($value["last_login_time"]); ?></td>
                        <td><a href="/zettaiOpe/admin.php?s=/Home/UserInfo/detail&server_id=<?php echo ($vGet["server_id"]); ?>&id=<?php echo ($vGet["id"]); ?>&team_id=<?php echo ($vGet["team_id"]); ?>&nickname=<?php echo ($vGet["nickname"]); ?>&tid=<?php echo ($value["tid"]); ?>"><?php echo (L("detail")); ?></a></td>
                        <?php if(($value["guide_skip"]) == "0"): ?><td id="td_skip<?php echo ($value["tid"]); ?>"><a id="skip<?php echo ($value["tid"]); ?>" href="javascript:;"><?php echo (L("skip_guide")); ?></a></td>
                        <?php else: ?>
                            <td id="td_skip<?php echo ($value["tid"]); ?>"><?php echo (L("guide_skipped")); ?></td><?php endif; ?>
                        <td><a id="kick<?php echo ($value["tid"]); ?>" href="javascript:;"><?php echo (L("kick_off")); ?></a></td>
                    </tr>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $("#skip<?php echo ($value["tid"]); ?>").click(function(){
                                $.get("/zettaiOpe/admin.php?s=/Home/UserInfo/skipGuide&server_id=<?php echo ($vGet["server_id"]); ?>&tid=<?php echo ($value["tid"]); ?>", function(data){
                                    if(data=='ok'){
                                        $("#td_skip<?php echo ($value["tid"]); ?>").html("<?php echo (L("guide_skipped")); ?>");
                                        alert("<?php echo (L("success")); ?>");
                                    }else{
                                        alert("<?php echo (L("fail")); ?>");
                                    }
                                });
                            });

                            $("#kick<?php echo ($value["tid"]); ?>").click(function(){
                                $.get("/zettaiOpe/admin.php?s=/Home/UserInfo/kick&server_id=<?php echo ($vGet["server_id"]); ?>&uid=<?php echo ($value["uid"]); ?>", function(data){
                                    if(data=='ok'){
                                        alert("<?php echo (L("success")); ?>");
                                    }else{
                                        alert("<?php echo (L("fail")); ?>");
                                    }
                                });
                            });
                        });
                    </script><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>

            <?php echo ($vPageBar); ?>
                    </div>
    </div><?php endif; endif; ?>

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