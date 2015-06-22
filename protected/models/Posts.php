<?php

class Posts extends CActiveRecord {

    const STATUS_NOTPASSED = 0;
    const STATUS_PASSED = 1;
    const STATUS_STAYCHECK = 2;
    const STATUS_DELED = 3;
    const STATUS_REDIRECT = 4; //重定向
    const CLASSIFY_POST = 1; //文章
    const CLASSIFY_BLOG = 2; //博客
    const CLASSIFY_TRAVEL_LOG = 3; //游记
    const CLASSIFY_CAIJI = 4; //采集的游记
    const CLASSIFY_GOODS = 5; //什么值得买的分类

    public $coltitle; //所属分类的标题

    public function tableName() {
        return '{{posts}}';
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('uid,colid, title, content', 'required'),
            array('colid', 'chkColid'),
            array('title', 'chkTitle'),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('cTime,updateTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('lat,long', 'default', 'setOnEmpty' => true, 'value' => '0'),
            //array('platform', 'default', 'setOnEmpty' => true, 'value' => tools::getPlatform()),          
            array('uid, colid, hits, cTime, updateTime, status, top , favors,mapZoom,classify,comments,areaid,faceimg,favorite', 'numerical', 'integerOnly' => true),
            array('title,sourceurl,sourceinfo,keywords,description,redirect', 'length', 'max' => 255),
            array('lat,long', 'length', 'max' => 50),
            array('platform', 'length', 'max' => 16),
            array('platform', 'default', 'setOnEmpty' => true, 'value' => tools::getPlatform()),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('title', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authorInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
            'columnInfo' => array(self::BELONGS_TO, 'Column', 'colid'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => 'Uid',
            'colid' => '分类',
            'title' => '标题',
            'content' => '正文',
            'hits' => '点击',
            'cTime' => '创建时间',
            'updateTime' => '更新时间',
            'status' => '状态',
            'top' => '是否置顶',
            'favors' => '赞',
            'keywords' => 'SEO关键词',
            'description' => 'SEO描述',
            'classify' => '分类',
            'sourceurl' => '来源地址',
            'sourceinfo' => '来源信息',
            'long' => '经度',
            'lat' => '纬度',
            'mapZoom' => '缩放级别',
            'comments' => '评论数',
            'areaid' => '所属地区',
            'platform' => '平台信息',
            'redirect' => '页面跳转',
            'faceimg' => '封面图',
            'favorite' => '收藏数',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('title', $this->title, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function chkColid($attribute, $params) {
        $a = $this->colid;
        if (!$a) {
            $this->addError('colid', '文章分类不能为空');
        }
    }

    public function chkTitle($attribute, $params) {
        $a = $this->title;
        if ($a) {
            if (strpos($a, '?') !== false || strpos($a, '？') !== false) {
                $this->addError('title', '您是不是要提问？' . CHtml::link('请点这里继续', array('question/create', 'title' => $a), array('target' => '_blank')));
            }
            $isq = Question::isQuestion($a);
            if ($isq) {
                $this->addError('title', '您是不是要提问？' . CHtml::link('请点这里继续', array('question/create', 'title' => $a), array('target' => '_blank')));
            }
        }
    }

    public static function getSimpleInfo($key, $type = '', $atype = '') {
        if (is_array($key)) {
            $keyid = $key['keyid'];
            $origin = $key['origin'];
        } else {
            $keyid = $key;
        }
        if ($origin == '' OR ! in_array($origin, array('posts', 'comments', 'attachments', 'naodong', 'gongyi', 'poipost', 'poitips', 'question', 'answer', 'position', 'yueban','goods'))) {
            return false;
        }
        if ($origin == 'poipost') {
            $origin = 'poi_post';
        } elseif ($origin == 'poitips') {
            $origin = 'poi_tips';
        } elseif ($origin == 'yueban') {
            $origin = 'user_yueban';
        }
        $sql = "SELECT * FROM {{{$origin}}} WHERE id={$keyid}";
        $infos = Yii::app()->db->createCommand($sql)->queryAll();
        $info = $infos[0];
        if (!$info) {
            return false;
        } elseif ($info['status'] != Posts::STATUS_PASSED) {
            if ($atype != 'admin') {
                return false;
            }
        }
        if (!empty($type)) {
            if ($origin == 'position' && $type == 'title') {
                $_title = '';
                if ($info['title_cn'] != '') {
                    $_title = $info['title_cn'];
                } elseif ($info['title_en'] != '') {
                    $_title = $info['title_en'];
                } else {
                    $_title = $info['title_local'];
                }
                return $_title;
            }
            return $info[$type];
        } else {
            return $info;
        }
    }

    public static function statInfo($keyid, $type = 'posts') {
        $data = array(
            'replyNum' => Comments::model()->count('logid=:keyid AND classify=:classify', array(':keyid' => $keyid, ':classify' => $type))
        );
        return $data;
    }

    public static function tops($params = array()) {
        $table = $params['table'];
        $limit = $params['limit'];
        $field = $params['field'];
        $order = $params['order'];
        $sql = $params['sql'];
        if (!$table && !$sql) {
            return false;
        }
        if (!$limit) {
            $limit = 10;
        }
        if (!$field) {
            $field = '*';
        }
        if ($order) {
            $_order = " ORDER BY {$order} DESC";
        } else {
            $_order = " ORDER BY hits DESC";
        }
        if (!$sql) {
            $sql = "SELECT {$field} FROM {{{$table}}} WHERE status=" . Posts::STATUS_PASSED . " {$_order} LIMIT {$limit}";
        }
        if (!$sql) {
            return false;
        }
        $key = md5($sql);
        //$com = zmf::getFCache($key);
        if (!$com) {
            $com = Yii::app()->db->createCommand($sql)->queryAll();
            //zmf::setFCache($key, $com, 86400);
        }
        return $com;
    }

    public static function getAll($params, &$pages, &$comLists) {
        $sql = $params['sql'];
        if (!$sql) {
            return false;
        }
        $pageSize = $params['pageSize'];
        $_size = isset($pageSize) ? $pageSize : 30;
        $com = Yii::app()->db->createCommand($sql)->query();
        //添加限制，最多取1000条记录
        //todo，按不同情况分不同最大条数
        $total=$com->rowCount>1000 ? 1000 : $com->rowCount;
        $pages = new CPagination($com->rowCount);
        $criteria = new CDbCriteria();
        $pages->pageSize = $_size;
        $pages->applylimit($criteria);
        $com = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $com->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $com->bindValue(':limit', $pages->pageSize);
        $comLists = $com->queryAll();
    }

    public static function checkAndNotice($type, $logid, $keyid) {
        if (zmf::config('badwordsHandleStyle') == 'filter') {
            return true;
            Yii::app()->end();
        }
        $status = Yii::app()->session['checkHasBadword'];
        if ($status == 'no') {
            return true;
            Yii::app()->end();
        } else {
            $uid = Yii::app()->user->id;
            switch ($type) {
                case 'posts':
                    if (zmf::config('badwordsHandleStyle') == 'forbidden') {
                        Posts::model()->updateByPk($keyid, array('status' => Posts::STATUS_STAYCHECK));
                    }
                    $info = Yii::app()->user->name . "的【文章】触及敏感词，<a href='" . zmf::config('domain') . Yii::app()->createUrl('posts/index', array('id' => $logid)) . "' target='_blank'>查看详情</a>";

                    if (T::addNotice('1', $logid, $info)) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'postsCom':
                    if (zmf::config('badwordsHandleStyle') == 'forbidden') {
                        Comments::model()->updateByPk($keyid, array('status' => Scenic::STATUS_STAYCHECK));
                    }
                    $info = Yii::app()->user->name . "的【文章评论】触及敏感词，<a href='" . zmf::config('domain') . Yii::app()->createUrl('posts/index', array('id' => $keyid)) . "' target='_blank'>查看详情</a>";
                    if (T::addNotice('1', $keyid, $info)) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'attachmentsCom':
                    if (zmf::config('badwordsHandleStyle') == 'forbidden') {
                        Comments::model()->updateByPk($keyid, array('status' => Scenic::STATUS_STAYCHECK));
                    }
                    $info = Yii::app()->user->name . "的【图片评论】触及敏感词，<a href='" . zmf::config('domain') . Yii::app()->createUrl('attachments/index', array('id' => $keyid)) . "' target='_blank'>查看详情</a>";
                    if (T::addNotice('1', $keyid, $info)) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
            }
        }
    }

    public static function getNearBy($arr, $origin) {
        $lat = $arr['lat'];
        $lng = $arr['long'];
        $notInclude = $arr['notId'];
        $limit = 5;
        if ($origin == '' || !in_array($origin, array('posts', 'position'))) {
            return false;
        }
        $_cal3 = "(ROUND(12756.274 * ASIN(SQRT(POW(SIN(((lat * PI() / 180.0) - ({$lat} * PI() / 180.0))/2),2) +COS(lat * PI() / 180.0)*COS({$lat} * PI() / 180.0)*POW(SIN(((`long` * PI() / 180.0)-({$lng} * PI() / 180.0))/2),2)))))";
        $longSql = "SELECT DISTINCT(id), '{$origin}',{$_cal3} AS distance FROM {{{$origin}}} WHERE status=1 AND id!={$notInclude} AND {$_cal3}<=10 ORDER BY distance LIMIT 0,{$limit}";
        $tops = Yii::app()->db->createCommand($longSql)->queryAll();
        if (!empty($tops)) {
            $ids = join(',', array_keys(CHtml::listData($tops, 'id', '')));
            $_into = zmf::now() . '#' . $ids;
            if ($origin != 'position') {
                Posts::model()->updateByPk($notInclude, array('nearby' => $_into));
                $_sql = "SELECT id,title FROM {{posts}} WHERE id IN($ids) AND status=" . Posts::STATUS_PASSED . " ORDER BY FIELD(id,{$ids})";
            } else {
                Position::model()->updateByPk($notInclude, array('nearby' => $_into));
                $_sql = "SELECT id,title_cn,title_en,title_local,score,scorer,classify FROM {{position}} WHERE id IN($ids) AND status=" . Posts::STATUS_PASSED . " ORDER BY FIELD(id,{$ids})";
            }
            if ($ids != '') {
                $tops = Yii::app()->db->createCommand($_sql)->queryAll();
            }
        }
        return $tops;
    }

    public static function checkInfo(&$info, $keyid, $origin, $return = false) {
        if (!$keyid) {
            return array('status' => 0, 'msg' => '请选择要查看的页面');
        }
        if (!$origin || !in_array($origin, array('posts', 'comments', 'attachments', 'travel'))) {
            return array('status' => 0, 'msg' => '不允许的类型');
        }
        //$_var = "{$origin}Field";
        //$field = $this->$_var;
        if ($field == '') {
            $field = '*';
        }
        $cacheKey = md5("checkOtherInfo-{$origin}-{$keyid}-{$field}");
        //$info = zmf::getFCache($cacheKey);
        if (!$info) {
            $_sql = "SELECT {$field} FROM {{{$origin}}} WHERE id={$keyid}";
            $info = Yii::app()->db->createCommand($_sql)->queryAll();
            $info = $info[0];
            //zmf::setFCache($cacheKey, $info, 3600);
        }
        if (!$info) {
            return array('status' => 0, 'msg' => Yii::t('default', 'pagenotexists'));
        } elseif (intval($info['status']) != Posts::STATUS_PASSED) {
            return array('status' => 0, 'msg' => Yii::t('default', 'notpassed'));
        }
        return array('status' => 1, 'msg' => $info);
    }

    public static function postClassify($return = '') {
        $arr = array(
            Posts::CLASSIFY_POST => '文章',
            Posts::CLASSIFY_BLOG => '博客',
            Posts::CLASSIFY_GOODS => '什么值得买',
        );
        if ($return) {
            return $arr[$return];
        } else {
            return $arr;
        }
    }

    public static function getOne($id) {
        if (!$id)
            return false;
        $info = Posts::model()->findByPk($id);
        return $info;
    }

    /**
     * 处理内容
     * @param type $content
     * @return type
     */
    public static function handleContent($content) {
        $pattern = "/<[img|IMG].*?data=[\'|\"](.*?)[\'|\"].*?[\/]?>/i";
        preg_match_all($pattern, $content, $match);
        $arr_attachids = array();
        if (!empty($match[0])) {
            $arr = array();
            foreach ($match[0] as $key => $val) {
                $_key = $match[1][$key];
                $arr[$_key] = $val;
                $arr_attachids[] = $match[1][$key];
            }
            if (!empty($arr)) {
                foreach ($arr as $thekey => $imgsrc) {
                    $content = str_ireplace("{$imgsrc}", '[attach]' . $thekey . '[/attach]', $content);
                }
            }
        }
        $content = strip_tags($content, '<b><strong><em><span><a><p><u><i><img><br><br/>');
        $replace = array(
            '/<a.*?href="(.*?)".*?>(.+?)<\/a>/ie',
            '/(((http|https):\/\/)[a-z0-9;&#@=_~%\?\/\.\,\+\-\!\:]+)/ie', //替换纯文本链接
            "/style=\"[^\"]*?\"/i"
        );
        $to = array(
            "self::autoUrl('\\1','\\2')",
            "self::textUrl('\\1')",
            ''
        );
        $content = preg_replace($replace, $to, $content);
        if (zmf::config('checkBadWords')) {
            $h_style = zmf::config("badwordsHandleStyle");
            //仅过滤
            if ($h_style === 'filter') {
                $content = zmf::badWordsReplace($content);
                //仅通知 过滤通知    
            } elseif ($h_style === 'notice' OR $h_style === 'filterNotice') {
                $status = Yii::app()->session['checkHasBadword'];
                if ($status != 'yes') {
                    $keywords = zmf::getBadwords();
                    foreach ($keywords as $word) {
                        if (mb_strpos($content, $word) !== false) {
                            Yii::app()->session['checkHasBadword'] = 'yes';
                        }
                    }
                }
                if ($h_style === 'filterNotice') {
                    $content = zmf::badWordsReplace($content);
                }
            }
        }
        $data = array(
            'content' => $content,
            'attachids' => $arr_attachids,
        );
        return $data;
    }

    /**
     * 判断发布内容是否应被禁止通过
     * @param string $content
     * @param string $type
     * @return array
     */
    public static function isForbidden($content, $type = 'post') {
        //todo，增加开关
//        $checkStatus=zmf::config('ugcForbidCheck');
//        if(!$checkStatus){
//            return array(
//                'status' => Posts::STATUS_PASSED,
//                'msg' => '',
//            );
//        }
        $status = Posts::STATUS_PASSED;
        $uid = zmf::uid();
        $reason = '';
        $forbidden = false;
        //判断文章的重复率        
        $rate = tools::calStrRate($content);
        if ($rate < 0.1) {
            $status = Posts::STATUS_STAYCHECK;
            $reason = '重复率太高';
            $forbidden = true;
        }
        //判断和以前发布的内容的重复率
        if (!$forbidden) {
            $posts = Users::getNewUgc($type, $uid, 5, 'content');
            if (!empty($posts)) {
                foreach ($posts as $val) {
                    $_content = tools::getContentOnly($val['content']);
                    similar_text($_content, $content, $_per);
                    if ($_per >= 85) {
                        $status = Posts::STATUS_STAYCHECK;
                        $reason = '和以前的重复';
                        $forbidden = true;
                        break;
                    }
                }
            }
        }
        //todo,根据内容KNN判断是否是广告
        if (!$forbidden) {
            
        }
        return array(
            'status' => $status,
            'msg' => $reason,
        );
    }

    /**
     * 给内容自动加上坐标链接
     * @param type $data
     * @return boolean
     */
    public static function autoLink($data) {
        $path = zmf::config('async_push_path');
        $host = zmf::config('async_push_host');
        if (!$path || !$host) {
            return false;
        }
        $content = $data['content'];
        $url = $data['url'];
        if (!$data || !$content || !$url) {
            return false;
        }
        $id = uniqid();
        $dir = Yii::app()->basePath . '/runtime/autolink';
        zmf::createUploadDir($dir);
        file_put_contents($dir . "/$id.txt", $content);
        $asyncdata = "method=linkPoi&" . $url . "&fileid={$id}";
        AsyncController::Async($asyncdata, 'get');
    }

    /**
     * 按标题搜索某个关键词的文章
     * @param type $keyword
     * @param type $limit
     * @return type
     */
    public function suggest($keyword, $limit = 20) {
        $posts = Posts::model()->findAll(array(
            'condition' => '(title LIKE :keyword) AND (classify="' . Posts::CLASSIFY_TRAVEL_LOG . '") AND status=' . Posts::STATUS_PASSED,
            'limit' => $limit,
            'params' => array(
                ':keyword' => '%' . strtr($keyword, array('%' => '\%', '_' => '\_', '\\' => '\\\\')) . '%',
            ),
        ));
        return $posts;
    }

    /**
     * 将链接自动转换为短链接
     * @param type $href
     * @param type $text
     * @return type
     */
    public static function autoUrl($href, $text) {
        if (self::checkImg($href)) {
            return $href;
        }
        if (self::checkUrlDomain($href)) {
            return $href;
        }
        $info = Urls::FAA($href);
        if ($info) {
            return "[url={$info['code']}]{$text}[/url]";
        } else {
            return $text;
        }
    }

    public static function textUrl($link) {
        if (self::checkImg($link)) {
            return $link;
        }
        if (self::checkUrlDomain($link)) {
            return $link;
        }
        $info = Urls::FAA($link);
        if ($info) {
            return "[texturl={$info['code']}]{$info['code']}[/texturl]";
        } else {
            return $link;
        }
    }

    /**
     * 根据链接判断是否是图片
     * @param type $url
     */
    public static function checkImg($url) {
        $suffix = substr($url, (strpos($url, '.', 1)) + 1);
        $suffix = strtolower($suffix);
        if (in_array($suffix, array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico'))) {
            return true;
        }
        return false;
    }

    /**
     * 如果是本网站的链接就不再短链接
     * @param type $url
     * @return boolean
     */
    public static function checkUrlDomain($url) {
        $config = zmf::config('notShortUrls');
        if (!$config) {
            return false;
        }
        $arr = array_filter(explode('#', $config));
        if (empty($arr)) {
            return false;
        }
        $url = strtolower($url);
        foreach ($arr as $v) {
            if (strpos($url, $v) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * 更新查看次数
     * @param type $keyid 对象ID
     * @param type $type 对象类型
     * @param type $num 更新数量
     * @param type $field 更新字段
     * @return boolean
     */
    public static function updateCount($keyid, $type, $num = 1, $field = 'hits') {
        if (!$keyid || !$type || !in_array($type, array('Question', 'Answer', 'PoiPost', 'Posts', 'PoiTips', 'Position', 'Attachments', 'Users', 'Travel', 'Tags', 'SiteInfo', 'UserYueban','Goods'))) {
            return false;
        }
        $model = new $type;
        $model->updateCounters(array($field => $num), ':id=id', array(':id' => $keyid));
    }

}
