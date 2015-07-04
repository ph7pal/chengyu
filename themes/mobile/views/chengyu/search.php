<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('index'),
    $this->searchKeywords ? '【'.$this->searchKeywords.'】搜索结果' : '搜索',
);
?>
<?php $form=$this->beginWidget('CActiveForm', array('id'=>'searcher-form','enableAjaxValidation'=>false,'method'=>'GET','action'=>Yii::app()->createUrl('chengyu/search'))); ?>
<div class="ui-searchbar-wrap ui-border-b">    
    <div class="ui-searchbar ui-border-radius">
        <i class="ui-icon-search"></i>
        <div class="ui-searchbar-text">请输入搜索关键词</div>
        <div class="ui-searchbar-input"><input name="keyword" type="text" placeholder="请输入搜索关键词" autocapitalize="off"></div>
        <i class="ui-icon-close"></i>
    </div>
    <button type="button" class="ui-searchbar-cancel">取消</button>    
</div>
<?php $this->endWidget(); ?>
<?php if(!$this->searchKeywords){?>
<div class="ui-tips ui-tips-info">
    <i></i><span>请输入搜索关键词</span>
</div>
<?php }elseif(empty($posts)){?>
<div class="ui-tips ui-tips-info">
    <i></i><span>暂无[<?php echo $this->searchKeywords;?>]的搜索结果</span>
    <p class="text-center color-grey">您可以尝试简化搜索关键词</p>
</div>
<?php }else{ ?>
<div class="mod-header">
    <h1>[<?php echo $this->searchKeywords;?>]的搜索结果</h1>
</div>
<ul class="ui-list ui-list-pure ui-border-tb">
    <?php foreach($posts as $post){?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/view',array('id'=>$post['id']));?>">
        <?php echo CHtml::link($post['title'],array('chengyu/view','id'=>$post['id']),array('class'=>'col-list-item','title'=>$post['title']));?> 
    </li>    
    <?php }?>
</ul>
<?php }