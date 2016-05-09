<?php

class Posts extends CActiveRecord {

    const STATUS_NOTPASSED = 0;
    const STATUS_PASSED = 1;
    const STATUS_STAYCHECK = 2;
    const STATUS_DELED = 3;
    const STATUS_REDIRECT = 4; //重定向
    
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{posts}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('cTime,updateTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('uid, title, content', 'required'),
            array('uid, faceimg, classify, mapZoom, comments, top, hits, status, cTime, updateTime', 'numerical', 'integerOnly' => true),
            array('title, tagids', 'length', 'max' => 255),
            array('lat, long', 'length', 'max' => 50),
            array('favors', 'length', 'max' => 11),
            array('favorite', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, title, content, faceimg, classify, lat, long, mapZoom, comments, favors, favorite, top, hits, tagids, status, cTime, updateTime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '作者ID',
            'title' => '标题',
            'content' => '正文',
            'faceimg' => '封面图',
            'classify' => '分类',
            'lat' => '纬度',
            'long' => '经度',
            'mapZoom' => '地图缩放级别',
            'comments' => '评论数',
            'favors' => '点赞数',
            'favorite' => '收藏数',
            'top' => '是否置顶',
            'hits' => '阅读数',
            'tagids' => '标签组',
            'status' => 'Status',
            'cTime' => '创建世界',
            'updateTime' => '最近更新时间',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Posts the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getSimpleInfo($key, $type = '', $atype = '') {
        if (is_array($key)) {
            $keyid = $key['keyid'];
            $origin = $key['origin'];
        } else {
            $keyid = $key;
        }
        if ($origin == '' OR ! in_array($origin, array('posts', 'comments', 'attachments', 'naodong', 'gongyi', 'poipost', 'poitips', 'question', 'answer', 'position', 'yueban', 'goods'))) {
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
        if (!$keyid || !$type || !in_array($type, array('Chengyu'))) {
            return false;
        }
        $model = new $type;
        $model->updateCounters(array($field => $num), ':id=id', array(':id' => $keyid));
    }

    public static function getAll($params, &$pages, &$comLists) {
        $sql = $params['sql'];
        if (!$sql) {
            return false;
        }
        $pageSize = $params['pageSize'];
        $_size = isset($pageSize) ? $pageSize : 30;
        $com = Yii::app()->db->createCommand($sql)->query();
        $pages = new CPagination($com->rowCount);
        $criteria = new CDbCriteria();
        $pages->pageSize = $_size;
        $pages->applylimit($criteria);
        $com = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $com->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $com->bindValue(':limit', $pages->pageSize);
        $comLists = $com->queryAll();
    }

}
