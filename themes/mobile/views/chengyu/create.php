<?php
/* @var $this ChengyuController */
/* @var $model Chengyu */

$this->breadcrumbs=array(
    CHtml::link('首页', zmf::config('baseurl')),
	'成语大全'=>array('index'),
	'新增',
);

$this->menu=array(
	array('label'=>'列表', 'url'=>array('index')),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>