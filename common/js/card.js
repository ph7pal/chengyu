//用户名片ajax/*
(function ($) {
    var $loading, $wrap, timers = [], optsArray = [], currentObj, currentOpts = {}, fulltime = null,
            _position = function ($ref, $target)

            {
                var scrollTop,
                        scrollLeft,
                        windowHeight,
                        windowWidth,
                        refOffset,
                        refHeight,
                        refWidth,
                        targetTop,
                        targetLeft,
                        targetHeight,
                        targetWidth,
                        originTop,
                        originRight,
                        originBottom,
                        originLeft,
                        arrowPositon, //箭头方向
                        arrowSize = 5,
                        isPosition = false;

                scrollTop = $(document).scrollTop();
                scrollLeft = $(document).scrollLeft();
                windowHeight = $(window).height();
                windowWidth = $(window).width();
                refOffset = $ref.offset();
                refHeight = $ref.outerHeight();
                refWidth = $ref.outerWidth();
                targetHeight = $target.outerHeight();
                targetWidth = $target.outerWidth();

                //定位显示的位置
                if (refOffset.top - scrollTop - targetHeight - arrowSize >= 0) {//上
                    targetTop = refOffset.top - targetHeight - arrowSize - 5;
                    targetLeft = refOffset.left - 50;
                    isPosition = true;
                    arrowPositon = "b";
                    /*if(windowWidth + scrollLeft - refOffset.left - targetWidth >= 0){//上右
                     targetTop = refOffset.top - targetHeight - arrowSize-5;
                     targetLeft = refOffset.left-50;
                     isPosition = true;
                     arrowPositon = "b";
                     }else if(refOffset.left + refWidth -scrollLeft - targetWidth >=0){//上左
                     targetTop = refOffset.top - targetHeight - arrowSize;
                     targetLeft = refOffset.left + refWidth - targetWidth;
                     isPosition = true;
                     arrowPositon = "b";
                     $target.find('.arrow').css({
                     left: 'auto',
                     right: '10px'
                     });
                     }
                     */
                }

                if (!isPosition) {
                    if (windowHeight - (refOffset.top + refHeight - scrollTop) - targetHeight - arrowSize >= 0) {//下
                        targetTop = refOffset.top + refHeight + arrowSize + 5;
                        targetLeft = refOffset.left - 50;
                        isPosition = true;
                        arrowPositon = "t";
                        /*if(windowWidth + scrollLeft  - refOffset.left - targetWidth >= 0){//下右
                         targetTop = refOffset.top + refHeight + arrowSize+5;
                         targetLeft = refOffset.left-50;
                         isPosition = true;
                         arrowPositon = "t";
                         }else if(refOffset.left + refWidth -scrollLeft - targetWidth >=0){//下左
                         targetTop = refOffset.top + refHeight + arrowSize;
                         targetLeft = refOffset.left + refWidth - targetWidth;
                         isPosition = true;
                         arrowPositon = "t";
                         $target.find('.arrow').css({
                         left: 'auto',
                         right: '10px'
                         });
                         }
                         */
                    }
                }
                /*				
                 if(!isPosition){
                 if(windowWidth + scrollLeft - refOffset.left - refWidth - targetWidth - arrowSize>= 0){//右
                 if(refOffset.top + refHeight - scrollTop - targetHeight >= 0){//右上
                 targetTop = refOffset.top + refHeight - targetHeight;
                 targetLeft = refOffset.left + refWidth + arrowSize;
                 isPosition = true;
                 arrowPositon = "l";
                 $target.find('.arrow').css({
                 top: 'auto', 
                 bottom: '10px'
                 });
                 }else if(refOffset.top + windowHeight - scrollTop - targetHeight>= 0){//右下
                 targetTop = refOffset.top;
                 targetLeft = refOffset.left + refWidth + arrowSize;
                 isPosition = true;
                 arrowPositon = "l";
                 }
                 }
                 }
                 
                 if(!isPosition){
                 if(refOffset.left - scrollLeft - targetWidth - arrowSize>=0){//左
                 if(windowHeight - (refOffset.top - scrollTop) - targetHeight >= 0){//左下
                 targetTop = refOffset.top;
                 targetLeft = refOffset.left - targetWidth -arrowSize;
                 isPosition = true;
                 arrowPositon = "r";
                 }else if(refOffset.top + refHeight - scrollTop - targetHeight >= 0){//左上
                 targetTop = refOffset.top + refHeight - targetHeight;
                 targetLeft = refOffset.left - targetWidth - arrowSize;
                 isPosition = true;
                 arrowPositon = "r";
                 $target.find('.arrow').css({
                 top: 'auto', 
                 bottom: '10px'
                 });
                 } 
                 }
                 }
                 */
                if (!isPosition) {
                    //特殊情况定位(非最大化情况下)
                    //计算原点与浏览器视窗边缘的距离
                    originTop = refOffset.top - scrollTop + refHeight / 2;
                    originBottom = windowHeight - originTop;
                    originLeft = refOffset.left - scrollLeft + refWidth / 2;
                    originRight = windowWidth - originLeft;

                    if (originTop >= originBottom) {
                        if (originRight >= originLeft) {
                            if (originTop < targetHeight && originRight >= targetWidth) {//右上
                                targetTop = refOffset.top + refHeight - targetHeight;
                                targetLeft = refOffset.left + refWidth + arrowSize;
                                arrowPositon = "l";
                                $target.find('.arrow').css({
                                    top: 'auto',
                                    bottom: '10px'
                                });
                            } else {//上右
                                targetTop = refOffset.top - targetHeight - arrowSize;
                                targetLeft = refOffset.left;
                                arrowPositon = "b";
                            }

                        } else {
                            if (originTop < targetHeight && originLeft >= targetWidth) {//左上
                                targetTop = refOffset.top + refHeight - targetHeight;
                                targetLeft = refOffset.left - targetWidth - arrowSize;
                                arrowPositon = "r";
                                $target.find('.arrow').css({
                                    top: 'auto',
                                    bottom: '10px'
                                });
                            } else {//上左
                                targetTop = refOffset.top - targetHeight - arrowSize;
                                targetLeft = refOffset.left + refWidth - targetWidth;
                                arrowPositon = "b";
                                $target.find('.arrow').css({
                                    left: 'auto',
                                    right: '10px'
                                });
                            }
                        }
                    } else {
                        if (originRight >= originLeft) {
                            if (originBottom < targetHeight && originRight >= targetWidth) {//右下
                                targetTop = refOffset.top;
                                targetLeft = refOffset.left + refWidth + arrowSize;
                                arrowPositon = "l";
                            } else {//下右
                                targetTop = refOffset.top + refHeight + arrowSize;
                                targetLeft = refOffset.left;
                                arrowPositon = "t";
                            }
                        } else {
                            if (originBottom < targetHeight && originLeft >= targetWidth) {//左下
                                targetTop = refOffset.top;
                                targetLeft = refOffset.left - targetWidth - arrowSize;
                                arrowPositon = "r";
                            } else {//下左
                                targetTop = refOffset.top + refHeight + arrowSize;
                                targetLeft = refOffset.left + refWidth - targetWidth;
                                arrowPositon = "t";
                                $target.find('.arrow').css({
                                    left: 'auto',
                                    right: '10px'
                                });
                            }
                        }
                    }

                    isPosition = true;
                }

                $target.find('.arrow').removeClass().addClass('arrow arrow_' + arrowPositon);

                if (isPosition) {
                    $target.css({
                        top: targetTop,
                        left: targetLeft
                    });
                }

            },
            _appendContent = function () {
                var type, href, data, content;
                href = $(currentObj).attr('action-type');
                data = $(currentObj).attr('action-data');
                id = href + '-' + data;
                var dom = $('#' + id);
                $loading = $('<div class="pinwheel_loading"></div>');

                if (dom[0]) {
                    dom.show();
                } else {
                    $('body').append('<div class="pinwheel_wrap" id="' + id + '"></div>');
                    $wrap = $("#" + id);
                }
                $wrap.html('<div class="pinwheel_layer"><div class="bg"><div class="effect"><div class="pinwheel_content"></div><div class="arrow"></div></div></div></div>');
                $wrap.find('.pinwheel_content').append($loading);
                _position($(currentObj), $wrap);
                for (var i = 0; i < optsArray.length; i++) {
                    if (optsArray[i] && optsArray[i].obj == currentObj) {
                        currentOpts = optsArray[i].opts;
                        break;
                    }
                }
                if (currentOpts.content) {
                    type = 'html';
                } else {
                    type = 'ajax';
                }

                switch (type) {
                    case 'html' :
                        $wrap.find('.pinwheel_content').html(currentOpts.content);
                        break;
                    case 'ajax' :
                        var sendurl;
                        if (href == 'user') {
                            sendurl = zmf.cardLink_user;
                        } 
                        var ajaxLoader = $.ajax({
                            type: 'post',
                            url: sendurl,
                            data: {
                                id: data,
                                YII_CSRF_TOKEN:zmf.csrfToken
                            },
                            success: function (data, textStatus, XMLHttpRequest) {
                                var o = typeof XMLHttpRequest == 'object' ? XMLHttpRequest : ajaxLoader;
                                if (o.status == 200) {
                                    data = eval("(" + data + ")");
                                    var cardHtml = "";
                                    if (data['status'] !=1) {
                                        cardHtml = '<div class="cardcontent_t">' + data['msg'] + '</div>';
                                    } else {
                                        data=data['msg'];
                                        if (href == 'user') {
                                            cardHtml = UserCard(data);
                                        } else if (href == 'tag') {
                                            cardHtml = TagCard(data);
                                        }else{
                                            cardHtml = '<div class="cardcontent_t">不存在的分类</div>';
                                        }
                                    }
                                    $wrap.find('.pinwheel_content').html(cardHtml);
                                    // 重新定位      
                                    _position($(currentObj), $wrap);//定位 
                                }
                            }
                        });
                        break;
                }
                //$wrap.find('.pinwheel_content').html(opts.content);	
                return $wrap;
            },
            //清除所有定时器
            _clearTimer = function () {
                for (var i = 0; i < timers.length; i++) {
                    if (timers[i]) {
                        clearInterval(timers[i]);
                    }
                }
                timers = [];
            },
            _debug = function ($obj) {
                if (window.console && window.console.log) {
                    window.console.log("pinwheel count :" + $obj.size());
                }
                ;
            };
    $.fn.pinwheel = function (options) {
        var opts = $.extend({}, $.fn.pinwheel.defaults, options);
        return this.each(function () {
            optsArray.push({
                obj: this,
                opts: opts
            });
            $(this).bind("mouseover", function (e) {
                e.stopPropagation();
                _clearTimer();
                var id = $(this).attr('action-type') + '-' + $(this).attr('action-data');
                var $wrap = $('#' + id);
                $(".pinwheel_wrap").hide();
                if ($wrap[0]) {
                    _position($(this), $wrap);//定位
                    $wrap.show();
                } else {
                    currentObj = this;
                    if (fulltime) {
                        clearTimeout(fulltime);
                    }
                    fulltime = setTimeout(function () {
                        $wrap = _appendContent();//为容器添加内容
                        $wrap.show();
                    }, 200);
                }

                $wrap = $(".pinwheel_wrap");
                $wrap.unbind().bind('mouseover', function (e) {
                    e.stopPropagation();
                    _clearTimer();
                });

                $(document).unbind().bind("mouseover", function () {
                    if (fulltime) {
                        clearTimeout(fulltime);
                    }
                    _clearTimer();
                    if ($wrap.is(':visible')) {
                        var timer = setInterval(function () {
                            $wrap.hide();
                            _clearTimer();

                        }, 50);
                        timers.push(timer);
                    }
                });

            });

        });
    };

    $.fn.pinwheel.defaults = {
    };
})(jQuery);

//名片的html
function UserCard(data) {
    var UCHtml = "";
    UCHtml += '<div class="cardcontent_t">';
    UCHtml += '<div class="media"><div class="media-left"><a class="pull-left" href="' + data['userurl'] + '"><img width="50" class="media-object" src="' + data['avatar'] + '" alt="' + data['username'] + '"></a><div class="media-body"><p><a href="' + data['userurl'] + '">' + data['username'] + '</a></p>'+((data['reputation']>0 || data['badge']>0) ? '<p><span title="用户声望">'+data['reputation']+'</span><span title="用户徽章" style="color:gold;margin-left:10px"><span class="icon-circle"></span> '+data['badge']+'</span></p>':'')+'<p class="color-grey">'+data['desc']+'</p></div></div></div>';
    UCHtml += '</div>';
    UCHtml += '<div class="cardcontent_f"><div class="cardcontent_f_c"><div class="col-xs-8 col-sm-8 no-padding"><ul class="uif"><li><a href="' + data['p_url'] + '" target="_blank"><p class="i_c">' + data['posts'] + '</p><p class="i_t">文章</p></a></li>';
    UCHtml += '<li><a href="' + data['a_url'] + '" target="_blank"><p class="i_c">' + data['answers'] + '</p><p class="i_t">回答</p></a></li>';
    UCHtml += '<li class="last_item"><a href="' + data['t_url'] + '" target="_blank"><p class="i_c">' + data['tips'] + '</p><p class="i_t">点评</p></a></li>';
    UCHtml += '</ul></div><div class="col-xs-4 col-sm-4 no-padding"><div style="margin-top:10px">';
    if(data['itself']==1){
    //如果是自己则不显示关注按钮
    }else{
        if(data['favored']==1){
            UCHtml+='<a href="javascript:;" class="btn btn-xs btn-default btn-block" id="user-fave-title-'+data['uid']+'" onclick="favorUser('+data['uid']+',$(this))"><span class="icon-ok"></span> 已关注</a>';
        }else{
            UCHtml+='<a href="javascript:;" class="btn btn-xs btn-primary btn-block" id="user-fave-title-'+data['uid']+'" onclick="favorUser('+data['uid']+',$(this))"><span class="icon-plus"></span> 关注他</a>';
        }
    }
    UCHtml+='</div></div></div>';
    return UCHtml;
}
function TagCard(data) {
    var TCHtml = "";
    TCHtml += '<div class="cardcontent_t">';
    TCHtml += '<a class="avater_b" href="' + data['totagdetail'] + '"><img src="' + data['tagimg'] + '"></a><p class="tagname"><a href="' + data['totagdetail'] + '">' + data['tagname'] + '</a></p><p class="tag_dcp">' + data['tagdcp'] + '</p></div>';
    TCHtml += '<div class="cardcontent_f"><div class="cardcontent_f_c">';
    TCHtml += '<ul class="uif"><li><p class="i_c" id="t_q_count_' + data['tagid'] + '">' + data['qcount'] + '</p><p class="i_t">问题</p></li>';
    TCHtml += '<li><p class="i_c">' + data['ftag'] + '</p><p class="i_t">父话题</p></li>';
    TCHtml += '<li class="last_item"><p class="i_c">' + data['ctag'] + '</p><p class="i_t">子话题</p></li>';
    //  TCHtml+='</li><li class="last_item"><p class="i_c" id="t_fave_count_'+data['tagid']+'">'+data['favecount']+'</p><p class="i_t">人关注</p></li>';
    //  TCHtml+='<a href="javascript:;" onclick="add_qt_fave('+data['tagid']+',\'tag\')" class="link_bbtn att_q" id="t-fave-title-'+data['tagid']+'">'+data['fave_title']+'</a>';
    TCHtml += '</ul></div></div>';
    TCHtml += '</div>';
    return TCHtml;
}