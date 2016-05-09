<div class="well well-sm" id="list-item-<?php echo $data->id;?>">
	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::link($data->userInfo->truename,array('user/view','id'=>$data->uid),array('target'=>'_blank')); ?>
        <span class="pull-right">
            <?php if($data->status==Feedback::STATUS_STAYCHECK){
             echo CHtml::ajaxLink(
         '标记为已处理',
         Yii::app()->createUrl("admin/feedback/manage"),
         array(
             'type'=>'POST',
             'success' => "function( data ){data = eval('('+data+')');if(data['status']){ $('#list-item-".$data->id."').fadeOut();}else{alert(data['msg']);}}",
             'data'=>array('id'=>$data->id,'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken)
        ),
         array('href' => Yii::app()->createUrl( "admin/feedback/manage")));            
        }else{echo '已处理';}?>
        </span>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('contact')); ?>:</b>
	<?php echo CHtml::encode($data->contact); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('appinfo')); ?>:</b>
	<?php echo CHtml::encode($data->appinfo); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('sysinfo')); ?>:</b>
	<?php echo CHtml::encode($data->sysinfo); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />        
	<b><?php echo CHtml::encode($data->getAttributeLabel('ip')); ?>:</b>
	<?php echo long2ip($data->ip); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('cTime')); ?>:</b>
	<?php echo zmf::time($data->cTime); ?>
	<br />
</div>