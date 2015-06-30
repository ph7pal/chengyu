<?php

/**
 * 用户接口类
 */
class UserController extends AppApi {

    /**
     * 判断软件是否有更新
     */
    public function actionCheckapp() {
        $appinfo = array();
        $versionInfo = AppVersion::check($this->version, $this->appPlatform);
        if ($versionInfo) {
            $appinfo = array(
                'status' => $versionInfo['status'],
                'downurl' => $versionInfo['downurl'],
                'content' => $versionInfo['content'],
            );
        } else {
            $appinfo = array(
                'status' => '1',
                'downurl' => '',
                'content' => '',
            );
        }
        $this->output($appinfo, $this->succCode);
    }

    /**
     * 用户反馈
     */
    public function actionFeedback() {
        $this->checkUser();
        $uid = $this->uid;
        $attr['uid'] = $uid;
        $attr['type'] = $this->appPlatform;
        $attr['contact'] = $this->getValue('contact', 0);
        $attr['appinfo'] = $this->getValue('appinfo', 0);
        $attr['sysinfo'] = $this->getValue('sysinfo', 0);
        $attr['content'] = $this->getValue('content', 1);
        $model = new Feedback();
        $model->attributes = $attr;
        if ($model->validate()) {
            if ($model->save()) {
                $this->output('感谢您的反馈', $this->succCode);
            } else {
                $this->output('感谢您的反馈', $this->succCode);
            }
        } else {
            $this->output('反馈失败，请重试', $this->errorCode);
        }
    }

}
