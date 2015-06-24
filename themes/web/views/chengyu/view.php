<?php
/* @var $this ChengyuController */
/* @var $model Chengyu */

$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('index'),
    $model->title,
);
$this->menu = array(
    array('label' => '列表', 'url' => array('index')),
    array('label' => '新增', 'url' => array('create')),
    array('label' => '更新', 'url' => array('update', 'id' => $model->id)),
    array('label' => '删除', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => '添加同义词', 'url' => array('ci', 'id' => $model->id, 'type' => 'tongyi')),
    array('label' => '添加反义词', 'url' => array('ci', 'id' => $model->id, 'type' => 'fanyi')),
    array('label' => '添加解释', 'url' => array('content', 'id' => $model->id, 'type' => 'jieshi')),
    array('label' => '添加出处', 'url' => array('content', 'id' => $model->id, 'type' => 'chuchu')),
    array('label' => '添加例句', 'url' => array('content', 'id' => $model->id, 'type' => 'liju')),
    array('label' => '添加故事', 'url' => array('content', 'id' => $model->id, 'type' => 'gushi')),
);
?>
<h1><?php echo $model->title;?></h1>
<p>发音：<?php echo $model->fayin;?></p>
<p>繁体：<?php echo $model->title_tw;?></p>
<p>语法：<?php echo $model->yufa;?></p>
<?php if(!empty($model->jieShis)){$jies=$model->jieShis;?>
<h4>解释</h4>
<ul>
  <?php foreach($jies as $jieshi){?>  
  <li><?php echo $jieshi['content'];?></li>
  <?php }?>
</ul>
<?php }?>
<?php if(!empty($model->chuChus)){$chuChus=$model->chuChus;?>
<h4>出处</h4>
<ul>
  <?php foreach($chuChus as $chuChu){?>  
  <li><?php echo $chuChu['content'];?></li>
  <?php }?>
</ul>
<?php }?>
<?php if(!empty($model->liJus)){$liJus=$model->liJus;?>
<h4>例句</h4>
<ul>
  <?php foreach($liJus as $liJu){?>  
  <li><?php echo $liJu['content'];?></li>
  <?php }?>
</ul>
<?php }?>
<?php if(!empty($model->guShis)){$guShis=$model->guShis;?>
<h4>故事</h4>
<ul>
  <?php foreach($guShis as $guShi){?>  
  <li><?php echo $guShi['content'];?></li>
  <?php }?>
</ul>
<?php }?>