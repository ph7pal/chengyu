<?php

class T extends CController {

    public $menu = array();
    public $breadcrumbs = array();
    public $currentColid;
    public $isMobile = 'no';
    protected $_noColButOther;
    protected $platform;

    public function init() {
        if (zmf::config('mobile')) {
            if (tools::checkmobile($this->platform)) {
                Yii::app()->theme = 'mobile';
                $this->isMobile = 'yes';
            }
        }
    }

    static public function jsonOutPut($status = 0, $msg = '', $end = true, $return = false) {
        $outPutData = array(
            'status' => $status,
            'msg' => $msg
        );
        $json = CJSON::encode($outPutData);
        if ($return) {
            return $json;
        } else {
            echo $json;
        }
        if ($end) {
            Yii::app()->end();
        }
    }

    public function message($status = 0, $message = '', $url = '', $time = 3, $jump = true, $render = true) {
        if (empty($url)) {
            $url = Yii::app()->user->returnUrl;
        }
        if ($status) {
            $success = $message;
        } else {
            $error = $message;
        }
        $data = array(
            'error' => $error,
            'success' => $success,
            'jumpUrl' => $url,
            'waitSecond' => $time,
            'jumpStatus' => $jump
        );
        if ($render) {
            $this->render('//msg/error', $data);
        } else {
            $this->renderPartial('//msg/error', $data);
        }
        Yii::app()->end();
    }

    static public function byteFormat($size, $dec = 2) {
        $a = array("B", "KB", "MB", "GB", "TB", "PB");
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos ++;
        }
        return round($size, $dec) . " " . $a[$pos];
    }

    public function addNotice($replytouid, $logid, $info, $type = 'comment') {
        $data = array(
            'uid' => $replytouid,
            'content' => $info,
            'new' => 1,
            'type' => $type,
            'cTime' => time(),
            'from_id' => $logid,
            'from_num' => 1
        );
        if (Notification::add($data)) {
            return true;
        } else {
            return false;
        }
    }

}
