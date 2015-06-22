<?php
/* @var $this ChengyuController */
/* @var $model Chengyu */

$this->breadcrumbs=array(
	'Chengyus'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Chengyu', 'url'=>array('index')),
	array('label'=>'Create Chengyu', 'url'=>array('create')),
	array('label'=>'View Chengyu', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Chengyu', 'url'=>array('admin')),
);
?>

<h1>Update Chengyu <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>