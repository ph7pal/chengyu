<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  11:51:24 
 */
$this->renderPartial('_nav');
?>
<style>
    .comments-box .media{
        border-bottom: 1px solid #f8f8f8
    }
    .comment-actions a{
        margin-left: 10px;
    }
</style>
<?php if(!empty($posts)){?>
<div class="comments-box">    
<?php foreach ($posts as $row): ?> 
    <?php $this->renderPartial('/comments/_view', array('data' => $row)); ?>
<?php endforeach; ?>
</div>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
<?php }else{?>
<p class="help-block text-center">暂无评论</p>
<?php } ?>
