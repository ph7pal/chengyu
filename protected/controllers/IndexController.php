<?php

class IndexController extends Q {

    public function actionIndex() {
        $key = "index-page";
        $data = zmf::getCache($key);
        if (!$data) {
            $new = Chengyu::getNew(39);
            $contens = ChengyuContent::getNew();
            $xinjie = ChengyuContent::getXinJie();
            $data = array(
                'new' => $new,
                'contens' => $contens,
                'xinjie' => $xinjie,
            );
            zmf::setCache($key, $data, 86400);
        }
        $this->render('index', $data);
    }

}
