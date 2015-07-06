<?php

class AppApi extends Controller {

    public $keyid;
    public $uid;
    public $appPlatform; //app平台    
    public $appCode; //当前平台所对应的code
    public $errorCode = 0; //错误代码
    public $succCode = 1; //成功代码
    public $tipsCode = 2; //提示代码    
    //必填参数
    public $version; //获取软件版本号
    public $time; //请求时客户端的时间戳
    public $usercode; //给用户的安全串
    public $userInfo;
    public $iosKey = 'AW0oQ9nMyqmF9erP';
    public $androidKey = '83nlir0NbKwfDaii';
    public $pageSize;
    public $page;
    public $emptyarr;
    public $startTime; //运行时间记录
    public $currentVersion; //返回给客户端当前的版本号
    public $tableVersion; //客户端传递的版本号

    function init() {
        $this->startTime = microtime(true);
//        self::checkApp();
        parent::init();
        $_pageSize = self::getValue('pageSize',0,2);
        $this->pageSize = $_pageSize ? $_pageSize : 30;
        $page = self::getValue('page',0,2);
        $this->page = $page ? $page : 1;
        $this->usercode = tools::val('usercode', 1);
        if ($this->page < 1) {
            $this->page = 1;
        }
        //获取app传递的内容版本号
        $tableVersion = tools::val('tableVersion');
        $this->tableVersion = $tableVersion ? $tableVersion : 0;
        $this->emptyarr = new stdClass();
    }

    public function checkApp() {
        $code = tools::val('appcode', 1);
        $time = tools::val('time', 1);
        $version = tools::val('version', 1);
        $platform = strtolower(tools::val('platform', 1));
        if (!$code || !$time || !$platform || !$version) {
            self::output(self::getErrorInfo('notInService'), 403);
        }
        if ($platform == 'ios') {
            $_code = $this->iosKey;
            $this->appPlatform = 'ios';
        } else {
            $_code = $this->androidKey;
            $this->appPlatform = 'android';
        }
        $this->appCode = $_code;
        $this->version = $version;
        if (md5($time . $_code) != $code) {
            self::output(self::getErrorInfo('dataIncorrect'),403);
        }
    }

    public function checkUser() {
        if (!$this->usercode) {
            self::output('缺少参数：usercode', $this->errorCode);
        }
        $code = zmf::jieMi($this->usercode);
        $arr = explode('#', $code);
        //如果不能解密字符串，或者不是类似于'123#ios#1412555521'则报错
        if (!$code || !$arr || count($arr) != 3 || $arr[1] != $this->appPlatform) {
            self::output('验证用户信息失败，请重新登录', 400);
        }
        $this->uid = $arr[0];
        $this->userInfo = User::model()->findByPk($this->uid);
        if (!$this->userInfo) {
            self::output('验证用户信息错误：不存在的用户', 400);
        }
        if($this->userInfo['code']!=$this->usercode){
            self::output('您的账号已在其他设备登录，请重新登录', 400);
        }
        //如果已经过期
        if ((zmf::now() - $arr[2]) > 86400 * 30) {
            self::output('由于长时间未登录，请重新登录', 400);
        }
    }

    /**
     * 统一已JSON输出
     * @param type $data 需要输出的内容
     * @param type $status 状态
     * @param type $code 状态码
     * @param type $encode 是否加密
     */
    public function output($data, $status = 1, $code = '', $encode = 0) {
        if ($code === '') {
            $code = $status;
        }
        $isJson = 0;
        $outPutData = array(
            'status' => $status,
            'encode' => $encode,
            'json' => $isJson,
            'msg' => $data,
            'code' => $code,
            'currentVersion' => $this->currentVersion ? $this->currentVersion : 0
        );
        if (!empty($ps) && is_array($ps)) {
            $outPutData = array_merge($outPutData, $ps);
        }
        $json = CJSON::encode($outPutData);
        header("Content-type:application/json;charset=UTF-8");
        echo $json;
        self::log();
        self::log(var_export($outPutData, true));
        Yii::app()->end();
    }

    /**
     * 获取图片宽高等信息
     */
    public function getImgInfo($url) {
        if ($this->dontGetImg != 'yes') {
//      $imginfo = @getimagesize($url);
            $tmp = zmf::myGetImageSize(trim($url));
            if (empty($tmp)) {
                return array('0', '0');
            }
            $imginfo[] = $tmp['width'];
            $imginfo[] = $tmp['height'];
            return $imginfo;
        } else {
            return array('0', '0');
        }
    }

    public function log($content = '') {
        if (zmf::config('appRuntimeLog')) {
            if (!$content) {
                $content = '##' . zmf::time() . '===' . ('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']) . PHP_EOL;
                $content.=(microtime(true) - $this->startTime) . PHP_EOL;
                $content.=var_export($_POST, true) . PHP_EOL;
                $content.=str_repeat('-', 40);
            }
            file_put_contents(Yii::app()->basePath . '/runtime/appTimes.txt', $content . PHP_EOL, FILE_APPEND);
        }
    }

    //==============____小函数___==============
    /**
     * 根据名称返回$_GET或$_POST的数据
     * @param type $key
     * @param type $notEmpty
     * @param type $ttype
     * @param type $textonly 0富文本，1纯文本，2数字，默认纯文本
     * @return type
     */
    public function getValue($key, $notEmpty = false, $textonly = 1, $decode = 0) {
        $return = zmf::filterInput($_GET[$key], $textonly);
        $arr = array(
            'uid' => '作者ID',
            'content' => '内容',
            'type' => '类型',
            'token' => '请求错误，请退出应用并重新启动',
        );
        if ($notEmpty) {
            if (empty($return)) {
                $_info = $arr[$key];
                if (!$_info) {
                    $_info = $key;
                }
                self::output('[' . $_info . ']不能为空', $this->errorCode);
            }
        }
        return $return;
    }

    public function getByPage($params, &$pages, &$posts) {
        $sql = $params['sql'];
        $pageSize = $this->pageSize;
        $page = $this->page;
        if (!$sql) {
            return false;
        }

        $criteria = new CDbCriteria();
//        $_sql = preg_replace('/select (.+?) from/i', 'SELECT COUNT(*) AS count FROM', $sql);
//        $_total = Yii::app()->db->createCommand($_sql)->queryRow();
//        $count = $_total['count'];
        $total = Yii::app()->db->createCommand($sql)->query();
        $count = $total->rowCount;
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;
        $pages->applyLimit($criteria);
        $com = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $com->bindValue(':offset', intval(($page - 1) * $pageSize));
        $com->bindValue(':limit', intval($pageSize));
        $posts = $com->queryAll();
    }

    /**
     * 根据指定code返回错误信息
     * @param type $code
     * @return string
     */
    public function getErrorInfo($code) {
        $infoArr = array(
            'dataIncorrect' => '数据不正确',
            'notInApp' => '不允许的来源APP',
            'notInService' => '暂不能提供服务',
            'haveNoUid' => '缺失用户ID',
            'haveNoKeyid' => '缺失KEYID',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
        );
        return $infoArr[$code];
    }

}
