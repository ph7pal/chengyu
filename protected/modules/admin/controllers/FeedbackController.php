<?php

class FeedbackController extends Admin {

    public function init() {
        parent::init();
        $this->checkPower('feedback');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $criteria = new CDbCriteria();
        $uid = zmf::val('uid', 2);
        if ($uid) {
            $criteria->addCondition("uid='{$uid}'");
        }
        $criteria->order = '`status` ASC,cTime DESC';
        $count = Feedback::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Feedback::model()->findAll($criteria);
        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }


    public function actionManage() {
        $id = zmf::filterInput($_POST['id']);
        if (!Yii::app()->request->isAjaxRequest) {
            Admin::jsonOutPut(0, '不允许的操作');
        }
        if (!isset($id) OR ! is_numeric($id)) {
            Admin::jsonOutPut(0, '缺少参数');
        }
        $status = Feedback::STATUS_CHECKED;
        if (Feedback::model()->updateByPk($id, array('status' => $status))) {
            Admin::jsonOutPut(1, '操作成功！');
        } else {
            Admin::jsonOutPut(0, '操作失败');
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Feedback the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Feedback::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Feedback $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'feedback-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
