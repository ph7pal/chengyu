<?php
$this->breadcrumbs=array(
    CHtml::link('首页',zmf::config('baseurl')),
    '词语大全',
    CHtml::link('词语故事',array('chengyu/story')),	
);
?>
<div class="wrap-container">
    <div class="wrap-content">
        <div class="char-bar">
            <p>
                筛选：
            <?php $charStr='ABCDEFGHIJKLMNOPQRSTUVWXYZ';$charArr=str_split($charStr);foreach($charArr as $char){?>
            <?php echo CHtml::link($char,array('chengyu/index','char'=>$char));?>
            <?php }?>
            </p>
        </div>
    </div>
        <div class="ui-form ui-border-t">
        <?php foreach($posts as $post){?>
            <div class="ui-form-item ui-form-item-link ui-border-b">
                <?php echo CHtml::link($post['title'],array('chengyu/view','id'=>$post['id']),array('class'=>'col-list-item','title'=>$post['title']));?> 
            </div>
        <?php }?>
        </div>        
        <?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
</div>