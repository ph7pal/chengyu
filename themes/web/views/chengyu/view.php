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
<p>
    <?php echo CHtml::link('添加同义词',array('ci','id'=>$model->id,'type'=>'tongyi'),array('class'=>'btn btn-default'));?>
    <?php echo CHtml::link('添加反义词',array('ci','id'=>$model->id,'type'=>'fanyi'),array('class'=>'btn btn-default'));?>
    <?php echo CHtml::link('添加解释',array('content','id'=>$model->id,'type'=>'jieshi'),array('class'=>'btn btn-default'));?>
    <?php echo CHtml::link('添加出处',array('content','id'=>$model->id,'type'=>'chuchu'),array('class'=>'btn btn-default'));?>
    <?php echo CHtml::link('添加例句',array('content','id'=>$model->id,'type'=>'liju'),array('class'=>'btn btn-default'));?>
    <?php echo CHtml::link('添加故事',array('content','id'=>$model->id,'type'=>'gushi'),array('class'=>'btn btn-default'));?>
</p>