<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('index'),
    CHtml::link('词语故事',array('chengyu/story')),
    $model->title,
);
$tongyis=$model->tongYiCis;
$fanyiis=$model->fanYiCis;
?>
<div class="wrap-container">
    <div class="wrap-content">
        <h1 class="item-title"><?php echo $model->title;?></h1>
    </div>        
        <table class="ui-table ui-border-tb zmf-table">
            <?php if($model->fayin!=''){?><tr><td>发音：</td><td><?php echo $model->fayin;?></td></tr><?php }?>
            <?php if($model->title_tw!=''){?><tr><td style="width: 80px">繁体：</td><td><?php echo $model->title_tw;?></td></tr><?php }?>
            <?php if($model->yufa!=''){?><tr><td>语法：</td><td><?php echo $model->yufa;?></td></tr><?php }?>
            <?php $jies=$model->jieShis;$_total=count($jies);if(!empty($jies)){foreach($jies as $k=>$jieshi){?>  
            <tr><td><?php echo $k==0 ? '解释：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').($jieshi['type']==ChengyuContent::TYPE_WL ? '<span class="badge">新解</span>':'').$jieshi['content'];if($this->uid){echo CHtml::link('编辑',array('chengyu/content','id'=>$model->id,'ccid'=>$jieshi['id'])).'&nbsp;'.CHtml::link('删除',array('chengyu/delcontent','id'=>$jieshi['id']));}?></td></tr>
            <?php }}?>
            <?php $chuChus=$model->chuChus;$_total=count($chuChus);if(!empty($chuChus)){foreach($chuChus as $k=>$chuChu){?>  
            <tr><td><?php echo $k==0 ? '出处：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').($chuChu['type']==ChengyuContent::TYPE_WL ? '<span class="badge">新解</span>':'').$chuChu['content'];if($this->uid){echo CHtml::link('编辑',array('chengyu/content','id'=>$model->id,'ccid'=>$chuChu['id'])).'&nbsp;'.CHtml::link('删除',array('chengyu/delcontent','id'=>$chuChu['id']));}?></td></tr>
            <?php }}?>
            <?php $liJus=$model->liJus;$_total=count($liJus);if(!empty($liJus)){foreach($liJus as $k=>$liJu){?>  
            <tr><td><?php echo $k==0 ? '例句：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').($liJu['type']==ChengyuContent::TYPE_WL ? '<span class="badge">新解</span>':'').$liJu['content'];if($this->uid){echo CHtml::link('编辑',array('chengyu/content','id'=>$model->id,'ccid'=>$liJu['id'])).'&nbsp;'.CHtml::link('删除',array('chengyu/delcontent','id'=>$liJu['id']));}?></td></tr>
            <?php }}?>
            <?php if(!empty($tongyis)){?><tr><td>同义词：</td><td class="breadwords"><?php foreach($tongyis as $tongyi){$_ciInfo=$tongyi->chengyuInfo;echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id']));}?></td></tr><?php }?>
            <?php if(!empty($fanyiis)){?><tr><td>反义词：</td><td  class="breadwords"><?php foreach($fanyiis as $fanyii){$_ciInfo=$fanyii->chengyuInfo;echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id']));}?></td></tr><?php }?>
        </table>
    
</div>
<?php if(!empty($model->guShis)){$guShis=$model->guShis;?>
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
<div class="mod-header">
    <h2>与“<?php echo $model->title;?>”相关的成语</h2>
</div>

<div class="wrap-container">
    <div class="wrap-content">
        <ul class="ui-row">
            <?php if(!empty($relatedWords['firstWord'])){?>
            <li class="ui-col ui-col-50">
                <div class="">
                    <p><?php echo $wordArr[0];?>***</p>
                    <?php foreach($relatedWords['firstWord'] as $_word){?>
                    <p><?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']),array('target'=>'_blank'));?></p>
                    <?php }?>
                </div>
            </li>
            <?php }?>
            <?php if(!empty($relatedWords['secondWord'])){?>
            <li class="ui-col ui-col-50">
                <div class="">
                    <p>*<?php echo $wordArr[1];?>**</p>
                    <?php foreach($relatedWords['secondWord'] as $_word){?>
                    <p><?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']),array('target'=>'_blank'));?></p>
                    <?php }?>
                </div>
            </li>
            <?php }?>
            <div style="clear: both;margin: 5px 0"></div>
            <?php if(!empty($relatedWords['thirdWord'])){?>
            <li class="ui-col ui-col-50">
                <div class="">
                    <p>**<?php echo $wordArr[2];?>*</p>
                    <?php foreach($relatedWords['thirdWord'] as $_word){?>
                    <p><?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']),array('target'=>'_blank'));?></p>
                    <?php }?>
                </div>
                
            </li>
            <?php }?>
            <?php if(!empty($relatedWords['fourthWord'])){?>
            <li class="ui-col ui-col-50">
                <div class="">
                    <p>***<?php echo $wordArr[3];?></p>
                    <?php foreach($relatedWords['fourthWord'] as $_word){?>
                    <p><?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']),array('target'=>'_blank'));?></p>
                    <?php }?>
                </div>
                
            </li>
            <?php }?>  
        </ul>
    </div>
</div>
<?php }?>