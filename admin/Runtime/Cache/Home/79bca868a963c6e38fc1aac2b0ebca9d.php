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
<script type="text/javascript" src="/zettaiOpe/Public/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
    function addContent(title, content){
        var count = $("#sub").val();
        ++count;
        var html = '';
        html += '<div class="form-group" id="div_subtitle' + count + '">';
        html += '<label class="col-md-3 control-label"><?php echo (L("notice_subtitle")); ?>' + count + '</label>';
        html += '<div class="col-md-9">';
        html += '<input type="text" class="form-control" id="subtitle' + count + '" name="subtitle' + count + '" >';
        html += '<span class="help-block"><?php echo (L("notice_subtitle_input")); ?></span>';
        html += '</div>';
        html += '</div>';

        html += '<div class="form-group" id="div_content' + count + '">';
        html += '<label class="col-md-3 control-label"><?php echo (L("notice_subtitle_content")); ?>' + count + '</label>';
        html += '<div class="col-md-9">';
        html += '<textarea class="form-control" rows="3" id="content' + count + '" name="content' + count + '"></textarea>';
        html += '<span class="help-block"><?php echo (L("notice_subtitle_content_input")); ?></span>';
        html += '</div>';
        html += '</div>';
        $("#div_button").before(html);
        $("#subtitle" + count).val(title);
        $("#content" + count).val(content);
        $("#sub").val(count);
    }

    function delContent(){
        var count = $("#sub").val();
        if(count == 1){
            alert('<?php echo (L("one_notice_subtitle_least")); ?>');
        }else{
            $("#div_subtitle" + count).remove();
            $("#div_content" + count).remove();
            --count;
            $("#sub").val(count);
        }
    }

    $(document).ready(function(){

        if('<?php echo ($vData["server"]); ?>' == '0'){
            $("#server_type_all").attr('checked',true);
            $("#server_radio").hide('fast');
        }else{
            $("#server_type_check").attr('checked',true);
            $("#server_radio").show();

            var server = '<?php echo ($vData["server"]); ?>';
            arr = server.split("#");
            for(var i in arr){
                $("#server"+arr[i]).attr('checked',true);
            }

        }

        $("#server_type_all").click(function(){
            $("#server_radio").hide('fast');
        });

        $("#server_type_check").click(function(){
            $("#server_radio").show();
        });

        if('<?php echo ($vData["channel"]); ?>' == '0'){
            $("#channel_type_all").attr('checked',true);
            $("#channel_radio").hide('fast');
        }else{
            $("#channel_type_check").attr('checked',true);
            $("#channel_radio").show();

            var channel = '<?php echo ($vData["channel"]); ?>';
            arr = channel.split("#");
            for(var i in arr){
                if(arr[i] > 0){
                    $("#channel"+arr[i]).attr('checked',true);
                }
            }
        }

        $("#channel_type_all").click(function(){
            $("#channel_radio").hide('fast');
        });

        $("#channel_type_check").click(function(){
            $("#channel_radio").show();
        });

        //初始值
        $("#index").val('<?php echo ($vData["index"]); ?>');
        $("#tab").val('<?php echo ($vData["tab"]); ?>');
        $("#title").val('<?php echo ($vData["title"]); ?>');
        $("#hot_<?php echo ($vData["hot"]); ?>").attr('checked', true);
        $("#priority").val('<?php echo ($vData["priority"]); ?>');
        if('<?php echo ($vData["endtime"]); ?>' != '0'){
            $("#time_type_1").attr('checked', true);
            $("#time_type_0").attr('checked', false);
            $("#div_starttime0").hide();

            $("#starttime0").val('<?php echo ($vTime["starttime"]); ?>');
            $("#starttime1").val('<?php echo ($vData["starttime"]); ?>');
            $("#endtime1").val('<?php echo ($vData["endtime"]); ?>');
        }else{
            $("#time_type_1").attr('checked', false);
            $("#time_type_0").attr('checked', true);
            $("#div_starttime0").show();
            $("#div_starttime1").hide();
            $("#div_endtime1").hide();

            $("#starttime0").val('<?php echo ($vData["starttime"]); ?>');
            $("#starttime1").val('<?php echo ($vTime["starttime"]); ?>');
            $("#endtime1").val('<?php echo ($vTime["endtime"]); ?>');
        }

        $("#tab").blur(function(){
            if($("#tab").val() == ''){
                $("#tab").val('<?php echo ($vData["tab"]); ?>');
            }
        });

        $("#title").blur(function(){
            if($("#title").val() == ''){
                $("#title").val('<?php echo ($vData["title"]); ?>');
            }
        });

        $("#priority").blur(function(){
            if(!($("#priority").val() > 0)){
                $("#priority").val('<?php echo ($vData["priority"]); ?>');
            }
        });

        //公告内容
        $("#sub").val(0);
        <?php if(is_array($vData["content"])): $i = 0; $__LIST__ = $vData["content"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?>addContent('<?php echo ($value["sub_title"]); ?>','<?php echo ($value["sub_content"]); ?>');<?php endforeach; endif; else: echo "" ;endif; ?>
        $("#add_content").click(function(){
            addContent('','');
        });
        $("#del_content").click(function(){
            delContent('','');
        });

        //时间类型

        $("#time_type_0").click(function () {
            $("#div_starttime0").show();
            $("#div_starttime1").hide();
            $("#div_endtime1").hide();
        });
        $("#time_type_1").click(function () {
            $("#div_starttime0").hide();
            $("#div_starttime1").show();
            $("#div_endtime1").show();
        });

        //时间
        $("#starttime0").focus(function(){
            WdatePicker({
                dateFmt:'yyyy-MM-dd HH:mm:ss',
                minDate:'<?php echo ($vData["starttime"]); ?>'
            });
        });
        $("#starttime0_button").click(function(){
            WdatePicker({
                el:'starttime0',
                dateFmt:'yyyy-MM-dd HH:mm:ss',
                minDate:'<?php echo ($vData["starttime"]); ?>'
            });
        });

        $("#starttime1").focus(function(){
            WdatePicker({
                dateFmt:'yyyy-MM-dd HH:mm:ss',
                minDate:'<?php echo ($vData["starttime"]); ?>',
                maxDate:'#F{$dp.$D(\'endtime1\')}'
            });
        });
        $("#starttime1_button").click(function(){
            WdatePicker({
                el:'starttime1',
                dateFmt:'yyyy-MM-dd HH:mm:ss',
                minDate:'<?php echo ($vData["starttime"]); ?>',
                maxDate:'#F{$dp.$D(\'endtime1\')}'
            });
        });

        $("#endtime1").focus(function(){
            WdatePicker({
                dateFmt:'yyyy-MM-dd HH:mm:ss',
                minDate:'#F{$dp.$D(\'starttime1\')}'
            });
        });
        $("#endtime1_button").click(function(){
            WdatePicker({
                el:'endtime1',
                dateFmt:'yyyy-MM-dd HH:mm:ss',
                minDate:'#F{$dp.$D(\'starttime1\')}'
            });
        });

        //提交
        $("#mainform").submit(function(){

            if($("#tab").val() == ''){
                alert('<?php echo (L("notice_tab_input")); ?>');
                return false;
            }

            if($("#title").val() == ''){
                alert('<?php echo (L("notice_title_input")); ?>');
                return false;
            }

            var count = $("#sub").val();
            for(var i=1;i<=count;++i){
                if($("#content" + i).val() == ''){
                    alert('<?php echo (L("notice_subtitle_content_input")); ?>');
                    return false;
                }
            }

            return true;

        });
    });
</script>

<!-- BEGIN PAGE -->
<div class="page-content" style="min-height:648px !important">

    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">

        <!-- BEGIN PAGE CONTENT-->
        <div class="row-fluid">

            <!-- BEGIN SAMPLE FORM PORTLET-->

            <div class="portlet box blue">

                <div class="portlet-title">

                    <div class="caption"><i class="fa fa-edit"></i><?php echo (L("gm_notice_edit")); ?></div>

                </div>

                <div class="portlet-body form">

                    <!-- BEGIN FORM-->

                    <form action="" method="POST" id="mainform" class="form-horizontal">
                        <input id="index" name="index" type="hidden" />
                        <div class="form-body">

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("exchange_type_server")); ?></label>
                                <div class="col-md-9 radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" id="server_type_all" name="server_type" value="all" /><?php echo (L("all_server")); ?>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" id="server_type_check" name="server_type" value="check" /><?php echo (L("select_server")); ?>
                                    </label>
                                    <span class="help-block"><?php echo (L("exchange_type_server_select")); ?></span>
                                </div>
                            </div>

                            <div class="form-group" id="server_radio">
                                <label class="col-md-3 control-label"><?php echo (L("exchange_type_server")); ?></label>
                                <div class="col-md-9">
                                    <?php if(is_array($vServer)): foreach($vServer as $key=>$value): ?><label class="checkbox">
                                            <input type="checkbox" id="server<?php echo ($key); ?>" name="server[]" value="<?php echo ($key); ?>" />
                                            <?php $i=1; ?>S<?php echo ($key); ?>-<?php if(is_array($value["channel"])): foreach($value["channel"] as $k=>$val): if(($i) == "1"): echo ($val["name"]); $i=0; else: ?>/<?php echo ($val["name"]); endif; endforeach; endif; ?>
                                        </label><?php endforeach; endif; ?>
                                    <span class="help-block"><?php echo (L("exchange_type_server_select")); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("exchange_type_channel")); ?></label>
                                <div class="col-md-9 radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" id="channel_type_all" name="channel_type" value="all" /><?php echo (L("all_channel")); ?>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" id="channel_type_check" name="channel_type" value="check" /><?php echo (L("select_channel")); ?>
                                    </label>
                                    <span class="help-block"><?php echo (L("exchange_type_channel_select")); ?></span>
                                </div>
                            </div>

                            <div class="form-group" id="channel_radio">
                                <label class="col-md-3 control-label"><?php echo (L("exchange_type_channel")); ?></label>
                                <div class="col-md-9">
                                    <?php if(is_array($vChannel)): foreach($vChannel as $key=>$value): ?><label class="checkbox">
                                            <input type="checkbox" id="channel<?php echo ($key); ?>" name="channel[]" value="<?php echo ($key); ?>" />
                                            <?php echo ($key); ?>-<?php echo ($value); ?>
                                        </label><?php endforeach; endif; ?>
                                    <span class="help-block"><?php echo (L("exchange_type_channel_select")); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("notice_tab")); ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="tab" name="tab" >
                                    <span class="help-block"><?php echo (L("notice_tab_input")); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("notice_title")); ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="title" name="title" >
                                    <span class="help-block"><?php echo (L("notice_title_input")); ?></span>
                                </div>
                            </div>

                            <div class="form-group radio-list">
                                <label class="col-md-3 control-label"><?php echo (L("notice_hot")); ?></label>
                                <div class="col-md-9">
                                    <label class="radio-inline">
                                        <input type="radio" id="hot_1" name="hot" value="1" /><?php echo (L("yes")); ?>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" id="hot_0" name="hot" value="0" /><?php echo (L("no")); ?>
                                    </label>
                                    <span class="help-block"><?php echo (L("notice_hot_select")); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("priority")); ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="priority" name="priority" >
                                    <span class="help-block"><?php echo (L("notice_priority_input")); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("time_limit")); ?></label>
                                <div class="col-md-9 radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" id="time_type_1" name="time_type" value="1" /><?php echo (L("limit")); ?>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" id="time_type_0" name="time_type" value="0" /><?php echo (L("no_limit")); ?>
                                    </label>
                                    <span class="help-block"><?php echo (L("time_limit_select")); ?></span>
                                </div>
                            </div>

                            <div class="form-group" id="div_starttime0">
                                <label class="col-md-3 control-label"><?php echo (L("starttime")); ?></label>
                                <div class="col-md-9">
                                    <input style="float:left;" id="starttime0" name="starttime0" class="form-control input-medium" type="text" value="" />
                                    <span style="float:left;">
                                    <button id="starttime0_button" class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                    <br>
                                    <br>
                                    <span class="help-block"><?php echo (L("notice_starttime_select")); ?></span>
                                </div>
                            </div>

                            <div class="form-group" id="div_starttime1">
                                <label class="col-md-3 control-label"><?php echo (L("starttime")); ?></label>
                                <div class="col-md-9">
                                    <input style="float:left;" id="starttime1" name="starttime1" class="form-control input-medium" type="text" value="" />
                                    <span style="float:left;">
                                    <button id="starttime1_button" class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                    <br>
                                    <br>
                                    <span class="help-block"><?php echo (L("notice_starttime_select")); ?></span>
                                </div>
                            </div>

                            <div class="form-group" id="div_endtime1">
                                <label class="col-md-3 control-label"><?php echo (L("endtime")); ?></label>
                                <div class="col-md-9">
                                    <input style="float:left;" id="endtime1" name="endtime1" class="form-control input-medium" type="text" value="" />
                                    <span style="float:left;">
                                    <button id="endtime1_button" class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                    <br>
                                    <br>
                                    <span class="help-block"><?php echo (L("notice_endtime_select")); ?></span>
                                </div>
                            </div>

                            <div class="form-group" id="div_button">
                                <label class="col-md-3 control-label"><?php echo (L("notice_content_operation")); ?></label>
                                <div class="col-md-9">
                                    <button type="button" class="btn blue" id="add_content"><i class="fa fa-plus"></i>&nbsp;<?php echo (L("add")); ?></button>
                                    <button type="button" class="btn blue" id="del_content"><i class="fa fa-plus"></i>&nbsp;<?php echo (L("delete")); ?></button>
                                </div>
                            </div>

                            <input id="sub" name="sub" type="hidden" />

                        </div>

                        <div class="form-actions">

                            <button type="submit" class="btn blue"><i class="fa fa-save"></i>&nbsp;<?php echo (L("save")); ?></button>

                            <button type="button" class="btn" onclick="javascript:window.location.href='/zettaiOpe/admin.php?s=/Home/GMNotice&p=<?php echo ($pg); ?>';"><i class="fa fa-undo"></i>&nbsp;<?php echo (L("return")); ?></button>

                        </div>

                    </form>

                    <!-- END FORM-->

                </div>

            </div>

            <!-- END SAMPLE FORM PORTLET-->

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