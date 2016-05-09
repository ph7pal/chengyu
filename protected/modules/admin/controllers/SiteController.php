<?php

class SiteController extends Admin {

    public function actions() {
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

    function actionLogin() {
        $this->layout = 'common';
        if (!Yii::app()->user->isGuest) {
            $this->message(0, '您已登录，请勿重复操作', Yii::app()->createUrl('admin/index/index'));
        }
        $model = new LoginForm;
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login()) {
                $arr = array(
                    'latestLoginTime' => zmf::now(),
                );
                $uid = Yii::app()->user->id;
                if (!$this->checkPower('user', $uid, true)) {
                    Yii::app()->user->logout();
                    $model->addError('username', '您不是管理员');
                } else {
                    //User::model()->updateByPk($uid, $arr);
                    zmf::delCookie('checkWithCaptcha');
                    //只允许单点登录
                    $randKey = zmf::randMykeys(8);
                    zmf::setCookie('adminRandKey' . $uid, $randKey, 86400);
                    zmf::setFCache('adminRandKey' . $uid, $randKey, 86400);
                    //记录操作
                    //UserLog::add($uid, '登录后台'.Yii::app()->request->userHostAddress);
                    $uuid = zmf::uuid();
                    zmf::setCookie('userCheckedLogin' . $uid, $uuid, 86400);
                    $this->redirect(array('index/index'));
                }
            } else {
                $times = zmf::getCookie('checkWithCaptcha');
                zmf::setCookie('checkWithCaptcha', (intval($times) + 1), 86400);
            }
        }
        $data = array(
            'model' => $model
        );
        $this->render('login', $data);
    }

}
