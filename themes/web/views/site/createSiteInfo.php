<?php 
$uploadurl=Yii::app()->createUrl('attachments/upload',array('type'=>'siteinfo','imgsize'=>600));
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'site-info-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>16,'maxlength'=>16,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	<div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php $this->renderPartial('/common/editor_bd', array('model' => $model,'content' => $model->content,'uploadurl'=>$uploadurl)); ?>
            <?php echo $form->error($model,'content'); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->