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
        <ul class="ui-list ui-list-pure ui-border-tb">
        <?php foreach($posts as $post){?>
            <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/view',array('id'=>$post['id']));?>">
                <?php echo CHtml::link($post['title'],array('chengyu/view','id'=>$post['id']),array('class'=>'col-list-item','title'=>$post['title']));?> 
            </li>
        <?php }?>
        </ul>        
        <?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
</div>