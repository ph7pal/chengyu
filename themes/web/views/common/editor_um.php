<?php 
$attri=isset($attribute)?$attribute:'content';
$upurl=isset($uploadurl) ? $uploadurl : Yii::app()->createUrl('attachments/upload',array('imgtype'=>$uptype,'id'=>$logid,'imgsize'=>$imgsize));
?>
<link href="<?php  echo Yii::app()->baseUrl.'/umeditor/themes/default/css/umeditor.css';?>" type="text/css" rel="stylesheet">
<script>
    URL= window.UEDITOR_HOME_URL||"<?php echo Yii::app()->baseUrl;?>/umeditor/";
    (function(){window.UMEDITOR_CONFIG={UMEDITOR_HOME_URL:URL}})();
</script>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/umeditor/umeditor.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/umeditor/lang/zh-cn/zh-cn.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/common/uploadify/jquery.uploadify.min.js', CClientScript::POS_END);
?>
<textarea id="<?php echo CHtml::activeId($model,$attri);?>" name="<?php echo CHtml::activeName($model,$attri);?>" style="width:600px;height:200px;">
<?php echo zmf::text(array('action'=>'edit','encode'=>'yes'),$content);?>
</textarea>
<input id="textareaid" type="hidden" value="<?php echo CHtml::activeId($model,$attri);?>"/>
<script>
var tipImgUploadUrl="<?php echo $upurl;?>";
  $(function(){
    myeditor=UM.getEditor('<?php echo CHtml::activeId($model,$attri);?>', {
       //UMEDITOR_HOME_URL : URL, 
       toolbar: ["bold","italic","underline","link","unlink","image"],
       lang:'zh-cn', //语言
       wordCount:false, //关闭字数统计       
       initialFrameWidth:620, //宽度
       initialFrameHeight:200, //高度
       zIndex:0,
       focus:true,
       pasteplain:true,
       elementPathEnabled : false,       
       contextMenu:[],       
       autoHeightEnabled:true,
       initialStyle:'.edui-editor-body .edui-body-container p{font-size:13px;line-height:1.3em;margin:0px}.edui-container .edui-toolbar{z-index:0 !important}',
       removeFormatTags:'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var',
       removeFormatAttributes:'class,style,lang,width,height,align,hspace,valign',
       imageScaleEnabled:false,
       dropFileEnabled:false,
       indentValue:'0em',
       textarea:'<?php echo CHtml::activeId($model,$attri);?>',
       fileFieldName:'filedata',
       imageUrl:tipImgUploadUrl,
       imagePath:'',
       loadUploadify:false,
       perAddImgNum:zmf.perAddImgNum,
       allowImgPerSize:zmf.allowImgPerSize,
       allowImgTypes:zmf.allowImgTypes,
       currentSessionId:zmf.currentSessionId       
   });   
   myeditor.addListener("keyup",function(){
            var inputstr=myeditor.getContentTxt();   
            if(inputstr!=''){
                $(window).bind('beforeunload',function(){
                    return '您输入的内容可能未保存，确定离开此页面吗？';
                });
                <?php if(Yii::app()->getController()->id=='question' && in_array(Yii::app()->getController()->getAction()->id,array('create'))){?>
                searchTimeOut(event,'new-question');
                <?php }?>
            }
          });  
  });
</script>