<ol class="breadcrumb">
    <?php if(in_array(Yii::app()->getController()->id,array('posts','map'))){?>
        <?php 
        echo ' <li>'.CHtml::link(zmf::t('indexPage'),Yii::app()->baseUrl).'</li>';
        if(!empty($colinfo)){
            echo ' <li>'.CHtml::link($colinfo['title'],array('posts/all','colid'=>$colinfo['id'])).'</li>';
        }             
        echo ' <li>'.CHtml::link(zmf::t('allTags'),array('tags/all')).' </li>';
        ?>        
        <?php if(!empty($taginfo)){        
            echo ' <li> '.CHtml::link($taginfo['title'],array('posts/all','tagid'=>$taginfo['id'])).'</li>';
        }?>
        <?php if(!empty($postInfo)){
            echo ' <li> '.CHtml::link($postInfo['title'],array('posts/index','id'=>$postInfo['id'])).'</li>';
        }?>
    <?php }?>
</ol>