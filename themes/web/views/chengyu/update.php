<?php
/* @var $this ChengyuController */
/* @var $model Chengyu */
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '成语大全' => array('index'),
    $model->title => array('view', 'id' => $model->id),
    '更新',
);
$this->menu = array(
    array('label' => '列表', 'url' => array('index')),
    array('label' => '新增', 'url' => array('create')),
    array('label' => '详细信息', 'url' => array('view', 'id' => $model->id)),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>