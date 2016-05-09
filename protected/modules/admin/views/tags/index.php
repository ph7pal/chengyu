<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  12:55:36 
 */
$this->renderPartial('/tags/_nav');
?>
<table class="table table-hover table-condensed table-striped">
    <tr>
        <th>名称</th>
        <th class="text-right" style="width:80px;">操作</th>
    </tr>
    <?php foreach ($posts as $tag){?>
    <tr id="tag-<?php echo $tag['id'];?>">
        <td><?php echo $tag['title'];?></td>
        <td class="text-right">
            <?php echo CHtml::link('编辑',array('tags/update','id'=>$tag['id']));?>
            <?php echo CHtml::link('删除','javascript:;',array('action'=>'del-content','action-type'=>'tag','action-data'=>  $tag['id'],'action-confirm'=>1,'action-target'=>'tag-'.$tag['id']));?>
        </td>
    </tr>
    <?php }?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>