<!DOCTYPE HTML>
<html lang="zh-CN">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="MSThemeCompatible" content="Yes" />
        <meta http-equiv="mobile-agent" content="format=html5; url=<?php echo $this->canonical;?>">
        <meta name="robots" content="all" />        
        <meta name="renderer" content="webkit">
        <meta name="keywords" content="<?php if (!empty($this->keywords)){echo $this->keywords;}else{ echo zmf::config('siteKeywords');}?>" />
        <meta name="description" content="<?php if (!empty($this->pageDescription)){echo $this->pageDescription;}else{ echo zmf::config('siteDesc');}?>" />
        <meta name="MSSmartTagsPreventParsing" content="True" />        
        <base href="<?php echo zmf::config('baseurl');?>" />
        <meta name="application-name" content="<?php echo zmf::config('sitename');?>" />
        <meta name="msapplication-tooltip" content="<?php echo zmf::config('sitename');?>" />
        <meta name="msapplication-task" content="name=<?php echo zmf::config('sitename');?>;action-uri=<?php echo zmf::config('baseurl');?>;icon-uri=<?php echo zmf::config('baseurl');?>favicon.ico" />
        <link rel="shortcut icon" href="<?php echo zmf::config('baseurl');?>favicon.ico" type="image/x-icon" />
        <link rel="canonical" href="<?php echo $this->canonical;?>">
        <?php assets::loadCssJs('web');?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <body>       
        <?php echo $content; ?>
        <?php $this->renderPartial('/common/footer'); ?>
    </body>
</html>