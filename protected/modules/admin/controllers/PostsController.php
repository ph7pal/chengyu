<?php

class PostsController extends Admin {

    public function init() {
        parent::init();
        $this->checkPower('posts');
    }

    public function actionIndex() {
        $uid = zmf::val('uid', 2);
        $username = zmf::val('username', 1);
        $start = zmf::val('start', 1);
        $end = zmf::val('end', 1);
        $orderBy = zmf::val('orderBy', 1);
        $zid = zmf::val('zid', 1);//所属杂志ID
        $criteria = new CDbCriteria();        
        $criteria->addInCondition('status', array(Posts::STATUS_NOTPASSED,  Posts::STATUS_PASSED));
        if ($username) {
            $uinfo = Users::model()->find("username LIKE '%{$username}%'");
            if ($uinfo) {
                $uid = $uinfo['id'];
            }
        }
        if ($uid) {
            $criteria->addCondition("uid='{$uid}'");
        }
        if ($start) {
            $start = strtotime($start, $now);
            $criteria->addCondition("cTime>='{$start}'");
        }
        if ($end) {
            $end = strtotime($end, $now);
            $criteria->addCondition("cTime<='{$end}'");
        }
        if ($zid) {
            $criteria->addCondition("zazhi='{$zid}'");
        }

        if ($orderBy == 'hits') {
            $criteria->order = 'hits DESC';
        } elseif ($orderBy == 'favors') {
            $criteria->order = 'favors DESC';
        } elseif ($orderBy == 'imgs') {
            $criteria->order = 'imgs DESC';
        } elseif ($orderBy == 'videos') {
            $criteria->order = 'videos DESC';
        } else {
            $criteria->order = 'cTime DESC';
        }
        if($zid){
            $criteria->order = '`order` ASC';
        }
        $count = Posts::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Posts::model()->findAll($criteria);

        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }

    public function actionCreate($id = '') {
        $this->layout = 'common';
        $id = zmf::myint($id);
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
        if ($id) {
            $model = $this->loadModel($id);
            $isNew = false;
        } else {
            $zid = zmf::val('zid', 1);//所属杂志ID
            $model = new Posts;
            $isNew = true;
            if($zid){
                $model->zazhi=$zid;
            }
        }
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'posts-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['Posts'])) {
            //处理文本
            $filter = Posts::handleContent($_POST['Posts']['content']);
            $videoIds=$filter['videoIds'];
            $_POST['Posts']['content'] = $filter['content'];
            if (!empty($filter['attachids'])) {
                $attkeys = array_filter(array_unique($filter['attachids']));
                if (!empty($attkeys)) {
                    $_POST['Posts']['faceimg'] = $attkeys[0]; //默认将文章中的第一张图作为封面图
                }
            } else {
                $_POST['Posts']['faceimg'] = ''; //否则将封面图置为空(有可能编辑后没有图片了)
            }
            if(!$_POST['Posts']['mapZoom']){
                //没有缩放级别则认为用户只是点开看了一下
                $_POST['Posts']['lat']=$_POST['Posts']['long']='';
            }
            $tagids = array_unique(array_filter($_POST['tags']));
            $model->attributes = $_POST['Posts'];
            if ($model->save()) {
                //将上传的图片置为通过
                Attachments::model()->updateAll(array('status' => Posts::STATUS_DELED), 'logid=:logid AND classify=:classify', array(':logid' => $model->id, ':classify' => 'posts'));
                if (!empty($attkeys)) {
                    $attstr = join(',', $attkeys);
                    if ($attstr != '') {
                        Attachments::model()->updateAll(array('status' => Posts::STATUS_PASSED, 'logid' => $model->id), 'id IN(' . $attstr . ')');
                    }
                }
                //处理已上传的视频
                Videos::model()->updateAll(array('status' => Posts::STATUS_DELED), 'logid=:logid AND classify=:classify', array(':logid' => $model->id, ':classify' => 'posts'));
                if(!empty($videoIds)){
                    $videosStr=join(',',$videoIds);
                    if($videosStr!=''){
                        Videos::model()->updateAll(array('logid'=>$model->id,'status'=>  Posts::STATUS_PASSED),"id IN({$videosStr})");
                    }
                }
                //处理标签
                $intoTags = array();
                if (!empty($tagids)) {
                    foreach ($tagids as $tagid) {
                        $_info = Tags::addRelation($tagid, $model->id, 'posts');
                        if ($_info) {
                            $intoTags[] = $tagid;
                        }
                    }
                }
                if (!$isNew || !empty($intoTags)) {
                    Posts::model()->updateByPk($model->id, array('tagids' => join(',', $intoTags)));
                }
                if($model->status==Posts::STATUS_NOTPASSED){
                    $this->redirect(array('posts/index'));
                }else{
                    $this->redirect(array('/posts/view', 'id' => $model->id));
                }
            }
        }
        $tags = Tags::getClassifyTags('posts');
        $postTags = array();
        if (!$isNew) {
            $postTags = Tags::getByIds($model->tagids);
        }
        $this->pageTitle = '与世界分享你的旅行见闻 - ' . zmf::config('sitename');
        $this->render('create', array(
            'model' => $model,
            'tags' => $tags,
            'postTags' => $postTags,
        ));
    }

    public function actionUpdate($id) {
        $this->actionCreate($id);
    }

    public function loadModel($id) {
        $model = Posts::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
