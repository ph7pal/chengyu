<?php
/* @var $this AppVersionController */
/* @var $model AppVersion */

$this->breadcrumbs = array(
    '首页' => array('index/index'),
    'App版本' => array('index'),
    $model->version => array('view', 'id' => $model->id),
    '更新',
);

$this->menu = array(
    array('label' => 'List AppVersion', 'url' => array('index')),
    array('label' => 'Create AppVersion', 'url' => array('create')),
    array('label' => 'View AppVersion', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage AppVersion', 'url' => array('admin')),
);
?>
<?php $this->renderPartial('_form', array('model' => $model)); ?>