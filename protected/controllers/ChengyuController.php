<?php

class ChengyuController extends Q {

    public function actionIndex() {
        $criteria = new CDbCriteria();
        $criteria->order = 'hits DESC';
        $char = tools::val('char', 't', 1);
        if ($char) {
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
    
    public function actionStory() {
        $sql = "SELECT c.id,c.title,c.fayin,cc.content FROM {{chengyu}} c,{{chengyu_content}} cc WHERE cc.classify='" . ChengyuContent::CLASSIFY_GUSHI . "' AND cc.cid=c.id AND cc.status=" . Posts::STATUS_PASSED . " ORDER BY cc.cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        if (!empty($posts)) {
            foreach ($posts as $k => $v) {
                $posts[$k]['content'] = zmf::subStr($v['content'], 280);
            }
        }
        $this->pageTitle='成语故事 - '.zmf::config('sitename');
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
        );
        $this->render('story',$data);
    }

    public function actionView($id) {
        $id = zmf::filterInput($id);
        $info = $this->loadModel($id);
        Posts::updateCount($id, 'Chengyu');
        $relatedWords=  Chengyu::getRelatedWords($info['title'], $id);
        $wordArr=zmf::chararray($info['title']);
        $wordArr=$wordArr[0];
        $this->pageTitle = $info['title'] . ' - ' . zmf::config('sitename');
        $this->pageDescription = $info['title'] . '的解释、' . $info['title'] . '英文翻译、' . $info['title'] . '的故事、' . $info['title'] . '的成语新解';
        $this->render('view', array(
            'model' => $info,
            'wordArr' => $wordArr,
            'relatedWords' => $relatedWords,
        ));
    }    

    public function actionSearch() {
        $keyword = tools::val('keyword', 't', 1);
        $this->searchKeywords = $keyword;
        //去掉标点
        $keyword = zmf::formatTitle($keyword);
        $posts = $conditionArr = array();
        if ($keyword != '') {
            //只取8个字符
            $keyword = zmf::subStr($keyword, 8, 0, '');
            //转换为简体
            $keyword = zmf::twTozh($keyword);
            $karr = zmf::chararray($keyword);
            $karr = $karr[0];
            foreach ($karr as $char) {
                $conditionArr[] = " (title LIKE '%{$char}%') ";
            }
            $conStr = join('AND', $conditionArr);
            $sql = "SELECT id,`hash`,title FROM {{chengyu}} WHERE ({$conStr}) AND `status`=" . Posts::STATUS_PASSED . " LIMIT 30";
            $posts = Yii::app()->db->createCommand($sql)->queryAll();
            SearchRecords::checkAndUpdate($keyword);
        }
        $data = array(
            'posts' => $posts
        );
        if ($keyword != '') {
            $this->pageTitle = "【{$this->searchKeywords}】解释_【{$this->searchKeywords}】英文翻译_【{$this->searchKeywords}】的故事 - " . zmf::config('sitename');
            $this->pageDescription = "搜索{$this->searchKeywords}解释，搜索{$this->searchKeywords}英文翻译，搜索{$this->searchKeywords}的故事，搜索{$this->searchKeywords}的成语新解";
        } else {
            $this->pageTitle = '成语解释_成语英文翻译_成语故事 - ' . zmf::config('sitename');
        }
        $this->render('search', $data);
    }
    
    /**
     * 新增成语
     * @param type $id
     */
    public function actionCreate($id = '') {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
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

    /**
     * 更新成语
     * @param type $id
     */
    public function actionUpdate($id) {
        $this->actionCreate($id);
    }

    /**
     * 为成语添加同义词、反义词
     */
    public function actionCi($id) {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
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

    /**
     * 为成语添加例句、故事等等
     * @param type $id
     * @throws CHttpException
     */
    public function actionContent($id) {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
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

    /**
     * 删除成语的例句、故事等
     * @param type $id
     * @throws CHttpException
     */
    public function actionDelcontent($id) {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
        $id = zmf::filterInput($id);
        $model = ChengyuContent::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $model->updateByPk($id, array('status' => Posts::STATUS_DELED));
        $this->redirect(array('chengyu/view', 'id' => $model->cid));
    }

    /**
     * 删除成语
     * @param type $id
     */
    public function actionDelete($id) {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
        $this->loadModel($id)->delete();
        $this->redirect(array('chengyu/index'));
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
