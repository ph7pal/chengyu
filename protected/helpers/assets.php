<?php

/**
 * @filename assets.php 
 * @Description 统一处理css、js加载
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-6-2  14:15:47 
 */
class assets {

    /**
     * 加载js路径配置文件
     * @param type $type 应用类型，web为旅行，blog为博客
     */
    public function jsConfig($type = 'web',$module='web') {
        $arr['web'] = array(
            'baseUrl' => zmf::config('baseurl'),
            'hasLogin' => Yii::app()->user->isGuest ? 'false' : 'true',
            'controller' => Yii::app()->getController()->id,
            'action' => Yii::app()->getController()->getAction()->id,
            'module' => $module,
            'reputation' => 'false',
            'loginHtml' => '',
            'reportUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/report'),
            'delUploadImgUrl' => zmf::config('domain') . Yii::app()->createUrl('/attachments/delUploadImg'),
            'csrfToken' => Yii::app()->request->csrfToken,
            'currentSessionId' => Yii::app()->session->sessionID,
            'allowImgTypes' => zmf::config('imgAllowTypes'),
            'allowImgPerSize' => tools::formatBytes(zmf::config('imgMaxSize')),
            'perAddImgNum' => zmf::config('imgUploadNum'),
            'searchUrl' => zmf::config('domain') . Yii::app()->createUrl('/search/suggest'),
            'searchPage' => zmf::config('domain') . Yii::app()->createUrl('/search/index'),
            'commentsUrl' => zmf::config('domain') . Yii::app()->createUrl('/comments/lists'), //获取评论列表
            'addCommentsUrl' => zmf::config('domain') . Yii::app()->createUrl('/comments/add'), //写评论
            'feedbackUrl' => zmf::config('domain') . Yii::app()->createUrl('/feed/add'), //用户反馈
        );
        $attrs = $arr[$type];
        $longHtml = '<script>var zmf={';
        foreach ($attrs as $k => $v) {
            $longHtml.=$k . ":'" . $v . "',";
        }
        $longHtml.='};</script>';
        echo $longHtml;
    }

    public function loadCssJs($type = 'web') {
        $_staticUrl = zmf::config('cssJsStaticUrl');
        $staticUrl = $_staticUrl ? $_staticUrl : zmf::config('baseurl');
        $cs = Yii::app()->clientScript;
        if ($type == 'web') {
            $cs->registerCssFile($staticUrl . 'common/css/bootstrap.min.css');
            $cs->registerCssFile($staticUrl . 'common/css/font-awesome.min.css');
            $cs->registerCssFile($staticUrl . 'common/css/font-awesome-ie7.min.css');
            $cs->registerCssFile($staticUrl . 'common/css/newsoul.css');
            $cs->registerCoreScript('jquery');
            $cs->registerScriptFile($staticUrl . "common/js/bootstrap.min.js", CClientScript::POS_END);
            $cs->registerScriptFile($staticUrl . "common/js/card.js", CClientScript::POS_END);
            $cs->registerScriptFile($staticUrl . "common/js/zmf.js", CClientScript::POS_END);
        } elseif ($type == 'frozen') {
            $cs->registerCssFile($staticUrl . 'themes/frozen/css/frozen.1.2.1.css');
            $cs->registerCssFile($staticUrl . 'themes/frozen/css/demo.css');
            $cs->registerCssFile($staticUrl . 'common/css/font-awesome.min.css');
            $cs->registerScriptFile($staticUrl . "common/js/zepto.min.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($staticUrl . "themes/frozen/js/frozen-1.0.1.js", CClientScript::POS_END);
            $cs->registerScriptFile($staticUrl . "themes/frozen/js/zmf.js", CClientScript::POS_END);
        } elseif ($type == 'admin') {
            $cs->registerCoreScript('jquery', CClientScript::POS_END);
            $cs->registerScriptFile(Yii::app()->baseUrl . '/common/uploadify/jquery.uploadify-3.1.min.js', CClientScript::POS_END);
            $cs->registerScriptFile(Yii::app()->baseUrl . "/common/js/zmf.js", CClientScript::POS_END);
            $cs->registerScriptFile(Yii::app()->baseUrl . "/common/js/bootstrap.min.js", CClientScript::POS_END);
            $cs->registerScriptFile(Yii::app()->baseUrl . "/common/js/admin.js", CClientScript::POS_END);
        }
    }

}
