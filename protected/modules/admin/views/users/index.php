<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  12:57:20 
 */
$this->renderPartial('/users/_nav');
?>
<table class="table table-hover">
    <tr>
        <th>用户名</th>
        <th style="width: 25%">操作</th>
    </tr>
<?php foreach($posts as $row):?> 
<?php $this->renderPartial('_view',array('data'=>$row));?>
 <?php endforeach;?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>