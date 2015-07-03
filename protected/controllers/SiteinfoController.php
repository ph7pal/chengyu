<?php

class SiteinfoController extends Q {

    public function actionView($code) {
        $code = zmf::filterInput($code, 't', 1);
        $info = SiteInfo::model()->find('code=:code', array(':code' => $code));
        if (!$info) {
            throw new CHttpException(404, '您所查看的页面不存在');
        }
        $allInfos = SiteInfo::model()->findAll(array(
            'select' => 'code,title',
            'condition' => 'status=' . Posts::STATUS_PASSED
        ));
        //更新访问统计
        Posts::updateCount($info['id'], 'SiteInfo');
        $data = array(
            'info' => $info,
            'allInfos' => $allInfos,
            'code' => $code,
        );
        $this->pageTitle = $info['title'] . ' - ' . zmf::config('sitename');
        $this->render('/site/siteinfo', $data);
    }

    public function actionCreate($id = '') {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
        if ($id) {
            $model = $this->loadModel($id);
        } else {
            $model = new SiteInfo;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['SiteInfo'])) {
            $filter = Posts::handleContent($_POST['SiteInfo']['content']);
            $_POST['SiteInfo']['content'] = $filter['content'];
            if (!empty($filter['attachids'])) {
                $attkeys = array_filter(array_unique($filter['attachids']));
                if (!empty($attkeys)) {
                    $_POST['SiteInfo']['faceimg'] = $attkeys[0]; //默认将文章中的第一张图作为封面图
                }
            }
            $model->attributes = $_POST['SiteInfo'];
            if ($model->save()) {
                //将上传的图片置为通过
                Attachments::model()->updateAll(array('status' => Posts::STATUS_DELED), 'logid=:logid AND classify=:classify', array(':logid' => $model->id, ':classify' => 'siteinfo'));
                if (!empty($attkeys)) {
                    $attstr = join(',', $attkeys);
                    if ($attstr != '') {
                        Attachments::model()->updateAll(array('status' => Posts::STATUS_PASSED, 'logid' => $model->id), 'id IN(' . $attstr . ')');
                    }
                }
                $this->redirect(array('siteinfo/view', 'code' => $model->code));
            }
        }
        $this->render('/site/createSiteInfo', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $this->actionCreate($id);
    }

}
