<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <script src="/zettaiOpe/Public/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script type="application/javascript">
        if('<?php echo ($alert); ?>' != ''){
            alert('<?php echo ($alert); ?>');
        }else{
            alert('<?php echo (L("success")); ?>');
        }
        window.location.href="<?php echo ($jump); ?>";
    </script>
    <title>jump</title>
</head>
<body>

</body>
</html>