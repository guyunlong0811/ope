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

        $('#openAll').click(function(){
            $.get("/zettaiOpe/admin.php?s=/Home/SMServer/channelOpenAll&server_id=<?php echo ($vGet["server_id"]); ?>", function(data){
                if(data=='ok'){
                    alert("<?php echo (L("success")); ?>");
                    var str = '/zettaiOpe/admin.php?s=/Home/SMServer/channel&amp;server_id=101&amp;search_channel_id=0&amp;id=1';
                    str = str.replace(/amp;/g,'');
                    window.location.href = str;
                }else{
                    alert("<?php echo (L("fail")); ?>");
                }
            });
        });

        $('#closeAll').click(function(){
            $.get("/zettaiOpe/admin.php?s=/Home/SMServer/channelCloseAll&server_id=<?php echo ($vGet["server_id"]); ?>", function(data){
                if(data=='ok'){
                    alert("<?php echo (L("success")); ?>");
                    var str = '/zettaiOpe/admin.php?s=/Home/SMServer/channel&amp;server_id=101&amp;search_channel_id=0&amp;id=1';
                    str = str.replace(/amp;/g,'');
                    window.location.href = str;
                }else{
                    alert("<?php echo (L("fail")); ?>");
                }
            });
        });

        $('#type').val('select');
        $('#type').attr('disabled', false);

        $('#type').change(function(){
            var type = $('#type').val();
            $.get("/zettaiOpe/admin.php?s=/Home/SMServer/channelChangeAll&server_id=<?php echo ($vGet["server_id"]); ?>&type=" + type, function(data){
                if(data=='ok'){
                    alert("<?php echo (L("success")); ?>");
                    var str = '/zettaiOpe/admin.php?s=/Home/SMServer/channel&amp;server_id=101&amp;search_channel_id=0&amp;id=1';
                    str = str.replace(/amp;/g,'');
                    window.location.href = str;
                }else{
                    alert("<?php echo (L("fail")); ?>");
                }
            });
        });

    });
</script>

<!-- BEGIN PAGE -->
<div class="page-content" style="min-height:648px !important">

    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">

        <!-- BEGIN PAGE CONTENT-->
        <div class="row-fluid">

            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">

                    <div class="caption"><i class="fa fa-hdd-o"></i><?php echo (L("server_list")); ?></div>

                    <select class="form-control input-medium" id="type" style="float:left;">
                        <option value="select"><?php echo (L("change_all_channel_type")); ?></option>
                        <?php if(is_array($vType)): foreach($vType as $k=>$val): ?><option value="<?php echo ($k); ?>"><?php echo ($val); ?></option><?php endforeach; endif; ?>
                    </select>

                    <div class="tools">
                        <a href="/zettaiOpe/admin.php?s=/Home/SMServer/channelAdd&server_id=<?php echo ($vGet["server_id"]); ?>&search_channel_id=<?php echo ($vGet["search_channel_id"]); ?>&id=<?php echo ($vGet["id"]); ?>" style="color:whitesmoke;"><?php echo (L("sm_server_open_channel")); ?></a>
                        &nbsp;&nbsp;|
                        <a id="openAll" href="javascript:;" style="color:whitesmoke;"><?php echo (L("open_all")); ?></a>
                        &nbsp;&nbsp;|
                        <a id="closeAll" href="javascript:;" style="color:whitesmoke;"><?php echo (L("close_all")); ?></a>
                        &nbsp;&nbsp;|
                        <a href="/zettaiOpe/admin.php?s=/Home/SMServer/index&search_channel_id=<?php echo ($vGet["search_channel_id"]); ?>" style="color:whitesmoke;"><?php echo (L("return")); ?></a>
                    </div>
                </div>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
                        <thead>
                            <tr>
                                <th><?php echo (L("server_name")); ?></th>
                                <th><?php echo (L("sm_server_type")); ?></th>
                                <th><?php echo (L("server_channel")); ?></th>
                                <th><?php echo (L("server_channel_status")); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><script type="text/javascript">
                                $(document).ready(function(){
                                    $("#type_<?php echo ($value["id"]); ?>").val('<?php echo ($value["type"]); ?>');
                                    $("#type_<?php echo ($value["id"]); ?>").attr('disabled',true);
                                    $("#save_<?php echo ($value["id"]); ?>").hide();
                                    $("#cancel_<?php echo ($value["id"]); ?>").hide();
                                    $("#change_<?php echo ($value["id"]); ?>").click(function(){
                                        $("#type_<?php echo ($value["id"]); ?>").attr('disabled',false);
                                        $("#change_<?php echo ($value["id"]); ?>").hide();
                                        $("#save_<?php echo ($value["id"]); ?>").show();
                                        $("#cancel_<?php echo ($value["id"]); ?>").show();
                                    });
                                    $("#cancel_<?php echo ($value["id"]); ?>").click(function(){
                                        $("#type_<?php echo ($value["id"]); ?>").attr('disabled',true);
                                        $("#change_<?php echo ($value["id"]); ?>").show();
                                        $("#save_<?php echo ($value["id"]); ?>").hide();
                                        $("#cancel_<?php echo ($value["id"]); ?>").hide();
                                    });
                                    $("#save_<?php echo ($value["id"]); ?>").click(function(){
                                        var url;
                                        if($("#type_<?php echo ($value["id"]); ?>").val() == '<?php echo ($value["type"]); ?>'){
                                            url = "javascript:alert('<?php echo (L("no_update")); ?>');";
                                        }else{
                                            url = $("#save_<?php echo ($value["id"]); ?>").attr('href');
                                            url += $("#type_<?php echo ($value["id"]); ?>").val();
                                        }
                                        $("#save_<?php echo ($value["id"]); ?>").attr('href',url);
                                    });
                                });
                            </script>
                            <tr>
                                <td><?php echo ($value["name"]); ?></td>
                                <td>
                                    <select class="form-control input-small" id="type_<?php echo ($value["id"]); ?>">
                                        <?php if(is_array($vType)): foreach($vType as $k=>$val): ?><option value="<?php echo ($k); ?>"><?php echo ($val); ?></option><?php endforeach; endif; ?>
                                    </select>
                                    <a id="change_<?php echo ($value["id"]); ?>" href="javascript:;">[<?php echo (L("change")); ?>]</a>
                                    <a id="save_<?php echo ($value["id"]); ?>" href="/zettaiOpe/admin.php?s=/Home/SMServer/type&server_id=<?php echo ($vGet["server_id"]); ?>&search_channel_id=<?php echo ($vGet["search_channel_id"]); ?>&channel_id=<?php echo ($value["id"]); ?>&id=<?php echo ($vGet["id"]); ?>&type=">[<?php echo (L("save")); ?>]</a>
                                    <a id="cancel_<?php echo ($value["id"]); ?>" href="javascript:;">[<?php echo (L("cancel")); ?>]</a>
                                </td>
                                <td><?php echo ($value["channel_id"]); ?>-<?php echo ($value["channel"]); ?></td>
                                <td>
                                    <?php echo ($value["status"]); ?>
                                    <a href="/zettaiOpe/admin.php?s=/Home/SMServer/channelStatus&server_id=<?php echo ($vGet["server_id"]); ?>&search_channel_id=<?php echo ($vGet["search_channel_id"]); ?>&channel_id=<?php echo ($value["id"]); ?>&id=<?php echo ($vGet["id"]); ?>">[<?php echo (L("change")); ?>]</a>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>

                </div>
                <!-- END EXAMPLE TABLE PORTLET-->

            </div>
            <!-- END PAGE CONTENT-->

        </div>
        <!-- END PAGE CONTENT-->

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