<?php

class IndexController extends Q {

    public function actionIndex() {
        $new = Chengyu::getNew(39);
        $contens = ChengyuContent::getNew();
        $xinjie = ChengyuContent::getXinJie();        
        $data = array(
            'new' => $new,
            'contens' => $contens,
            'xinjie' => $xinjie,
        );
        $this->render('index',$data);
    }

}
