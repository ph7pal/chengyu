<?php

class AjaxController extends Q {

    public function init() {
        parent::init();
        if (!Yii::app()->request->isAjaxRequest) {
            $this->jsonOutPut(0, Yii::t('default', 'forbiddenaction'));
        }
    }

    private function checkLogin() {
        if (Yii::app()->user->isGuest) {
            $this->jsonOutPut(0, Yii::t('default', 'loginfirst'));
        }
    }

    /**
     * 自动联想成语
     */
    public function actionSuggest() {
        if (Yii::app()->request->isAjaxRequest && isset($_GET['q'])) {
            $name = $_GET['q'];
            $criteria = new CDbCriteria;
            $criteria->condition = "(title LIKE :keyword OR title_tw LIKE :keyword OR pinyin LIKE :keyword) AND status=".Posts::STATUS_PASSED;
            $criteria->params = array(':keyword' => '%' . strtr($name, array('%' => '\%', '_' => '\_', '\\' => '\\\\')) . '%');
            $criteria->limit = 10;
            $criteria->select = 'id,title';
            $userArray = Chengyu::model()->findAll($criteria);
            $returnVal = '';
            foreach ($userArray as $userAccount) {
                $returnVal .= $userAccount->getAttribute('title') . '|' . $userAccount->getAttribute('id') . "\n";
            }
            echo $returnVal;
        }
    }

    /**
     * 为成语添加同义词\反义词
     */
    public function actionAddCi() {
        $keyid = tools::val('keyid');
        $fromid = tools::val('fromid');
        $type = tools::val('type', 't', 1);
        if(!$keyid || !$fromid || !$type){
            $this->jsonOutPut(0, '缺少参数');
        }
        if($keyid==$fromid){
            $this->jsonOutPut(0, '不能添加自己');
        }        
        if ($type == 'tongyi') {
            $_classify = ChengyuCi::CLASSIFY_TONGYICI;
        } else {
            $_classify = ChengyuCi::CLASSIFY_FANYICI;
        }
        $_info = ChengyuCi::model()->find("cid=:cid AND tocid=:tocid AND classify='" . $_classify."'", array(':cid' => $keyid, ':tocid' => $fromid));
        if ($_info) {
            $this->jsonOutPut(1, '已添加');
        }
        $attr = array(
            'cid' => $keyid,
            'tocid' => $fromid,
            'classify' => $_classify
        );
        $model = new ChengyuCi;
        $model->attributes = $attr;
        if ($model->save()) {
            zmf::delCache("chengyu-detail-{$keyid}");
            $this->jsonOutPut(1, '已添加');
        } else {
            $this->jsonOutPut(0, '添加失败');
        }
    }
    /**
     * 删除成语的同义词\反义词
     */
    public function actionDelCi() {
        $keyid = tools::val('keyid');
        $fromid = tools::val('fromid');
        $type = tools::val('type', 't', 1);
        if(!$keyid || !$fromid || !$type){
            $this->jsonOutPut(0, '缺少参数');
        }
        if($keyid==$fromid){
            $this->jsonOutPut(0, '缺少错误');
        }        
        if ($type == 'tongyi') {
            $_classify = ChengyuCi::CLASSIFY_TONGYICI;
        } elseif($type=='fanyi') {
            $_classify = ChengyuCi::CLASSIFY_FANYICI;
        }else{
            $_classify=$type;
        }
        $_info = ChengyuCi::model()->find("cid=:cid AND tocid=:tocid AND classify='" . $_classify."'", array(':cid' => $keyid, ':tocid' => $fromid));
        if (!$_info) {
            zmf::delCache("chengyu-detail-{$keyid}");
            $this->jsonOutPut(1, '已删除');
        }        
        if (ChengyuCi::model()->deleteByPk($_info['id'])) {
            zmf::delCache("chengyu-detail-{$keyid}");
            $this->jsonOutPut(1, '已删除');
        } else {
            $this->jsonOutPut(0, '删除失败');
        }
    }

}
