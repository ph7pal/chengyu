<?php $this->beginContent('/layouts/common'); ?>
<div class="top-header">                
    <nav class="navbar navbar-default">
        <div class="container-fluid">
        <div class="navbar-header">                        
            <?php echo CHtml::link('管理中心',array('index/index'),array('class'=>'navbar-brand'));?>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?php if(zmf::uid()){?>
            <ul class="nav navbar-nav">
                <?php 
                $navs=  $this->navbar();
                foreach($navs as $nav){
                    if(!$nav['seconds']){
                        echo "<li class='".($nav['active']?'active':'')."'>".CHtml::link($nav['title'],$nav['url'])."</li>";
                    }else{
                        echo "<li class='".($nav['active']?'active':'')." dropdown'><a href='{$nav['url']}' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'>{$nav['title']} <span class='caret'></span></a><ul class='dropdown-menu' role='menu'>";
                        foreach ($nav['seconds'] as $val){
                            echo "<li>".CHtml::link($val['title'],$val['url'])."</li>";
                        }
                        echo '</ul></li>';
                    }
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $this->userInfo['truename'];?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php echo CHtml::link('新增文章', array('posts/create'),array('role'=>'menuitem')); ?></li>
                        <li class="divider"></li>
                        <li><?php echo CHtml::link('站点首页',  zmf::config('baseurl'),array('role'=>'menuitem'));?></li>
                        <li class="divider"></li>
                        <li><?php echo CHtml::link('退出',array('/site/logout'),array('role'=>'menuitem'));?></li>
                    </ul>
                </li>
            </ul>
            <?php }?>
        </div>
        </div>
    </nav>
</div>
<div class="settings-container">   
    <?php if(!empty($this->menu)){?>
    <div class="settings-side-box  col-xs-3 col-sm-3 col-lg-2">
        <div class="list-group">
            <?php foreach($this->menu as $k=>$v){?>
            <?php echo CHtml::link($k,$v['link'],array('class'=>'list-group-item'.($v['active'] ? ' active' : ''),'target'=>($v['target'] ? '_blank': '')));?>
            <?php }?>
        </div>
    </div>
    <?php }?>
    <div class="settings-main-box col-xs-9 col-sm-9 col-lg-10">
        <?php if(!empty($this->breadcrumbs)){?>
        <ol class="breadcrumb">
            <?php foreach($this->breadcrumbs as $k=>$v){?>
            <li><?php echo is_array($v) ? CHtml::link($k,$v):$v;?></li>
            <?php }?>
        </ol>
        <?php }?>     
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>
<script>
    $(document).ready(function() {
        setSideHeight();
    });
    $(window).resize(function() {
        setSideHeight();
    });
    function setSideHeight(){
        var dom=$('.settings-main-box');
        $('.settings-side-box').css({
            height:dom.outerHeight()
        });
    }
</script>