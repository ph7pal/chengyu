<?php
/**
 * @filename content.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-6-23  17:30:52 
 */
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('chengyu/index'),
);
?>
<div class="login-form">
    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true
    )); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email', array('class'=>'form-control','placeholder'=>'邮箱/用户名')); ?> <?php echo $form->error($model,'email'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password', array('class'=>'form-control','placeholder'=>'请输入密码')); ?> <?php echo $form->error($model,'password'); ?>
    </div>
    <?php $cookieInfo=zmf::getCookie('checkWithCaptcha');if($cookieInfo=='1'){?>
    <div class="form-group">
        <label class="required"><?php echo zmf::t('verifyCode');?> <span class="required">*</span></label>
        <?php echo $form->textField($model,'verifyCode', array('class'=>'form-control verify-code')); ?>
        <?php echo $form->error($model,'verifyCode'); ?>
        <?php $this->widget ( 'CCaptcha', array ('showRefreshButton' => true, 'clickableImage' => true, 'buttonType' => 'link', 'buttonLabel' => zmf::t('change_verify'), 'imageOptions' => array ('alt' => zmf::t('change_verify'), 'align'=>'absmiddle'  ) ) );?>
    </div>
    <?php }?>
    <div class="checkbox"><label><?php echo $form->checkBox($model, 'rememberMe', array('class' => 'remember')); ?> <?php echo zmf::t('remember_me');?></label></div>
    <div class="form-group">
      <input type="submit" name="login" class="btn btn-success" value="登录"/>  
    </div>
    <?php $this->endWidget(); ?>
</div>