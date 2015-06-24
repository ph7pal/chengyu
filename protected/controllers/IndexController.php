<?php

class IndexController extends Q {

    public function actionIndex() {
        $new = Chengyu::getNew();
        $data = array(
            'new' => $new
        );
        $this->render('index',$data);
    }

}
