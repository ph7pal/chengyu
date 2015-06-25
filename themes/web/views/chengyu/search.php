<?php
$this->breadcrumbs = array(
    CHtml::link('首页', zmf::config('baseurl')),
    '词语大全' => array('index'),
    $this->searchKeywords ? '【'.$this->searchKeywords.'】搜索结果' : '搜索',
);
?>
<?php if(!$this->searchKeywords){?>
<div class="alert alert-danger">
    <h4>请输入搜索关键词</h4>
</div>
<?php }elseif(empty($posts)){?>
<div class="alert alert-danger">
    <h4>暂无【<?php echo $this->searchKeywords;?>】的搜索结果</h4>
    <p class="help-block">您可以尝试简化搜索关键词</p>
</div>
<?php }else{ ?>
<h4>【<?php echo $this->searchKeywords;?>】的搜索结果</h4>
<table class="table table-hover">
<?php foreach($posts as $post){?>
<?php $this->renderPartial('/chengyu/_view',array('data'=>$post));?>
<?php } ?>
</table>
<?php } ?>