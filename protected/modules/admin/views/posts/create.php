<?php

/**
 * @filename create.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-12-18  16:36:08 
 */
?>
<style>
    h1{
        font-size: 16px;
        text-indent: 2em
    }
    h2,h3,h4,h5,h6{
        font-size: 14px;
        font-weight: 700;
        text-indent: 2em
    }
    blockquote {
        padding: 5px 20px;
        font-size: inherit;
    }
    .add-post-form{
        width: 1000px;
        margin: 50px auto;
        padding: 0 10px  100px
    }
    .tags-holder{
        background: #fff;
        padding: 10px 5px;
        border:1px solid #ccc
    }
    .tags-holder .tag-item{
        margin-right: 5px;
        word-break: keep-all;
        display: inline-block;
        padding: 2px 5px;
        margin-bottom: 10px;
        border: 1px solid #fff
    }
    .tags-holder .tag-item a{
        text-decoration: none;
        color: #333
    }
    .tags-holder .tag-item .fa-check{
        color: #fff
    }
    .tags-holder .tag-item:hover{
        border: 1px solid green
    }
    .tags-holder .tag-item:hover>a>i{
        color: #333
    }
    .tags-holder .active{
        background: green;        
    }
    .tags-holder .active a{
        color: #fff
    }
    .tags-holder .active:hover>a>i{
        color: #fff
    }
    .actions-fixed{
        position: fixed;
        top: 15px;
        left: 10px;
    }
    .map-holder{
        border: 1px solid #f8f8f8;
        margin-bottom: 15px;
        display: none
    }
    .map-holder .input-group-addon,.map-holder .form-control,.map-holder .btn{
        border-radius: 0
    }
    .add-map-tips{
        cursor: pointer
    }
    .editor-holder{
        position: relative
    }
    .editor-holder [class^="col-xs-"]{
        border:1px solid #f2f2f2;
        padding: 0 10px;
        word-break: break-all
    }
    .editor-holder .row{
        margin-left: 0;
        margin-right: 0;
    }
    .editor-holder .attach-desc{
        color: #ccc;
        text-indent:0 !important
    }
    .editor-extra-funcs{
        position: fixed;
        bottom: 15px;
        left: 50%;
        margin-left: -490px;
    }
    
</style>
<?php 
$uploadurl=Yii::app()->createUrl('attachments/upload',array('type'=>'posts','imgsize'=>600));
$selectedTagids=array_keys(CHtml::listData($postTags, 'id', ''));
?>
<div class="actions-fixed">
    <div class="btn-group" role="group">
        <?php echo CHtml::link('<i class="fa fa-reply"></i> 管理中心',array('index/index'),array('class'=>'btn btn-default'));?>
        <?php echo CHtml::link('<i class="fa fa-home"></i> 站点首页',  zmf::config('baseurl'),array('class'=>'btn btn-default'));?>
    </div>
</div>
<div class="add-post-form">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'posts-form',
            'enableAjaxValidation'=>false,
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control','placeholder'=>'文章标题')); ?>
    </div>
    <div class="form-group">
        <?php echo $form->dropDownlist($model,'zazhi', Zazhi::listTitles(),array('class'=>'form-control','empty'=>'--请选择所属杂志--')); ?>
    </div>
    <div class="form-group editor-holder">
        <?php $this->renderPartial('//common/editor_um', array('model' => $model,'content' => $model->content!='' ? $model->content.'<p><br/></p>' : '','uploadurl'=>$uploadurl,'editorWidth'=>980,'editorHeight'=>400)); ?>        
    </div>    
    <?php $this->renderPartial('/posts/addMapImg',array('model'=>$model));?>
    <div class="tags-holder form-group">
        <?php if(!empty($tags)){?>
        <?php foreach ($tags as $tagid=>$tagname){$_selected=in_array($tagid,$selectedTagids);?>
        <span class="tag-item <?php echo $_selected ? 'active' : '';?>">
            <?php echo CHtml::link($tagname.' <i class="fa fa-check"></i>'.($_selected ? '<input type="hidden" name="tags[]" value="'.$tagid.'"/>': ''),'javascript:;',array('action'=>'select-tag','action-data'=>$tagid));?>
        </span>
        <?php }?>
        <span class="tag-item"><?php echo CHtml::link('<i class="fa fa-plus"></i> 新增',array('tags/create'),array('target'=>'_blank'));?></span>
        <?php }else{?>
        <p class="help-block"><i class="fa fa-exclamation-circle"></i> 还没有创建任何标签，建议先<?php echo CHtml::link('创建',array('tags/create'));?>！</p>
        <?php }?>
    </div> 
    <div class="checkbox">        
        <label class="checkbox-inline">        
            <?php echo $form->checkBox($model, 'isFaceimg'); ?> 是否仅封面图
        </label>       
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-xs-9"></div>
            <div class="col-xs-2">
                <div class="form-group pull-right">
                    <?php echo $form->dropDownlist($model,'status', Posts::exStatus('admin'),array('class'=>'form-control')); ?>
                </div>
            </div>
            <div class="col-xs-1">
                <?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-success pull-right','id'=>'add-post-btn')); ?>
            </div>
        </div>
    </div>
    <div class="btn-group editor-extra-funcs" role="group">
        <?php echo CHtml::link('<i class="fa fa-table"></i> 分栏','javascript:;',array('class'=>'btn btn-primary','onclick'=>'addColumn()','id'=>'addColumn-btn','title'=>'插入分栏'));?>
        <?php echo CHtml::link('<i class="fa fa-user"></i> 图描','javascript:;',array('class'=>'btn btn-primary','onclick'=>'addImgDesc()','title'=>'添加图片描述'));?>
        <?php echo CHtml::link('<i class="fa fa-user"></i> 编注','javascript:;',array('class'=>'btn btn-primary','onclick'=>'addEditorBox()','id'=>'addUser-btn','title'=>'插入编辑者名称'));?>
        <?php echo CHtml::link('<i class="fa fa-quote-left"></i> 引用','javascript:;',array('class'=>'btn btn-primary','onclick'=>'addQuoteBox()','id'=>'addQuote-btn','title'=>'插入引用信息'));?>
        <?php echo CHtml::link('<i class="fa fa-info"></i> 提示','javascript:;',array('class'=>'btn btn-primary','onclick'=>'addTipsBox()','id'=>'addTips-btn','title'=>'插入Tips'));?>
        <?php echo CHtml::link('<i class="fa fa-map-marker"></i> 位置','javascript:;',array('class'=>'btn btn-primary','onclick'=>"$('.map-holder').slideDown();loadScript();",'id'=>'addMap-btn','title'=>'插入位置信息'));?>
        <?php echo CHtml::link('<i class="fa fa-video-camera"></i> 视频','javascript:;',array('class'=>'btn btn-primary','onclick'=>'addVideo()','id'=>'addVideo-btn','title'=>'插入视频'));?>
    </div>
    <style>
        .popover{
            max-width: 640px
        }
        .column-holder{
            width: 300px;
            padding: 0 10px
        }
        .column-item{
            width: 100px;
            margin-right: 40px;
            display: inline-block
        }
        .column-item:hover{
            cursor: pointer
        }
        .column-item [class^="col-xs-"]{
            border:1px solid #f2f2f2;
            min-height: 80px;
            z-index: 0
        }
        .column-item .active{
            background: #f2f2f2
        }
        .chapter-author{
            text-align: right;
            color: #ccc
        }
    </style>    
    <?php $this->endWidget(); ?>    
</div><!-- form -->
<script>
    function addColumn(){
        var html = '<div class="column-holder"><div class="column-item" onclick="insertColumn(\'1\')"><div class="row"><div class="col-xs-12 active"></div></div><p class="text-center">1</p></div><div class="column-item" onclick="insertColumn(\'1-1\')"><div class="row"><div class="col-xs-6 active"></div><div class="col-xs-6"></div></div><p class="text-center">1：1</p></div><div class="column-item" onclick="insertColumn(\'1-1-1\')"><div class="row"><div class="col-xs-4 active"></div><div class="col-xs-4"></div><div class="col-xs-4"></div></div><p class="text-center">1：1：1</p></div><div class="column-item" onclick="insertColumn(\'1-1-1-1\')"><div class="row"><div class="col-xs-3 active"></div><div class="col-xs-3"></div><div class="col-xs-3"></div><div class="col-xs-3"></div></div><p class="text-center">1：1：1：1</p></div><div class="column-item" onclick="insertColumn(\'1-3\')"><div class="row"><div class="col-xs-3 active"></div><div class="col-xs-9"></div></div><p class="text-center">1：3</p></div><div class="column-item" onclick="insertColumn(\'3-1\')"><div class="row"><div class="col-xs-9 active"></div><div class="col-xs-3"></div></div><p class="text-center">3：1</p></div></div>';
        var dom=$('#addColumn-btn');
        var hack=dom.attr("data-original-title");
        dom.popover({
            container: 'body',
            title:'添加分栏',
            content:html,
            placement:'top',
            html:true
        });
        if(!hack){
            dom.popover('show');
        }
    }
    function insertColumn(code){
        var arr=code.split("-");
        var len=arr.length;
        if(len<1){
            return false;
        }
        var t=0;
        for (var i=0;i<len ;i++ ) {
            t+=parseInt(arr[i]);
        }
        if(12%t!==0){
            return false;
        }
        var sep=12/t;
        var html='<div class="row">';
        for (var j=0;j<len ;j++ ) {
            var col=arr[j]*sep;
            html+='<div class="col-xs-'+col+' col-sm-'+col+'"><p><br/></p></div>';
        }
        html+='</div><div class="clearfix"></div><p><br/></p>';
        myeditor.execCommand("inserthtml", html);
        $('#addColumn-btn').popover('hide');
    }
    function addEditorBox(){
        var html = '<div class="form-group"><input type="text" id="the-editor-name" class="form-control"/><p class="help-block">页脚显示的编辑名称，如“编辑 大飞”</p></div><div class="form-group"><button type="button" class="btn btn-primary" onclick="addEditorName()">插入</button></div>';
        var dom=$('#addUser-btn');
        var hack=dom.attr("data-original-title");
        dom.popover({
            container: 'body',
            title:'编辑名称',
            content:html,
            placement:'top',
            html:true
        });
        if(!hack){
            dom.popover('show');
        }
    }
    function addImgDesc(){
        var html='<p class="attach-desc"><br/></p>';
        myeditor.execCommand("inserthtml", html);
    }
    function addEditorName(){
        var n=$('#the-editor-name').val();
        if(!n){
            dialog({msg:'请填写内容'});
            return false;
        }
        myeditor.execCommand("inserthtml", '<p class="chapter-author">'+n+'</p>');
        $('#addUser-btn').popover('hide');
    }
    function addQuoteBox(){
        var html = '<div class="form-group"><textarea id="the-quote-text" class="form-control" rows=5 style="width:320px"></textarea><p class="help-block">插入引用信息</p></div><div class="form-group"><button type="button" class="btn btn-primary" onclick="addQuote()">插入</button></div>';
        var dom=$('#addQuote-btn');
        var hack=dom.attr("data-original-title");
        dom.popover({
            container: 'body',
            title:'编辑引用信息',
            content:html,
            placement:'top',
            html:true
        });
        if(!hack){
            dom.popover('show');
        }
    }
    function addQuote(){
        var n=$('#the-quote-text').val();
        if(!n){
            dialog({msg:'请填写内容'});
            return false;
        }
        myeditor.execCommand("inserthtml", '<blockquote>'+n+'</blockquote>');
        $('#addQuote-btn').popover('hide');
    }
    function addTipsBox(){
        var html = '<div class="form-group"><textarea id="the-tips-text" class="form-control" rows=5 style="width:320px"></textarea><p class="help-block">插入Tips</p></div><div class="form-group"><button type="button" class="btn btn-primary" onclick="addTips()">插入</button></div>';
        var dom=$('#addTips-btn');
        var hack=dom.attr("data-original-title");
        dom.popover({
            container: 'body',
            title:'编辑Tips',
            content:html,
            placement:'top',
            html:true
        });
        if(!hack){
            dom.popover('show');
        }
    }
    function addTips(){
        var n=$('#the-tips-text').val();
        if(!n){
            dialog({msg:'请填写内容'});
            return false;
        }
        myeditor.execCommand("inserthtml", '<div class="well well-sm">'+n+'</div>');
        $('#addTips-btn').popover('hide');
    }
    function addVideo() {
        var html = '<div class="form-group"><label>请输入链接地址</label><input type="text" class="form-control" placeholder="视频地址" id="parse_video_url" style="width:320px"/><p class="help-block">暂时仅支持优酷、土豆视频</p><textarea id="parse_video_desc" class="form-control" placeholder="视频描述（选填）"></textarea></div><div class="form-group"><button type="button" class="btn btn-primary" onclick="parseVideo()">插入视频</button></div>';
        var dom=$('#addVideo-btn');
        var hack=dom.attr("data-original-title");
        dom.popover({
            container: 'body',
            title:'编辑名称',
            content:html,
            placement:'top',
            html:true
        });
        if(!hack){
            dom.popover('show');
        }
    }
    function parseVideo() {
        var url = $('#parse_video_url').val();
        var desc = $('#parse_video_desc').val();
        if (!url) {
            alert('请填写视频地址');
            return false;
        }
        if (!checkAjax()) {
            return false;
        }
        $.post(zmf.parseVideoUrl, {url: url, desc: desc, YII_CSRF_TOKEN: zmf.csrfToken}, function (data) {
            data = $.parseJSON(data);
            ajaxReturn = true;
            if (data.status === 1) {
                myeditor.execCommand("inserthtml", data.msg);
                $('#addVideo-btn').popover('hide');
            } else {
                dialog({msg:data.msg});
                return false;
            }
        });
    }
</script>
