function changeUserGroup(a){
    var b=$('#userGroup').val();
    if(typeof b=='undefined' || b<1){
        alert('请选择用户组');
        return false;
    }    
    $.ajax({
        type: "POST",
        url: userGroupUrl,
        data: "a=" + a + "&b=" + b + "&YII_CSRF_TOKEN=" + csrfToken,
        beforeSend:function(){
            loading('btn-user-group',2,'');
        },
        success: function(result) {
            result = eval('(' + result + ')');
            if (result['status']) { 
                $('#btn-user-group').html('已保存');
            } else {
                alert(result['msg']);
                $('#btn-user-group').html('保存失败');
            }
        }
    });
}
function delAvator(a){    
    $.ajax({
        type: "POST",
        url: delAvatorUrl,
        data: "a=" + a + "&YII_CSRF_TOKEN=" + csrfToken,
        beforeSend:function(){            
        },
        success: function(result) {
            result = eval('(' + result + ')');
            if (result['status']) {                
                alert(result['msg']);                
            } else {
                alert(result['msg']);                
            }
        }
    });
}
function delUser(a){    
    $.ajax({
        type: "POST",
        url: delUserUrl,
        data: "a=" + a + "&YII_CSRF_TOKEN=" + csrfToken,
        beforeSend:function(){            
        },
        success: function(result) {
            result = eval('(' + result + ')');
            if (result['status']) {                
                alert(result['msg']);                
            } else {
                alert(result['msg']);                
            }
        }
    });
}
var nicknameKey=0;
function nickName(){    
    var str="<div id='nickHolder"+nicknameKey+"' class='input-group'><input type='text' name='nickname[]' id='nickname"+nicknameKey+"' class='form-control'/>"+
    "<span class='input-group-addon'><a href='javascript:void(0);' onclick='nickName();' class='addcut_btn'>＋</a></span>"+
    "<span class='input-group-addon'><a href='javascript:void(0);' onclick=\"clearDiv('nickHolder"+nicknameKey+"');\" class='addcut_btn'>－</a></span></div>";
    $("#nicknameHolder").append(str);
    nicknameKey++;
}