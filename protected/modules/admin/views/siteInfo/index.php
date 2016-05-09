<?php
$a = Yii::app()->getController()->getAction()->id;
$this->menu=array(
    '文章列表'=>array(
        'link'=>array('siteInfo/index'),
        'active'=>in_array($a,array('index'))
    ),
    '新增文章'=>array(
        'link'=>array('siteInfo/create'),
        'active'=>in_array($a,array('create'))
    )
);
?>

<table class="table table-hover">
<tr>
    <th>标题</th>
    <th style="width: 20%">操作</th>
</tr>
<?php foreach($posts as $row):?> 
<?php $this->renderPartial('_view',array('data'=>$row));?>
 <?php endforeach;?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>