<?php

/* @var $this AppVersionController */
/* @var $model AppVersion */

$this->breadcrumbs = array(
    '首页' => array('index/index'),
    'App版本' => array('index'),
    $model->version,
);

$this->renderPartial('_nav');
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'version',
        'type',
        'downurl',
        array(
            'label' => $model->getAttributeLabel('status'),
            'value' => AppVersion::exStatus($model->status)
        ),
    ),
));
