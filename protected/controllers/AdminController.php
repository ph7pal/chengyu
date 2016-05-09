<?php

/**
 * @filename AdminController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-9  11:51:32 
 */
class AdminController extends Q {
    public function actionAppVersion() {
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $count = AppVersion::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = AppVersion::model()->findAll($criteria);

        $this->render('appVersion', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }

    public function actionCheckLog() {
        $type = zmf::filterInput($_GET['type'], 1);
        switch ($type) {
            case 'system':
                $content = file_get_contents(Yii::app()->basePath . '/runtime/application.log');
                break;
            case 'log':
                $content = file_get_contents(Yii::app()->basePath . '/runtime/log.txt');
                break;
            case 'slowlog':
                $content = file_get_contents(Yii::app()->basePath . '/runtime/slowquery.log');
                break;
            case 'delApp':
                unlink(Yii::app()->basePath . '/runtime/application.log');
                $this->message(1, '已删除', Yii::app()->createUrl('admin/tools/index'), 1);
                break;
            case 'appLogs':
                $topDir = Yii::app()->basePath . '/runtime/appLogs';
                $dirNames = zmf::readDir($topDir, false);
                $sizeTotal = 0;
                foreach ($dirNames as $_dir) {
                    $dir = $topDir . '/' . $_dir;
                    $ctime = filemtime($dir);
                    $size = filesize($dir);
                    $dirs[] = array(
                        'filename' => $_dir,
                        'cTime' => $ctime,
                        'size' => $size,
                    );
                    $sizeTotal+=$size;
                }
                $dirs = zmf::multi_array_sort($dirs, 'cTime', SORT_DESC);
                break;
            case 'appLog':
                $content = file_get_contents(Yii::app()->basePath . '/runtime/appLogs/' . $_GET['file']);
                break;
            case 'delAppLog':
                unlink(Yii::app()->basePath . '/runtime/appLogs/' . $_GET['file']);
                $this->message(1, '已删除', Yii::app()->createUrl('admin/tools/index'), 1);
                break;
            case 'delLog':
                unlink(Yii::app()->basePath . '/runtime/log.txt');
                $this->message(1, '已删除', Yii::app()->createUrl('admin/tools/index'), 1);
                break;
            case 'crash':
                $dirs = zmf::readDir(Yii::app()->basePath . '/runtime/crashLog', false);
                break;
            case 'crashLog':
                $content = file_get_contents(Yii::app()->basePath . '/runtime/crashLog/' . $_GET['file']);
                break;
            case 'delCrashLog':
                unlink(Yii::app()->basePath . '/runtime/crashLog/' . $_GET['file']);
                $this->message(1, '已删除', Yii::app()->createUrl('admin/tools/index'), 1);
                break;
        }
        if (in_array($type, array('appLogs', 'crash'))) {
            $data = array(
                'dirs' => $dirs,
                'type' => $type,
                'totalSize' => $sizeTotal,
            );
            $this->render('crash', $data);
        } else {
            $data = array(
                'content' => $content
            );
            $this->render('log', $data);
        }
    }

}
