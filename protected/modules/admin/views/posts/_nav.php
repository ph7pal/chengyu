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
    '文章列表'=>array(
        'link'=>array('posts/index'),
        'active'=>in_array($a,array('index'))
    ),
    '新增'=>array(
        'link'=>array('posts/create'),
        'active'=>false,
        'target'=>true,
    ),
);