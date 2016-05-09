<?php

/**
 * @filename TagsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  12:54:36 
 */
class AttachmentsController extends Admin {

    public function init() {
        parent::init();
        $this->checkPower('attachments');
    }

    public function actionIndex() {
        $criteria = new CDbCriteria();
        $criteria->order = 'cTime DESC';
        $count = Attachments::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Attachments::model()->findAll($criteria);
        foreach ($posts as $k => $val) {
            $_img = Attachments::getUrl($val, 240);
            $posts[$k]['filePath'] = $_img;
        }
        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }

}
