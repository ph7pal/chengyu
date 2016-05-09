<?php

/**
 * @filename _nav.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  15:18:10 
 */
$c = Yii::app()->getController()->id;
$a = Yii::app()->getController()->getAction()->id;
$this->menu=array(
    '系统信息'=>array(
        'link'=>array('index/index'),
        'active'=>in_array($a,array('index'))
    ),
    '基本统计'=>array(
        'link'=>array('index/stat'),
        'active'=>in_array($a,array('stat'))
    ),
);