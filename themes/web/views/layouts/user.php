<?php $this->beginContent('/layouts/main'); ?>
<div class="wrapper">
    <div id="content">
        <div class="main">
            <ul class="nav nav-tabs">
                <li class="<?php echo (!$_GET['type'] && Yii::app()->getController()->getAction()->id == 'index') ? 'active' : ''; ?>"><?php echo CHtml::link('首页', array('users/index','id'=>$this->uid)); ?></li>
                <li class="<?php echo ($_GET['type'] == 'posts') ? 'active' : ''; ?>"><?php echo CHtml::link('文章('.$this->userInfo['posts'].')', array('users/index','id'=>$this->uid,'type'=>'posts')); ?></li>
                <li class="<?php echo ($_GET['type'] == 'answer') ? 'active' : ''; ?>"><?php echo CHtml::link('回答('.$this->userInfo['answers'].')', array('users/index','id'=>$this->uid,'type'=>'answer')); ?></li>
                <li class="<?php echo ($_GET['type'] == 'tips') ? 'active' : ''; ?>"><?php echo CHtml::link('点评('.$this->userInfo['tips'].')', array('users/index','id'=>$this->uid,'type'=>'tips')); ?></li>
                <li class="<?php echo ($_GET['type'] == 'yueban') ? 'active' : ''; ?>"><?php echo CHtml::link('约伴', array('users/index','id'=>$this->uid,'type'=>'yueban')); ?></li>
                <!--li class="<?php echo (Yii::app()->getController()->getAction()->id == 'images') ? 'active' : ''; ?>"><?php echo CHtml::link('图片', array('users/images','id'=>$this->uid)); ?></li-->
                <li class="<?php echo (Yii::app()->getController()->getAction()->id == 'favorites') ? 'active' : ''; ?>"><?php echo CHtml::link('收藏', array('users/favorites','id'=>$this->uid)); ?></li>
                <?php if($this->mySelf=='yes'){?>
                <li class="<?php echo (Yii::app()->getController()->getAction()->id == 'notice') ? 'active' : ''; ?>"><?php echo CHtml::link('提醒', array('users/notice')); ?></li>
                <!--li class="<?php echo (Yii::app()->getController()->getAction()->id == 'config') ? 'active' : ''; ?>"><?php echo CHtml::link('设置', array('users/config')); ?></li-->
                <li class="dropdown <?php echo in_array(Yii::app()->getController()->getAction()->id ,array('config','update')) ? 'active' : ''; ?>"><a href="#" id="order-poi-type" class="dropdown-toggle" data-toggle="dropdown" >设置 <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php echo CHtml::link('基本设置', array('users/config')); ?></li>
                        <li><?php echo CHtml::link('信息修改', array('users/update','type'=>'info')); ?></li>
                        <li><?php echo CHtml::link('密码修改', array('users/update','type'=>'passwd')); ?></li>
                    </ul>
                </li>
                <?php }?>
            </ul>
        <?php echo $content; ?>   
        </div>
        <?php $this->renderPartial('/users/aside'); ?>  
        <div class="extra"></div>
    </div>
</div>
<?php $this->endContent(); ?>            