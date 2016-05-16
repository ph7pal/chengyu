<?php

/**
 * 内容接口类
 */
class PostController extends AppApi {

    public function actionSyncAll() {
        $sql = "SELECT `table`,logid,action FROM {{chengyu_log}} WHERE cTime>=:time";
        $ckInfo = $this->getByPage(array('sql' => $sql, 'pageSize' => $this->pageSize, 'page' => $this->page, 'bindValues' => array(':time' => $this->tableVersion)));
        $logs = $ckInfo['posts'];
        $posts = array();
        if (!empty($logs)) {
            foreach ($logs as $log) {
                $_action = ChengyuLog::exActions($log['action']);
                if ($_action != 'del') {
                    $_action = 'update';
                }
                $_table = ChengyuLog::exTables($log['table']);
                $posts[$_table][$_action][] = $log['logid'];
            }
        }
        $chengyuIds = join(',', array_filter(array_unique($posts['chengyu']['update'])));
        $ciIds = join(',', array_filter(array_unique($posts['ci']['update'])));
        $contentIds = join(',', array_filter(array_unique($posts['content']['update'])));
        $posts['chengyu']['del'] = $posts['chengyu']['del'] ? join(',', array_filter(array_unique($posts['chengyu']['del']))) : '';
        $posts['ci']['del'] = $posts['ci']['del'] ? join(',', array_filter(array_unique($posts['ci']['del']))) : '';
        $posts['content']['del'] = $posts['content']['del'] ? join(',', array_filter(array_unique($posts['content']['del']))) : '';
        if ($chengyuIds != '') {
            $_sql = "SELECT id,title,title_tw,pinyin,firstChar,yufa,fayin,firstWord,secondWord,thirdWord,fourthWord,lastWord,cTime FROM {{chengyu}} WHERE id IN({$chengyuIds})";
            $_chengyu = Yii::app()->db->createCommand($_sql)->queryAll();
            $posts['chengyu']['update'] = !empty($_chengyu) ? $_chengyu : array();
        } else {
            $posts['chengyu']['update'] = array();
        }
        if ($contentIds != '') {
            $_sql = "SELECT id,cid,content,classify,type,cTime FROM {{chengyu_content}} WHERE id IN({$contentIds})";
            $_content = Yii::app()->db->createCommand($_sql)->queryAll();
            $posts['content']['update'] = !empty($_content) ? $_content : array();
        } else {
            $posts['content']['update'] = array();
        }
        if ($ciIds != '') {
            $_sql = "SELECT id,cid,tocid,classify,cTime FROM {{chengyu_ci}} WHERE id IN({$ciIds})";
            $_ci = Yii::app()->db->createCommand($_sql)->queryAll();
            $posts['ci']['update'] = !empty($_ci) ? $_ci : array();
        } else {
            $posts['ci']['update'] = array();
        }
        $data = array(
            'posts' => $posts,
            'loadMore' => count($posts) == $this->pageSize ? 1 : 0
        );
        if(!$data['loadMore']){
            $this->currentVersion= zmf::now();
        }
        $this->output($data, $this->succCode);
    }

}
