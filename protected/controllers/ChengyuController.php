<?php

class ChengyuController extends Q {

    private function check() {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
        if ($this->isMobile == 'yes') {
            $this->redirect(zmf::config('baseurl'));
        }
    }

    public function actionIndex() {
        $criteria = new CDbCriteria();
        $criteria->order = 'hits DESC';
        $criteria->condition = 'status=' . Posts::STATUS_PASSED;
        $char = tools::val('char', 't', 1);
        if ($char) {
            $criteria->addCondition("firstChar='{$char}'");
        }
        $count = Chengyu::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Chengyu::model()->findAll($criteria);
        $this->mobileTitle = '成语大全';
        $this->canonical = zmf::config('domain') . Yii::app()->createUrl('chengyu/index');
        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }

    public function actionStory() {
        $filter = tools::val('filter');
        $order = tools::val('order');
        $where = $orderBy = '';
        if ($filter == 1) {
            $where = " AND type='" . ChengyuContent::TYPE_ZC . "'";
        } elseif ($filter == 2) {
            $where = " AND type='" . ChengyuContent::TYPE_WL . "'";
        }
        if ($order == 2) {
            $orderBy = 'c.hits';
        } else {
            $orderBy = 'cc.cTime';
        }
        $sql = "SELECT c.id,c.title,c.fayin,cc.content FROM {{chengyu}} c,{{chengyu_content}} cc WHERE cc.classify='" . ChengyuContent::CLASSIFY_GUSHI . "' {$where} AND cc.cid=c.id AND cc.status=" . Posts::STATUS_PASSED . " ORDER BY {$orderBy} DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        if (!empty($posts)) {
            foreach ($posts as $k => $v) {
                $posts[$k]['content'] = zmf::subStr($v['content'], 280);
            }
        }
        $this->pageTitle = '成语故事 - ' . zmf::config('sitename');
        $this->mobileTitle = '成语故事';
        $this->canonical = zmf::config('domain') . Yii::app()->createUrl('chengyu/story');
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
        );
        $this->render('story', $data);
    }

    public function actionView($id) {
        $id = zmf::filterInput($id);
        $cacheKey = "chengyu-detail-{$id}";
        $data = zmf::getCache($cacheKey);
        if (!$data) {
            $info = $this->loadModel($id);
            if ($info['status'] != Posts::STATUS_PASSED) {
                throw new CHttpException(404, 'The requested page does not exist.');
            }            
            $relatedWords = Chengyu::getRelatedWords($info['title'], $id);
            $tongyis = $info->tongYiCis;
            $fanyiis = $info->fanYiCis;
            $jies = $info->jieShis;
            $chuChus = $info->chuChus;
            $liJus = $info->liJus;
            $guShis = $info->guShis;
            $data = array(
                'model' => $info,
                'wordArr' => $wordArr,
                'relatedWords' => $relatedWords,
                'tongyis' => $tongyis,
                'fanyiis' => $fanyiis,
                'jies' => $jies,
                'chuChus' => $chuChus,
                'liJus' => $liJus,
                'guShis' => $guShis,
            );
            zmf::setCache($cacheKey, $data,2592000);
        }else{
            $info=$data['model'];
            $jies=$data['jies'];
            $chuChus=$data['chuChus'];
        }
        //更新访问次数
        Posts::updateCount($id, 'Chengyu');
        $wordArr = zmf::chararray($info['title']);
        $wordArr = $wordArr[0];
        $data['']=$wordArr;
        $this->pageTitle = $info['title'] . ' - ' . zmf::config('sitename');
        $this->keywords=$info['title'] . '解释、' .$info['title'] . '出处、' . $info['title'] . '英文翻译、' . $info['title'] . '故事、' . $info['title'] . '成语新解';
        $this->pageDescription = $info['title'] . "（{$info['fayin']}）" . (!empty($jies) ? '的解释【'.$jies[0]['content'].'】':''). (!empty($chuChus) ? "；{$info['title']}的出处【".$chuChus[0]['content'].'】':'');
        $this->mobileTitle = $info['title'];
        $this->canonical = zmf::config('domain') . Yii::app()->createUrl('chengyu/view', array('id' => $id));
        $this->render('view', $data);
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
        $this->mobileTitle = '搜索';
        $this->canonical = zmf::config('domain') . Yii::app()->createUrl('chengyu/search');
        $this->render('search', $data);
    }

    /**
     * 新增成语
     * @param type $id
     */
    public function actionCreate($id = '') {
        $this->check();
        if ($id) {
            $model = $this->loadModel($id);
        } else {
            $model = new Chengyu;
        }
        if (isset($_POST['Chengyu'])) {
            $model->attributes = $_POST['Chengyu'];
            if ($model->save()) {
                if($id){
                    zmf::delCache("chengyu-detail-{$id}");
                }
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
        $this->check();
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
        $this->check();
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
                zmf::delCache("chengyu-detail-{$id}");
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
        $this->check();
        $id = zmf::filterInput($id);
        $model = ChengyuContent::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $model->updateByPk($id, array('status' => Posts::STATUS_DELED));
        zmf::delCache("chengyu-detail-{$id}");
        $this->redirect(array('chengyu/view', 'id' => $model->cid));
    }

    /**
     * 删除成语
     * @param type $id
     */
    public function actionDelete($id) {
        $this->check();
        $info = $this->loadModel($id);
        if (!$info) {
            $this->message(0, '页面不存在');
        }
        Chengyu::model()->updateByPk($id, array('status' => Posts::STATUS_DELED));
        zmf::delCache("chengyu-detail-{$id}");
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
