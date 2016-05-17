<?php

/**
 * @filename navbar.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-17  10:31:53 
 */
$this->menu=array(
    '日志列表'=>array(
        'link'=>array('config/navbar'),
        'active'=>true
    ),
    '基本设置'=>array(
        'link'=>array('config/index'),
        'active'=>false
    ),
);
?>
<div class="list-group">
    <?php echo CHtml::link('系统日志',array('checkLog','type'=>'system'),array('class'=>'list-group-item'));?>
    <?php echo CHtml::link('APP日志',array('checkLog','type'=>'appLogs'),array('class'=>'list-group-item'));?>
</div>