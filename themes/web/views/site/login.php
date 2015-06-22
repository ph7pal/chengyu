<div class="main-page">
<?php $this->renderPartial('//ads/ads',array('position'=>'logpage','type'=>'flash'));?>    
</div>
<div class="aside-page row">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">        
        <div class="panel panel-success">
            <div class="panel-heading" role="tab"><h3 class="panel-title" data-toggle="collapse"><a data-toggle="collapse" data-parent="#accordion" href="#collapseLogin" aria-expanded="true" aria-controls="collapseLogin"><?php echo $this->loginTitle;?></a></h3></div>
            <div id="collapseLogin" class="panel-collapse collapse <?php echo $from=='login' ? 'in' :'';?>" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
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
                      <input type="submit" name="login" class="btn btn-success" value="<?php echo $this->loginTitle;?>"/>
                      <?php echo CHtml::link(zmf::t('register_link'),'javascript:;',array('onclick'=>"collPanel('login')"));?>
                    </div>
                  <?php $this->endWidget(); ?>                    
                  <div class="more-awesome"><span>快捷登录</span></div>
                  <div class="quick-login-bar">
                    <?php echo Users::quickLoginBar('login');?>
                  </div>
                </div>
          </div>
        </div>
        <?php $this->renderPartial('/site/addUser',array('model'=>$modelUser,'from'=>$from));?>
    </div>
</div>
<script>
function collPanel(t){
    if(t=='reg'){
        $('#collapseLogin').collapse('show');
        $('#collapseReg').collapse('hide');
    }else{
        $('#collapseLogin').collapse('hide');
        $('#collapseReg').collapse('show');
    }
}
</script>