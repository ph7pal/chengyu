<?php
/* @var $this ChengyuController */
/* @var $model Chengyu */

$this->breadcrumbs=array(
	'Chengyus'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Chengyu', 'url'=>array('index')),
	array('label'=>'Manage Chengyu', 'url'=>array('admin')),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>