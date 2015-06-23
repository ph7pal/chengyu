<?php
/**
 * @filename content.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-6-23  17:30:52 
 */
?>
<h1><?php echo $info['title'];?></h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'chengyu-content-form',
    'enableAjaxValidation'=>false,
)); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-default')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->