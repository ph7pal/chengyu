<?php

/**
 * @filename story.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-7-1  19:38:18 
 */
?>
<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('index'),
    '词语故事'
);
$this->menu = array(
    array('label' => '列表', 'url' => array('index')),
    array('label' => '新增', 'url' => array('create')),
    array('label' => '更新', 'url' => array('update', 'id' => $model->id)),
    array('label' => '添加同义词', 'url' => array('ci', 'id' => $model->id, 'type' => 'tongyi')),
    array('label' => '添加反义词', 'url' => array('ci', 'id' => $model->id, 'type' => 'fanyi')),
    array('label' => '添加解释', 'url' => array('content', 'id' => $model->id, 'type' => 'jieshi')),
    array('label' => '添加出处', 'url' => array('content', 'id' => $model->id, 'type' => 'chuchu')),
    array('label' => '添加例句', 'url' => array('content', 'id' => $model->id, 'type' => 'liju')),
    array('label' => '添加故事', 'url' => array('content', 'id' => $model->id, 'type' => 'gushi')),
    array('label' => '删除', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
);
?>
<div class="">
    
</div>
<?php if(!empty($posts)){?>
<?php foreach($posts as $_xinjie){?>
<div class="media zmf-border-bottom">
  <div class="media-body">
      <p><b><?php echo CHtml::link($_xinjie['title'],array('chengyu/view','id'=>$_xinjie['id']),array('target'=>'_blank'));?></b></p>
      <?php echo zmf::subStr($_xinjie['content'],140,0,'...'.CHtml::link('查看详情',array('chengyu/view','id'=>$_xinjie['id']),array('target'=>'_blank')));?>
  </div>
</div>
<?php }?>
<?php }?>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>