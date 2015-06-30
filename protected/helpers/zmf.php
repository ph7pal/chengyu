<?php

class zmf {

    public static function test($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public static function config($type) {
        if ($type == 'authcode') {
            return 'b93154b988e33fdf0d144fde73028b77';
        } elseif ($type == 'authorCode') {
            return '2013@zhangMaofei';
        }

        if (empty(Yii::app()->params['c'])) {
            $_c = Config::model()->findAll();
            $configs = CHtml::listData($_c, 'name', 'value');
            tools::writeSet($configs);
            return stripcslashes($configs[$type]);
        } else {
            return stripcslashes(Yii::app()->params['c'][$type]);
        }
    }

    public static function getBadwords() {
        $words = self::config('badwords');
        if (!$words) {
            return false;
        }
        $words = array_unique(array_filter(explode('#', $words)));
        return $words;
    }

    public static function badWordsReplace($str, $returnOnly = false) {
        if (self::config('checkBadWords')) {
            $bdc = Yii::app()->basePath . '/runtime/bad_words_cache.php';
            if (file_exists($bdc)) {
                include $bdc;
            }
            if (empty($badwords_array)) {
                $w_arr = self::getBadwords();
                if (!empty($w_arr)) {
                    foreach ($w_arr as $v) {
                        $s_len = mb_strlen($v, 'UTF-8');
                        switch ($s_len) {
                            // 两个字的敏感词，只替换第二字为'*'
                            case 2 : {
                                    $badwords_array[$v] = mb_substr($v, 0, 1, 'UTF-8') . '*';
                                    break;
                                }
                            // 敏感词，一个字，虽然很少。不过可能会有。
                            case 1 : {
                                    $badwords_array[$v] = '*';
                                    break;
                                }
                            // 其他情况，取首位各一个字，中间根据多少个字替换多少个'*';
                            default : {
                                    $badwords_array[$v] = mb_substr($v, 0, 1, 'UTF-8') . str_repeat('*', ($s_len - 2)) . mb_substr($v, -1, 1, 'UTF-8');
                                }
                        }
                    }
                    $fp = fopen($bdc, 'w+');
                    $str = '<?php $badwords_array =' . var_export($badwords_array, true) . ';';
                    fputs($fp, $str);
                    fclose($fp);
                }
            }
            $str = strtr($str, $badwords_array);
            return $str;
        } else {
            return $str;
        }
    }

    public static function keywordsUrl($str) {
        if (self::config('keywordsUrl')) {
            $keys = Yii::app()->basePath . '/runtime/keywords.php';
            if (file_exists($keys)) {
                include $keys;
            }
            if (empty($keywords)) {
                $words = self::config('keywords');
                if (!$words) {
                    return $str;
                }
                $w_arr = array_unique(array_filter(explode(',', $words)));
                if (!empty($w_arr)) {
                    foreach ($w_arr as $v) {
                        $item_arr = array_filter(explode("|", $v));
                        $keywords[$item_arr[0]] = $item_arr[1];
                    }
                }
                $fp = fopen($keys, 'w+');
                $str = '<?php $badwords_array =' . var_export($keywords, true) . ';';
                fputs($fp, $str);
                fclose($fp);
            }
            foreach ($keywords as $key => $url) {
                if (mb_strpos($str, $key) !== false) {
                    $str = str_replace($key, "<a href='{$url}' target=_blank >" . $key . "</a>", $str);
                }
            }
            return $str;
        } else {
            return $str;
        }
    }

    public static function badWordsCache($badwords, $cachefile = null, $enc = 'UTF-8') {
        $file = file($badwords);
        if (!$file) {
            return false;
        }
        $array = array();
        foreach ($file as $k => $v) {
            $v = trim($v);
            if (!$v)
                continue;
            // 下面只是生成 敏感词 转换后的格式,如果要求不苛刻，可以简单写成 $array[$k] = '*';
            // 这里我复杂处理一下，因为是生成 cache 不用管 效率。
            // 下面的替换过程其实可以很随意，不喜欢'*'号可以用'?','x'等等，随意的很。
            $s_len = mb_strlen($v, $enc);
            switch ($s_len) {
                // 两个字的敏感词，只替换第二字为'*'
                case 2 : {
                        $array[$v] = mb_substr($v, 0, 1, $enc) . '*';
                        break;
                    }
                // 敏感词，一个字，虽然很少。不过可能会有。
                case 1 : {
                        $array[$v] = '*';
                        break;
                    }
                // 其他情况，取首位各一个字，中间根据多少个字替换多少个'*';
                default : {
                        $array[$v] = mb_substr($v, 0, 1, $enc) . str_repeat('*', ($s_len - 2)) . mb_substr($v, -1, 1, $enc);
                    }
            }
        }
        // 保存序列备用。
        if (!$cachefile)
            $cachefile = $badwords . '_cache.php';
        $str = '<?php $badwords_last_time = ' . filemtime($badwords) . ';$badwords_array = ' . var_export($array, true) . ';';
        file_put_contents($cachefile, $str, LOCK_EX);
        return $array;
    }

    public static function keyWordsCache($keywords, $cachefile = null, $enc = 'UTF-8') {
        $file = file($keywords);
        if (!$file) {
            return false;
        }
        $array = array();
        foreach ($file as $k => $v) {
            $v = trim($v);
            if (!$v)
                continue;
            $item_arr = array_filter(explode("|", $v));
            $array[$item_arr[0]] = $item_arr[1];
        }
        $str = '<?php $keywords = ' . var_export($array, true) . ';';
        file_put_contents($cachefile, $str, LOCK_EX);
        return $array;
    }

    public static function readTxt($file, $mode = 'r') {
        if (is_readable($file)) {
            $handle = fopen($file, $mode);
            flock($handle, LOCK_EX);
            $content = fread($handle, filesize($file));
            flock($handle, LOCK_UN);
            fclose($handle);
            return $content;
        } else {
            return false;
        }
    }

    public static function stripStr($string) {
        $string = strip_tags($string);
        $replace = array(
            '/\[attach\](\d+)\[\/attach\]/i',
            '/\[atone\](\d+)\[\/atone\]/i',
            "/\[url=.+?\](.+?)\[\/url\]/i",
            "/\[texturl=.+?\].+?\[\/texturl\]/i",
            "/\[poi=.+?\](.+?)\[\/poi\]/i",
        );
        $to = array(
            '',
            '',
            '$1',
            '',
            '$1',
        );
        $string = preg_replace($replace, $to, $string);
        return $string;
    }

    public static function subStr($string, $sublen = 20, $start = 0, $separater = '...') {
//        $string = trim(str_replace(PHP_EOL, '', $string));
//        $qian = array(" ", "　", "\t", "\n", "\r");
//        $hou = array("", "", "", "", "");
//        $string = str_replace($qian, $hou, $string);
        $string = self::stripStr($string);
        $string = self::cutstr($string, $sublen, $separater);
        return $string;
        $code = 'UTF-8';
        if ($code == 'UTF-8') {
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if (count($t_string[0]) - $start > $sublen) {
                $str = join('', array_slice($t_string[0], $start, $sublen));
                return $str . $separater;
            } else {
                return join('', array_slice($t_string[0], $start, $sublen));
            }
        } else {
            $start = $start * 2;
            $sublen = $sublen * 2;
            $strlen = strlen($string);
            $tmpstr = '';
            for ($i = 0; $i < $strlen; $i++) {
                if ($i >= $start && $i < ($start + $sublen)) {
                    if (ord(substr($string, $i, 1)) > 129) {
                        $tmpstr .= substr($string, $i, 2);
                    } else {
                        $tmpstr .= substr($string, $i, 1);
                    }
                }
                if (ord(substr($string, $i, 1)) > 129)
                    $i++;
            }
            if (strlen($tmpstr) < $strlen)
                $tmpstr .= $separater;
            return $tmpstr;
        }
    }

    public static function cutstr($string, $length, $dot = ' ...', $charset = 'utf-8') {
        if (strlen($string) <= $length) {
            return $string;
        }
        $pre = chr(1);
        $end = chr(1);
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);
        $strcut = '';
        if (strtolower($charset) == 'utf-8') {
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
        } else {
            $_length = $length - 1;
            for ($i = 0; $i < $length; $i++) {
                if (ord($string[$i]) <= 127) {
                    $strcut .= $string[$i];
                } else if ($i < $_length) {
                    $strcut .= $string[$i] . $string[++$i];
                }
            }
        }
        $strcut = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        $pos = strrpos($strcut, chr(1));
        if ($pos !== false) {
            $strcut = substr($strcut, 0, $pos);
        }
        return $strcut . $dot;
    }

    public static function createUploadDir($dir) {
        if (!is_dir($dir)) {
            $temp = explode('/', $dir);
            $cur_dir = '';
            for ($i = 0; $i < count($temp); $i++) {
                $cur_dir .= $temp[$i] . '/';
                if (!is_dir($cur_dir)) {
                    mkdir($cur_dir, 0777);
                }
            }
        }
    }

    public static function uploadDirs($ctime = '', $base = 'site', $type = 'posts', $return = '', $create = false) {
        $dirConfig = self::config('imgThumbSize');
        $sizes = array_unique(array_filter(explode(",", $dirConfig)));
        if (empty($sizes)) {
            return false;
        }
        $dir = array();
        if (!$ctime) {
            $ctime = time();
        }
        $baseUrl = self::attachBase($base);
        $_extra = self::getUpExtraUrl($ctime);
        $_thedir = $baseUrl . $type . '/' . $_extra . '/';
        if ($create) {
            zmf::createUploadDir($_thedir);
        }
        foreach ($sizes as $size) {
            $dir[$size] = $_thedir . $size . '_';
        }
        if (!empty($return)) {
            $dir = $dir[$return];
        }
        return $dir;
    }

    public static function attachBase($base) {
        if ($base === 'site') {
            //根据网站          
            if (self::config('imgVisitUrl') != '') {
                $baseUrl = self::config('imgVisitUrl') . '/';
            } else {
                $baseUrl = self::config('baseurl') . 'attachments/';
            }
        } elseif ($base === 'app') {
            //根据应用来
            if (self::config('imgUploadUrl') != '') {
                $baseUrl = self::config('imgUploadUrl') . '/';
            } else {
                $baseUrl = Yii::app()->basePath . "/../attachments/";
            }
        } elseif ($base == 'upload') {
            //解决imagick open图片问题
            if (self::config('imgUploadUrl') != '') {
                $baseUrl = self::config('imgUploadUrl') . '/';
            } else {
                $baseUrl = self::config('baseurl') . 'attachments/';
            }
        } else {
            $baseUrl = '';
        }
        return $baseUrl;
    }

    public static function getUpExtraUrl($date = '') {
        if (!$date) {
            $date = time();
        }
        $_extra = date('Y', $date) . '/' . date('m', $date) . '/' . date('d', $date);
        return $_extra;
    }

    public static function ftpPath($ctime, $type, $return = '') {
        $dirConfig = self::config('imgThumbSize');
        $sizes = array_unique(array_filter(explode(",", $dirConfig)));
        if (empty($sizes)) {
            return false;
        }
        $_extra = self::getUpExtraUrl($ctime);
        $_thedir = $type . '/' . $_extra . '/';
        foreach ($sizes as $size) {
            $dir[$size] = $_thedir . $size . '_';
        }
        if (!empty($return)) {
            $dir = $dir[$return];
        }
        return $dir;
    }

    public static function url($param = array()) {
        $title = $param['title'];
        $main = $param['main'];

        $areaid = $param['areaid'];
        $colid = $param['colid'];
        $type = $param['type'];
        $order = $param['order'];
        $class = $param['class'];

        $data = array(
            $main,
            'areaid' => $areaid,
            'colid' => $colid,
            'order' => $order,
            'type' => $type
        );
        $opt = array(
            'class' => $class
        );
        $data = array_filter($data);
        $opt = array_filter($opt);
        $url = CHtml::link($title, $data, $opt);
        return $url;
    }

    public static function imgurl($logid, $filepath, $imgtype, $type = 'scenic') {
        return self::config('baseurl') . 'attachments/' . $type . '/' . $imgtype . '/' . $logid . '/' . $filepath;
    }

    public static function noImg($type = '') {
        $url = self::config('baseurl') . '/common/images/nopic_124.gif';
        if ($type == 'url') {
            return $url;
        }
        return CHtml::image($url, '暂无图片', array('width' => '124px'));
    }

    public static function lazyImg() {
        return zmf::config('baseurl') . 'common/images/grey.gif';
    }

    public static function avatar($uid, $type = 'small', $urlonly = false, $urlType = 'site') {
        if (!$uid) {
            return false;
        }
        $dir = Yii::app()->basePath . '/../attachments/avatar/' . $type . '/' . $uid . '/' . $uid . '.jpg';
        $img = self::attachBase('site') . 'avatar/' . $type . '/' . $uid . '/' . $uid . '.jpg';
        if (file_exists($dir)) {
            if ($urlonly) {
                if ($urlType == 'app') {
                    return $dir;
                } elseif ($urlType == 'site') {
                    return $img;
                }
            } else {
                return CHtml::link("<img src='{$img}' />", array('users/index', 'id' => $uid));
            }
        } else {
            $_img = self::attachBase('site') . "avatar/default/{$type}.gif";
            if ($urlonly) {
                if ($urlType == 'app') {
                    return false;
                } elseif ($urlType == 'site') {
                    return $_img;
                }
            } else {
                return CHtml::link("<img src='{$_img}' />", array('users/index', 'id' => $uid));
            }
        }
    }

//memCache
    public static function setCache($key, $value, $expire = '3600') {
        Yii::app()->filecache->set($key, $value, $expire);
    }

    public static function getCache($key) {
        return Yii::app()->filecache->get($key);
    }

    public static function delCache($key) {
        Yii::app()->filecache->delete($key);
    }

//fileCache
    public static function setFCache($key, $value, $expire = '60') {
        Yii::app()->filecache->set($key, $value, $expire);
    }

    public static function getFCache($key) {
        return Yii::app()->filecache->get($key);
    }

    public static function delFCache($key) {
        Yii::app()->filecache->delete($key);
    }

    public static function setCookie($key, $value, $expire = 3600) {
        header("P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
        $key = zmf::jiaMi($key);
        $ck = new CHttpCookie($key, $value);
        if (!$value) {
            $ck->expire = zmf::now() - 3600;
        } else {
            $ck->expire = zmf::now() + $expire;
        }
        if (zmf::config('cookieDomain') != '') {
            $ck->domain = zmf::config('cookieDomain');
        }
        Yii::app()->request->cookies[$key] = $ck;
    }

    public static function getCookie($key) {
        $key = zmf::jiaMi($key);
        $value = isset(Yii::app()->request->cookies[$key]) ? Yii::app()->request->cookies[$key]->value : '';
        return $value;
    }

    public static function updateCookieCounter($key, $value, $expire = 3600) {
        $_value = self::getCookie($key);
        if ($_value) {
            $value = (int) $value + (int) $_value;
        }
        self::setCookie($key, $value, $expire);
    }

    public static function delCookie($key) {
        $key = zmf::jiaMi($key);
        $cookie = Yii::app()->request->getCookies();
        unset($cookie[$key]);
    }

    public static function request_by_curl($remote_server, $post_string) {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    static public function curlget($url) {
        $user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)";
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent); // 模拟用户使用的浏览器
        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Get请求
        curl_setopt($curl, CURLOPT_TIMEOUT, 120); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            curl_close($curl); // 关闭CURL会话
            return false;
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }

    public static function tipSubStr($string, $sublen = 20, $lazyload = false, $separater = '...', $imgsize = 170) {
        $string = strip_tags($string);
        $start = 0;
        if (stripos($string, '[attach]') !== false) {
            preg_match_all("/\[attach\](\d+)\[\/attach\]/i", $string, $match);
            $str = preg_replace("/\[attach\](\d+)\[\/attach\]/i", '', $string);
            if (mb_strlen($str) > $sublen) {
                $str = self::subStr($str, $sublen, $start, $separater);
            } elseif ((mb_strlen($str) - $start) == 0) {
                $str = $str . $separater;
            }
            if (!empty($match[1])) {
                $imgsSrc = '';
                $total = count($match[1]);
                foreach ($match[1] as $key => $val) {
                    if ($key >= 1) {
                        $imgsSrc.="共{$total}张";
                        break;
                    }
                    $thekey = $match[0][$key];
                    $src = Attachments::model()->findByPk($val);
                    if ($src) {
                        $url = $src['filePath'];
                        $imgurl = self::uploadDirs($src['cTime'], 'site', $src['classify'], $imgsize) . $url;
                        if ($lazyload) {
                            $imgsSrc .= "<img src='" . self::config('baseurl') . "common/images/grey.gif' class='lazy media-object' data-original='{$imgurl}'  width='124px'/>";
                        } else {
                            $imgsSrc.="<img src='{$imgurl}' width='124px'/>";
                        }
                    }
                }
                $str = '<div class="media"><div class="media-left">' . $imgsSrc . '</div><div class="media-body">' . $str . '</div></div>';
            } else {
                $str = '<div class="media"><div class="media-body">' . $str . '</div></div>';
            }
        } else {
            $str = self::subStr($string, $sublen, $start, $separater);
            $str = '<div class="media"><div class="media-body">' . $str . '</div></div>';
        }
        return self::filterOutput($str);
    }

    public static function text($params, $content, $lazyload = true, $size = 600) {
        if (is_array($params)) {
            $width = $params['imgwidth'];
            $action = $params['action'];
            $encode = $params['encode'];
        }
        if (!$width) {
            $width = $size;
        }
        if ($action != 'edit') {
            $content = tools::addcontentlink($content);
        } else {
            $lazyload = false;
        }
        if (strpos($content, '[attach]') !== false) {
            preg_match_all("/\[attach\](\d+)\[\/attach\]/i", $content, $match);
            if (!empty($match[1])) {
                foreach ($match[1] as $key => $val) {
                    $thekey = $match[0][$key];
                    $src = Attachments::model()->findByPk($val);
                    if ($src) {
                        $_imgurl = self::uploadDirs($src['cTime'], 'site', $src['classify'], $size) . $src['filePath'];
                        $imgurl = self::uploadDirs($src['cTime'], 'app', $src['classify'], $size) . $src['filePath'];
                        if ($lazyload) {
                            $filesize = getimagesize($imgurl);
                            if (empty($filesize)) {
                                $content = str_ireplace("{$thekey}", '', $content);
                                continue;
                            }
                            $imgurl = "<img src='" . self::lazyImg() . "' width='" . $filesize[0] . "px' height='" . $filesize[1] . "' class='lazy img-responsive' data-original='{$_imgurl}' " . ($action == 'edit' ? 'data="' . $src['id'] . '"' : '') . "/>";
                        } else {
                            $imgurl = "<img src='{$_imgurl}' class='img-responsive' " . ($action == 'edit' ? 'data="' . $src['id'] . '"' : '') . "/>";
                        }
                        $content = str_ireplace("{$thekey}", $imgurl, $content);
                    } else {
                        $content = str_ireplace("{$thekey}", '', $content);
                    }
                }
            }
        }
        $content = self::handleContent($content);
        return $content;
    }

    public static function handleContent($content) {
        $replace = array(
            "/\[poi=([^\]]+?)\](.+?)\[\/poi\]/ie",
            "/\[url=(.+?)\](.+?)\[\/url\]/ie",
            "/\[texturl=(.+?)\](.+?)\[\/texturl\]/ie",
        );
        $to = array(
            //"<a href=\"\\1\" data=\"\\2\" class=\"guide\" target=\"_blank\"><span class=\"glyphicon glyphicon-map-marker\"></span> \\3</a>",
            //"<a href=\"\\1\" data=\"\\2\" class=\"guide\" target=\"_blank\">\\3</a>",
            "self::handlePoiLink('\\1','\\2','link')",
            "self::handleLink('\\1','\\2','link')",
            "self::handleLink('\\1','\\2','textlink')",
        );
        $content = preg_replace($replace, $to, $content);
        $content = nl2br($content);
        return $content;
    }

    public static function handlePoiLink($code, $text) {
        $arr = explode('-', $code);
        if (!$arr || !$arr[1]) {
            return $text;
        }
        $_url = zmf::config('domain') . Yii::app()->createUrl('position/view', array('id' => $arr[1]));
        //return CHtml::link($text, $_url, array('action' => 'card','action-type'=>'poi','action-data'=>$arr[1]));
        return CHtml::link($text, $_url);
    }

    public static function handleLink($code, $text, $type = 'link') {
        $_url = zmf::config('domain') . Yii::app()->createUrl('redirect/to', array('code' => $code));
        if ($type == 'textlink') {
            $text = $_url;
        }
        return CHtml::link('<span class="icon-link"></span> 网页链接', $_url, array('target' => '_blank'));
    }

    /**
     * 过滤输入
     * @param type $str
     * @param type $type
     * @param type $textonly 0富文本，1纯文本，2数字
     * @return type
     */
    public static function filterInput($str, $type = 'n', $textonly = false) { 
        if ($textonly || $type===1) {
            $str = strip_tags(trim($str));
            $str = preg_replace('/\r\n|\r|\n/i', '', $str);
        }
        if ($type === 'n' || $type===2) {
            $str = self::myint($str);
        }
        return $str;
    }
    
    /**
     * 仅返回整数
     * intval、(int)在32位系统上有问题
     * @param type $s
     * @return type
     */
    public static function myint($s) {
        return($a = preg_replace('/[^\-\d]*(\-?\d*).*/', '$1', $s)) ? $a : '0';
    }

    public static function filterOutput($str, $encode = false) {
        $str = self::keywordsUrl($str);
        $str = stripslashes($str);
        if ($encode) {
            $str = CHtml::encode($str);
        }
        $str = self::handleContent($str);
        return $str;
    }

    /**
     * 获取远程图片的宽高和体积大小
     *
     * @param string $url 远程图片的链接
     * @param string $type 获取远程图片资源的方式, 默认为 curl 可选 fread
     * @param boolean $isGetFilesize 是否获取远程图片的体积大小, 默认false不获取, 设置为 true 时 $type 将强制为 fread 
     * @return false|array
     */
    public static function myGetImageSize($url, $type = 'curl', $isGetFilesize = false) {
        // 若需要获取图片体积大小则默认使用 fread 方式
        $type = $isGetFilesize ? 'fread' : $type;
        if ($type == 'fread') {
            // 或者使用 socket 二进制方式读取, 需要获取图片体积大小最好使用此方法
            $handle = fopen($url, 'rb');
            if (!$handle)
                return false;
            // 只取头部固定长度168字节数据
            $dataBlock = fread($handle, 168);
        } else {
            // 据说 CURL 能缓存DNS 效率比 socket 高
            $ch = curl_init($url);
            // 超时设置
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            // 取前面 168 个字符 通过四张测试图读取宽高结果都没有问题,若获取不到数据可适当加大数值
            curl_setopt($ch, CURLOPT_RANGE, '0-256');
            // 跟踪301跳转
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            // 返回结果
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataBlock = curl_exec($ch);
            curl_close($ch);
            if (!$dataBlock)
                return false;
        }
        // 将读取的图片信息转化为图片路径并获取图片信息,经测试,这里的转化设置 jpeg 对获取png,gif的信息没有影响,无须分别设置
        // 有些图片虽然可以在浏览器查看但实际已被损坏可能无法解析信息 
        $size = getimagesize('data://image/jpeg;base64,' . base64_encode($dataBlock));
        if (empty($size)) {
            return false;
        }
        $result['width'] = $size[0];
        $result['height'] = $size[1];
        // 是否获取图片体积大小
        if ($isGetFilesize) {
            // 获取文件数据流信息
            $meta = stream_get_meta_data($handle);
            // nginx 的信息保存在 headers 里，apache 则直接在 wrapper_data 
            $dataInfo = isset($meta['wrapper_data']['headers']) ? $meta['wrapper_data']['headers'] : $meta['wrapper_data'];
            foreach ($dataInfo as $va) {
                if (preg_match('/length/iU', $va)) {
                    $ts = explode(':', $va);
                    $result['size'] = trim(array_pop($ts));
                    break;
                }
            }
        }
        if ($type == 'fread')
            fclose($handle);
        return $result;
    }

    public static function jiaMi($plain_text) {
        $key = zmf::config('authorCode');
        $plain_text = trim($plain_text);
        Yii::import('application.vendors.*');
        require_once 'rc4crypt.php';
        $rc4 = new Crypt_RC4();
        $rc4->setKey($key);
        $text = $plain_text;
        $x = $rc4->encrypt($text);
        return $x;
    }

    public static function jieMi($string) {
        $key = zmf::config('authorCode');
        $plain_text = trim($string);
        Yii::import('application.vendors.*');
        require_once 'rc4crypt.php';
        $rc4 = new Crypt_RC4();
        $rc4->setKey($key);
        $text = $plain_text;
        $x = $rc4->decrypt($text);
        return $x;
    }

    /**
     * 繁体转简体
     * @param type $str
     * @return string
     */
    public static function twTozh($str) {
        if (!$str) {
            return '';
        }
        Yii::import('application.vendors.zhtw.*');
        require_once 'zhtw.php';
        $zhtw = new zhtw();
        $str = $zhtw->tw_zh($str);
        return $str;
    }

    /**
     * 简体转繁体
     * @param type $str
     * @return string
     */
    public static function zhTotw($str) {
        if (!$str) {
            return '';
        }
        Yii::import('application.vendors.zhtw.*');
        require_once 'zhtw.php';
        $zhtw = new zhtw();
        $str = $zhtw->zh_tw($str);
        return $str;
    }

    /**
     * 遍历目录下所有文件
     * @param type $dir
     * @return type
     */
    public static function readDir($dir, $name = true) {
        $name_arr = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        if ($name) {
                            $_tmp = explode('.', $file);
                            $name_arr[] = $_tmp[0];
                        } else {
                            $name_arr[] = $file;
                        }
                    }
                }
                closedir($dh);
            }
        }
        return $name_arr;
    }

    public static function isSystem($return = '') {
        $positions = array(
            '1' => '是',
            '0' => '否',
        );
        if ($return != '') {
            return $positions[$return];
        } else {
            return $positions;
        }
    }

    /**
     * 处理语言输出
     * @param type $type
     * @param type $value
     * @return type
     */
    public static function t($type, $value = '') {
        $str = Yii::t('default', $type);
        if (isset($value) && !is_array($value)) {
            return sprintf($str, $value);
        } elseif (is_array($value)) {
            $_tmp = join(',', $value);

            //foreach($value as $s){
            $str = sprintf($str, $_tmp);
            //}
            return $str;
        } else {
            return $str;
        }
    }

    /**
     * 根据时区获取当前时间
     * @param type $timestamp
     * @return type
     */
    public static function now($timestamp = '') {
        $timeset = date_default_timezone_get();
        if (!in_array($timeset, array('Etc/GMT-8', 'PRC', 'Asia/Shanghai', 'Asia/Shanghai', 'Asia/Chongqing'))) {
            date_default_timezone_set('Etc/GMT-8');
        }
        if ($timestamp == '') {
            return time();
        } else {
            return strtotime($timestamp, time());
        }
    }

    /**
     * 格式化时间戳
     * @param type $time
     * @param type $format
     * @return type
     */
    public static function time($time = '', $format = 'Y-m-d H:i:s') {
        if (!$time) {
            $time = zmf::now();
        }
        $timeset = date_default_timezone_get();
        if (!in_array($timeset, array('Etc/GMT-8', 'PRC', 'Asia/Shanghai', 'Asia/Shanghai', 'Asia/Chongqing'))) {
            date_default_timezone_set('Etc/GMT-8');
        }
        return date($format, $time);
    }

    public static function uid() {
        if (Yii::app()->user->isGuest) {
            return false;
        } else {
            return Yii::app()->user->id;
        }
    }

    /**
     * 限制用户对某一操作的频率，如点赞，收藏，关注
     * 默认4次
     */
    public static function actionLimit($type, $keyid, $num = 4) {
        $cacheKey = 'actionLimit-' . $type . '-' . $keyid;
        $info = (int) zmf::getCookie($cacheKey);
        if ($info >= $num) {
            return true;
        } else {
            zmf::setCookie($cacheKey, $info + 1, 60);
            return false;
        }
    }
    /**
     * 去掉标点
     */
    public function formatTitle($title) {
        $replace = array(
            '?',
            '!',
            '[',
            ']',
            '(',
            ')',
            ',',
            ':',
            ';',
            '？',
            '！',
            '【',
            '】',
            '（',
            '）',
            '，',
            '：',
            '；',
        );
        $title = str_replace($replace, '', $title);
        return $title;
    }
    
    /**
     * 返回一个字符的数组
     * @param type $str 字符串
     * @param type $charset 编码
     * @return type
     */
    public static function chararray($str, $charset = "utf-8") {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        return $match;
    }

}
