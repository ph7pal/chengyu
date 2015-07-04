<?php

/**
 * @filename story.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-7-1  19:38:18 
 */
?>
<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('index'),
    '词语故事'
);
?>
<div class="wrap-container">
<div class="filterBar">
    <span class="pull-left">
        <?php echo $_GET['filter']!='' ? CHtml::link('全部',array('chengyu/story')):'全部';?>
        <?php $_attr=array('chengyu/story','filter'=>1,'order'=>$_GET['order']);$_attr=  array_filter($_attr);echo $_GET['filter']==1 ? '原意' : CHtml::link('原意',$_attr);?>
        <?php $_attr=array('chengyu/story','filter'=>2,'order'=>$_GET['order']);$_attr=  array_filter($_attr);echo $_GET['filter']==2 ? '新解' : CHtml::link('新解',$_attr);?>
    </span>
    <span class="pull-right">
        <?php $_attr=array('chengyu/story','order'=>1,'filter'=>$_GET['filter']);$_attr=  array_filter($_attr);echo $_GET['order']==1 ? '时间' : CHtml::link('时间',$_attr);?>
        <?php $_attr=array('chengyu/story','order'=>2,'filter'=>$_GET['filter']);$_attr=  array_filter($_attr);echo $_GET['order']==2 ? '热门' : CHtml::link('热门',$_attr);?>
    </span>
</div>
<div class="clearfix"></div>
<?php if(!empty($posts)){?>
<ul class="ui-list ui-list-pure ui-border-tb">
<?php foreach($posts as $_xinjie){?>
    <li class="ui-border-t ui-form-item-link" data-href="<?php echo Yii::app()->createUrl('chengyu/view',array('id'=>$_xinjie['id']));?>">
        <p><?php echo CHtml::link($_xinjie['title'],array('chengyu/view','id'=>$_xinjie['id']),array('target'=>'_blank'));?></p>
        <p class="ui-nowrap-multi"><?php echo zmf::subStr($_xinjie['content'],140);?></p>
    </li>
<?php }?>
</ul>
<?php }?>
<?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
</div>