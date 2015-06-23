<?php
/**
 * @filename ci.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-6-23  15:19:19 
 */
?>
<h1><?php echo $info['title'];?></h1>
<div class="form-group">
    <?php if($type=='tongyi'){?>
    <label>添加同义词</label>
    <?php }else{?>
    <label>添加反义词</label>
    <?php }?>
    <?php $this->widget('CAutoComplete',
        array(
           'name'=>'suggest_word',
           'url'=>array('ajax/suggest'),
           'max'=>10, //specifies the max number of items to display
           'minChars'=>2,
           'delay'=>500, //number of milliseconds before lookup occurs
           'matchCase'=>false, //match case when performing a lookup?
           'htmlOptions'=>array('class'=>'form-control'),
           'methodChain'=>".result(function(event,item){ addWord('{$info['id']}',item,'{$type}');})",
           ));
    ?>
</div>
<div id="chengyu-ci-holder">
    <?php if(!empty($relations)){foreach($relations as $relation){?>
    <div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="delWord('<?php echo $info['id'];?>','<?php echo $relation['tocid'];?>','<?php echo $relation['classify'];?>')"><span aria-hidden="true">&times;</span></button><?php echo $relation->chengyuInfo['title'];?></div>
    <?php }}?>
</div>
<script>
function addWord(keyid,item,type){
    $.post(zmf.addCiUrl, {keyid: keyid, fromid: item[1],type: type,YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        result = eval('(' + result + ')');
        if (result['status'] == 1) {
            var html='<div class=\"alert alert-success alert-dismissible\" role=\"alert\">'+item[0]+'<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\" onclick="delWord(\''+keyid+'\',\''+item[1]+'\',\''+type+'\')"><span aria-hidden=\"true\">&times;</span></button></div>';$('#chengyu-ci-holder').append(html);$('#suggest_word').val('');
        } else {
            $('#suggest_word').val('');
            alert(result['msg']);
        }
    });
}
function delWord(keyid,fromid,type){
    $.post(zmf.delCiUrl, {keyid: keyid, fromid: fromid,type: type,YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        result = eval('(' + result + ')');
        if (result['status'] == 1) {
        } else {
            alert('删除失败，请刷新');
        }
    });
}
</script>