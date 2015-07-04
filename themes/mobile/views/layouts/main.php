<!DOCTYPE html>
<html>  
  <head>    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1">
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style"  />
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="full-screen" content="yes">
    <meta name="format-detection" content="telephone=no">    
    <meta name="format-detection" content="address=no">
    <meta name="keywords" content="<?php if (!empty($this->keywords)){echo $this->keywords;}else{ echo zmf::config('siteKeywords');}?>" />
    <meta name="description" content="<?php if (!empty($this->pageDescription)){echo $this->pageDescription;}else{ echo zmf::config('siteDesc');}?>" />
    <?php assets::loadCssJs('mobile');?>  
    <link rel="shortcut icon" href="<?php echo zmf::config('baseurl');?>favicon.ico" type="image/x-icon" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>     
  </head>
  <body ontouchstart>
      <header class="ui-header ui-header-positive ui-border-b">
          <i class="ui-icon-return" ontap="history.back()"></i>
          <?php echo CHtml::link('<h1>'.zmf::subStr($this->mobileTitle,16).'</h1>',zmf::config('baseurl'),array('class'=>'text-logo'));?>
          <a class="header-right" href="javascript:;"><ul><li><span></span></li><li><span></span></li><li><span></span></li></ul></a>
      </header>
      <div class="header-select-holder">
        <div class="ui-scroller header-select">
           <ul style="transition: 0ms cubic-bezier(0.1, 0.57, 0.1, 1); -webkit-transition: 0ms cubic-bezier(0.1, 0.57, 0.1, 1); transform: translate(0px, 0px) translateZ(0px);" class="ui-list ui-list-text ui-list-link ui-border-b">
               <li class="ui-border-t" data-href="<?php echo zmf::config('baseurl');?>"><h4 class="ui-nowrap">首页</h4></li>
               <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('chengyu/story');?>"><h4 class="ui-nowrap">成语故事</h4></li>
               <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('chengyu/index');?>"><h4 class="ui-nowrap">成语大全</h4></li>
               <li class="ui-border-t" data-href="<?php echo Yii::app()->createUrl('chengyu/search');?>"><h4 class="ui-nowrap">搜索</h4></li>
           </ul>
        </div>
     </div>
    <?php echo $content; ?>
    <footer>
        <div class="ft_fix">
            <ul>
                <li><?php echo CHtml::link(zmf::config('sitename'),zmf::config('baseurl'));?></li>
                <li><?php echo CHtml::link('关于',array('siteinfo/view','code'=>'about'));?></li>
            </ul>
        </div>        
        <div id="copyright">
            <span class="copyright_text"><?php echo zmf::config('copyright');?><?php echo CHtml::link(zmf::config('sitename'),zmf::config('baseurl'));?><a href="http://www.miibeian.gov.cn" target="_blank"><?php echo zmf::config('beian');?></a></span>
        </div>
    </footer>  
    <?php assets::jsConfig('web');echo zmf::config('tongji');?>
    <script type="text/javascript">
        $('.ui-searchbar').tap(function(){
            $('.ui-searchbar-wrap').addClass('focus');
            $('.ui-searchbar-input input').focus();
        });
        $('.ui-searchbar-cancel').tap(function(){
            $('.ui-searchbar-wrap').removeClass('focus');
        });
        $('.header-right').tap(function(){
            $('.header-select-holder').toggle();
            $('.header-select-holder').tap(function(){
                if($('.header-select-holder').css('display')!='none'){
                    $('.header-select-holder').hide();
                }
            });
        });
        $('.ui-list-text li,.ui-list li,button[action=link]').tap(function(){
            var href=$(this).data('href')?$(this).data('href'):$(this).attr('href');
            if(href){
                location.href= href;
            }
        });
    </script>  
  </body>
</html>