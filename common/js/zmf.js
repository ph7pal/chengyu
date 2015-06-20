var isc_Hide = true; //.show_comment_c_foot_r是否支持hover=before_submit_scro事件。
var nicknameKey = 1;
var tagnameKey = 1;
var hasEditedContent = '';
var tipsImgOrder = 1;
var ajaxReturn = true;
var navTimeout, navTimeover;//导航卡片
/*搜索框*/
var curr_suggitem = 0, lastv = '', search_keyup_t;
var reputationInfo = {};//声望卡片
var tipsImgOrder = 0;
var mystat;
var beforeModal = '';//对话框
$(document).ready(function() {
    $(window).scroll(function() {
        $(window).scrollTop() > 100 ? $(".back-to-top").fadeIn() : $(".back-to-top").fadeOut();
    }), $(".back-to-top").click(function() {
        return $("body,html").animate({
            scrollTop: 0
        }, 200), !1;
    }), $(window).resize(function() {
        backToTop();
    }), backToTop();
    $("#search-btn").click(function() {
        var c = $("#keyword").val();
        if (typeof c == "undefined") {
            return false;
        } else {
            if (c == "") {
                alert("请输入搜索关键词");
            } else {
                var b = zmf.searchPage;
                var a;
                if (b.indexOf("?") > 0) {
                    a = "&";
                } else {
                    a = "?";
                }
                location.href = b + a + "keyword=" + c;
            }
        }
    });
    $("img.lazy").lazyload();
    $('.dropdown-toggle').dropdown();
    //$('.collapse').collapse();
    $(".fixed-container").pin({containerSelector: ".article", minWidth: 960});
    $("#nav-index,#nav-float").mouseover(function() {
        clearTimeout(navTimeout);
        navTimeover = setTimeout(function() {
            floatFixedBox("nav-index", "nav-float");
        }, 100);
    });
    $("#nav-index,#nav-float").mouseout(function() {
        clearTimeout(navTimeover);
        navTimeout = setTimeout(function() {
            closeDiv("nav-float");
        }, 300);
    });
    textareaAutoResize();
    $("#editorSubmit").click(function() {
        $(window).unbind("beforeunload");
    });
    $("a[action=favorite]").click(function() {
        var k = $(this).attr("action-data");
        var t = $(this).attr("action-type");
        var dom = $(this);
        favoriteIt(dom, t, k);
    });
    $("a[action=favor]").click(function() {
        if (!checkLogin()) {
            //dialog({msg:zmf.loginHtml});
            checkRep($(this));
            return false;
        }
        var k = $(this).attr("action-data");
        var t = $(this).attr("action-type");
        var dom = $(this);
        favorIt(dom, t, k);
    });
    $("a[action=list-comments]").click(function() {
        var k = $(this).attr("action-data");
        var t = $(this).attr("action-type");
        var dom = $(this);
        getComments(dom, t, k);
    });
    $("a[action=add-comment]").click(function() {
        if (!checkLogin()) {
            dialog({msg: zmf.loginHtml});
            return false;
        }
        var dom = $(this);
        var k = dom.attr("action-data");
        var t = dom.attr("action-type");
        addComment(dom, t, k);
    });
    $("a[action=share]").click(function() {
        var dom = $(this);
        var url = dom.attr("action-data");
        qrcode(url);
    });
    if(zmf.module=='web'){
        $('a[action=card]').pinwheel();
    }    
    $(".check-reputation").click(function() {
        var dom = $(this);
        checkRep(dom);
    });
    $(".toggle-area").click(function() {
        var dom = $(this).parents('.content-substr');
        var _dom = dom.prev('.content-detail');
        _dom.removeClass('hidden');
        dom.addClass('hidden');
        dom.next('p').find('.toggle-btn').show();
    });
    $(".toggle-btn").click(function() {
        var _dom = $(this);
        _dom.hide();
        var dom = _dom.parent('p').parent('div').find('.content-detail');
        dom.addClass('hidden');
        dom.next('.content-substr').removeClass('hidden');
    });
    $("a[action=feedback]").unbind('click').click(function() {
        var html = '<div class="form-group"><label for="feedback-contact">联系方式</label><input type="text" id="feedback-contact" class="form-control" placeholder="常用联系方式(邮箱、QQ、微信等)，便于告知反馈处理进度(可选)"/></div><div class="form-group"><label for="feedback-content">反馈内容</label><textarea id="feedback-content" class="form-control" max-lenght="255" placeholder="您的意见或建议"></textarea></div>';
        dialog({msg: html, title: '意见反馈', action: 'feedback'});
        textareaAutoResize();
        $("button[action=feedback]").unbind('click').click(function() {
            feedback();
        });
    });
    //检查是否已填标签
    $("#add-post-btn").click(function() {
        var len = $("#selectedTags").find('span');
        if (len.length == 0) {
            dialog({msg: '标签为必填哦', time: 2});
            return false;
        }
    });
    $('#keyword').click(function() {
        if ($('#main-search-holder').html()) {
            var _h = $('#main-search-holder').offset().top;
            $("body,html").animate({scrollTop: _h}, 200);
        }
    });
});
function searchKeyup(e, type) {
    searchTimeOut(e, type);
    var event = e || window.event;
    var etarget = event.target || event.srcElement;
    if (event.keyCode == 13) {
        if (navigator.userAgent.toUpperCase().indexOf("MSIE") != -1 && lastv != etarget.value) {
            lastv = etarget.value;
            return false;
        }
        $(etarget).siblings("#search-btn").click();
    } else if (event.keyCode == 38 || event.keyCode == 40) {
        if ($("#search-float").css("display") == "block") {
            var itemlist = $(".list-group-item");
            itemlist.removeClass('active');
            itemlist.eq(curr_suggitem).addClass('active');
            $(event.target).val(itemlist.eq(curr_suggitem).text());
            if (curr_suggitem < itemlist.length) {
                curr_suggitem++;
            } else {
                curr_suggitem = 0;
            }
        }
    }
    lastv = etarget.value;
}
function hideSearch() {
    clearTimeout(search_keyup_t);
    search_keyup_t = setTimeout(function() {
        $("#search-float").remove();
    }, 500);
}
function searchTimeOut(e, type) {
    var event = e || window.event;
    clearTimeout(search_keyup_t);
    if (event.keyCode != 38 && event.keyCode != 40) {
        search_keyup_t = setTimeout(function() {
            if (type == 'question') {
                searchSuggest("question", "search-tag")
            } else if (type == 'new-question') {
                suggestQuestion()
            } else {
                searchSuggest('main')
            }
        }, 500);
    }
}
function showMore(t, type) {
    if (type == 'weather') {
        if ($('#area-weather-forecast>.more-items').hasClass("hidden")) {
            $('#area-weather-forecast>.more-items').each(function() {
                $(this).removeClass('hidden');
            });
            $(t).html('<span class="icon-double-angle-up"></span> 收起');
        } else {
            $('#area-weather-forecast>.more-items').each(function() {
                $(this).addClass('hidden');
            });
            $(t).html('<span class="icon-double-angle-down"></span> 未来5天');
        }
    } else {
        if ($('.area-list>.more-items').hasClass("hidden")) {
            $('.area-list>.more-items').each(function() {
                $(this).removeClass('hidden');
            });
            $(t).html('<span class="icon-double-angle-up"></span> 收起');
        } else {
            $('.area-list>.more-items').each(function() {
                $(this).addClass('hidden');
            });
            $(t).html('<span class="icon-double-angle-down"></span> 显示全部');
        }
    }

}
function showNavMore(t, pclass) {
    if ($('.' + pclass + '>.more-items').hasClass("hidden")) {
        $('.' + pclass + '>.more-items').each(function() {
            $(this).removeClass('hidden');
        });
        $(t).html('<div class="col-sm-3 col-xs-3 no-padding nav-item color-grey"><h2 class="min-h2"><span class="icon-double-angle-up"></span> 收起</h2></div>');
    } else {
        $('.' + pclass + '>.more-items').each(function() {
            $(this).addClass('hidden');
        });
        $(t).html('<div class="col-sm-3 col-xs-3 no-padding nav-item color-grey"><h2 class="min-h2"><span class="icon-double-angle-down"></span> 更多</h2></div>');
    }
}
function delUploadImg(attachid, redirecturl) {
    if (!checkLogin()) {
        dialog({msg: zmf.loginHtml});
        return false;
    }
    if (attachid == '' || typeof attachid == 'undefined') {
        dialog({msg: '请选择删除对象', time: 2});
    }
    var tmp = $("#uploadAttach" + attachid).html();
    $("#uploadAttach" + attachid).fadeOut();
    $("#uploadAttach" + attachid).html('');
    $.post(zmf.delUploadImgUrl, {attachid: attachid, YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == 1) {
            if (typeof swfStats != 'undefined') {
                swfStats.successful_uploads = swfStats.successful_uploads - 1;
                swfEvent.setStats(swfStats);
            }
            if (typeof redirecturl != 'undefined') {
                window.location.href = redirecturl;
            }
        } else {
            dialog({msg: result['msg'], time: 2});
        }
    });
}
function favoriteIt(dom, t, k) {
    if (!checkLogin()) {
        if (t != 'area') {
            dialog({msg: zmf.loginHtml});
            return false;
        }
    }
    if (!checkAjax()) {
        return false;
    }
    $.post(zmf.favoriteUrl, {type: t, keyid: k, YII_CSRF_TOKEN: zmf.csrfToken}, function(res) {
        ajaxReturn = true;
        res = eval("(" + res + ")");
        if (res['status'] == 0) {
            dialog({msg: res['msg']});
            return false;
        } else if (res['status'] == 1) {
            //收藏成功
            dom.removeClass('btn-default').addClass('btn-danger');
        } else if (res['status'] == 3) {
            //取消收藏成功
            dom.removeClass('btn-danger').addClass('btn-default');
        }
    });
}
function favorIt(dom, t, k) {
    if (!checkLogin()) {
        dialog({msg: zmf.loginHtml});
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    var tmp = dom.html();
    if (t == 'answer' || t == 'dislike-answer') {
        if (t == 'answer') {
            $('#answer-feedback-' + k + ' .answer-nouse').removeClass('btn-success').addClass('btn-default');
        } else if (t == 'dislike-answer') {
            $('#answer-feedback-' + k + ' .answer-useful').removeClass('btn-success').addClass('btn-default');
        }
    }
    var n = parseInt(dom.text());
    if (dom.hasClass('btn-success')) {
        //取消赞成功      
        dom.removeClass('btn-success').addClass('btn-default');
        if (n > 0) {
            n--
            if (t == 'answer') {
                dom.html('<span class="icon-sort-up answer-feedback-btn"></span>' + n)
            } else if (t == 'dislike-answer') {
                dom.html('<span class="icon-sort-down answer-feedback-btn"></span>' + n)
            } else {
                dom.text(n)
            }
        }
    } else {
        //赞成功
        dom.removeClass('btn-default').addClass('btn-success');
        n++;
        if (t == 'answer') {
            dom.html('<span class="icon-sort-up answer-feedback-btn"></span>' + n)
        } else if (t == 'dislike-answer') {

        } else {
            dom.text(n)
        }
    }
    $.post(zmf.favorUrl, {keyid: k, type: t, YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == 1) {

        } else if (result['status'] == 3) {

        } else if (result['status'] == 2) {
            window.location.href = userLoginUrl + "&redirect=" + window.location.href;
        } else {
            dialog({msg: result['msg']});
            dom.html(tmp);
        }
    });
}
//关注用户
function favorUser(uid, dom) {
    if (!checkLogin()) {
        dialog({msg: zmf.loginHtml});
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    var tmp = dom.html();
    $.get(zmf.favorUserLink, 'uid=' + uid, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == 0) {
            dialog({msg: result['msg']});
            dom.html(tmp);
        } else if (result['status'] == 1) {
            dom.removeClass('btn-primary').addClass('btn-default').html('<span class="icon-ok"></span> 已关注');
        } else if (result['status'] == 3) {
            dom.removeClass('btn-default').addClass('btn-primary').html('<span class="icon-plus"></span> 关注他');
        }
    });
}
function combineCookie(type, from) {
    if (!checkAjax()) {
        return false;
    }
    $.get(zmf.combineCookieUrl, 'type=' + type, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == 0) {
            dialog({msg: result['msg']});
        } else {
            if (from == 'topbar') {
                $('#wholeNotice-bar').hide();
            }
        }
    });
}
function getComments(dom, t, k) {
    if ($("#comments-" + t + "-box-" + k).attr('loaded') == '1') {
        $("#comments-" + t + "-box-" + k).toggle();
    } else {
        if (!checkAjax()) {
            return false;
        }
        $.post(zmf.commentsUrl, {id: k, type: t, YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
            ajaxReturn = true;
            result = eval('(' + result + ')');
            if (result['status'] == 1) {
                $("#comments-" + t + "-box-" + k).show().attr('loaded', '1').append(result['msg']['form']);
                $("#comments-" + t + "-" + k).html(result['msg']['html']);
                textareaAutoResize();
                $("a[action=add-comment]").unbind('click').click(function() {
                    addComment($(this));
                });
                rebind();
            } else {
                dialog({msg: result['msg']});
            }
        });
    }
}
function addComment(dom) {
    if (!checkLogin()) {
        //dialog({msg:zmf.loginHtml});
        //return false;
    }
    var k = dom.attr("action-data");
    var t = dom.attr("action-type");
    var to = $('#replyoneHolder-' + k).attr('tocommentid');
    var c = $('#content-' + t + '-' + k).val();
    if (!k || !t || !c) {
        dialog({msg: '请填写内容'});
        return false;
    }
    if (!to) {
        to = 0;
    }
    if (!checkAjax()) {
        return false;
    }
    $.post(zmf.addCommentsUrl, {k: k, t: t, c: c, to: to, YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            $('#content-' + t + '-' + k).val('');
            $("#comments-" + t + "-" + k).append(result['msg']);
            cancelReplyOne(k);
        } else if (result['status'] == '2') {
            dialog({msg: zmf.loginHtml});
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function checkRep(dom) {
    var t = dom.attr("action");
    if (!t) {
        t = 'comment';
    }
    if (!reputationInfo[t]) {
        var e = '';
        if (zmf.checkReputationUrl.indexOf('?') > -1) {
            e = '&';
        } else {
            e = '?';
        }
        $.post(zmf.checkReputationUrl + e + 't=' + t, {YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
            _checkRep(result, dom);
            reputationInfo[t] = result;
        });
    } else {
        _checkRep(reputationInfo[t], dom);
    }
}
function _checkRep(result, dom) {
    ajaxReturn = true;
    result = eval('(' + result + ')');
    if (result['status'] == '1') {
        var id = dom.attr('id');
        var _width = dom.outerWidth();
        if (_width < 540) {
            _width = 540;
        }
        //alert($('#reputation-float-fixed').html());
        if (typeof $('#reputation-float-fixed').html() == 'undefined') {
            var longstr = '<div id="reputation-float-fixed" style="width:' + _width + 'px">' + result['msg'] + '</div>';
            $("body").append(longstr);
        } else {
            $('#reputation-float-fixed').css('width', _width).html(result['msg']).show();
        }

        floatFixedBox(id, 'reputation-float-fixed', 3);
    } else {
        dialog({msg: result['msg']});
    }
}
/**
 * 意见反馈
 */
function feedback() {
    var c = $('#feedback-content').val();
    if (!c) {
        alert('内容不能为空哦~');
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    var url = window.location.href, email = $("#feedback-contact").val();
    $.post(zmf.feedbackUrl, {content: c, email: email, url: url, YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        dialog({msg: result['msg']});
        return false;
    });
}
function redirectPoi(id) {
    var html = '<div class="form-group"><label for="feedback-contact">重定向到：</label><input type="hidden" value="' + id + '" id="redirect-fromid"/><input type="text" id="redirect-toid" class="form-control" placeholder="重定向到某坐标的ID"/></div><p class="help-block">重定向后访问本坐标将直接跳转到指定的坐标页</p>';
    dialog({msg: html, title: '坐标重定向', action: 'doPoiRedirect'});
    textareaAutoResize();
    $("button[action=doPoiRedirect]").unbind('click').click(function() {
        doPoiRedirect();
    });
}
function doPoiRedirect() {
    if (!checkAjax()) {
        return false;
    }
    var fid = $('#redirect-fromid').val();
    var tid = $('#redirect-toid').val();
    if (!fid || !tid) {
        alert('缺少参数~');
        return false;
    }
    $.post(zmf.redirectPoiUrl, {fid: fid, tid: tid, YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        dialog({msg: result['msg']});
        return false;
    });
}
function rebind() {
    $(".check-reputation").unbind('click').click(function() {
        var dom = $(this);
        checkRep(dom);
    });
}
function replyOne(id, logid, title) {
    var longstr = "<span class='label label-success'><span class='icon-share-alt'></span>回复" + title + "<a href='javascript:' onclick='cancelReplyOne(" + logid + ")' title='取消设置'> <span class='icon-remove'></span></a></span>";
    var pos = $("#replyoneHolder-" + logid).offset().top;
    $("html,body").animate({scrollTop: pos}, 1000);
    $("#replyoneHolder-" + logid).attr('tocommentid', id).html(longstr);
}
function cancelReplyOne(logid) {
    $("#replyoneHolder-" + logid).attr('tocommentid', '').html('');
}
function initMin(a, b) {
    var html = '<div id="init_mini_com" class="floatfixed"><p><textarea id="fast_reply_' + a + '"></textarea></p><p><a href="javascript:;" onclick="fastReply(\'' + a + '\',\'' + b + '\');" class="min_btn min_submit">提交</a><a href="javascript:;" onclick="_c_f_r();" class="min_btn min_close">关闭</a></p></div>';
    if ($('#init_mini_com').html()) {
        $('#init_mini_com').html('');
        $('#init_mini_com').remove();
    }
    $(document.body).append(html);
    floatFixedBox('min_com_button_' + a, 'init_mini_com', 2);
}
function _c_f_r() {
    $('#init_mini_com').html('');
    $('#init_mini_com').remove();
}
function setStatus(a, b, c) {
    if (!checkLogin()) {
        dialog({msg: zmf.loginHtml});
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    $.post(zmf.setStatusUrl, {a: a, b: b, c: c, YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == 1) {
            dialog({msg: result['msg'], time: 3});
        } else if (result['status'] == 2) {
            window.location.href = zmf.userLoginUrl + "&redirect=" + window.location.href;
        } else {
            //$("#favorite" + logid).html(tmp);
            dialog({msg: result['msg']});
        }
    });
}
function report(a, b) {
    if (!checkLogin()) {
        dialog({msg: zmf.loginHtml});
        return false;
    }
    var html = '<input type="hidden" name="report_logid" value="' + a + '"/><input type="hidden" name="report_type" value="' + b + '"/><ul class="options"><li><label><input type="radio" checked="true" name="reason" value="广告或垃圾信息" />广告或垃圾信息</label></li><li><label><input type="radio" name="reason" value="色情或低俗信息"/>色情或低俗信息</label></li><li><label><input type="radio" name="reason" value="激进时政或意识形态"/>激进时政或意识形态</label></li><li><label><input type="radio" name="reason" value="其他原因"/>其他原因</label></li></ul>';
    dialog({msg: html, action: 'doReport', id: 'reportModal', title: '反馈不良信息'});
    $("button[action='doReport']").click(function() {
        doReport();
    });
}
function doReport() {
    if (!checkLogin()) {
        dialog({msg: zmf.loginHtml});
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    var reason = $("input[name='reason']:checked").val(), logid = $("input[name='report_logid']").val(), atype = $("input[name='report_type']").val();
    if (reason == '') {
        dialog({msg: '请选择举报理由', time: 2});
        return false;
    }
    var url = window.location.href;
    $.post(zmf.reportUrl, {k: logid, t: atype, u: url, desc: reason, YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == 1) {
            dialog({msg: result['msg']});
        } else {
            dialog({msg: result['msg'], id: 'reportError'});
        }
    });
}
function addTags2() {
    var str = "<div id='tagHolder" + tagnameKey + "' class='clear'><input type='text' name='tagname[]' id='tagname" + tagnameKey + "'/>" +
            "<a href='javascript:void(0);' onclick='addTags();' class='addcut_btn'>+</a>" +
            "<a href='javascript:void(0);' onclick=\"clearDiv('tagHolder" + tagnameKey + "');\" class='addcut_btn'>-</a></div>";
    $("#tagnameHolder").append(str);
    tagnameKey++;
}
function addTags() {
    var k = $("#tag_input").val();
    if (k == '') {
        closeDiv('tag_suggest');
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    $.post(zmf.addTagsUrl, {k: k, YII_CSRF_TOKEN: zmf.csrfToken}, function(s) {
        ajaxReturn = true;
        s = eval('(' + s + ')');
        if (s['status'] == 1) {
            $("#tag_suggest").html(s['msg']);
            floatFixedBox('tag_input', 'tag_suggest');
        } else {
            dialog({msg: s['msg']});
        }
    });
}
function selectTag(t, i) {
    $(this).remove();
    var randId = parseInt(100000000 * Math.random());
    var str = '<div class="tag-edit-item" id="' + randId + '"><span>' + t + '</span><button type="button" class="close" onclick="removeTag(\'' + randId + '\');"><span aria-hidden="true">&times;</span></button></div>';
    var len = $("#selectedTags .tag-edit-item");
    if (len.length >= 5) {
        $("#search-tag").hide();
        $("#search-float").hide();
    } else {
        var find = false;
        len.each(function() {
            var tag = $(this).children('span').text();
            if ($.trim(t) == $.trim(tag)) {
                find = true;
                return false;
            }
        });
        if (!find) {
            $("#selectedTags").append(str);
            $("#" + randId).append('<input type="hidden" name="tagnames[]" value="' + t + '-' + i + '"/>');
        }
    }
    $('#search-tag').val('');
}
function removeTag(randId) {
    $('#' + randId).remove();
    var len = $("#selectedTags").find('.tag-edit-item');
    if (len.length < 5) {
        $("#search-tag").show();
    }
}
function getWeather() {
    $('#area-weather-forecast').html('<p class="color-grey"><i class="icon-spinner icon-spin"></i> 正在获取当地天气...</p>');
    $.get('https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.media.weather%20where%20woeid%3D2347129&format=json&env=http%3A%2F%2Fdatatables.org%2Falltables.env&callback=', '', function(result) {
        if (result['query']['count'] == 1) {
            var data = result['query']['results']['result']['location']['forecast']['day'];
            var html = '<div class="media"><div class="media-body">';
            for (var i = 0; i < data.length; i++) {
                var _data = data[i];
                html += '<p>' + _data['name'] + ' <span class="pull-right">' + _data['temp']['low'] + '~' + _data['temp']['high'] + '°C</span></p>';
            }
            html += '</div></div>';
            $('#area-weather-forecast').html(html);
        }
    });
}
/*汇率计算*/
function calRate(s) {
    var rateJson = eval('(' + zmf.rateJson + ')');
    var ft = $('#rate-from'), tt = $('#rate-to'), fv = $('#rate-from-value'), tv = $('#rate-to-value');
    var rf = ft.val(), rt = tt.val(), rfv = parseFloat(fv.val()), rtv = parseFloat(tv.val());
    rfv = rfv ? rfv : 0;
    rtv = rtv ? rtv : 0;
    var rate = 0;
    if (rf == 'CNY' && rt == 'CNY') {
        rate = 1.0;
    } else if (rf == 'CNY') {
        rate = parseFloat(rateJson[rt]['rate']);
    } else if (rt == 'CNY') {
        rate = parseFloat(rateJson[rf]['rate']);
        rate = 1 / rate;
    } else {
        var leftRate = parseFloat(rateJson[rf]['rate']);
        var rightRate = parseFloat(rateJson[rt]['rate']);
        rate = rightRate / leftRate;
    }
    if (s == 'r' || s == 'lv') {
        var num = (rate * rfv).toFixed(4);
    } else {
        rate = 1 / rate;
        var num = (rate * rtv).toFixed(4);
    }
    if (s == 'r' || s == 'lv') {
        tv.val(num);
    } else {
        fv.val(num)
    }
}
/**
 * 
 * @param {type} a 输入框id
 * @param {type} b 浮层id
 * @param {type} c 浮靠类型，默认为下方，2：左侧，3：上方
 * @returns {Boolean}
 */
function floatFixedBox(a, b, c) {
    var _t1 = $("#" + a).html();
    var _t2 = $("#" + b).html();
    if (typeof _t1 == 'undefined' || typeof _t2 == 'undefined') {
        return false;
    }
    var top = $("#" + a).offset().top;
    var _top = $("#" + a).height();
    if (c == 1) {
        var left = $("#" + a).offset().left + $("#" + a).outerWidth() - $("#" + b).outerWidth();
        var height = parseInt(top) + parseInt(_top) + 12;
    } else if (c == 2) {
        var left = $("#" + a).offset().left - $("#" + b).outerWidth();
        var height = parseInt(top);
    } else if (c == 3) {
        var left = $("#" + a).offset().left;
        var height = parseInt(top) - $("#" + b).outerHeight();
    } else {
        var left = $("#" + a).offset().left;
        var height = parseInt(top) + parseInt(_top) + 13;
    }
    $("#" + b).css("top", height + 'px');
    $("#" + b).css("left", left + 'px');
    if ($("#" + b).css('display') == 'none') {
        $("#" + b).show();
    }
    $(window).resize(function() {
        floatFixedBox(a, b, c);
    });
}
function loading(divid, type, desc) {
    if (typeof type == 'undefined') {
        type = 0;
    }
    if (type == 0 || type == 2) {
        margin = ';margin:8px 0 0 0'
    } else {
        margin = '';
    }
    if (typeof desc == 'undefined') {
        desc = '正在加载中，请稍候...';
        descstr = "<span style='height:32px;line-height:32px;display:block'>" + desc + "</span>";
    } else if (desc == '') {
        descstr = '';
    } else {
        descstr = "<span style='height:32px;line-height:32px;display:block'>" + desc + "</span>";
    }
    var loadingstr = "<span style='height:32px" + margin + "'><img src='" + baseUrl + "/common/images/loading" + type + ".gif'/></span>" + descstr;
    $("#" + divid).html(loadingstr);
}
function clearDiv(divid) {
    $("#" + divid).html('');
}
function openDiv(divid) {
    $("#" + divid).toggle();
}
function closeDiv(divid) {
    $("#" + divid).hide();
}
function deletePost(a, b, c) {
    if (!checkLogin()) {
        dialog({msg: zmf.loginHtml});
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    if (a == '') {
        dialog({msg: '请选择对象', time: 2});
        return false;
    }
    if (!b) {
        dialog({msg: '删除类型不能为空', time: 2});
        return false;
    }
    $.post(zmf.delPostUrl, {logid: a, type: b, YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == 1) {
            if (c) {
                location.href = c;
            } else {
                dialog({msg: result['msg']});
                $("#" + b + '_' + a).fadeOut();
            }
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function deleteComments(logid) {
    if (!checkLogin()) {
        dialog({msg: zmf.loginHtml});
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    if (logid == '') {
        dialog({msg: '请选择对象', time: 2});
        return false;
    }
    $.post(zmf.delTipsUrl, {logid: logid, type: 'delcom', YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == 1) {
            $("#comment_item_" + logid).fadeOut();
            dialog({msg: result['msg'], time: 2});
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function WordLength(str) {
    var myLen = 0;
    i = 0;
    for (; (i < str.length); i++) {
        myLen += 2;
    }
    myLen = Math.floor((2 * 30 - myLen) / 2);
    return myLen;
}
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
                var longstr = '<div class="flash-error" style="float:left" id="tip_error_' + tipsImgOrder + '"><div style="float:left;width:540px"><span>' + file.name + '</span><br/><span>' + data['msg'] + '</span></div><div style="width:20px;float:right"><a href="javascript:" onclick="closeDiv(\'tip_error_' + tipsImgOrder + '\')">X</a></div></div>';
                dialog({msg: longstr});
            }
            tipsImgOrder++;
        }
    });
}
function singleUploadify(placeHolder, inputId, limit) {
    $("#" + placeHolder).uploadify({
        height: 34,
        width: 120,
        swf: zmf.baseUrl + '/common/uploadify/uploadify.swf',
        queueID: 'singleFileQueue',
        auto: true,
        multi: true,
        uploadLimit: limit,
        fileObjName: 'filedata',
        fileTypeExts: zmf.allowImgTypes,
        fileSizeLimit: zmf.allowImgPerSize,
        fileTypeDesc: 'Image Files',
        uploader: imgUploadUrl,
        buttonText: '请选择',
        formData: {'PHPSESSID': zmf.currentSessionId, 'YII_CSRF_TOKEN': zmf.csrfToken},
        onUploadSuccess: function(file, data, response) {
            data = eval("(" + data + ")");
            mystat = this;
            if (data['status'] == 1) {
                var img = "<div class='pull-left col-xs-2 col-sm-2 no-padding' id='uploadAttach" + data['attachid'] + "'><img src='" + data['imgsrc'] + "' class='img-thumbnail'/><p class='text-center'><a href='javascript:;' onclick=\"delUploadImg('" + data['attachid'] + "');\">删除</a></p></div>";
                $("#fileSuccess").append(img);
                $('#tmp-save-btn').show();
            } else {
                var stats = mystat.getStats();
                stats.successful_uploads--;
                mystat.setStats(stats);
                alert(data['msg']);
            }
            tipsImgOrder++;
        }
    });
}
function backToTop() {
    var x = $(window).width();
    var x1 = $(".container").width();
    var x2 = $("#back-to-top").width();
    if (x < x1) {
        var x3 = x1 + 8;
    } else {
        var x3 = parseInt((x + x1 + 16) / 2);
    }
    $("#back-to-top").css('left', x3 + 'px');
    //alert(x3);
}
/*
 * a:the div id
 * b:container div id
 * c:wrapper
 * d:a's width -1:minus,0:ignore,1:plus
 * e:fixed a only,not cal left etc.
 */
function _fixedFloatBox(fbox, abox, left, top) {
    //var fbox_set=fbox.offset();
    var abox_set = abox.offset();
    abox.css({position: 'relative'});
    left = (left == undefined) ? 0 : left;
    top = (top == undefined) ? 0 : top;
    _left = abox_set.left + left;
    _top = abox_set.top + top;
    //console.log('%s',_top);
    fbox.css({position: 'fixed', left: _left + 'px', top: top + 'px'});
}
function calFloatBox(fbox) {
    fbox.css({position: 'absolute', left: '', top: ''});
}
function checkLogin() {
    if (typeof zmf.hasLogin == 'undefined') {
        return false;
    } else if (zmf.hasLogin) {
        return true;
    } else {
        return false;
    }
}
function checkAjax() {
    if (!ajaxReturn) {
        dialog({msg: '请求正在发送中，请稍后'});
        return false;
    }
    ajaxReturn = false;
    return true;
}
/*
 * a:对话框id
 * t:提示
 * c:对话框内容
 * ac:下一步的操作名
 * time:自动关闭
 */
function dialog(diaObj) {
    if (typeof diaObj != "object") {
        return false;
    }
    var c = diaObj.msg;
    var a = diaObj.id;
    var t = diaObj.title;
    var ac = diaObj.action;
    var time = diaObj.time;
    $('#' + beforeModal).modal('hide');
    if (typeof t == 'undefined' || t == '') {
        t = '提示';
    }
    if (typeof a == 'undefined' || a == '') {
        a = 'myDialog';
    }
    if (typeof ac == 'undefined') {
        ac = '';
    }
    $('#' + a).remove();
    var longstr = '<div class="modal fade mymodal" id="' + a + '" tabindex="-1" role="dialog" aria-labelledby="' + a + 'Label" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="' + a + 'Label">' + t + '</h4></div><div class="modal-body">' + c + '</div><div class="modal-footer">';
    longstr += '<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>';
    if (ac != '' && typeof ac != 'undefined') {
        longstr += '<button type="button" class="btn btn-primary" action="' + ac + '" data-loading-text="Loading...">确定</button>';
    }
    longstr += '</div></div></div></div>';
    $("body").append(longstr);
    $('#' + a).modal({
        backdrop: false,
        keyboard: false
    });
    beforeModal = a;
    if (time > 0 && typeof time != 'undefined') {
        setTimeout("closeDialog('" + a + "')", time * 1000);
    }
}
function closeDialog(a) {
    $('#' + a).modal('hide');
    $('#' + a).remove();
}
/**
 * 
 * @param {type} f 来自哪儿，main或top
 * @returns {undefined}
 */
function searchSuggest(f, field) {
    if (!field) {
        field = 'keyword';
    }
    if (!ajaxReturn) {
        return false;
    }
    var longstr = '<div id="search-float" class="list-group ' + f + '-search"></div>';
    var k = $('#' + field).val();
    if (!k) {
        $('#search-float').remove();
        return false;
    }
    $("body").append(longstr);
    floatFixedBox(field, 'search-float');
    var _data = 'keyword=' + k + '&f=' + f;
    _ajax({url: zmf.searchUrl, data: _data});
}
function suggestQuestion() {
    if (!ajaxReturn) {
        return false;
    }
    var k = $('#Question_title').val(), c = myeditor.getContentTxt();
    var k = k + c;
    if (!k) {
        return false;
    }
    $.post(zmf.searchUrl, {keyword: k, f: 'new-question', YII_CSRF_TOKEN: zmf.csrfToken}, function(result) {
        ajaxReturn = true;
        $('#questions-like-this').html(result)
        return false;
    });
}
function _searchResult(res) {
    if (!res) {
        return false;
    }
    $('#search-float').html(res);
}
/**
 * ajax
 * @param {type} params
 * @returns {Boolean}
 */
function _ajax(params) {
    if (typeof params != "object") {
        return false;
    }
    var type = params.type;
    var url = params.url;
    var data = params.data;
//  var fuc=params.resFuc;
//  var ac=params.action;
//  var time=params.time;
    $.ajax({
        type: type ? type : 'POST',
        url: url,
        data: data + '&YII_CSRF_TOKEN=' + zmf.csrfToken,
        success: function(result) {
            if (params.callback) {
                params.callback(result);
            } else {
                _searchResult(result);
            }
        }
    });
}
//生成二维码
function qrcode(text) {
    if (!text) {
        return false;
    }
    $.getScript(zmf.baseUrl + "common/js/qrcode.min.js", function() {
        dialog({msg: '<div id="qrcode-container" class="thumbnail text-center"></div><p class="help-block">打开微信“扫一扫”，再点击展示页面的右上角，即可分享。</p>', title: '微信分享'});
        var qrcode = new QRCode(document.getElementById("qrcode-container"), {
            width: 400,
            height: 400
        });
        qrcode.makeCode(text);
    });
}
function textareaAutoResize() {
    $('textarea').autoResize({
        // On resize:  
        onResize: function() {
            $(this).css({opacity: 0.8});
        },
        // After resize:  
        animateCallback: function() {
            $(this).css({opacity: 1});
        },
        // Quite slow animation:  
        animateDuration: 100,
        // More extra space:  
        extraSpace: 20
    });
}
/*
 * jQuery autoResize (textarea auto-resizer)
 * @copyright James Padolsey http://james.padolsey.com
 * @version 1.04
 */
(function($) {

    $.fn.autoResize = function(options) {

        // Just some abstracted details,
        // to make plugin users happy:
        var settings = $.extend({
            onResize: function() {
            },
            animate: true,
            animateDuration: 150,
            animateCallback: function() {
            },
            extraSpace: 20,
            limit: 1000
        }, options);

        // Only textarea's auto-resize:
        this.filter('textarea').each(function() {

            // Get rid of scrollbars and disable WebKit resizing:
            var textarea = $(this).css({resize: 'none', 'overflow-y': 'hidden'}),
                    // Cache original height, for use later:
                    origHeight = textarea.height(),
                    // Need clone of textarea, hidden off screen:
                    clone = (function() {

                        // Properties which may effect space taken up by chracters:
                        var props = ['height', 'width', 'lineHeight', 'textDecoration', 'letterSpacing'],
                                propOb = {};

                        // Create object of styles to apply:
                        $.each(props, function(i, prop) {
                            propOb[prop] = textarea.css(prop);
                        });

                        // Clone the actual textarea removing unique properties
                        // and insert before original textarea:
                        return textarea.clone().removeAttr('id').removeAttr('name').css({
                            position: 'absolute',
                            top: 0,
                            left: -9999
                        }).css(propOb).attr('tabIndex', '-1').insertBefore(textarea);

                    })(),
                    lastScrollTop = null,
                    updateSize = function() {

                        // Prepare the clone:
                        clone.height(0).val($(this).val()).scrollTop(10000);

                        // Find the height of text:
                        var scrollTop = Math.max(clone.scrollTop(), origHeight) + settings.extraSpace,
                                toChange = $(this).add(clone);

                        // Don't do anything if scrollTip hasen't changed:
                        if (lastScrollTop === scrollTop) {
                            return;
                        }
                        lastScrollTop = scrollTop;

                        // Check for limit:
                        if (scrollTop >= settings.limit) {
                            $(this).css('overflow-y', '');
                            return;
                        }
                        // Fire off callback:
                        settings.onResize.call(this);

                        // Either animate or directly apply height:
                        settings.animate && textarea.css('display') === 'block' ?
                                toChange.stop().animate({height: scrollTop}, settings.animateDuration, settings.animateCallback)
                                : toChange.height(scrollTop);
                    };

            // Bind namespaced handlers to appropriate events:
            textarea
                    .unbind('.dynSiz')
                    .bind('keyup.dynSiz', updateSize)
                    .bind('keydown.dynSiz', updateSize)
                    .bind('change.dynSiz', updateSize);

        });

        // Chain:
        return this;

    };



})(jQuery);
/*lazyload*/
!function(c, b, d, f) {
    var a = c(b);
    c.fn.lazyload = function(h) {
        function i() {
            var k = 0;
            e.each(function() {
                var l = c(this);
                if (!j.skip_invisible || l.is(":visible")) {
                    if (c.abovethetop(this, j) || c.leftofbegin(this, j)) {
                    } else {
                        if (c.belowthefold(this, j) || c.rightoffold(this, j)) {
                            if (++k > j.failure_limit) {
                                return !1
                            }
                        } else {
                            l.trigger("appear"), k = 0
                        }
                    }
                }
            })
        }
        var g, e = this, j = {threshold: 0, failure_limit: 0, event: "scroll", effect: "show", container: b, data_attribute: "original", skip_invisible: !0, appear: null, load: null};
        return h && (f !== h.failurelimit && (h.failure_limit = h.failurelimit, delete h.failurelimit), f !== h.effectspeed && (h.effect_speed = h.effectspeed, delete h.effectspeed), c.extend(j, h)), g = j.container === f || j.container === b ? a : c(j.container), 0 === j.event.indexOf("scroll") && g.bind(j.event, function() {
            return i()
        }), this.each(function() {
            var k = this, l = c(k);
            k.loaded = !1, l.one("appear", function() {
                if (!this.loaded) {
                    if (j.appear) {
                        var m = e.length;
                        j.appear.call(k, m, j)
                    }
                    c("<img />").bind("load", function() {
                        l.hide().attr("src", l.data(j.data_attribute))[j.effect](j.effect_speed), k.loaded = !0;
                        var p = c.grep(e, function(n) {
                            return !n.loaded
                        });
                        if (e = c(p), j.load) {
                            var o = e.length;
                            j.load.call(k, o, j)
                        }
                    }).attr("src", l.data(j.data_attribute))
                }
            }), 0 !== j.event.indexOf("scroll") && l.bind(j.event, function() {
                k.loaded || l.trigger("appear")
            })
        }), a.bind("resize", function() {
            i()
        }), /iphone|ipod|ipad.*os 5/gi.test(navigator.appVersion) && a.bind("pageshow", function(k) {
            k.originalEvent && k.originalEvent.persisted && e.each(function() {
                c(this).trigger("appear")
            })
        }), c(d).ready(function() {
            i()
        }), this
    }, c.belowthefold = function(h, e) {
        var g;
        return g = e.container === f || e.container === b ? a.height() + a.scrollTop() : c(e.container).offset().top + c(e.container).height(), g <= c(h).offset().top - e.threshold
    }, c.rightoffold = function(h, e) {
        var g;
        return g = e.container === f || e.container === b ? a.width() + a.scrollLeft() : c(e.container).offset().left + c(e.container).width(), g <= c(h).offset().left - e.threshold
    }, c.abovethetop = function(h, e) {
        var g;
        return g = e.container === f || e.container === b ? a.scrollTop() : c(e.container).offset().top, g >= c(h).offset().top + e.threshold + c(h).height()
    }, c.leftofbegin = function(h, e) {
        var g;
        return g = e.container === f || e.container === b ? a.scrollLeft() : c(e.container).offset().left, g >= c(h).offset().left + e.threshold + c(h).width()
    }, c.inviewport = function(e, g) {
        return !(c.rightoffold(e, g) || c.leftofbegin(e, g) || c.belowthefold(e, g) || c.abovethetop(e, g))
    }, c.extend(c.expr[":"], {"below-the-fold": function(e) {
            return c.belowthefold(e, {threshold: 0})
        }, "above-the-top": function(e) {
            return !c.belowthefold(e, {threshold: 0})
        }, "right-of-screen": function(e) {
            return c.rightoffold(e, {threshold: 0})
        }, "left-of-screen": function(e) {
            return !c.rightoffold(e, {threshold: 0})
        }, "in-viewport": function(e) {
            return c.inviewport(e, {threshold: 0})
        }, "above-the-fold": function(e) {
            return !c.belowthefold(e, {threshold: 0})
        }, "right-of-fold": function(e) {
            return c.rightoffold(e, {threshold: 0})
        }, "left-of-fold": function(e) {
            return !c.rightoffold(e, {threshold: 0})
        }})
}(jQuery, window, document);
/*jquery.pin.min.js*/
(function(e) {
    "use strict";
    e.fn.pin = function(t) {
        var n = 0, r = [], i = false, s = e(window);
        t = t || {};
        var o = function() {
            for (var n = 0, o = r.length; n < o; n++) {
                var u = r[n];
                if (t.minWidth && s.width() <= t.minWidth) {
                    if (u.parent().is(".pin-wrapper")) {
                        u.unwrap()
                    }
                    u.css({width: "", left: "", top: "", position: ""});
                    if (t.activeClass) {
                        u.removeClass(t.activeClass)
                    }
                    i = true;
                    continue
                } else {
                    i = false
                }
                var a = t.containerSelector ? u.closest(t.containerSelector) : e(document.body);
                var f = u.offset();
                var l = a.offset();
                var c = u.offsetParent().offset();
                if (!u.parent().is(".pin-wrapper")) {
                    u.wrap("<div class='pin-wrapper'>")
                }
                var h = e.extend({top: 0, bottom: 0}, t.padding || {});
                u.data("pin", {pad: h, from: (t.containerSelector ? l.top : f.top) - h.top, to: l.top + a.height() - u.outerHeight() - h.bottom, end: l.top + a.height(), parentTop: c.top});
                u.css({width: u.outerWidth()});
                u.parent().css("height", u.outerHeight())
            }
        };
        var u = function() {
            if (i) {
                return
            }
            n = s.scrollTop();
            var o = [];
            for (var u = 0, a = r.length; u < a; u++) {
                var f = e(r[u]), l = f.data("pin");
                if (!l) {
                    continue
                }
                o.push(f);
                var c = l.from - l.pad.bottom, h = l.to - l.pad.top;
                if (c + f.outerHeight() > l.end) {
                    f.css("position", "");
                    continue
                }
                if (c < n && h > n) {
                    !(f.css("position") == "fixed") && f.css({left: f.offset().left, top: l.pad.top}).css("position", "fixed");
                    if (t.activeClass) {
                        f.addClass(t.activeClass)
                    }
                } else if (n >= h) {
                    f.css({left: "", top: h - l.parentTop + l.pad.top}).css("position", "absolute");
                    if (t.activeClass) {
                        f.addClass(t.activeClass)
                    }
                } else {
                    f.css({position: "", top: "", left: ""});
                    if (t.activeClass) {
                        f.removeClass(t.activeClass)
                    }
                }
            }
            r = o
        };
        var a = function() {
            o();
            u()
        };
        this.each(function() {
            var t = e(this), n = e(this).data("pin") || {};
            if (n && n.update) {
                return
            }
            r.push(t);
            e("img", this).one("load", o);
            n.update = a;
            e(this).data("pin", n)
        });
        s.scroll(u);
        s.resize(function() {
            o()
        });
        o();
        s.load(a);
        return this
    }
})(jQuery);