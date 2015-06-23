<?php

class ChengyuController extends Q {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
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
    
    public function actionContent($id){
        $info = $this->loadModel($id);
        $type = tools::val('type', 't', 1);
        switch ($type){
            case 'jieshi':
                $relations=$info->jieShis;
                $_classify=  ChengyuContent::CLASSIFY_JIESHI;
                break;
            case 'chuchu':
                $relations=$info->chuChus;
                $_classify=  ChengyuContent::CLASSIFY_CHUCHU;
                break;
            case 'liju':
                $relations=$info->liJus;
                $_classify=  ChengyuContent::CLASSIFY_LIJU;
                break;
            case 'gushi':
                $relations=$info->guShis;
                $_classify=  ChengyuContent::CLASSIFY_GUSHI;
                break;
        }
        $model=new ChengyuContent;
        $model->classify=$_classify;
        $model->cid=$id;
        $data = array(
            'info' => $info,
            'type' => $type,
            'relations' => $relations,
            'model' => $model,
        );
        $this->render('content', $data);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Chengyu');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Chengyu('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Chengyu']))
            $model->attributes = $_GET['Chengyu'];

        $this->render('admin', array(
            'model' => $model,
        ));
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
