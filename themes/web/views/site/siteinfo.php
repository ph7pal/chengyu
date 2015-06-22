<div class="main-page">
    <?php echo zmf::text(array(), $info['content']); ?>
</div>
<div class="aside-page">
    <?php if(!empty($allInfos)){?>
    <div class="list-group">
        <?php foreach($allInfos as $val){?>
        <?php echo CHtml::link($val['title'],array('siteinfo/view','code'=>$val['code']),array('class'=>'list-group-item '.($code==$val['code']?'active':'')));?>
        <?php }?>
    </div>
    <?php }?>
</div>