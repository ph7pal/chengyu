<?php

/* @var $this AppVersionController */
/* @var $model AppVersion */

$this->breadcrumbs = array(
    '首页' => array('index/index'),
    'App版本' => array('index'),
    '新增',
);
$this->renderPartial('_nav');
$this->renderPartial('_form', array('model' => $model));
