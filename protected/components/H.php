<?php

class H extends T {

    public $layout = 'admin';
    public $selectType;
    public $listTableTitle; //当前查看列表的名称

    public function init() {
        if (!zmf::uid()) {
            $this->redirect(zmf::config('baseurl'));
        }
        Users::checkPower('admin');
        Yii::app()->language = 'zh_cn';
    }

    public function checkPower($type) {
        Users::checkPower($type);
    }

}
