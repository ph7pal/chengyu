<?php if(!empty($new)){?>
<h4>最新收录</h4>
<div class="row">
    <?php foreach($new as $_new){?>
    <?php echo CHtml::link('<div class="col-sm-3 col-xs-3">'.$_new['title'].'</div>',array('chengyu/view','id'=>$_new['id']));?>
    <?php echo CHtml::link('<div class="col-sm-3 col-xs-3">'.$_new['title'].'</div>',array('chengyu/view','id'=>$_new['id']));?>
    <?php echo CHtml::link('<div class="col-sm-3 col-xs-3">'.$_new['title'].'</div>',array('chengyu/view','id'=>$_new['id']));?>
    <?php echo CHtml::link('<div class="col-sm-3 col-xs-3">'.$_new['title'].'</div>',array('chengyu/view','id'=>$_new['id']));?>
    <?php echo CHtml::link('<div class="col-sm-3 col-xs-3">'.$_new['title'].'</div>',array('chengyu/view','id'=>$_new['id']));?>
    <?php }?>
</div>
<?php }?>
<h4>最新故事</h4>