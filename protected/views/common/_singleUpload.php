<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/common/admin/ajaxfileupload.js", CClientScript::POS_END);?>
<div class="input-group">
    <input type="file" id="<?php echo CHtml::activeId($model,$fieldName);?>_holder" name="<?php echo CHtml::activeId($model,$fieldName);?>_holder" class="form-control"/>
    <span class="input-group-btn">
        <button class="btn btn-default" onclick="ajaxFileUpload()" type="button">点击上传</button>
    </span>
</div><!-- /input-group -->
<div id="<?php echo CHtml::activeId($model,$fieldName);?>_upload">
<?php if($attachid>0){
    $attachinfo=  Attachments::getOne($attachid);
    if($attachinfo){
        echo '<div id="uploadAttach'.$attachid.'"><img src="'.zmf::getUrl($attachinfo,170).'"/>'
                .CHtml::link('删除','javascript:;',array('onclick'=>"delUploadImg({$attachid},'".CHtml::activeId($model,$fieldName)."')",'confirm'=>'不可恢复，确认删除？'))
                . '</div>';
    }
}
?>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
function ajaxFileUpload(){
        $.ajaxFileUpload({
                url:'<?php echo Yii::app()->createUrl('attachments/upload',array('id'=>$keyid,'type'=>$type,'fileholder'=>CHtml::activeId($model,$fieldName).'_holder','imgsize'=>170));?>',
                fileElementId:'<?php echo CHtml::activeId($model,$fieldName);?>_holder',
                type:'post',
                data: {'PHPSESSID':'<?php echo Yii::app()->session->sessionID;?>', 'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken;?>'},
                dataType: 'json',
                success: function (res){
                    if(res['status']==1){ 
                      <?php if($multi){?>
                      	var img="<div class='col-xs-2 col-sm-2 no-padding' id='uploadAttach"+res['attachid']+"'><p><img src='"+res['imgsrc']+"' class='img-responsive'/><input type='hidden' name='uploadAttach[]'  value='"+res['attachid']+"'/></p><p   class='text-center'><a href='javascript:;' onclick='delUploadImg("+res['attachid']+")'>删除</a></p></div>";
                      	$('#<?php echo CHtml::activeId($model,$fieldName);?>_upload').append(img);
                      $('#<?php echo CHtml::activeId($model,$fieldName);?>').val(res['attachid']);
                      <?php }else{?>
                      	var img="<div class='col-xs-2 col-sm-2 no-padding' id='uploadAttach"+res['attachid']+"'><p><img src='"+res['imgsrc']+"' class='img-responsive'/></p><p class='text-center'><a href='javascript:;' onclick='delUploadImg("+res['attachid']+")'>删除</a></p></div>";
                      $('#<?php echo CHtml::activeId($model,$fieldName);?>_upload').html(img);
                      $('#<?php echo CHtml::activeId($model,$fieldName);?>').val(res['attachid']);
                      <?php }?>
                    }else{
                      alert(res['msg']);
                    }
                }
        })
}
</script>   