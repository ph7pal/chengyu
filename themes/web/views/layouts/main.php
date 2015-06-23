<?php $this->beginContent('/layouts/common'); ?>
<?php if($this->wholeNotice){?>
    <div class="alert alert-warning" id="wholeNotice-bar">
        <div class="container"><?php echo $this->wholeNotice;?></div>            
    </div>
<?php }?>
<div class="header-top">
    <div class="header-logo"><?php echo CHtml::link(zmf::config('sitename'),zmf::config('baseurl'));?></div>          
</div>
<!--div class="navbar navbar-default" role="navigation">
  <div class="container no-padding">     
      <div class="navbar-collapse collapse no-padding">
        <ul class="nav navbar-nav">
            <li><?php echo CHtml::link('首页',zmf::config('baseurl'));?></li>
            <li><?php echo CHtml::link('成语大全',zmf::config('baseurl'));?></li>
        </ul>
        <form class="navbar-form navbar-right" method="GET" action="<?php echo zmf::config("domain").Yii::app()->createUrl("posts/search");?>">
        <div class="form-group">
          <input type="text" id="keyword" name="keyword" class="form-control input-sm" placeholder="<?php echo zmf::t('search_placeholder');?>" onchange="searchSuggest('top')">
        </div>
      </form>
    </div>
  </div> 
</div-->
<div class="wrapper">
    <ol class="breadcrumb">
  <li><a href="#">Home</a></li>
  <li><a href="#">Library</a></li>
  <li class="active">Data</li>
</ol>
    <div class="main-page">
        <?php echo $content; ?>
    </div>
    <div class="aside-page">
        <div class="aside-mod">
            一些推荐
        </div>
        <div class="aside-searchHolder" style="margin-bottom: 15px">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="请输入关键词">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button">搜索</button>
                </span>
            </div><!-- /input-group -->
        </div>
        <div class="aside-mod">
            <p class="text-center"><a href="<?php echo zmf::config('domain');?>" target="_blank"><?php echo zmf::config('sitename');?></a><?php echo zmf::config('version');?> <?php echo zmf::config('copyright');?> <?php echo zmf::config('beian');?>&nbsp;<?php echo CHtml::link('关于本站',array('siteinfo/view','code'=>'about'));?></p>
        </div>
    </div>    
<div class="side-fixed back-to-top"><a href="#top" title="返回顶部"><span class="icon-angle-up"></span></a></div><div class="side-fixed feedback"><a href="javascript:;" title="意见反馈" action="feedback"><span class="icon-comment"></span></a></div>
</div>
<?php $this->endContent(); ?>