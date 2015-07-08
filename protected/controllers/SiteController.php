<?php

class SiteController extends Q {

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
        if ($this->isMobile != 'yes') {
            $this->layout = 'common';
        }
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
        $this->pageTitle = '登录 - ' . zmf::config('sitename');
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
        if (!zmf::uid()) {
            $this->redirect(zmf::config('domain'));
        }
        $a = $_GET['a'];
        $basedir = Yii::app()->basePath . '/../attachments/sitemap/';
        zmf::createUploadDir($basedir);
        $total = Chengyu::model()->count('status=' . Posts::STATUS_PASSED);
        $perFileNum = 1000; //每个文件的连接数
        $now = zmf::time('', 'Y-m-d');
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if ($a == 'indexer') {
            $dir = $basedir . 'indexer.xml';
            $str = '';
            for ($i = 1; $i <= ($total / $perFileNum + 1); $i++) {
                $str.="<sitemap><loc>http://ciyuxinjie.org/attachments/sitemap/sitemap{$i}.xml</loc><lastmod>{$now}</lastmod></sitemap>";
            }
            $content = <<<SITEMAP
<?xml version="1.0" encoding="UTF-8"?>\r\n
<sitemapindex>$str</sitemapindex>\r\n
SITEMAP;
            file_put_contents($dir, $content);
            exit();
        } elseif ($a == 'create') {
            $criteria = new CDbCriteria();
            $criteria->condition = 'status=' . Posts::STATUS_PASSED;
            $criteria->select = 'id';
            $criteria->order = 'id ASC';
            $criteria->limit = $perFileNum;
            $criteria->offset = ($page - 1) * $perFileNum;
            $items = Chengyu::model()->findAll($criteria);
            if (!empty($items)) {
                foreach ($items as $v) {
                    $str.="<url><loc>http://ciyuxinjie.org/detail/{$v['id']}</loc><lastmod>{$now}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>";
                }
                $dir = $basedir . "sitemap{$page}.xml";
                $content = <<<SITEMAP
<?xml version='1.0' encoding='UTF-8'?>\r\n
<urlset>{$str}</urlset>
SITEMAP;
                file_put_contents($dir, $content);
                $this->message(1, "正在处理{$page}页", Yii::app()->createUrl('site/sitemap', array('a' => 'create', 'page' => ($page + 1))));
            } else {
                exit('well done');
            }
        } else {
            exit('不允许的操作');
        }
    }

}