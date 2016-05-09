<?php

$this->beginContent('/layouts/main');
$items = Config::navbar('admin');
foreach ($items as $k => $t) {
    $this->menu[$t] = array(
        'link' => array('config/index', 'type' => $k),
        'active' => $k == $_GET['type']
    );
}
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'config-addConfigs-form',
    'action' => Yii::app()->createUrl('admin/config/add'),
    'enableAjaxValidation' => false,
        ));
echo $content;
echo CHtml::submitButton('提交', array('class' => 'btn btn-primary', 'name' => ''));
$this->endWidget();
$this->endContent();
