<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('chengyu/index'),
    CHtml::link('词语故事',array('chengyu/story')),
);
?>
<?php if(!empty($new)){?>
<h3>最新收录</h3>

<div class="ui-form ui-border-t">
    <?php foreach($new as $_new){?>
    <div class="ui-form-item ui-form-item-link ui-border-b">
        <?php echo CHtml::link($_new['title'],array('chengyu/view','id'=>$_new['id']),array('class'=>'col-list-item','title'=>$_new['title']));?> 
    </div>    
    <?php }?>
    <div class="ui-form-item ui-form-item-link ui-border-b">
        <?php echo CHtml::link('查看更多>>',array('chengyu/index'),array('class'=>'col-list-item color-grey'));?>
    </div>
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
<ul class="ui-list ui-list-pure ui-border-tb">
    
    <li class="ui-border-t">
        <p><span>2.faycheng </span><span class="date"> 2月12日</span></p>
        <h4>标题标题标题标题标题标题题标题标题标题标题标题标题标题标题标题</h4>
    </li>
    <li class="ui-border-t">
        <p><span>3.faycheng </span><span class="date"> 2月12日</span></p>
        <h4>标题标题标题标题标题标题题标题标题标题标题标题标题标题标题标题</h4>
    </li>


<?php foreach($contens as $content){?>
    
<div class="media zmf-border-bottom">
  <div class="media-body">
      <p><b><?php $_ciInfo=$content->chengyuInfo; echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id']),array('target'=>'_blank'));?></b></p>
      <?php echo zmf::subStr($content['content'],140,0,'...'.CHtml::link('查看详情',array('chengyu/view','id'=>$_ciInfo['id']),array('target'=>'_blank')));?>
  </div>
</div>
<?php }?>
</ul>
<?php }?>