<div class="panel panel-success">
    <div class="panel-heading" role="tab"><h3 class="panel-title" data-toggle="collapse"><a data-toggle="collapse" data-parent="#accordion" href="#collapseReg" aria-expanded="true" aria-controls="collapseLogin"><?php echo $this->regTitle;?></a></h3></div>
    <div id="collapseReg" class="panel-collapse collapse <?php echo $from=='reg' ? 'in' :'';?>" role="tabpanel">        
        <div class="panel-body">
            <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'users-addUser-form',
                    'enableAjaxValidation'=>false,
            )); ?>
            <?php echo $form->errorSummary($model); ?>
            <div class="form-group">
            <?php echo $form->labelEx($model,'truename'); ?>
            <?php echo $form->textField($model,'truename',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'truename'); ?>
            </div>
            <div class="form-group">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'email'); ?>
            </div>
            <div class="form-group">
            <?php echo $form->labelEx($model,'password'); ?>
            <?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'password'); ?>
            </div>
            <div class="form-group">
            <?php echo CHtml::submitButton($this->regTitle,array('class'=>'btn btn-success')); ?>
            <?php echo CHtml::link(zmf::t('login_link'),'javascript:;',array('onclick'=>"collPanel('reg')"));?>
            </div>
            <?php $this->endWidget(); ?>
            <div class="more-awesome"><span>快捷注册</span></div>
            <div class="quick-login-bar">
                <?php echo Users::quickLoginBar('reg');?>
            </div>
        </div>
    </div>
</div>