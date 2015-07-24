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
    public function jsConfig($type = 'web', $module = 'web') {
        $arr['web'] = array(
            'baseUrl' => zmf::config('baseurl')         
        );
        if(in_array(Yii::app()->getController()->id,array('chengyu')) && in_array(Yii::app()->getController()->getAction()->id, array('create','update','ci','content'))){
            $arr['web']['csrfToken']=Yii::app()->request->csrfToken;
            $arr['web']['addCiUrl']=zmf::config('domain') . Yii::app()->createUrl('/ajax/addCi');//添加同义词
            $arr['web']['delCiUrl']=zmf::config('domain') . Yii::app()->createUrl('/ajax/delCi');//删除同义词
        }
        $attrs = $arr[$type];
        $longHtml = '<script>var zmf={';
        $jsArr=array();
        foreach ($attrs as $k => $v) {
            $jsAr[].=$k . ":'" . $v . "'";
        }
        $longHtml.=join(',',$jsAr);
        $longHtml.='};</script>';
        echo $longHtml;
    }

    public function loadCssJs($type = 'web') {
        $_staticUrl = zmf::config('cssJsStaticUrl');
        $staticUrl = $_staticUrl ? $_staticUrl : zmf::config('baseurl');
        $cs = Yii::app()->clientScript;
        if ($type == 'web') {
            $cs->registerCssFile($staticUrl . 'common/css/bootstrap.min.css');
//            $cs->registerCssFile($staticUrl . 'common/css/font-awesome.min.css');
//            $cs->registerCssFile($staticUrl . 'common/css/font-awesome-ie7.min.css');
            $cs->registerCssFile($staticUrl . 'common/css/newsoul.css');
            if (in_array(Yii::app()->getController()->id, array('siteinfo','chengyu')) && in_array(Yii::app()->getController()->getAction()->id, array('create','update','content'))) {
                $cs->registerCoreScript('jquery');
                $cs->registerScriptFile($staticUrl . "common/js/zmf.js", CClientScript::POS_END);
            }
        }elseif($type=='mobile'){
            $cs->registerCssFile($staticUrl . 'common/css/frozen.css');
            $cs->registerCssFile($staticUrl . 'common/css/mobile.css');
            $cs->registerScriptFile($staticUrl . "common/js/zepto.min.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($staticUrl . "common/js/frozen.js", CClientScript::POS_END);
        }
    }

}
