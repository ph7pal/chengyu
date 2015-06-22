<?php
echo '<div class="input-group">';
echo $form->textField($model, 'fanyici[]', array('class'=>'form-control'));
echo '<span class="input-group-addon">'.CHtml::link('<span class="icon-plus"></span>', 'javascript:', array('onclick' => 'nickName()', 'class' => 'addcut_btn')).'</span></div>';