<?php
$this->menu=array(
    '反馈列表'=>array(
        'link'=>array('feedback/index'),
        'active'=>true
    )
);
?>
<?php foreach ($posts as $row): ?> 
    <?php $this->renderPartial('_view', array('data' => $row)); ?>
<?php endforeach; ?>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>