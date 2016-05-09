<tr>
    <td>
        <?php echo CHtml::encode($data->title); ?><?php if($data->status==Posts::STATUS_NOTPASSED){?><span style="color: red">[草稿]</span><?php }?>
    </td>
    <td class="text-center">
        <?php echo CHtml::encode($data->hits); ?> | <?php echo CHtml::encode($data->favors); ?>
    </td>
    <td>
        <?php echo zmf::formatTime($data->cTime); ?>
    </td>
    <td>
        <?php echo CHtml::link('预览',array('/posts/view','id'=>  $data->id),array('target'=>'_blank'));?>
        <?php echo CHtml::link('编辑',array('posts/update','id'=> $data->id),array('target'=>'_blank'));?>        
        <?php echo CHtml::link('删除','javascript:;',array('action'=>'del-content','action-type'=>'post','action-data'=>  $data->id,'action-confirm'=>1));?>
    </td>
</tr>