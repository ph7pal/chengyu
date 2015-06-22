<?php
/* @var $this ChengyuController */
/* @var $model Chengyu */

$this->breadcrumbs=array(
	'Chengyus'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Chengyu', 'url'=>array('index')),
	array('label'=>'Create Chengyu', 'url'=>array('create')),
	array('label'=>'Update Chengyu', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Chengyu', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Chengyu', 'url'=>array('admin')),
);
?>
<h1><?php echo $model->title;?></h1>
<p><?php echo $model->title_tw;?></p>