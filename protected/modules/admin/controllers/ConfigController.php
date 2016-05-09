<?php

class ConfigController extends Admin {

    public function init() {
        parent::init();
        $this->checkPower('config');
        $this->layout = 'config';
    }

    public function actionIndex() {
        $type = zmf::filterInput($_GET['type'], 't', 1);
        if ($type == '' OR ! in_array($type, array('baseinfo', 'upload', 'base', 'email'))) {
            $type = 'baseinfo';
        }
        $configs = Config::model()->findAllByAttributes(array('classify' => $type));
        $_c = CHtml::listData($configs, 'name', 'value');
        $data = array(
            'c' => $_c,
            'type' => $type,
            'model' => new Config()
        );
        $this->render($type, $data);
    }

    public function actionAdd() {
        $this->checkPower('setConfig');
        $type = zmf::filterInput($_POST['type'], 't', 1);
        if ($type == '' OR ! in_array($type, array('baseinfo', 'upload', 'base', 'email'))) {
            $type = 'baseinfo';
        }
        unset($_POST['type']);
        unset($_POST['YII_CSRF_TOKEN']);
        $configs = $_POST;
        if (!empty($configs)) {
            foreach ($configs as $k => $v) {
                if (is_array($v)) {
                    $v = join(',', $v);
                }
                //组织出hash，根据变量、变量的值及分类的md5
                $_hash = md5($k . $v . $type);
                //如果能找到hash则说明该设置未变化
                $_configInfo = Config::model()->find('`hash`=:hash', array(':hash' => $_hash));
                if (!$_configInfo) {
                    //没找到说明已更改或者不存在该设置
                    //根据name和classify判断是否有该设置，没有则新增，有则更新
                    $_detailInfo = Config::model()->find('`name`=:name AND classify=:type', array(':name' => $k, ':type' => $type));
                    if (!$_detailInfo) {
                        //新增
                        $data = array(
                            'name' => zmf::filterInput($k, 't'),
                            'value' => zmf::filterInput($v, 't'),
                            'classify' => zmf::filterInput($type, 't'),
                            'hash' => $_hash
                        );
                        $model = new Config();
                        $model->attributes = $data;
                        $model->save();
                    } else {
                        //更新
                        Config::model()->updateByPk($_detailInfo['id'], array('value' => zmf::filterInput($v, 't'), 'hash' => $_hash));
                    }
                } else {
                    //未做变化，不操作
                }
            }
        }
        //更新本地配置缓存
        $_c = Config::model()->findAll();
        $configs = CHtml::listData($_c, 'name', 'value');
        zmf::writeSet($configs);
        $this->redirect(array('config/index', 'type' => $type));
    }

}
