<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('chengyu/index'),
    CHtml::link('词语故事',array('chengyu/story')),
);
?>
<?php if(!empty($new)){?>
<h3>最新收录</h3>
<div class="zmf-border-bottom clearfix">
    <?php foreach($new as $_new){?>
    <?php echo CHtml::link(zmf::subStr($_new['title'],15),array('chengyu/view','id'=>$_new['id']),array('class'=>'col-list-item','title'=>$_new['title']));?> 
    <?php }?>
    <?php echo CHtml::link('查看更多>>',array('chengyu/index'),array('class'=>'col-list-item color-grey'));?>
</div>
<?php }?>
<div class="clearfix"></div>
<?php if(!empty($xinjie)){?>
<h3>词语新解</h3>
<?php foreach($xinjie as $_xinjie){?>
<div class="media zmf-border-bottom">
  <div class="media-body">
      <p><b><?php echo CHtml::link($_xinjie['title'],array('chengyu/view','id'=>$_xinjie['id']),array('target'=>'_blank'));?></b></p>
      <?php echo zmf::subStr($_xinjie['content'],140,0,'...'.CHtml::link('查看详情',array('chengyu/view','id'=>$_xinjie['id']),array('target'=>'_blank')));?>
  </div>
</div>
<?php }?>
<?php }?>
<div class="clearfix"></div>
<?php if(!empty($contens)){?>
<h3>最新故事</h3>
<?php foreach($contens as $content){?>
<div class="media zmf-border-bottom">
  <div class="media-body">
      <p><b><?php $_ciInfo=$content->chengyuInfo; echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id']),array('target'=>'_blank'));?></b></p>
      <?php echo zmf::subStr($content['content'],140,0,'...'.CHtml::link('查看详情',array('chengyu/view','id'=>$_ciInfo['id']),array('target'=>'_blank')));?>
  </div>
</div>
<?php }?>
<?php }?>