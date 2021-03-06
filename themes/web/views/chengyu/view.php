<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('index'),
    CHtml::link('词语故事',array('chengyu/story')),
    $model->title,
);
$this->menu = array(
    array('label' => '列表', 'url' => array('index')),
    array('label' => '新增', 'url' => array('create')),
    array('label' => '更新', 'url' => array('update', 'id' => $model->id)),
    array('label' => '添加同义词', 'url' => array('ci', 'id' => $model->id, 'type' => 'tongyi')),
    array('label' => '添加反义词', 'url' => array('ci', 'id' => $model->id, 'type' => 'fanyi')),
    array('label' => '添加解释', 'url' => array('content', 'id' => $model->id, 'type' => 'jieshi')),
    array('label' => '添加出处', 'url' => array('content', 'id' => $model->id, 'type' => 'chuchu')),
    array('label' => '添加例句', 'url' => array('content', 'id' => $model->id, 'type' => 'liju')),
    array('label' => '添加故事', 'url' => array('content', 'id' => $model->id, 'type' => 'gushi')),
    array('label' => '删除', 'url' => array('delete', 'id' => $model->id)),
);
?>
<h1 class="item-title"><?php echo $model->title;?></h1>
<table class="table table-striped">
    <?php if($model->fayin!=''){?><tr><td>发音：</td><td><?php echo $model->fayin;?></td></tr><?php }?>
    <?php if($model->title_tw!=''){?><tr><td style="width: 80px">繁体：</td><td><?php echo $model->title_tw;?></td></tr><?php }?>
    <?php if($model->yufa!=''){?><tr><td>语法：</td><td><?php echo $model->yufa;?></td></tr><?php }?>
    <?php if(!empty($jies)){$_total=count($jies);foreach($jies as $k=>$jieshi){?>  
    <tr><td><?php echo $k==0 ? '解释：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').($jieshi['type']==ChengyuContent::TYPE_WL ? '<span class="badge">新解</span>':'').$jieshi['content'];if($this->uid){echo CHtml::link('编辑',array('chengyu/content','id'=>$model->id,'ccid'=>$jieshi['id'])).'&nbsp;'.CHtml::link('删除',array('chengyu/delcontent','id'=>$jieshi['id']));}?></td></tr>
    <?php }}?>
    <?php if(!empty($chuChus)){$_total=count($chuChus);foreach($chuChus as $k=>$chuChu){?>  
    <tr><td><?php echo $k==0 ? '出处：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').($chuChu['type']==ChengyuContent::TYPE_WL ? '<span class="badge">新解</span>':'').$chuChu['content'];if($this->uid){echo CHtml::link('编辑',array('chengyu/content','id'=>$model->id,'ccid'=>$chuChu['id'])).'&nbsp;'.CHtml::link('删除',array('chengyu/delcontent','id'=>$chuChu['id']));}?></td></tr>
    <?php }}?>
    <?php if(!empty($liJus)){$_total=count($liJus);foreach($liJus as $k=>$liJu){?>  
    <tr><td><?php echo $k==0 ? '例句：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').($liJu['type']==ChengyuContent::TYPE_WL ? '<span class="badge">新解</span>':'').$liJu['content'];if($this->uid){echo CHtml::link('编辑',array('chengyu/content','id'=>$model->id,'ccid'=>$liJu['id'])).'&nbsp;'.CHtml::link('删除',array('chengyu/delcontent','id'=>$liJu['id']));}?></td></tr>
    <?php }}?>
    <?php if(!empty($tongyis)){?><tr><td>同义词：</td><td class="breadwords"><?php foreach($tongyis as $tongyi){$_ciInfo=$tongyi->chengyuInfo;echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id']));}?></td></tr><?php }?>
    <?php if(!empty($fanyiis)){?><tr><td>反义词：</td><td  class="breadwords"><?php foreach($fanyiis as $fanyii){$_ciInfo=$fanyii->chengyuInfo;echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id']));}?></td></tr><?php }?>
</table>
<?php if(!empty($guShis)){?>
<h4>“<?php echo $model->title;?>”的故事</h4>
  <?php foreach($guShis as $k=>$guShi){?> 
    <div class="media zmf-border-bottom">
        <div class="media-body">
          <p><span class="badge"><?php echo ($k+1);?></span><?php if($guShi['type']==ChengyuContent::TYPE_WL){?><span class="badge">网络新解</span><?php }?></p>
          <div class="text-indent">
              <?php echo nl2br($guShi['content']);?>
          </div>
          <?php if($this->uid){?><p class="help-block">
          <?php echo CHtml::link('编辑',array('chengyu/content','id'=>$model->id,'ccid'=>$guShi['id']));?>
          <?php echo CHtml::link('删除',array('chengyu/delcontent','id'=>$guShi['id']));?>
          </p><?php }?>
        </div>
    </div>
  <?php }?>
<?php }?>
<?php if(!empty($relatedWords)){?>
<h4>与“<?php echo $model->title;?>”相关的成语</h4>
<div class="row">
    <?php if(!empty($relatedWords['firstWord'])){?>
    <div class="col-sm-3 col-xs-3">
        <h5><?php echo $wordArr[0];?>***</h5>
        <?php foreach($relatedWords['firstWord'] as $_word){?>
        <p><?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']),array('target'=>'_blank'));?></p>
        <?php }?>
    </div>
    <?php }?>
    <?php if(!empty($relatedWords['secondWord'])){?>
    <div class="col-sm-3 col-xs-3">
        <h5>*<?php echo $wordArr[1];?>**</h5>
        <?php foreach($relatedWords['secondWord'] as $_word){?>
        <p><?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']),array('target'=>'_blank'));?></p>
        <?php }?>
    </div>
    <?php }?>
    <?php if(!empty($relatedWords['thirdWord'])){?>
    <div class="col-sm-3 col-xs-3">
        <h5>**<?php echo $wordArr[2];?>*</h5>
        <?php foreach($relatedWords['thirdWord'] as $_word){?>
        <p><?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']),array('target'=>'_blank'));?></p>
        <?php }?>
    </div>
    <?php }?>
    <?php if(!empty($relatedWords['fourthWord'])){?>
    <div class="col-sm-3 col-xs-3">
        <h5>***<?php echo $wordArr[3];?></h5>
        <?php foreach($relatedWords['fourthWord'] as $_word){?>
        <p><?php echo CHtml::link($_word['title'],array('chengyu/view','id'=>$_word['id']),array('target'=>'_blank'));?></p>
        <?php }?>
    </div>
    <?php }?>    
</div>
<?php }?>