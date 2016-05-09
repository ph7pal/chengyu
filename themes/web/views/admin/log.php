<?php

/**
 * @filename log.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-7-9  18:05:15 
 */
$this->breadcrumbs = array(
    '首页'=>array('index/index'),
    '管理中心'=>array('admin/index'),
    '小工具'
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
<?php zmf::test($content);?>