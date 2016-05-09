<?php

/**
 * @filename _nav.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  14:38:00 
 */
$this->menu=array(
    '待审核'=>array(
        'link'=>array('comments/index'),
        'active'=>in_array($_GET['type'],array('staycheck')) || !$_GET['type']
    ),
    '已通过'=>array(
        'link'=>array('comments/index','type'=>'passed'),
        'active'=>in_array($_GET['type'],array('passed'))
    ),
);