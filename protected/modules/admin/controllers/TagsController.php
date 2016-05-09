<?php

/**
 * @filename TagsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  12:54:36 
 */
class TagsController extends Admin {

    public function init() {
        parent::init();
        $this->checkPower('tags');
    }

    public function actionIndex() {
        $criteria = new CDbCriteria();
        $criteria->addCondition('status=' . Posts::STATUS_PASSED);
        $criteria->order = 'cTime DESC';
        $count = Tags::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Tags::model()->findAll($criteria);

        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }

    public function actionCreate($id = '') {
        $this->checkPower('addTag');
        if ($id) {
            $model = Tags::model()->findByPk($id);
            if (!$model) {
                $this->message(0, '该标签不存在');
            }
        } else {
            $model = new Tags;
            $model->classify = 'posts';
        }
        if (isset($_POST['Tags'])) {
            $model->attributes = $_POST['Tags'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $this->actionCreate($id);
    }

}
