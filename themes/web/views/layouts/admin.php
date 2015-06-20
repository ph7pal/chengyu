<?php $this->beginContent('/layouts/common'); ?>
<link rel="stylesheet" href="<?php echo Yii::app()->baseUrl ?>/common/css/newsoul.css">
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/common/js/admin.js", CClientScript::POS_END); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/common/uploadify/jquery.uploadify-3.1.min.js", CClientScript::POS_END); ?>
<div id="header">
    <?php $this->renderPartial('/common/topnav');?>
    <div class="sp-nav">
        <div class="sp-logo">
            <a href="<?php echo Yii::app()->createUrl('admin/index');?>" class="logo">                
                管理中心
            </a>
        </div>
        <div class="nav-items">
            <ul>
                <li><?php echo CHtml::link('首页', Yii::app()->homeUrl); ?></li>
                <?php
                $action=Yii::app()->getController()->getAction()->id;
                if($action=='config'){?>
                <li class="<?php echo ($this->selectType=='baseinfo')?'on':'';?>"><?php echo CHtml::link('基本', array('admin/config','type'=>'baseinfo')); ?></li>
                <li class="<?php echo ($this->selectType=='page')?'on':'';?>"><?php echo CHtml::link('分页', array('admin/config','type'=>'page')); ?></li>
                <li class="<?php echo ($this->selectType=='siteinfo')?'on':'';?>"><?php echo CHtml::link('站点', array('admin/config','type'=>'siteinfo')); ?></li>
                <li class="<?php echo ($this->selectType=='upload')?'on':'';?>"><?php echo CHtml::link('上传', array('admin/config','type'=>'upload')); ?></li>
                <li class="<?php echo ($this->selectType=='base')?'on':'';?>"><?php echo CHtml::link('运维', array('admin/config','type'=>'base')); ?></li>
                <?php }else if($action=='check'){?>
                <li class="<?php echo ($this->selectType=='posts')?'on':'';?>"><?php echo CHtml::link('文章', array('admin/check','type'=>'posts')); ?></li>
                <li class="<?php echo ($this->selectType=='comments')?'on':'';?>"><?php echo CHtml::link('评论', array('admin/check','type'=>'comments')); ?></li>
                <?php }else if($action=='report'){?>
                <li class="<?php echo ($this->selectType=='posts')?'on':'';?>"><?php echo CHtml::link('文章', array('admin/report','type'=>'posts')); ?></li>
                <li class="<?php echo ($this->selectType=='attachments')?'on':'';?>"><?php echo CHtml::link('图片', array('admin/report','type'=>'attachments')); ?></li>
                <li class="<?php echo ($this->selectType=='comments')?'on':'';?>"><?php echo CHtml::link('评论', array('admin/report','type'=>'comments')); ?></li>
                <?php }else if($action=='new'){?>
                <li class="<?php echo ($this->selectType=='posts')?'on':'';?>"><?php echo CHtml::link('文章', array('admin/new','type'=>'posts')); ?></li>
                <li class="<?php echo ($this->selectType=='comments')?'on':'';?>"><?php echo CHtml::link('评论', array('admin/new','type'=>'comments')); ?></li>
                <li class="<?php echo ($this->selectType=='attachments')?'on':'';?>"><?php echo CHtml::link('图片', array('admin/new','type'=>'attachments')); ?></li>
                <li class="<?php echo ($this->selectType=='tags')?'on':'';?>"><?php echo CHtml::link('标签', array('admin/new','type'=>'tags')); ?></li>
                <li class="<?php echo ($this->selectType=='ads')?'on':'';?>"><?php echo CHtml::link('展示', array('admin/new','type'=>'ads')); ?></li>
                <li class="<?php echo ($this->selectType=='link')?'on':'';?>"><?php echo CHtml::link('友链', array('admin/new','type'=>'link')); ?></li>
                <?php }else if($action=='manage'){?>
                <li class="<?php echo ($this->selectType=='group' || $this->selectType=='listGroup' || $this->selectType=='addgroup')?'on':'';?>"><?php echo CHtml::link('群组', array('admin/manage','type'=>'group')); ?></li>
                <li class="<?php echo ($this->selectType=='user' || $this->selectType=='listUser')?'on':'';?>"><?php echo CHtml::link('用户', array('admin/manage','type'=>'user')); ?></li>
                <?php }else if($action=='deled'){?>
                <li class="<?php echo ($this->selectType=='posts')?'on':'';?>"><?php echo CHtml::link('文章', array('admin/deled','type'=>'posts')); ?></li>
                <li class="<?php echo ($this->selectType=='comments')?'on':'';?>"><?php echo CHtml::link('评论', array('admin/deled','type'=>'comments')); ?></li>
                <li class="<?php echo ($this->selectType=='attachments')?'on':'';?>"><?php echo CHtml::link('图片', array('admin/deled','type'=>'attachments')); ?></li>
                <li class="<?php echo ($this->selectType=='tags')?'on':'';?>"><?php echo CHtml::link('标签', array('admin/deled','type'=>'tags')); ?></li>
                <?php }else if($action==''){?>
                <?php }?>
            </ul>
        </div>

    </div>

</div>

<div id="content">
    <div class="main">
        <?php echo $content; ?>   
    </div>
    <?php $this->renderPartial('/admin/aside'); ?>          
    <div class="extra"></div>
</div>
<?php $this->endContent(); ?>