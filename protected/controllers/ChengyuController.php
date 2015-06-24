<?php

class ChengyuController extends Q {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $id = zmf::filterInput($id);
        $info = $this->loadModel($id);
        Posts::updateCount($id, 'Chengyu');
        $this->pageTitle = $info['title'] . ' - ' . zmf::config('sitename');
        $this->pageDescription = $info['title'] . '的解释、' . $info['title'] . '英文翻译、';
        $this->render('view', array(
            'model' => $info,
        ));
    }

    public function actionCreate($id = '') {
        if ($id) {
            $model = $this->loadModel($id);
        } else {
            $model = new Chengyu;
        }
        if (isset($_POST['Chengyu'])) {
            $model->attributes = $_POST['Chengyu'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $this->actionCreate($id);
    }

    /**
     * 为成语添加同义词、反义词
     */
    public function actionCi($id) {
        $info = $this->loadModel($id);
        $type = tools::val('type', 't', 1);
        if ($type == 'tongyi') {
            $relations = $info->tongYiCis;
        } else {
            $relations = $info->fanYiCis;
        }
        $data = array(
            'info' => $info,
            'type' => $type,
            'relations' => $relations,
        );
        $this->render('ci', $data);
    }

    public function actionContent($id) {
        $id = zmf::filterInput($id); //所属成语的id
        $cid = tools::val('ccid'); //内容id，chengyu's content id
        $info = $this->loadModel($id);
        $type = tools::val('type', 't', 1);
        switch ($type) {
            case 'jieshi':
                $relations = $info->jieShis;
                $_classify = ChengyuContent::CLASSIFY_JIESHI;
                break;
            case 'chuchu':
                $relations = $info->chuChus;
                $_classify = ChengyuContent::CLASSIFY_CHUCHU;
                break;
            case 'liju':
                $relations = $info->liJus;
                $_classify = ChengyuContent::CLASSIFY_LIJU;
                break;
            case 'gushi':
                $relations = $info->guShis;
                $_classify = ChengyuContent::CLASSIFY_GUSHI;
                break;
        }
        if ($cid) {
            $model = ChengyuContent::model()->findByPk($cid);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        } else {
            $model = new ChengyuContent;
            $model->classify = $_classify;
            $model->cid = $id;
        }
        if (isset($_POST['ChengyuContent'])) {
            if ($model->classify == ChengyuContent::CLASSIFY_GUSHI) {
                $filter = Posts::handleContent($_POST['ChengyuContent']['content']);
                $_POST['ChengyuContent']['content'] = $filter['content'];
            } else {
                $_POST['ChengyuContent']['content'] = zmf::filterInput($_POST['ChengyuContent']['content'], 't', 1);
            }
            $model->attributes = $_POST['ChengyuContent'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->cid));
            }
        }
        $data = array(
            'info' => $info,
            'type' => $type,
            'relations' => $relations,
            'model' => $model,
        );
        $this->render('content', $data);
    }

    public function actionDelcontent($id) {
        $id=zmf::filterInput($id);
        $model = ChengyuContent::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $model->updateByPk($id, array('status'=>  Posts::STATUS_DELED));
        $this->redirect(array('chengyu/view','id'=>$model->cid));
    }

    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionIndex() {
        $criteria = new CDbCriteria();
        $criteria->order = 'cTime DESC';
        $char=  tools::val('char','t',1);
        if($char){
            $criteria->addCondition("firstChar='{$char}'");
        }
        $count = Chengyu::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Chengyu::model()->findAll($criteria);

        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }
    
    public function actionSearch(){
        $keyword=  tools::val('keyword','t',1);
        $data=array(
            ''
        );
        $this->render('search',$data);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Chengyu the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Chengyu::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Chengyu $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'chengyu-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
