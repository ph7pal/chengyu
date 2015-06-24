<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('index'),
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
    array('label' => '删除', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
);
$tongyis=$model->tongYiCis;
$fanyiis=$model->fanYiCis;
?>
<h1><?php echo $model->title;?></h1>
<table class="table table-striped">
    <tr><td style="width: 80px">发音：</td><td><?php echo $model->fayin;?></td></tr>
    <tr><td>繁体：</td><td><?php echo $model->title_tw;?></td></tr>
    <tr><td>语法：</td><td><?php echo $model->yufa;?></td></tr>
    <?php $jies=$model->jieShis;$_total=count($jies);if(!empty($jies)){foreach($jies as $k=>$jieshi){?>  
    <tr><td><?php echo $k==0 ? '解释：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').$jieshi['content'];?></td></tr>
    <?php }}?>
    <?php $chuChus=$model->chuChus;$_total=count($chuChus);if(!empty($chuChus)){foreach($chuChus as $k=>$chuChu){?>  
    <tr><td><?php echo $k==0 ? '出处：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').$chuChu['content'];?></td></tr>
    <?php }}?>
    <?php $liJus=$model->liJus;$_total=count($liJus);if(!empty($liJus)){foreach($liJus as $k=>$liJu){?>  
    <tr><td><?php echo $k==0 ? '例句：' : '&nbsp;';?></td><td><?php echo ($_total >1 ? (($k+1).'、'):'').$liJu['content'];?></td></tr>
    <?php }}?>
    <tr><td>同义词：</td><td><?php foreach($tongyis as $tongyi){$_ciInfo=$tongyi->chengyuInfo;echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id'])).'&nbsp;';}?></td></tr>
    <tr><td>反义词：</td><td><?php foreach($fanyiis as $fanyii){$_ciInfo=$fanyii->chengyuInfo;echo CHtml::link($_ciInfo['title'],array('chengyu/view','id'=>$_ciInfo['id'])).'&nbsp;';}?></td></tr>
</table>
<?php if(!empty($model->guShis)){$guShis=$model->guShis;?>
<h4>故事</h4>
  <?php foreach($guShis as $k=>$guShi){?> 
    <div class="media zmf-border-bottom">
        <div class="media-body">
          <p><span class="badge"><?php echo ($k+1);?></span></p>
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