<tr>
	<td><?php echo CHtml::link(CHtml::encode($data->truename),array('view','id'=>$data->id));?></td>
	<td>
            <?php echo CHtml::link('编辑',array('update','id'=>$data->id));?>
	</td>
</tr>