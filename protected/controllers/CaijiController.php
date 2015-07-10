<?php

/**
 * @filename CaijiController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-6-26  15:52:57 
 */
class CaijiController extends Q {

    public function actionT086() {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '1800');
        $dir = Yii::app()->basePath . '/../../caiji/t086/';
        $total = zmf::readDir($dir, false);
        $_total = count($total);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $num = 100;
        $start = ($page - 1) * $num;
        $files = array_slice($total, $start, $num);
        if (empty($files)) {
            exit('well done');
        }
        foreach ($files as $file) {
            $_data = include $dir . $file;
            $model = new Chengyu;
            $model->attributes = $_data;
            if ($model->save()) {
                $chengyuID = $model->id;
                if ($_data['jieshi'] != '' && $_data['jieshi'] != '无') {
                    $_attr = array(
                        'cid' => $chengyuID,
                        'content' => strip_tags($_data['jieshi']),
                        'classify' => ChengyuContent::CLASSIFY_JIESHI,
                    );
                    $model = new ChengyuContent;
                    $model->attributes = $_attr;
                    $model->save();
                }
                if ($_data['chuchu'] != '' && $_data['chuchu'] != '无') {
                    $_attr = array(
                        'cid' => $chengyuID,
                        'content' => strip_tags($_data['chuchu']),
                        'classify' => ChengyuContent::CLASSIFY_CHUCHU,
                    );
                    $model = new ChengyuContent;
                    $model->attributes = $_attr;
                    $model->save();
                }
                if ($_data['liju'] != '' && $_data['liju'] != '无') {
                    $_attr = array(
                        'cid' => $chengyuID,
                        'content' => strip_tags($_data['liju']),
                        'classify' => ChengyuContent::CLASSIFY_LIJU,
                    );
                    $model = new ChengyuContent;
                    $model->attributes = $_attr;
                    $model->save();
                }
                $str = $chengyuID . '#' . $_data['tongyici'] . '#' . $_data['fanyici'];
                file_put_contents(Yii::app()->basePath . '/runtime/tongyici.txt', $str . PHP_EOL, FILE_APPEND);
            } else {
                file_put_contents(Yii::app()->basePath . '/runtime/failed.txt', $file . PHP_EOL, FILE_APPEND);
            }
        }
        $url = Yii::app()->createUrl('caiji/t086', array('page' => ($page + 1)));
        $this->message(1, "正在处理第{$page}页", $url, 1);
    }

    public function actionErtong() {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '1800');
//        $dir = Yii::app()->basePath . '/../../chengyugushi/chengyugushi/';
//        $total = zmf::readDir($dir, false);
        $dir = Yii::app()->basePath . '/runtime/failed_gushi.txt';
        $total = file($dir);
        $_total = count($total);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $num = 100;
        $start = ($page - 1) * $num;
        $files = array_slice($total, $start, $num);
        if (empty($files)) {
            exit('well done');
        }
        foreach ($files as $file) {
            $file = trim($file);
            $_data = include Yii::app()->basePath . '/../../chengyugushi/chengyugushi/' . $file;
            $_title = str_replace(array('的典故', '典故', '的故事', '故事'), array('', '', '', ''), $_data['title']);
            $_hash = md5($_title);
            $_info = Chengyu::model()->find("`hash`='{$_hash}'");
            if ($_info) {
                $_attr = array(
                    'cid' => $_info['id'],
                    'content' => $_data['content'],
                    'classify' => ChengyuContent::CLASSIFY_GUSHI,
                );
                $model = new ChengyuContent;
                $model->attributes = $_attr;
                $model->save();
            } else {
                file_put_contents(Yii::app()->basePath . '/runtime/failed_gushi_2.txt', $file . PHP_EOL, FILE_APPEND);
            }
        }
        $url = Yii::app()->createUrl('caiji/ertong', array('page' => ($page + 1)));
        $this->message(1, "正在处理第{$page}页", $url, 1);
    }

    public function actionEdu() {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '1800');
        $dir = Yii::app()->basePath . '/../../caiji/edu/';
        $total = zmf::readDir($dir, false);
        $_total = count($total);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $num = 100;
        $start = ($page - 1) * $num;
        $files = array_slice($total, $start, $num);
        if (empty($files)) {
            exit('well done');
        }
        foreach ($files as $file) {
            $_data = include $dir . $file;
            $model = new Chengyu;
            $model->attributes = $_data;
            if ($model->save()) {
                $chengyuID = $model->id;
                if ($_data['jieshi'] != '' && $_data['jieshi'] != '无') {
                    $_attr = array(
                        'cid' => $chengyuID,
                        'content' => strip_tags($_data['jieshi']),
                        'classify' => ChengyuContent::CLASSIFY_JIESHI,
                    );
                    $model = new ChengyuContent;
                    $model->attributes = $_attr;
                    $model->save();
                }
                if ($_data['chuchu'] != '' && $_data['chuchu'] != '无') {
                    $_attr = array(
                        'cid' => $chengyuID,
                        'content' => strip_tags($_data['chuchu']),
                        'classify' => ChengyuContent::CLASSIFY_CHUCHU,
                    );
                    $model = new ChengyuContent;
                    $model->attributes = $_attr;
                    $model->save();
                }
                if ($_data['liju'] != '' && $_data['liju'] != '无') {
                    $_attr = array(
                        'cid' => $chengyuID,
                        'content' => strip_tags($_data['liju']),
                        'classify' => ChengyuContent::CLASSIFY_LIJU,
                    );
                    $model = new ChengyuContent;
                    $model->attributes = $_attr;
                    $model->save();
                }
                $str = $chengyuID . '#' . $_data['tongyici'] . '#' . $_data['fanyici'];
                file_put_contents(Yii::app()->basePath . '/runtime/tongyici.txt', $str . PHP_EOL, FILE_APPEND);
            } else {
                file_put_contents(Yii::app()->basePath . '/runtime/failed.txt', $file . PHP_EOL, FILE_APPEND);
            }
        }
        $url = Yii::app()->createUrl('caiji/edu', array('page' => ($page + 1)));
        $this->message(1, "正在处理第{$page}页", $url, 1);
    }

    public function actionCi() {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '1800');
        $dir = Yii::app()->basePath . '/runtime/tongyici.txt';
        $files = file($dir);
        foreach ($files as $file) {
            $file = trim($file);
            $_arr = explode('#', $file);
            if (!$_arr[1] && !$_arr[2]) {
                continue;
            }
            if (strpos($file, '、') !== false) {
                $_sep = '、';
            } else {
                $_sep = ' ';
            }
            $_match_t = explode($_sep, $_arr[1]);
            $_match_f = explode($_sep, $_arr[2]);
            if (!empty($_match_t)) {
                foreach ($_match_t as $_one) {
                    $_one = trim($_one);
                    $_hash = md5($_one);
                    $_info = Chengyu::model()->find("`hash`='{$_hash}'");
                    if ($_info) {
                        $attr = array(
                            'cid' => $_arr[0],
                            'tocid' => $_info['id'],
                            'classify' => ChengyuCi::CLASSIFY_TONGYICI
                        );
                        $model = new ChengyuCi;
                        $model->attributes = $attr;
                        $model->save();
                    }
                }
            }
            if (!empty($_match_f)) {
                foreach ($_match_f as $_one) {
                    $_one = trim($_one);
                    $_hash = md5($_one);
                    $_info = Chengyu::model()->find("`hash`='{$_hash}'");
                    if ($_info) {
                        $attr = array(
                            'cid' => $_arr[0],
                            'tocid' => $_info['id'],
                            'classify' => ChengyuCi::CLASSIFY_FANYICI
                        );
                        $model = new ChengyuCi;
                        $model->attributes = $attr;
                        $model->save();
                    }
                }
            }
        }
        exit('well done');
        $url = Yii::app()->createUrl('caiji/ci', array('page' => ($page + 1)));
        $this->message(1, "正在处理第{$page}页", $url, 1);
    }
    
    public function actionSame(){
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $num = 100;        
        $total=  Chengyu::model()->count();        
        $start = ($page - 1) * $num+$total/2;
        $sql="SELECT id,title FROM {{chengyu}} ORDER BY id ASC LIMIT {$start},$num";
        $items=  Yii::app()->db->createCommand($sql)->queryAll();
        if(empty($items)){
            exit('well done');
        }
        foreach($items as $v){
            $_info=  Chengyu::model()->find("title='{$v['title']}' AND id!={$v['id']}");
            if($_info){
                file_put_contents(Yii::app()->basePath.'/runtime/same.txt', $v['id'].','.$_info['id'].',',FILE_APPEND);
            }
        }
        $this->message(1, '正在处理', Yii::app()->createUrl('caiji/same',array('page'=>($page+1))), 1);
    }

}
