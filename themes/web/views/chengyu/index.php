<?php
$this->breadcrumbs=array(
    CHtml::link('首页',zmf::config('baseurl')),
    '词语大全',
    CHtml::link('词语故事',array('chengyu/story')),	
);
$this->menu=array(
	array('label'=>'新增', 'url'=>array('create')),
);
?>
<div class="char-bar">
    <p>
        筛选：
    <?php $charStr='ABCDEFGHIJKLMNOPQRSTUVWXYZ';$charArr=str_split($charStr);foreach($charArr as $char){?>
    <?php echo CHtml::link($char,array('chengyu/index','char'=>$char));?>
    <?php }?>
    </p>
</div>
<table class="table table-hover">
<?php foreach($posts as $post){?>
<?php $this->renderPartial('/chengyu/_view',array('data'=>$post));?>
<?php } ?>
</table>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>