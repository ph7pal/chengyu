<?php

/**
 * @filename crash.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-9-6  16:37:42 
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
$urlType='';
switch ($type) {
    case 'crash':
        $urlType='crashLog';
        break;
    case 'appLogs':
        $urlType='appLog';
        break;
    default:
        break;
}
if(!empty($dirs)){?>
<?php if($totalSize>0){?>
<div class="alert alert-danger">
    <p>日志总大小：<?php echo zmf::formatBytes($totalSize);?></p>
</div>
<?php }?>
<table class="table table-hover">
    <?php if($type=='appLogs'){?>
    <?php foreach($dirs as $dir){?>
    <tr>
        <td>
            <?php echo CHtml::link($dir['filename'],array('tools/checklog','type'=>$urlType,'file'=>$dir['filename']));?>
            <span class="pull-right"><?php echo zmf::formatBytes($dir['size']);?></span>
        </td>
    </tr>
    <?php }?>
    <?php }else{?>
    <?php foreach($dirs as $dir){?>
    <tr><td><?php echo CHtml::link($dir,array('tools/checklog','type'=>$urlType,'file'=>$dir));?></td></tr>
    <?php }?>
    <?php }?>
</table>
<?php }else{ ?>
<p class="help-block">暂无记录</p>
<?php } ?>