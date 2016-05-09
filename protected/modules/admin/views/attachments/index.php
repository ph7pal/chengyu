<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  12:58:04 
 */
$this->renderPartial('_nav');
?>
<div class="row">
    <?php foreach($posts as $k=>$img){?>
    <div class="col-xs-3 col-sm-3">
        <img src="<?php echo $img['filePath'];?>" class="img-responsive"/>
    </div>
    <?php if(($k+1)%4==0){?><div class="clearfix"></div><?php }?>
    <?php }?>
</div>