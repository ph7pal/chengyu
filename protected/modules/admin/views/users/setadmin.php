<?php
$this->renderPartial('/users/_nav');
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admins-powers-form',
	'enableAjaxValidation'=>true,
)); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="form-group">
	<?php echo $form->labelEx($model,'uid'); ?>
        <?php echo $form->textField($model,'uid',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
        <p class="help-block">用户的ID（不是手机号、用户名）</p>
        <?php echo $form->error($model,'uid'); ?>            		
    </div>
    <label>后台用户操作权限</label>
   <ul class="list-group">
        <?php $powers=  Admins::getDesc('super');foreach($powers as $key=>$val){
            echo "<li style='color:red' class='list-group-item'><span>{$val['desc']}</span></li>";
            foreach($val['detail'] as $k=>$v){
               echo "<li class='list-group-item'><label class='checkbox-inline'><span>&nbsp;&nbsp;<input type='checkbox' name='powers[]' value='{$k}'";
               if(in_array('all',$mine)){
                   echo "checked='checked'";
                }elseif(in_array($k,$mine)){
                   echo "checked='checked'"; 
                }
               echo "/>{$v}</label></li>";  
            }
        }?>
    </ul> 
    <?php echo CHtml::submitButton('提交',array('class'=>'btn btn-primary')); ?> 
<?php $this->endWidget(); ?>
</div><!-- form -->