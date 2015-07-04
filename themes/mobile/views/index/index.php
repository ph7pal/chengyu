<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('chengyu/index'),
    CHtml::link('词语故事',array('chengyu/story')),
);
?>
<?php if(!empty($xinjie)){?>
<div class="mod-header">
    <h3>词语新解</h3>
</div>
<ul class="ui-list ui-list-pure ui-border-tb">
<?php foreach($xinjie as $_xinjie){?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/view',array('id'=>$_xinjie['id']));?>">
        <p><?php echo CHtml::link($_xinjie['title'],array('chengyu/view','id'=>$_xinjie['id']));?></p>
        <p class="ui-nowrap-multi"><?php echo zmf::subStr($_xinjie['content'],140);?></p>
    </li>
<?php }?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/story');?>">
        <?php echo CHtml::link('查看更多>>',array('chengyu/story'),array('class'=>'col-list-item color-grey'));?>
    </li>
</ul>
<?php }?>
<div class="clearfix"></div>
<?php if(!empty($contens)){?>
<div class="mod-header">
    <h3>最新故事</h3>
</div>
<ul class="ui-list ui-list-pure ui-border-tb">
<?php foreach($contens as $content){?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/view',array('id'=>$content['cid']));?>">
        <p><?php $_ciInfo=$content->chengyuInfo; echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id']),array('target'=>'_blank'));?></p>
        <p class="ui-nowrap-multi"><?php echo zmf::subStr($content['content'],140);?></p>
    </li>
<?php }?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/story');?>">
        <?php echo CHtml::link('查看更多>>',array('chengyu/story'),array('class'=>'col-list-item color-grey'));?>
    </li>
</ul>
<?php }?>
<div class="clearfix"></div>
<?php if(!empty($new)){?>
<div class="mod-header">
    <h3>最新收录</h3>
</div>
<ul class="ui-list ui-list-pure ui-border-tb">
    <?php foreach($new as $_new){?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/view',array('id'=>$_new['id']));?>">
        <?php echo CHtml::link($_new['title'],array('chengyu/view','id'=>$_new['id']),array('class'=>'col-list-item','title'=>$_new['title']));?> 
    </li>    
    <?php }?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/index');?>">
        <?php echo CHtml::link('查看更多>>',array('chengyu/index'),array('class'=>'col-list-item color-grey'));?>
    </li>
</ul>
<?php }