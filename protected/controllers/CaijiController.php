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
                $chengyuID=$model->id;
                if($_data['jieshi']!='' && $_data['jieshi']!='无'){
                    $_attr=array(
                        'cid'=>$chengyuID,
                        'content'=>  strip_tags($_data['jieshi']),
                        'classify'=>  ChengyuContent::CLASSIFY_JIESHI,
                    );
                    $model=new ChengyuContent;
                    $model->attributes=$_attr;
                    $model->save();
                }
                if($_data['chuchu']!='' && $_data['chuchu']!='无'){
                    $_attr=array(
                        'cid'=>$chengyuID,
                        'content'=>  strip_tags($_data['chuchu']),
                        'classify'=>  ChengyuContent::CLASSIFY_CHUCHU,
                    );
                    $model=new ChengyuContent;
                    $model->attributes=$_attr;
                    $model->save();
                }
                if($_data['liju']!='' && $_data['liju']!='无'){
                    $_attr=array(
                        'cid'=>$chengyuID,
                        'content'=>  strip_tags($_data['liju']),
                        'classify'=>  ChengyuContent::CLASSIFY_LIJU,
                    );
                    $model=new ChengyuContent;
                    $model->attributes=$_attr;
                    $model->save();
                }
                $str=$chengyuID.'#'.$_data['tongyici'].'#'.$_data['fanyici'];
                file_put_contents(Yii::app()->basePath.'/runtime/tongyici.txt', $str,FILE_APPEND);
            }else{
                file_put_contents(Yii::app()->basePath.'/runtime/failed.txt', $file,FILE_APPEND);
            }
        }
        $url = Yii::app()->createUrl('caiji/t086', array('page' => ($page + 1)));
        $this->message(1,"正在处理第{$page}页",$url, 1);
    }

}
