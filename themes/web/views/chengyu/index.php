<?php
/* @var $this ChengyuController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Chengyus',
);

$this->menu=array(
	array('label'=>'Create Chengyu', 'url'=>array('create')),
	array('label'=>'Manage Chengyu', 'url'=>array('admin')),
);
?>

<h1>Chengyus</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
