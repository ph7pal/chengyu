<?php

/**
 * @filename log.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-7-9  18:05:15 
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
if($_GET['type']=='app'){
    $delAction='delApp';
}elseif($_GET['type']=='apptimes'){
    $delAction='delApptimes';
}elseif($_GET['type']=='log'){
    $delAction='delLog';
}elseif($_GET['type']=='crashLog'){
    $delAction='delCrashLog';
}elseif($_GET['type']=='appLog'){
    $delAction='delAppLog';
}
?>

<?php echo nl2br($content);?>
