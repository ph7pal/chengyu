<h1><?php echo $info['title'];?></h1>
<div class="zmf-border-bottom">
    <?php echo zmf::text(array(), $info['content'],false); ?>
</div>
<?php if(!empty($allInfos)){?>
<h4>相关文章</h4>
<div class="list-group">
    <?php foreach($allInfos as $val){?>
    <?php echo CHtml::link($val['title'],array('siteinfo/view','code'=>$val['code']),array('class'=>'list-group-item '.($code==$val['code']?'active':'')));?>
    <?php }?>
</div>
<?php }