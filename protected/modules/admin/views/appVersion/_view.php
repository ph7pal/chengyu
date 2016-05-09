<tr>
    <td><?php echo CHtml::encode($data->version); ?></td>
    <td><?php echo CHtml::encode($data->type); ?></td>
    <td><?php echo AppVersion::exStatus($data->status) ?></td>    
    <td>
            <?php echo CHtml::link('详细',array('view','id'=>$data->id));?>
            <?php echo CHtml::link('编辑',array('update','id'=>$data->id));?>
            <?php echo CHtml::link('删除',array('delete','id'=>$data->id));?>	
    </td>
</tr>