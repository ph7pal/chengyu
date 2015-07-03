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
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>     
  </head>
  <body ontouchstart>
      <header class="ui-header ui-header-positive ui-border-b" style="height: 120px">
          <?php echo CHtml::link('<h1>'.zmf::config('sitename').'</h1>',zmf::config('baseurl'),array('class'=>'text-logo'));?>
      </header>
    <?php echo $content; ?>
    <?php $this->renderPartial('/common/footer');?>
  </body>
</html>