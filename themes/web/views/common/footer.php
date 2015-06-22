<div class="clearfix"></div>
<footer class="footer">
    <div class="container">
      <div class="row footer-top">
        <?php $links=Link::allLinks();if(!empty($links)){?><p><h4><?php echo zmf::t('friendlyLink');?></h4><?php foreach($links as $link){?><a href="<?php echo $link['url'];?>" target="_blank"><?php echo $link['title'];?></a><?php }?></p><?php }?>
      <p class="text-center"><a href="<?php echo zmf::config('domain');?>" target="_blank"><?php echo zmf::config('sitename');?></a><?php echo zmf::config('version');?> <?php echo zmf::config('copyright');?> <?php echo zmf::config('beian');?>&nbsp;<?php echo CHtml::link('关于本站',array('siteinfo/view','code'=>'about'));?></p>
      </div>
      <?php assets::jsConfig('web');echo zmf::config('tongji');?>  
    </div>
</footer>