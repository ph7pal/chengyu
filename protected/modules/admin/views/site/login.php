<div class="col-md-4 visible-md visible-lg"></div>
<div class="col-md-4 col-xs-12" style="margin-top: 100px;">
    <div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">登录</h3>
        </div>
        <div class="panel-body">
    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true
)); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->textField($model,'username', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>
    <div class="form-group">    
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password', array('class'=>'form-control')); ?> <?php echo $form->error($model,'password'); ?>
    </div>
    <?php $cookieInfo=zmf::getCookie('checkWithCaptcha');if(intval($cookieInfo)>0){?>
    <div class="form-group">
        <label>验证码</label>
        <div class="clearfix"></div>
        <div class="row">
        <div class="col-sm-6 col-xs-6">
        <?php echo $form->textField($model,'verifyCode', array('class'=>'form-control')); ?>
        </div>
        <div class="col-sm-6 col-xs-6">
        <?php echo $form->error($model,'verifyCode'); ?>
        <?php $this->widget ( 'CCaptcha', array ('showRefreshButton' => true, 'clickableImage' => true, 'buttonType' => 'link', 'buttonLabel' => '点击刷新', 'imageOptions' => array ('alt' => '点击刷新验证码', 'align'=>'absmiddle'  ) ) );?>
        </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php }?>    
    <div class="form-group">
      <input type="submit" name="login" class="btn btn-danger btn-block" value="登录"/>
    </div>
  <?php $this->endWidget(); ?>
      </div>
    </div>
</div>
<div class="col-md-4 visible-md visible-lg"></div>
