<?php $this->beginContent('/layouts/common'); ?>
<?php if($this->wholeNotice){?>
    <div class="alert alert-warning" id="wholeNotice-bar">
        <div class="container"><?php echo $this->wholeNotice;?></div>            
    </div>
<?php }?>
<div class="header-top">
    <div class="header-logo"><?php echo CHtml::link(zmf::config('sitename'),zmf::config('baseurl'));?></div>          
</div>
<div class="wrapper">
    <?php if(!empty($this->breadcrumbs)){?>
    <ol class="breadcrumb">
        <?php foreach($this->breadcrumbs as $k=>$v){?>
        <li><?php echo is_array($v) ? CHtml::link($k,$v):$v;?></li>
        <?php }?>
    </ol>
    <?php }?>
    <div class="main-page">
        <?php echo $content; ?>
    </div>
    <div class="aside-page">
        <?php $form=$this->beginWidget('CActiveForm', array('id'=>'searcher-form','enableAjaxValidation'=>false,'method'=>'GET','action'=>Yii::app()->createUrl('chengyu/search'))); ?>
        <div class="aside-searchHolder" style="margin-bottom: 15px">
            <div class="input-group">
                <input type="text" id='keyword' name='keyword' class="form-control" placeholder="请输入关键词" value="<?php echo $this->searchKeywords;?>">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">搜索</button>
                </span>
            </div><!-- /input-group -->
            <div class="list-group" id="searcher-holder"></div>
        </div>
        <?php $this->endWidget(); ?>
        <?php if(!empty($this->menu) && $this->uid){?>
        <div class="list-group">
            <?php foreach($this->menu as $k=>$v){?>
            <?php echo CHtml::link($v['label'],$v['url'],array('class'=>'list-group-item'));?>
            <?php }?>
        </div>
        <?php }?>
        <!--div class="aside-mod">
            一些推荐
        </div-->
        <div class="aside-mod">
            <blockquote>
                <p><?php echo zmf::config('shortTitle');?></p>
                <footer><?php echo CHtml::link(zmf::config('sitename'),zmf::config('domain'));?></footer>
            </blockquote>
            <p class="help-block"><?php echo zmf::config('copyright');?> <?php echo zmf::config('beian');?></p>
        </div>
    </div>    
</div>
<?php $this->endContent(); ?>