<tr>
	<td><?php echo CHtml::link($data->title,array('/site/info','code'=>$data->code)); ?></td>
	
	<td>
            <?php echo CHtml::link('编辑',array('update','id'=>$data->id));?>
	</td>
</tr>