<?php

/**
 * 前台共用类
 */
class Q extends T {

    public $layout = 'main';
    public $referer;
    //public $uid;    
    //public $mySelf;
    public $truename;
    public $userInfo;
    public $pageDescription;
    public $keywords;
    public $searchKeywords; //搜索词
    public $theAreaId;
    public $likeAreas; //关注的地区
    public $myLikeAreas; //我关注的地区，数组
    public $areaInfo = ''; //地区信息
    public $areaSeconds = array(); //该地区的下级
    public $areaIds = ''; //该地区所包含的下级地区IDs
    public $areaBread = array(); //地区的面包屑
    public $wholeNotice = ''; //全站通知，导航条顶部

    function init() {
        parent::init();
        if (zmf::config('closeSite')) {
            header("Content-type: text/html; charset=utf-8");
            exit(zmf::config('closeSiteReason'));
        }
        if (!Yii::app()->user->isGuest) {
            $myLikeAreasCache = zmf::getCookie('myLikeAreas');
            if ($myLikeAreasCache) {
                $this->wholeNotice = '是否合并未登陆时关注的地区？' . CHtml::link('合并', 'javascript:;', array('onclick' => "combineCookie('add','topbar')", 'class' => 'alert-link')) . '&nbsp;&nbsp;' . CHtml::link('清除记录', 'javascript:;', array('onclick' => "combineCookie('del','topbar')", 'class' => 'color-grey'));
            }
            $uid = Yii::app()->user->id;
            $userInfo = Users::getUserInfo($uid);
            $this->truename = $userInfo['truename'];
            $this->userInfo = $userInfo;            
        }
        //获取我关注的地区，生成数组，不区分是否已登录
        $this->myLikeAreas = AreaLikes::getLikes($this->userInfo, 'a');
        $notice = zmf::config('noticeall');
        if ($notice) {
            $this->wholeNotice = $notice;
        }
        $this->likeAreas = AreaLikes::getLikes($userInfo, 's');
        if (zmf::config('selectedArea')) {
            $areaId = zmf::config('selectedArea');
        } else {
            $areaId = zmf::filterInput($_GET['areaid']);
            if (!$areaId) {
                $areaId = zmf::getCookie('selectedArea');
            }
        }
        if ($areaId) {
            $extra = Area::getAreaInfo($areaId);
            $this->theAreaId = $areaId;
            $this->areaIds = $extra['areaIds'];
            $this->areaInfo = $extra['areaInfo'];
            $this->areaSeconds = $extra['areaSeconds'];
            $this->areaBread = $extra['areaBread'];
        }
        self::_referer();
    }

    function _referer() {
        $currentUrl = Yii::app()->request->url;
        $arr = array(
            '/site/',
            '/error/',
            '/attachments/',
            '/weibo/',
            '/qq/',
            '/weixin/',
        );
        $set = true;
        if (Posts::checkImg($currentUrl)) {
            $set = false;
        }
        if ($set) {
            foreach ($arr as $val) {
                if (!$set) {
                    break;
                }
                if (strpos($currentUrl, $val) !== false) {
                    $set = false;
                    break;
                }
            }
        }
        if ($set && Yii::app()->request->isAjaxRequest) {
            $set = false;
        }
        $referer = zmf::getCookie('refererUrl');
        if ($set) {
            zmf::setCookie('refererUrl', $currentUrl, 86400);
        }
        if ($referer != '') {
            $this->referer = $referer;
        }
    }

}
