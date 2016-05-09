<?php

/**
 * @filename _nav.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  15:38:01 
 */
$c = Yii::app()->getController()->id;
$a = Yii::app()->getController()->getAction()->id;
$this->menu=array(
    '用户列表'=>array(
        'link'=>array('users/index'),
        'active'=>in_array($a,array('index'))
    ),
    '新增用户'=>array(
        'link'=>array('users/create'),
        'active'=>in_array($a,array('create'))
    ),
    '管理员'=>array(
        'link'=>array('users/admins'),
        'active'=>in_array($a,array('admins'))
    ),
);