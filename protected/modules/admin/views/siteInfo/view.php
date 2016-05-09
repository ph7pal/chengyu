<?php
$a = Yii::app()->getController()->getAction()->id;
$this->menu=array(
    '文章列表'=>array(
        'link'=>array('siteInfo/index'),
        'active'=>in_array($a,array('index'))
    ),
    '新增文章'=>array(
        'link'=>array('siteInfo/create'),
        'active'=>in_array($a,array('create','update'))
    ),
    '更新文章'=>array(
        'link'=>array('siteInfo/update','id'=>$model->id),
        'active'=>in_array($a,array('create','update'))
    ),
    '文章详情'=>array(
        'link'=>array('siteInfo/view','id'=>$model->id),
        'active'=>in_array($a,array('view'))
    ),
    '文章预览'=>array(
        'link'=>array('/site/info','code'=>$model->code)
    ),
);
?>
<h1><?php echo $model->title;?></h1>
<p class="help-block">    
    <?php echo zmf::formatTime($model->cTime);?>
</p>
<?php echo zmf::text(array(), $model->content,false);?>