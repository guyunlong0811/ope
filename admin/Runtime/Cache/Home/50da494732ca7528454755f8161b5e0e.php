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
    var html = '<option value="0"><?php echo (L("please_select")); ?></option>';
    var htmlItem;
    htmlItem = html;
    <?php if(is_array($vItemConfig)): foreach($vItemConfig as $key=>$value): ?>htmlItem += '<option value="<?php echo ($key); ?>"><?php echo ($key); ?>-<?php echo ($value["name"]); ?></option>';<?php endforeach; endif; ?>

    var htmlAttr;
    htmlAttr = html;
    <?php if(is_array($vFieldConfig)): foreach($vFieldConfig as $key=>$value): ?>htmlAttr += '<option value="<?php echo ($key); ?>"><?php echo ($key); ?>-<?php echo (L("$value")); ?></option>';<?php endforeach; endif; ?>

    var htmlPartner;
    htmlPartner = html;
    <?php if(is_array($vPartnerConfig)): foreach($vPartnerConfig as $key=>$value): ?>htmlPartner += '<option value="<?php echo ($key); ?>"><?php echo ($key); ?>-<?php echo ($value); ?></option>';<?php endforeach; endif; ?>

    var htmlEmblem;
    htmlEmblem = html;
    <?php if(is_array($vEmblemConfig)): foreach($vEmblemConfig as $key=>$value): ?>htmlEmblem += '<option value="<?php echo ($key); ?>"><?php echo ($key); ?>-<?php echo ($value["name"]); ?></option>';<?php endforeach; endif; ?>

    $(document).ready(function(){
        $("#index").val('<?php echo ($vData["index"]); ?>');
        $("#name").val('<?php echo ($vData["name"]); ?>');
        $("#name").attr('disabled', true);
        $("#value").val('<?php echo ($vData["value"]); ?>');
        $("#receive_max").val('<?php echo ($vData["receive_max"]); ?>');
        $("#pt_activity_id").val('<?php echo ($vData["pt_activity_id"]); ?>');

        <?php for($i=1;$i<=8;++$i){ ?>
        $("#bonus_<?php echo ($i); ?>_type").val('<?php echo ($vData["bonus_{$i}_type"]); ?>');
        if($('#bonus_<?php echo ($i); ?>_type').val() == 0){
            $('#bonus_<?php echo ($i); ?>_value_1').attr('disabled',true);
            $('#bonus_<?php echo ($i); ?>_value_2').attr('disabled',true);
        }else{
            $('#bonus_<?php echo ($i); ?>_value_1').attr('disabled',false);

            switch ($('#bonus_<?php echo ($i); ?>_type').val()){
                case '1':
                    $('#bonus_<?php echo ($i); ?>_value_1').html(htmlItem);
                    break;
                case '2':
                    $('#bonus_<?php echo ($i); ?>_value_1').html(htmlAttr);
                    break;
                case '3':
                case '4':
                case '5':
                case '6':
                case '7':
                    $('#bonus_<?php echo ($i); ?>_value_1').html(htmlPartner);
                    break;
                case '9':
                    $('#bonus_<?php echo ($i); ?>_value_1').html(htmlEmblem);
                    break;
            }

        }
        $("#bonus_<?php echo ($i); ?>_value_1").val('<?php echo ($vData["bonus_{$i}_value_1"]); ?>');
        $("#bonus_<?php echo ($i); ?>_value_2").val('<?php echo ($vData["bonus_{$i}_value_2"]); ?>');
        <?php } ?>

        $("#value").blur(function(){
            if(!($("#value").val() > 0)){
                $("#value").val('<?php echo ($vData["value"]); ?>');
            }
        });

        $("#receive_max").blur(function(){
            if(!($("#receive_max").val() > 0)){
                $("#receive_max").val('<?php echo ($vData["receive_max"]); ?>');
            }
        });

        $("#pt_activity_id").blur(function(){
            if($("#pt_activity_id").val() == ''){
                $("#pt_activity_id").val('<?php echo ($vData["pt_activity_id"]); ?>');
            }
        });

        $("#mainform").submit(function(){

            var count = 0;
            for(var i=1;i<=8;++i){

                if($('#bonus_'+i+'_type').val() > 0){
                    ++count;
                    if(!($('#bonus_'+i+'_value_1').val() > 0) || !($('#bonus_'+i+'_value_2').val() > 0) || ($('#bonus_'+i+'_value_1').val() == '1' && $('#bonus_'+i+'_value_2').val() > 1000)){
                        alert('<?php echo (L("activity_bonus_error")); ?>');
                        return false;
                    }
                }

            }

            if(count == 0){
                alert('<?php echo (L("activity_bonus_select")); ?>');
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

        <!-- BEGIN PAGE CONTENT-->
        <div class="row-fluid">

            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-edit"></i><?php echo (L("gm_pay_consume_edit")); ?></div>
                </div>

                <div class="portlet-body form">

                    <!-- BEGIN FORM-->
                    <form action="" method="POST" id="mainform" class="form-horizontal">
                        <input id="index" name="index" type="hidden" />
                        <div class="form-body">

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("activity_name")); ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="name" name="name" disabled>
                                    <span class="help-block"><?php echo (L("activity_name_input")); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("activity_condition")); ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="value" name="value" >
                                    <span class="help-block"><?php echo (L("activity_condition_input")); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("activity_max_complete")); ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="receive_max" name="receive_max" >
                                    <span class="help-block"><?php echo (L("activity_max_complete_input")); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("platform_activity_id")); ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="pt_activity_id" name="pt_activity_id" >
                                    <span class="help-block"><?php echo (L("platform_activity_id_input")); ?></span>
                                </div>
                            </div>

                            <?php for($i=1;$i<=8;++$i){ ?>

                            <script type="application/javascript">
                                $(document).ready(function(){

                                    $('#bonus_<?php echo ($i); ?>_type').change(function(){
                                        $('#bonus_<?php echo ($i); ?>_value_1').val(0);
                                        $('#bonus_<?php echo ($i); ?>_value_2').val(0);
                                        if($('#bonus_<?php echo ($i); ?>_type').val() == 0){
                                            $('#bonus_<?php echo ($i); ?>_value_1').attr('disabled',true);
                                            $('#bonus_<?php echo ($i); ?>_value_2').attr('disabled',true);
                                        }else{
                                            $('#bonus_<?php echo ($i); ?>_value_1').attr('disabled',false);

                                            switch ($('#bonus_<?php echo ($i); ?>_type').val()){
                                                case '1':
                                                    $('#bonus_<?php echo ($i); ?>_value_1').html(htmlItem);
                                                    break;
                                                case '2':
                                                    $('#bonus_<?php echo ($i); ?>_value_1').html(htmlAttr);
                                                    break;
                                                case '3':
                                                case '4':
                                                case '5':
                                                case '6':
                                                case '7':
                                                    $('#bonus_<?php echo ($i); ?>_value_1').html(htmlPartner);
                                                    break;
                                                case '9':
                                                    $('#bonus_<?php echo ($i); ?>_value_1').html(htmlEmblem);
                                                    break;
                                            }

                                        }

                                    });

                                    $('#bonus_<?php echo ($i); ?>_value_1').change(function(){
                                        if($('#bonus_<?php echo ($i); ?>_value_1').val() == 0){
                                            $('#bonus_<?php echo ($i); ?>_value_2').attr('disabled',true);
                                        }else{
                                            $('#bonus_<?php echo ($i); ?>_value_2').attr('disabled',false);
                                        }
                                    });

                                    $("#bonus_<?php echo ($i); ?>_value_2").blur(function(){
                                        if(!($("#bonus_<?php echo ($i); ?>_value_2").val() >= 0)){
                                            $("#bonus_<?php echo ($i); ?>_value_2").val(0);
                                        }else{
                                            $("#bonus_<?php echo ($i); ?>_value_2").val(parseInt($("#bonus_<?php echo ($i); ?>_value_2").val()));
                                        }
                                    });


                                });
                            </script>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo (L("activity_bonus")); echo ($i); ?></label>
                                <div class="col-md-9">
                                    <select class="form-control input-small" id="bonus_<?php echo ($i); ?>_type" name="bonus_<?php echo ($i); ?>_type" style="float:left;">
                                        <?php if(is_array($bonus_type)): foreach($bonus_type as $key=>$value): ?><option value="<?php echo ($key); ?>"><?php echo (L("$value")); ?></option><?php endforeach; endif; ?>
                                    </select>
                                    <select class="form-control input-medium" style="float:left;" id="bonus_<?php echo ($i); ?>_value_1" name="bonus_<?php echo ($i); ?>_value_1">
                                        <option value="0"><?php echo (L("please_select")); ?></option>
                                    </select>
                                    <input type="text" class="form-control input-small" id="bonus_<?php echo ($i); ?>_value_2" name="bonus_<?php echo ($i); ?>_value_2" value="0" >
                                    <span class="help-block"><?php echo (L("activity_bonus_select")); echo ($i); ?></span>
                                </div>
                            </div>

                            <?php } ?>

                        </div>

                        <div class="form-actions">

                            <button type="submit" class="btn blue"><i class="fa fa-save"></i>&nbsp;<?php echo (L("save")); ?></button>

                            <button type="button" class="btn" onclick="javascript:window.location.href='/zettaiOpe/admin.php?s=/Home/GMPayConsume/groupIndex&group=<?php echo ($vData["group"]); ?>';"><i class="fa fa-undo"></i>&nbsp;<?php echo (L("return")); ?></button>

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