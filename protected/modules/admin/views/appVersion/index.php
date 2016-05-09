<?php
/* @var $this AppVersionController */
/* @var $dataProvider CActiveDataProvider */
$this->renderPartial('_nav');
$this->breadcrumbs = array(
    '首页' => array('index/index'),
    'App版本',
);
?>
<table class="table table-hover">
    <tr>
        <th style="width: 25%">版本号</th>
        <th style="width: 25%">类型</th>
        <th>状态</th>
        <th style="width: 10%">操作</th>
    </tr>
    <?php foreach ($posts as $row): ?> 
        <?php $this->renderPartial('_view', array('data' => $row)); ?>
    <?php endforeach; ?>
</table>
<?php  $this->renderPartial('/common/pager',array('pages'=>$pages));?>
<p><?php echo CHtml::link('新增',array('create'),array('class'=>'btn btn-primary'));?></p>