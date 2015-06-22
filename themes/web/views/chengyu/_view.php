<?php
/* @var $this ChengyuController */
/* @var $data Chengyu */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hash')); ?>:</b>
	<?php echo CHtml::encode($data->hash); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title_tw')); ?>:</b>
	<?php echo CHtml::encode($data->title_tw); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pinyin')); ?>:</b>
	<?php echo CHtml::encode($data->pinyin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('firstChar')); ?>:</b>
	<?php echo CHtml::encode($data->firstChar); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yufa')); ?>:</b>
	<?php echo CHtml::encode($data->yufa); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('hits')); ?>:</b>
	<?php echo CHtml::encode($data->hits); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cTime')); ?>:</b>
	<?php echo CHtml::encode($data->cTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>