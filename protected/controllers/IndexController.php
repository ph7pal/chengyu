<?php

class IndexController extends Q {

    public function actionIndex() {
        $new = Chengyu::getNew(39);
        $contens = ChengyuContent::getNew();
        $data = array(
            'new' => $new,
            'contens' => $contens,
        );
        $this->render('index',$data);
    }

}
