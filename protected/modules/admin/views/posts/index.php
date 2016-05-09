<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  10:12:36 
 */
$this->renderPartial('_nav');
?>
<table class="table table-hover">
    <tr>
        <th>标题</th>
        <th class="text-center">点/藏</th>
        <th>时间</th>
        <th style="width: 110px;">操作</th>
    </tr>
    <?php foreach ($posts as $row): ?> 
        <?php $this->renderPartial('/posts/_view', array('data' => $row)); ?>
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>