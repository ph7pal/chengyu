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
        $filter=  $this->getValue('filter', 0, 2);
        $order=  $this->getValue('order', 0, 2);        
        $where=$orderBy='';
        if($filter==1){
            $where=" AND type='".ChengyuContent::TYPE_ZC."'";
        }elseif($filter==2){
            $where=" AND type='".ChengyuContent::TYPE_WL."'";
        }
        if($order==2){
            $orderBy='c.hits';
        }else{
            $orderBy='cc.cTime';
        }
        $sql = "SELECT c.id,c.title,c.fayin,cc.content FROM {{chengyu}} c,{{chengyu_content}} cc WHERE cc.classify='" . ChengyuContent::CLASSIFY_GUSHI . "' {$where} AND cc.cid=c.id AND cc.status=" . Posts::STATUS_PASSED . " ORDER BY {$orderBy} DESC";
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
            foreach ($jies as $k=>$_jies) {
                $jieshi[$k]['content'] = $_jies['content'];
                $jieshi[$k]['type'] = $_jies['type'];
            }
        }
        if (!empty($chuChus)) {
            foreach ($chuChus as $k=>$_chuChu) {
                $chuchu[$k]['content'] = $_chuChu['content'];
                $chuchu[$k]['type'] = $_chuChu['type'];
            }
        }
        if (!empty($liJus)) {
            foreach ($liJus as $k=>$_liJu) {
                $liju[$k]['content'] = $_liJu['content'];
                $liju[$k]['type'] = $_liJu['type'];
            }
        }
        if (!empty($guShis)) {
            foreach ($guShis as $k=>$_guShi) {
                $gushi[$k]['content'] = $_guShi['content'];
                $gushi[$k]['type'] = $_guShi['type'];
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
        $wordArr=zmf::chararray($detail['title']);
        $wordArr=$wordArr[0];
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
            'wordArr' => $wordArr,
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
            $sql = "SELECT id,title,fayin FROM {{chengyu}} WHERE ({$conStr}) AND `status`=" . Posts::STATUS_PASSED;
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
