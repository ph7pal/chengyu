<?php $this->beginContent('/layouts/common'); ?>
<?php if($this->wholeNotice){?>
    <div class="alert alert-warning" id="wholeNotice-bar">
        <div class="container"><?php echo $this->wholeNotice;?></div>            
    </div>
<?php }?>
    <div class="container">
        
      <div class="header-top row">
          <div class="col-sm-3 col-xs-3 header-logo"><?php echo CHtml::link(zmf::config('sitename'),zmf::config('baseurl'));?></div>
          <div class="col-sm-6 col-xs-6">
              <?php if(Yii::app()->getController()->id!='index' || !in_array(Yii::app()->getController()->getAction()->id,array('index'))){?>
              <form role="form" action="<?php echo zmf::config("domain").Yii::app()->createUrl("posts/search");?>"  method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="<?php echo zmf::t('search_placeholder');?>" onkeyup="searchKeyup(event)" onblur="hideSearch();" name="keyword" id="keyword" value="<?php echo isset($this->searchKeywords) ? $this->searchKeywords : ''; ?>" autocomplete="off" disableautocomplete>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-success" id="search-btn"><?php echo zmf::t('search');?></button>
                        </span>              
                    </div>      
              </form>       
              <?php }?>
          </div>
          <div class="col-sm-3 col-xs-3"></div>
      </div>
    </div>

<div class="navbar navbar-default" role="navigation">
  <div class="container no-padding">     
      <div class="navbar-collapse collapse no-padding">
        <ul class="nav navbar-nav">
            <?php if(!empty($this->areaInfo)){?>
            <li id="nav-index"><?php echo CHtml::link($this->areaInfo['title'].'<span class="caret"></span>', array('index/show','areaid'=>$this->areaInfo['id'])); ?></li>
            <?php }else{?>
            <li id="nav-index"><?php echo CHtml::link(zmf::t('indexPage').'<span class="caret"></span>', array('index/all')); ?></li>
            <?php }?>
            <?php $topcols=  Column::navbar($this->theAreaId,$this->areaInfo['title'],$this->likeAreas);foreach($topcols as $col){?>
            <li class="<?php echo $col['active'] ? 'active' : ''; ?>"><?php echo CHtml::link($col['title'],$col['url']);?></li>
            <?php }?>          
        </ul>
      <?php if(Yii::app()->getController()->id=='index' && in_array(Yii::app()->getController()->getAction()->id,array('index'))){?><?php }else{?>
        <!--form class="navbar-form navbar-left" method="GET" style="width:196px;padding:0;" action="<?php echo zmf::config("domain").Yii::app()->createUrl("posts/search");?>">
        <div class="form-group">
          <input type="text" id="keyword" name="keyword" class="form-control input-sm" placeholder="<?php echo zmf::t('search_placeholder');?>" onchange="searchSuggest('top')" value="<?php echo isset($this->searchKeywords) ? $this->searchKeywords : ''; ?>">
        </div>
      </form-->
      <?php }?>
      <?php if (Yii::app()->user->isGuest) { ?>
      <ul class="nav navbar-nav navbar-right">
        <li><?php echo CHtml::link(zmf::t('login'), array('site/login')); ?></li>
        <li><?php echo CHtml::link(zmf::t('register'), array('site/reg')); ?></li>
      </ul>
      <?php }else{ ?>
      <ul class="nav navbar-nav navbar-right">
        <?php $noticeNum=  Notification::getNum();if($noticeNum>0){$_notice='提醒<span class="top-nav-count">'.$noticeNum.'</span>';}else{$_notice='提醒';}?>
        <li><?php echo CHtml::link($_notice, array('users/notice'),array('role'=>'menuitem')); ?></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->truename;?> <span class="caret"></span></a>               
            <ul class="dropdown-menu">
              <li><?php echo CHtml::link(zmf::t('homepage'), array('users/index', 'id' => Yii::app()->user->id),array('role'=>'menuitem')); ?></li>
              <li><?php echo CHtml::link(zmf::t('favorite'), array('users/favorites'),array('role'=>'menuitem')); ?></li>
              <li><?php echo CHtml::link(zmf::t('setting'), array('users/config'),array('role'=>'menuitem')); ?></li>
              <li><?php echo CHtml::link(zmf::t('logout'), array('site/logout'),array('role'=>'menuitem')); ?></li>
            </ul>
        </li>
      </ul>
      <?php }?>
    </div>
  </div> 
</div>
<?php $this->renderPartial('/index/floatNav');?>
<?php if(Yii::app()->getController()->id=='index' && in_array(Yii::app()->getController()->getAction()->id,array('index'))){?>
<div class="header-ppt">
  <div class="header-bg">
      <img id="backdrop" class="lazy" data-original="<?php echo zmf::attachBase('site');?>daodao/bg.jpg" src="<?php echo zmf::lazyImg();?>" width="100%" />
  </div>
<div class="header-search-bg"></div>
<div class="header-search">
  <div class="search-login" id="main-search-holder"> 
    <h1 class="logo-title text-center">完美你的旅行计划</h1>
    <form role="form" action="<?php echo zmf::config("domain").Yii::app()->createUrl("posts/search");?>"  method="GET">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="<?php echo zmf::t('search_placeholder');?>" onkeyup="searchKeyup(event)" onblur="hideSearch();" name="keyword" id="keyword" value="<?php echo isset($this->searchKeywords) ? $this->searchKeywords : ''; ?>" autocomplete="off" disableautocomplete>
            <span class="input-group-btn">
                <button type="button" class="btn btn-success" id="search-btn"><?php echo zmf::t('search');?></button>
            </span>              
        </div>      
    </form>        
  </div>
  </div>
</div>
<div class="clearfix"></div>
<?php }?>
<div class="wrapper">
<div id="content">
<?php echo $content; ?> 
</div>
<div class="side-fixed back-to-top"><a href="#top" title="返回顶部"><span class="icon-angle-up"></span></a></div><div class="side-fixed feedback"><a href="javascript:;" title="意见反馈" action="feedback"><span class="icon-comment"></span></a></div>
</div>
<?php $this->endContent(); ?>