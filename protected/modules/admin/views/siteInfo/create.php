<?php

$a = Yii::app()->getController()->getAction()->id;
$this->menu = array(
    '文章列表' => array(
        'link' => array('siteInfo/index'),
        'active' => in_array($a, array('index'))
    ),
    '新增文章' => array(
        'link' => array('siteInfo/create'),
        'active' => in_array($a, array('create'))
    )
);
if ($a == 'update') {
    $this->menu['更新文章'] = array(
        'link' => array('siteInfo/create'),
        'active' => true
    );
}
$this->renderPartial('_form', array('model' => $model));