<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('index'),
    CHtml::link('词语故事',array('chengyu/story')),
    $model->title,
);
?>
<div class="wrap-container">
    <div class="wrap-content">
        <h1 class="item-title"><?php echo $model->title;?></h1>
    </div>        
    <table class="ui-table ui-border-tb zmf-table">
        <?php if($model->fayin!=''){?><tr><td>发音：</td><td><?php echo $model->fayin;?></td></tr><?php }?>
        <?php if($model->title_tw!=''){?><tr><td style="width: 80px">繁体：</td><td><?php echo $model->title_tw;?></td></tr><?php }?>
        <?php if($model->yufa!=''){?><tr><td>语法：</td><td><?php echo $model->yufa;?></td></tr><?php }?>
        <?php if(!empty($jies)){$_total=count($jies);foreach($jies as $k=>$jieshi){?>  
        <tr><td><?php echo $k==0 ? '解释：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').($jieshi['type']==ChengyuContent::TYPE_WL ? '<span class="ui-badge-muted">新解</span>':'').$jieshi['content'];?></td></tr>
        <?php }}?>
        <?php if(!empty($chuChus)){$_total=count($chuChus);foreach($chuChus as $k=>$chuChu){?>  
        <tr><td><?php echo $k==0 ? '出处：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').($chuChu['type']==ChengyuContent::TYPE_WL ? '<span class="ui-badge-muted">新解</span>':'').$chuChu['content'];?></td></tr>
        <?php }}?>
        <?php if(!empty($liJus)){$_total=count($liJus);foreach($liJus as $k=>$liJu){?>  
        <tr><td><?php echo $k==0 ? '例句：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').($liJu['type']==ChengyuContent::TYPE_WL ? '<span class="ui-badge-muted">新解</span>':'').$liJu['content'];?></td></tr>
        <?php }}?>
        <?php if(!empty($tongyis)){?><tr><td>同义词：</td><td class="breadwords"><?php foreach($tongyis as $tongyi){$_ciInfo=$tongyi->chengyuInfo;echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id']));}?></td></tr><?php }?>
        <?php if(!empty($fanyiis)){?><tr><td>反义词：</td><td  class="breadwords"><?php foreach($fanyiis as $fanyii){$_ciInfo=$fanyii->chengyuInfo;echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id']));}?></td></tr><?php }?>
    </table>
</div>
<?php if(!empty($guShis)){?>
<div class="mod-header">
    <h2>“<?php echo $model->title;?>”的故事</h2>
</div>
<div class="wrap-container">
    <div class="wrap-content">
        <?php foreach($guShis as $k=>$guShi){?> 
            <p><span class="ui-badge"><?php echo ($k+1);?></span><?php if($guShi['type']==ChengyuContent::TYPE_WL){?><span class="ui-badge-muted">网络新解</span><?php }?></p>
            <div class="text-indent">
                <?php echo nl2br($guShi['content']);?>
            </div>
          <?php }?>
    </div>
</div>
<?php }?>

<?php if(!empty($relatedWords)){?>
<?php if(!empty($relatedWords['firstWord'])){?>
<div class="mod-header">
    <h3>第一个字为“<?php echo $wordArr[0];?>”的成语</h3>
</div>
<ul class="ui-list ui-list-pure ui-border-tb">
    <?php foreach($relatedWords['firstWord'] as $_word){?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/view',array('id'=>$_word['id']));?>">
        <?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']));?>
    </li>
    <?php }?>
</ul>
<?php }?>
<?php if(!empty($relatedWords['secondWord'])){?>
<div class="mod-header">
    <h3>第二个字为“<?php echo $wordArr[1];?>”的成语</h3>
</div>
<ul class="ui-list ui-list-pure ui-border-tb">
    <?php foreach($relatedWords['secondWord'] as $_word){?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/view',array('id'=>$_word['id']));?>">
        <?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']));?>
    </li>
    <?php }?>
</ul>
<?php }?>
<?php if(!empty($relatedWords['thirdWord'])){?>
<div class="mod-header">
    <h3>第三个字为“<?php echo $wordArr[2];?>”的成语</h3>
</div>
<ul class="ui-list ui-list-pure ui-border-tb">
    <?php foreach($relatedWords['thirdWord'] as $_word){?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/view',array('id'=>$_word['id']));?>">
        <?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']));?>
    </li>
    <?php }?>
</ul>
<?php }?>
<?php if(!empty($relatedWords['fourthWord'])){?>
<div class="mod-header">
    <h3>第四个字为“<?php echo $wordArr[3];?>”的成语</h3>
</div>
<ul class="ui-list ui-list-pure ui-border-tb">
    <?php foreach($relatedWords['fourthWord'] as $_word){?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/view',array('id'=>$_word['id']));?>">
        <?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']));?>
    </li>
    <?php }?>
</ul>
<?php }?>
<?php }?>