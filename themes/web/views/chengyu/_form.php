<?php
/* @var $this ChengyuController */
/* @var $model Chengyu */
/* @var $form CActiveForm */
?>

<div class="col-sm-12 col-xs-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'chengyu-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'yufa'); ?>
		<?php echo $form->textField($model,'yufa',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yufa'); ?>
	</div>
	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '保存',array('class'=>'btn btn-default')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->