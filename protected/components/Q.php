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
    
    public $wholeNotice = ''; //全站通知，导航条顶部

    function init() {
        parent::init();
        if (zmf::config('closeSite')) {
            header("Content-type: text/html; charset=utf-8");
            exit(zmf::config('closeSiteReason'));
        }
        if (!Yii::app()->user->isGuest) {
            $uid = Yii::app()->user->id;
            $userInfo = Users::getUserInfo($uid);
            $this->truename = $userInfo['truename'];
            $this->userInfo = $userInfo;            
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
