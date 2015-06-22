<?php

class SiteController extends Q {

    public $layout = '';
    public $newMembers = array();
    public $loginTitle = '';
    public $regTitle = '';

    public function init() {
        parent::init();
    }

    public function actions() {
        $cookieInfo = zmf::getCookie('checkWithCaptcha');
        if ($cookieInfo == '1') {
            return array(
                'captcha' => array(
                    'class' => 'CCaptchaAction',
                    'backColor' => 0xFFFFFF,
                    'minLength' => '2', // 最少生成几个字符
                    'maxLength' => '3', // 最多生成几个字符
                    'height' => '30',
                    'width' => '60'
                ),
                'page' => array(
                    'class' => 'CViewAction',
                ),
            );
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionLogin($from = '') {
        if (!Yii::app()->user->isGuest) {
            $this->message(0, '您已登录，请勿重复操作');
        }
        if (!$from) {
            $from = 'login';
        }
        $bind = tools::val('bind', 't');
        $model = new LoginForm; //登录
        $modelUser = new Users(); //注册
        if ($bind == 'weibo') {
            $strdata = zmf::getCookie('userWeiboData'); //取出cookie中用户的微博信息
            if ($strdata) {
                $data = unserialize($strdata);
                $modelUser->truename = $data['screen_name'];
            }
        }elseif($bind=='qq'){
            $strdata = zmf::getCookie('userQQData'); //取出cookie中用户的微博信息
            if ($strdata) {
                $data = unserialize($strdata);
                $modelUser->truename = $data['nickname'];
            }
        }elseif($bind=='weixin'){
            $strdata = zmf::getCookie('userWeixinData'); //取出cookie中用户的微博信息
            if ($strdata) {
                $data = unserialize($strdata);
                $modelUser->truename = $data['nickname'];
            }
        }
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'users-addUser-form') {
            echo CActiveForm::validate($modelUser);
            Yii::app()->end();
        }
        //登录
        if (isset($_POST['LoginForm'])) {
            $from = 'login';
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate()) {
                $hasBind = false;
                //判断是否绑定过微博
                if ($bind == 'weibo') {
                    $strdata = zmf::getCookie('userWeiboData'); //取出cookie中用户的微博信息
                    if (!$strdata) {
                        $model->addError('email', '未能获取微博绑定信息，请重试');
                    } else {
                        $binddata = unserialize($strdata);
                    }
                    //根据当前用户名获取他是否已经绑定过
                    $email = $_POST['LoginForm']['email'];
                    $validator = new CEmailValidator;
                    if ($validator->validateValue($email)) {
                        $user = Users::model()->find('email=:email', array(':email' => $email));
                    } else {
                        $user = Users::model()->find('truename=:truename', array(':truename' => $email));
                    }
                    if ($user) {
                        $bindInfo = UserSina::model()->findByPk($user['id']);
                        if ($bindInfo) {
                            $hasBind = true;
                        }
                        $binddata['uid'] = $user['id'];
                    }
                }elseif($bind=='qq'){
                    $strdata = zmf::getCookie('userQQData'); //取出cookie中用户的微博信息
                    if (!$strdata) {
                        $model->addError('email', '未能获取微博绑定信息，请重试');
                    } else {
                        $binddata = unserialize($strdata);
                    }
                    //根据当前用户名获取他是否已经绑定过
                    $email = $_POST['LoginForm']['email'];
                    $validator = new CEmailValidator;
                    if ($validator->validateValue($email)) {
                        $user = Users::model()->find('email=:email', array(':email' => $email));
                    } else {
                        $user = Users::model()->find('truename=:truename', array(':truename' => $email));
                    }
                    if ($user) {
                        $bindInfo = UserQq::model()->findByPk($user['id']);
                        if ($bindInfo) {
                            $hasBind = true;
                        }
                        $binddata['uid'] = $user['id'];
                    }
                }elseif($bind=='weixin'){
                    $strdata = zmf::getCookie('userWeixinData'); //取出cookie中用户的微博信息
                    if (!$strdata) {
                        $model->addError('email', '未能获取微信绑定信息，请重试');
                    } else {
                        $binddata = unserialize($strdata);
                    }
                    //根据当前用户名获取他是否已经绑定过
                    $email = $_POST['LoginForm']['email'];
                    $validator = new CEmailValidator;
                    if ($validator->validateValue($email)) {
                        $user = Users::model()->find('email=:email', array(':email' => $email));
                    } else {
                        $user = Users::model()->find('truename=:truename', array(':truename' => $email));
                    }
                    if ($user) {
                        $bindInfo = UserWeixin::model()->findByPk($user['id']);
                        if ($bindInfo) {
                            $hasBind = true;
                        }
                        $binddata['uid'] = $user['id'];
                    }
                }
                if ($hasBind) {
                    $model->addError('email', '该账号已绑定其他账号');
                } elseif ($model->login()) {
                    $arr = array(
                        'last_login_ip' => ip2long(Yii::app()->request->userHostAddress),
                        'last_login_time' => time(),
                    );
                    Users::model()->updateByPk(Yii::app()->user->id, $arr);
                    Users::model()->updateCounters(array('login_count' => 1), ':id=id', array(':id' => Yii::app()->user->id));
                    if ($this->referer == '') {
                        $this->referer = array('users/index', 'id' => Yii::app()->user->id);
                    }
                    zmf::delCookie('checkWithCaptcha');
                    //微博绑定已有账号
                    if ($bind == 'weibo') {
                        UserSina::addCookie($binddata);
                    }elseif($bind=='qq'){
                        UserQq::addCookie($binddata);
                    }elseif($bind=='weixin'){
                        UserWeixin::addCookie($binddata);
                    }
                    $this->redirect($this->referer);
                }
            } else {
                zmf::setCookie('checkWithCaptcha', 1, 86400);
            }
        } elseif (isset($_POST['Users'])) {
            $from = 'reg';
            //注册
            if (UserAction::checkRegTimes()) {
                $this->message(0, '您今天的注册次数已用完');
            }
            $email = zmf::filterInput($_POST['Users']['email'], 't', 1);
            $truename = zmf::filterInput($_POST['Users']['truename'], 't', 1);
            $inputData = array(
                'truename' => $truename,
                'password' => $_POST['Users']['password'] != '' ? md5($_POST['Users']['password']) : '',
                'email' => $email,
                'cTime' => time(),
                'register_time' => time(),
                'last_login_time' => time(),
                'groupid' => zmf::config('userDefaultGroup'),
                'register_ip' => ip2long(Yii::app()->request->userHostAddress),
                'last_login_ip' => ip2long(Yii::app()->request->userHostAddress),
            );
            $modelUser->attributes = $inputData;
            if ($modelUser->validate()) {
                if ($modelUser->save()) {
                    $_model = new LoginForm;
                    $_model->email = $email;
                    $_model->password = $_POST['Users']['password'];
                    $_model->login();
                    if($bind=='weibo'){
                        $strdata = zmf::getCookie('userWeiboData'); //取出cookie中用户的微博信息
                        if($strdata){
                            $binddata = unserialize($strdata);
                            $binddata['uid']=Yii::app()->user->id;
                            UserSina::addCookie($binddata);
                        }
                    }elseif($bind=='qq'){
                        $strdata = zmf::getCookie('userQQData'); //取出cookie中用户的微博信息
                        if($strdata){
                            $binddata = unserialize($strdata);
                            $binddata['uid']=Yii::app()->user->id;
                            UserQq::addCookie($binddata);
                        }
                    }elseif($bind=='weixin'){
                        $strdata = zmf::getCookie('userWeixinData'); //取出cookie中用户的微信信息
                        if($strdata){
                            $binddata = unserialize($strdata);
                            $binddata['uid']=Yii::app()->user->id;
                            UserWeixin::addCookie($binddata);
                        }
                    }
                    if ($this->referer == '') {
                        //$this->referer = zmf::config('baseurl');
                        $this->referer = array('users/index', 'id' => Yii::app()->user->id);
                    }
                    $this->redirect($this->referer);
                }
            }
        }
        if ($bind) {
            $this->loginTitle = '绑定已有账户';
            $this->regTitle = '完善资料';
        } else {
            $this->loginTitle = '登录';
            $this->regTitle = '注册';
        }
        if ($from == 'login') {
            $this->pageTitle = $this->loginTitle . ' - ' . zmf::config('sitename');
        } else {
            $this->pageTitle = $this->regTitle . ' - ' . zmf::config('sitename');
        }
        $this->render('login', array(
            'model' => $model,
            'modelUser' => $modelUser,
            'from' => $from,
        ));
    }

    public function actionLogout() {
        if (Yii::app()->user->isGuest) {
            $this->message(0, '您尚未登录');
        }
        Yii::app()->user->logout();
        if ($this->referer == '') {
            $this->referer = Yii::app()->request->urlReferrer;
        }
        $this->redirect($this->referer);
    }

    public function actionReg() {
        $this->actionLogin('reg');
    }

    public function actionSitemap() {
        $page = $_GET['id'];
        $page = isset($page) ? $page : 1;
        $dir = Yii::app()->basePath . '/runtime/site/sitemap' . $page . '.xml';
        $a = $_GET['a'];
        $obj = new Sitemap();
        if ($a == 'update' || !file_exists($dir)) {
            $limit = 10000;
            $start = ($page - 1) * $limit;
            $rss = $obj->show(Posts::CLASSIFY_POST, $start, $limit);
            if ($rss) {
                $obj->saveToFile($dir);
            } else {
                exit($page . '-->empty');
            }
        } else {
            $rss = $obj->getFile($dir);
        }
        //rss创建
        $this->render('//site/sitemap', array('rss' => $rss));
    }

}
