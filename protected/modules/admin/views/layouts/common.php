<!DOCTYPE html>
<html>  
    <head>    
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <meta name="renderer" content="webkit">
        <?php
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->baseUrl . '/static/css/bootstrap-v3.3.4.css');
        $cs->registerCssFile(Yii::app()->baseUrl . '/static/css/font-awesome-v4.4.0.css');
        $cs->registerCssFile(Yii::app()->baseUrl . '/static/css/zmf.css');        
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile(Yii::app()->baseUrl . "/static/js/bootstrap-v3.3.4.js", CClientScript::POS_HEAD);
        ?>  
        <link rel="shortcut icon" href="<?php echo zmf::config('baseurl');?>favicon.ico" type="image/x-icon" />
        <title>管理中心</title>     
    </head>
    <body>
        <?php echo $content; ?>
        <?php assets::jsConfig('web');?> 
    </body>
</html>