<?php
$this->renderPartial('/users/_nav');
?>
<table class="table table-hover">
    <tr>
        <th>用户名</th>
        <th style="width:80px">操作</th>
    </tr>
    <?php foreach ($posts as $row): ?> 
    <tr>
        <td><?php echo $row->truename;?></td>
        <td>
            <?php echo CHtml::link('权限',array('setadmin','id'=>$row->id));?>
            <?php echo CHtml::link('删除',array('deladmin','id'=>$row->id));?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>