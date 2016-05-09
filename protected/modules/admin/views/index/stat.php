<?php

/**
 * @filename stat.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  15:24:05 
 */
$this->renderPartial('_nav');
?>
<style>
    .simple-stat i{
        font-size: 48px;
    }
</style>
<div class="row simple-stat">
    <div class="col-xs-3 col-xs-3 text-center">
        <p><i class="fa fa-bookmark"></i></p>
        <p><?php echo $posts;?></p>
        <p class="help-block">文章数</p>
    </div>
    <div class="col-xs-3 col-xs-3 text-center">
        <p><i class="fa fa-comments"></i></p>
        <p><?php echo $commentsNum;?></p>
        <p class="help-block">评论数</p>
    </div>
    <div class="col-xs-3 col-xs-3 text-center">
        <p><i class="fa fa-image"></i></p>
        <p><?php echo $attachsNum;?></p>
        <p class="help-block">图片数</p>
    </div>
    <div class="col-xs-3 col-xs-3 text-center">
        <p><i class="fa fa-comment"></i></p>
        <p><?php echo $feedbackNum;?></p>
        <p class="help-block">意见反馈</p>
    </div>
    
</div>