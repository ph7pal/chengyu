<?php

/**
 * @filename _nav.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  14:35:16 
 */
$c = Yii::app()->getController()->id;
$a = Yii::app()->getController()->getAction()->id;
$this->menu=array(
    '列表'=>array(
        'link'=>array('index'),
        'active'=>in_array($a,array('index'))
    ),
    '新增'=>array(
        'link'=>array('create'),
        'active'=>false,
    ),
);