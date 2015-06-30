
function myUploadify() {
    $("#uploadfile").uploadify({
        height: 34,
        width: 120,
        swf: zmf.baseUrl + '/common/uploadify/uploadify.swf',
        queueID: 'fileQueue',
        auto: true,
        multi: true,
        fileObjName: 'filedata',
        uploadLimit: zmf.perAddImgNum,
        fileSizeLimit: zmf.allowImgPerSize,
        fileTypeExts: zmf.allowImgTypes,
        fileTypeDesc: 'Image Files',
        uploader: tipImgUploadUrl,
        buttonText: '请选择',
        debug: false,
        formData: {'PHPSESSID': zmf.currentSessionId, 'YII_CSRF_TOKEN': zmf.csrfToken},
        onUploadSuccess: function(file, data, response) {
            data = eval("(" + data + ")");
            if (data['status'] == 1) {
                var img;
                img = "<p><img src='" + data['imgsrc'] + "' data='" + data['attachid'] + "' class='img-responsive'/><br/></p>";
                myeditor.execCommand("inserthtml", img);
            } else {
                alert(data['msg']);
            }
        }
    });
}