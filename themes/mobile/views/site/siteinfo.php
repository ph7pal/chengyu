<div class="wrap-container">
    <div class="wrap-content">
        <h1 class="item-title"><?php echo $info['title'];?></h1>
        <?php echo zmf::text(array(), $info['content'],false); ?>
    </div>
</div>
<?php if(!empty($allInfos)){?>
<div class="mod-header">
    <h4>相关文章</h4>
</div>
<ul class="ui-list ui-list-pure ui-border-tb">
<?php foreach($allInfos as $val){?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('siteinfo/view',array('code'=>$val['code']));?>">
        <?php echo CHtml::link($val['title'],array('siteinfo/view','code'=>$val['code']),array('title'=>$val['title']));?> 
    </li>
<?php }?>
</ul>
<?php }