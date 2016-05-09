<?php

/**
 * @filename _view.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  11:52:24 
 */
$_uname='';
if($data['uid']){
    $_uname=$data['loginUsername'];
}else{
    $_uname=$data['username'];
}
if(!$_uname){
    $_uname='匿名网友';
}
?>

<div class="media" id="comment-<?php echo $data['id'];?>">
    <div class="media-body">
        <p class="media-heading"><?php echo CHtml::link($data['title'],array('comments/index','logid'=>$data['logid']));?></p> 
        <p><b><?php echo $_uname;?></b></p>
        <p><?php echo nl2br(CHtml::encode($data['content']));?></p>
        <p>
            <?php echo zmf::formatTime($data['cTime']);?>
            <span class="pull-right comment-actions">
                <?php 
                if($data['status']==Posts::STATUS_STAYCHECK){                    
                    echo CHtml::link('通过','javascript:;',array('onclick'=>"setStatus('".$data['id']."','comments','passed')"));
                }elseif($data['status']==Posts::STATUS_PASSED){
                    echo CHtml::link('回复',array('/posts/view','id'=>$data['logid'],'#'=>'comment-'.$data['id']),array('target'=>'_blank'));
                }
                echo CHtml::link('删除','javascript:;',array('action'=>'del-content','action-type'=>'comment','action-data'=>  $data['id'],'action-confirm'=>1,'action-target'=>'comment-'.$data['id']));
                ?>
            </span>
        </p>
    </div>
</div>
