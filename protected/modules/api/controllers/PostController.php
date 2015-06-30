<?php

/**
 * 内容接口类
 */
class PostController extends AppApi {

    /**
     * 成语列表
     */
    public function actionAll() {
        $char = $this->getValue('char', 0, 1);
        $where = '';
        if ($char) {
            $where.="firstChar='{$char}' AND ";
        }
        $sql = "SELECT id,title,fayin FROM {{chengyu}} WHERE {$where} `status`=" . Posts::STATUS_PASSED . ' ORDER BY hits DESC';
        $this->getByPage(array('sql' => $sql), $pages, $posts);
        $data = array(
            'posts' => $posts,
            'loadMore'=>($pages->itemCount > ($this->page*$this->pageSize)) ? 1 :0,
        );
        $this->jsonOutPut(1, $data);
    }

    public function actionStory() {
        $sql = "SELECT c.id,c.title,c.fayin,cc.content FROM {{chengyu}} c,{{chengyu_content}} cc WHERE cc.classify='" . ChengyuContent::CLASSIFY_GUSHI . "' AND cc.cid=c.id AND cc.status=" . Posts::STATUS_PASSED . " ORDER BY cc.cTime DESC";
        $this->getByPage(array('sql' => $sql), $pages, $posts);
        if (!empty($posts)) {
            foreach ($posts as $k => $v) {
                $posts[$k]['content'] = zmf::subStr($v['content'], 280);
            }
            $posts = array_values($posts);
        }
        $data = array(
            'posts' => $posts,
            'loadMore'=>($pages->itemCount > ($this->page*$this->pageSize)) ? 1 :0,
        );
        $this->output($data, $this->succCode);
    }

    public function actionDetail() {
        $id = $this->getValue('id', 1, 2);
        $detail = Chengyu::model()->findByPk($id);
        if (!$detail || $detail['status'] != Posts::STATUS_PASSED) {
            $this->output('您所查看的页面已不存在或已删除', $this->errorCode);
        }
        $jieshi = $chuchu = $liju = $tongyici = $fanyici = $gushi = array();
        $jies = $detail->jieShis;
        $chuChus = $detail->chuChus;
        $liJus = $detail->liJus;
        $tongyis = $detail->tongYiCis;
        $fanyiis = $detail->fanYiCis;
        $guShis = $detail->guShis;
        if (!empty($jies)) {
            foreach ($jies as $_jies) {
                $jieshi[]['content'] = $_jies['content'];
            }
        }
        if (!empty($chuChus)) {
            foreach ($chuChus as $_chuChu) {
                $chuchu[]['content'] = $_chuChu['content'];
            }
        }
        if (!empty($liJus)) {
            foreach ($liJus as $_liJu) {
                $liju[]['content'] = $_liJu['content'];
            }
        }
        if (!empty($guShis)) {
            foreach ($guShis as $_guShi) {
                $gushi[]['content'] = $_guShi['content'];
            }
        }
        if (!empty($tongyis)) {
            foreach ($tongyis as $_tongyi) {
                $_ciInfo = $_tongyi->chengyuInfo;
                $tongyici[] = array('id' => $_ciInfo['id'], 'title' => $_ciInfo['title']);
            }
        }
        if (!empty($fanyiis)) {
            foreach ($fanyiis as $_fanyii) {
                $_ciInfo = $_fanyii->chengyuInfo;
                $fanyici[] = array('id' => $_ciInfo['id'], 'title' => $_ciInfo['title']);
            }
        }
        $relatedWords = Chengyu::getRelatedWords($detail->title, $id);
        $data = array(
            'id' => $id,
            'title' => $detail->title,
            'fayin' => $detail->fayin,
            'title_tw' => $detail->title_tw,
            'yufa' => $detail->yufa,
            'jieshi' => $jieshi,
            'chuchu' => $chuchu,
            'liju' => $liju,
            'tongyici' => $tongyici,
            'fanyici' => $fanyici,
            'gushi' => $gushi,
            'relatedWords' => $relatedWords,
        );
        $this->output($data, $this->succCode);
    }

    public function actionSearch() {
        $keyword = $this->getValue('keyword', 0, 1);
        $posts = array();
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
            $sql = "SELECT id,title FROM {{chengyu}} WHERE ({$conStr}) AND `status`=" . Posts::STATUS_PASSED;
            $this->getByPage(array('sql'=>$sql), $pages, $posts);
            if($this->page==1){
                SearchRecords::checkAndUpdate($keyword);
            }
        }
        $data=array(
            'posts'=>$posts,
            'loadMore'=>($pages->itemCount > ($this->page*$this->pageSize)) ? 1 :0,
        );
        $this->output($data, $this->succCode);
    }

}
