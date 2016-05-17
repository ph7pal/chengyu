<?php

class Admin extends Controller {

    public $pageTitle;
    public $layout = 'main';
    public $menu = array();
    public $breadcrumbs = array();
    public $userInfo;
    public $uid;

    public function init() {
        parent::init();
        $passwdErrorTimes = zmf::getCookie('checkWithCaptcha');
        $time = zmf::config('adminErrorTimes');
        if ($time > 0) {
            if ($passwdErrorTimes >= $time) {
                header('Content-Type: text/html; charset=utf-8');
                echo '您暂时已被禁止访问';
                Yii::app()->end();
            }
        }
        $uid = zmf::uid();
        if ($uid) {
//            $randKey_cookie = zmf::getCookie('adminRandKey' . $uid);
//            $randKey_cache = zmf::getFCache('adminRandKey' . $uid);
//            if (!$randKey_cookie || ($randKey_cache != $randKey_cookie)) {
//                Yii::app()->user->logout();
//                $this->message(0, '登录已过期，请重新登录', Yii::app()->createUrl('admin/site/login'));
//            }
            $this->userInfo = Users::getOne($uid);
            $this->uid=$uid;
        } else {
            $currentUrl = Yii::app()->request->url;
            if (strpos($currentUrl, '/site/') === false) {
                $this->message(0, '请先登录', Yii::app()->createUrl('/site/login'));
            }
        }
    }
    
    /**
     * 判断用户是否有权限
     * @param type $type 判断权限类型
     * @param type $fuid 用户ID，默认为当前登录用户
     * @param type $return 是否返回
     * @param type $json 是否以JSON格式输出
     * @return boolean
     */
    public function checkPower($type, $fuid = '', $return = false, $json = false) {
        $uid = $fuid ? $fuid : Yii::app()->user->id;
        if (!$uid) {
            if ($return) {
                return false;
            } elseif (!$json AND ! Yii::app()->request->isAjaxRequest) {
                $this->redirect(array('/site/login'));
            } else {
                $this->jsonOutPut(0, '请先登录');
            }
        }
        if ($type == 'login') {
            $pinfo = Admins::model()->find('uid=:uid', array(':uid' => $uid));
            if (!$pinfo) {
                if ($return) {
                    return false;
                } elseif (!$json AND ! Yii::app()->request->isAjaxRequest) {
                    throw new CHttpException(403, '您无权该操作');
                } else {
                    $this->jsonOutPut(0, '不是管理员');
                }
            }
            return true;
        }
        $power = Admins::model()->find('powers=:p AND uid=:uid', array(':p' => $type, ':uid' => $uid));
        if (!$power) {
            if ($return) {
                return false;
            } elseif (!$json AND ! Yii::app()->request->isAjaxRequest) {
                throw new CHttpException(403, '您无权该操作');
            } else {
                $this->jsonOutPut(0, '您无权该操作');
            }
        }
        return true;
    }
    
    public static function navbar() {
        $c = Yii::app()->getController()->id;
        $a = Yii::app()->getController()->getAction()->id;
        $attr['login'] = array(
            'title' => '首页',
            'url' => Yii::app()->createUrl('admin/index/index'),
            'active' => in_array($c, array('index'))
        );
//        $attr['comments'] = array(
//            'title' => '评论',
//            'url' => Yii::app()->createUrl('admin/comments/index'),
//            'active' => in_array($c, array('comments'))
//        );
        $attr['feedback'] = array(
            'title' => '反馈',
            'url' => Yii::app()->createUrl('admin/feedback/index'),
            'active' => in_array($c, array('feedback'))
        );       
//        $attr['posts'] = array(
//            'title' => '文章',
//            'url' => Yii::app()->createUrl('admin/posts/index'),
//            'active' => in_array($c, array('posts'))
//        );        
//        $attr['group'] = array(
//            'title' => '标签',
//            'url' => Yii::app()->createUrl('admin/tags/index'),
//            'active' => in_array($c, array('tags'))
//        );
        
        $attr['user'] = array(
            'title' => '用户',
            'url' => Yii::app()->createUrl('admin/users/index'),
            'active' => in_array($c, array('users'))
        );
//        $attr['attachments'] = array(
//            'title' => '图片',
//            'url' => Yii::app()->createUrl('admin/attachments/index'),
//            'active' => in_array($c, array('attachments'))
//        );
        $attr['siteInfo'] = array(
            'title' => '站点',
            'url' => Yii::app()->createUrl('admin/siteInfo/index'),
            'active' => in_array($c, array('siteInfo'))
        );
        $attr['appVersion'] = array(
            'title' => '版本',
            'url' => Yii::app()->createUrl('admin/appVersion/index'),
            'active' => in_array($c, array('appVersion'))
        );
        $attr['system'] = array(
            'title' => '系统',
            'url' => Yii::app()->createUrl('admin/config/navbar'),
            'active' => in_array($c, array('site','config'))
        );
        foreach ($attr as $k => $v) {
            if (!self::checkPower($k, '', true)) {
                //unset($attr[$k]);
            }
        }
        return $attr;
    }
}
