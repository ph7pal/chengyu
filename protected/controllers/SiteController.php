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
        $model = new LoginForm; //登录
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
                if ($model->login()) {
                    $arr = array(
                        'last_login_ip' => ip2long(Yii::app()->request->userHostAddress),
                        'last_login_time' => time(),
                    );
                    Users::model()->updateByPk(Yii::app()->user->id, $arr);
                    Users::model()->updateCounters(array('login_count' => 1), ':id=id', array(':id' => Yii::app()->user->id));
                    if ($this->referer == '') {
                        $this->referer = array('chengyu/index');
                    }
                    zmf::delCookie('checkWithCaptcha');                    
                    $this->redirect($this->referer);
                }
            } else {
                zmf::setCookie('checkWithCaptcha', 1, 86400);
            }
        }
        $this->pageTitle =  '登录 - ' . zmf::config('sitename');        
        $this->render('login', array(
            'model' => $model,
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
