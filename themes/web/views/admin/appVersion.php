<?php

/**
 * @filename appVersion.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-9  13:48:27 
 */
?>
<table class="table table-hover">
    <tr>
        <th style="width: 25%">版本号</th>
        <th style="width: 25%">类型</th>
        <th>状态</th>
        <th style="width: 10%">操作</th>
    </tr>
    <?php foreach ($posts as $data): ?> 
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
    <?php endforeach; ?>
</table>
<?php  $this->renderPartial('/common/pager',array('pages'=>$pages));?>
<p><?php echo CHtml::link('新增',array('create'),array('class'=>'btn btn-primary'));?></p>