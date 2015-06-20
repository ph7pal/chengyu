<?php

class tools {

    public static function formatBytes($size) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++)
            $size /= 1024;
        return round($size, 2) . $units[$i];
    }

    function byteFormat($size, $dec = 2) {
        $a = array("B", "KB", "MB", "GB", "TB", "PB");
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos ++;
        }
        return round($size, $dec) . " " . $a[$pos];
    }

    public static function formatTime($date) {
        $thisyear = intval(zmf::time(NULL, 'Y'));
        $dateyear = intval(zmf::time($date, 'Y'));
        if (($thisyear - $dateyear) > 0) {
            return zmf::time($date, 'Y-m-d H:i');
        }
        $thismo = intval(zmf::time(NULL, 'm'));
        $datemo = intval(zmf::time($date, 'm'));
        if ($thisyear == $dateyear && $thismo != $datemo) {
            return zmf::time($date, 'm-d H:i');
        }
        $timer = $date;
        $diff = zmf::now() - $timer;
        $thisto = intval(zmf::time(NULL, 'd'));
        $dateto = intval(zmf::time($date, 'd'));
        $day = $thisto - $dateto;
        $free = $diff % 86400;
        if ($day > 0) {
            if ($day > 7) {
                return zmf::time($date, 'n-j H:i');
            } elseif ($day == 1) {
                return "昨天 " . zmf::time($date, 'H:i');
            } elseif ($day == 2) {
                return "前天 " . zmf::time($date, 'H:i');
            } else {
                return $day . "天前";
            }
        } else {
            if ($free > 0) {
                $hour = floor($free / 3600);
                $free = $free % 3600;
                if ($hour > 0) {
                    return $hour . "小时前";
                } else {
                    if ($free > 0) {
                        $min = floor($free / 60);
                        $free = $free % 60;
                        if ($min > 0) {
                            return $min . "分钟前";
                        } else {
                            if ($free > 0) {
                                return $free . "秒前";
                            } else {
                                return '刚刚';
                            }
                        }
                    } else {
                        return '刚刚';
                    }
                }
            } else {
                return '刚刚';
            }
        }
    }

    public static function pinyin($string) {
        $dir = Yii::app()->basePath . '/data/pinyin_table.php';
        if (file_exists($dir)) {
            $pinyin = include $dir;
        } else {
            return $string;
            exit;
        }
        $arr = explode('\\', strtoupper(str_replace('"', '', json_encode(urldecode($string)))));
        $arr = array_values(array_filter($arr));
        for ($i = 0; $i < count($arr); $i++) {
            $_pin.=$pinyin['\\' . $arr[$i]] . '';
        }
        return strtolower($_pin);
    }

    /**
     * 对字符串全角到半角的转换
     * @param type $str 传入要转换的字符串
     * @param type $args2 取0，半角转全角；取1，全角到半角
     */
    public static function sbcDbc($str, $args2 = 1) {
        $DBC = Array(
            '０', '１', '２', '３', '４',
            '５', '６', '７', '８', '９',
            'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ',
            'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ',
            'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ',
            'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ',
            'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ',
            'Ｚ', 'ａ', 'ｂ', 'ｃ', 'ｄ',
            'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ',
            'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ',
            'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ',
            'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ',
            'ｙ', 'ｚ', '－', '　', '：',
            '．', '，', '／', '％', '＃',
            '！', '＠', '＆', '（', '）',
            '＜', '＞', '＂', '＇', '？',
            '［', '］', '｛', '｝', '＼',
            '｜', '＋', '＝', '＿', '＾',
            '￥', '￣', '｀'
        );
        $SBC = Array(// 半角
            '0', '1', '2', '3', '4',
            '5', '6', '7', '8', '9',
            'A', 'B', 'C', 'D', 'E',
            'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y',
            'Z', 'a', 'b', 'c', 'd',
            'e', 'f', 'g', 'h', 'i',
            'j', 'k', 'l', 'm', 'n',
            'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x',
            'y', 'z', '-', ' ', ':',
            '.', ',', '/', '%', '#',
            '!', '@', '&', '(', ')',
            '<', '>', '"', '\'', '?',
            '[', ']', '{', '}', '\\',
            '|', '+', '=', '_', '^',
            '$', '~', '`'
        );
        if ($args2 == 0) {
            return str_replace($SBC, $DBC, $str);  // 半角到全角
        } else if ($args2 == 1) {
            return str_replace($DBC, $SBC, $str);  // 全角到半角
        } else {
            return $str;
        }
    }

    /**
     * 将字符串拆分为一个数组
     * @param type $str 字符串
     * @param type $charset 字符集
     * @return type
     */
    public static function chararray($str, $charset = "utf-8") {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        return $match[0];
    }
    
    /**
     * 仅文本
     * @param type $str
     * @return type
     */
    public static function getContentOnly($str){
        $str = self::sbcDbc($str);
        $qian = array(
            "\t",
            "\n",
            "\r",
            " ",
            PHP_EOL,
        );
        $hou = array(
            "",
            "",
            "",
            "",
            "",
        );
        $str = str_replace($qian, $hou, $str);
        $str = self::filterMark($str);
        return $str;
    }

    /**
     * 计算文章字符重复率
     * 转换所有全角
     * 去掉所有空格
     * @param type $str
     * @return string
     */
    public static function calStrRate($str) {
        $str=  self::getContentOnly($str);
        //判断内容是否全是英文
        $lenA = strlen($str); //检测字符串实际长度
        $lenB = mb_strlen($str, "utf-8"); //文件的编码方式要是UTF8     
        if ($lenA === $lenB) {
            //todo，判断英文单词重复率
            return "1"; //全英文    
        } else {
            //todo，判断全中文、中英混合
        }
        $arr = tools::chararray($str);
        $toal = count($arr);
        if (!$toal) {
            return '0';
        }
        $arr = array_unique(array_filter($arr));
        $cal = count($arr);
        return $cal / $toal;
    }

    /**
     * 删除中英文标点
     * @param type $text
     * @return string
     */
    public static function filterMark($text) {
        if (trim($text) == '')
            return '';
        $text = preg_replace("/[[:punct:]\s]/", '', $text);
        $text = urlencode($text);
        $text = preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99|%EF%BD%9E|%EF%BC%8E|%EF%BC%88|%E2%80%A6)+/", '', $text);
        $text = urldecode($text);
        return trim($text);
    }

    public static function allowOrNot($return = '') {
        $arr = array(
            '1' => '是',
            '0' => '不是'
        );
        if ($return != '') {
            return $arr[$return];
        } else {
            return $arr;
        }
    }

    public static function albumClassify($return = '') {
        $arr = array(
            'people' => '人物',
            'scenic' => '风景'
        );
        if ($return != '') {
            return $arr[$return];
        } else {
            return $arr;
        }
    }

    public static function adsStyles($return = '') {
        $arr = array(
            'txt' => '文字',
            'img' => '图片',
            'flash' => '幻灯片'
        );
        if ($return != '') {
            return $arr[$return];
        } else {
            return $arr;
        }
    }

    public static function multiManage() {
        $arr = array(
            'del' => '删除'
        );
        return $arr;
    }

    public function writeSet($array) {
        $dir = Yii::app()->basePath . "/runtime/config/";
        zmf::createUploadDir($dir);
        $dir = $dir . 'zmfconfig.php';
        $values = array_values($array);
        $keys = array_keys($array);
        $len = count($keys);
        $config = "<?php\n";
        $config .= "return array(\n";
        for ($i = 0; $i < $len; $i++) {
            $config .= "'" . $keys[$i] . "'=> '" . addslashes($values[$i]) . "',\n";
        }
        $config .= ");\n";
        $config .= "?>";
        $fp = fopen($dir, 'w');
        $fw = fwrite($fp, $config);
        if (!$fw) {
            fclose($fp);
            return false;
        } else {
            fclose($fp);
            return true;
        }
    }

    public static function getPlatform() {
        if (self::checkmobile($platform)) {
            $platform = 'mobile';
        } else {
            $platform = 'web';
        }
        return $platform;
    }

    public static function checkmobile(&$platform) {
        if (!zmf::config("mobile")) {
            //return false;      
        }

        $mobile = array();
        static $mobilebrowser_list = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
            'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
            'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
            'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
            'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
            'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
            'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
        $pad_list = array('pad', 'gt-p1000');

        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (self::dstrpos($useragent, $pad_list)) {
            return false;
        }
        if (($v = self::dstrpos($useragent, $mobilebrowser_list, true))) {
            $platform = $v;
            return true;
        }
        $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
        if (self::dstrpos($useragent, $brower))
            return false;
    }

//判断是平板电脑还是手机
    public static function dstrpos($string, &$arr, $returnvalue = false) {
        if (empty($string))
            return false;
        foreach ((array) $arr as $v) {
            if (strpos($string, $v) !== false) {
                $return = $returnvalue ? $v : true;
                return $return;
            }
        }
        return false;
    }

    public static function exStatus($status) {
        if (is_numeric($status)) {
            switch ($status) {
                case 0:
                    return 'notpassed';
                case 1:
                    return 'passed';
                case 2:
                    return 'staycheck';
                case 3:
                    return 'deled';
            }
        } else {
            switch ($status) {
                case 'notpassed':
                    return 0;
                case 'passed':
                    return 1;
                case 'staycheck':
                    return 2;
                case 'deled':
                    return 3;
            }
        }
    }

    public static function exStatusTitle($return = '') {
        $arr = array(
            0 => '未通过',
            1 => '已通过',
            2 => '待审核',
            3 => '已删除'
        );
        if ($return != '') {
            return $arr[$return];
        } else {
            return $arr;
        }
    }

    public static function exStatusToClass($status, $return = false) {
        switch ($status) {
            case 0:
                $css = 'warning';
                break;
            case 1:
                $css = '';
                break;
            case 2:
                $css = 'warning';
                break;
            case 3:
                $css = 'warning';
                break;
        }
        if ($return) {
            return $css;
        } else {
            echo 'class="' . $css . '"';
        }
    }

    public static function addcontentlink($content) {
        //在处理之前，先要把a或img标签内的排除，先替换
        preg_match_all('/<a.*?href=".*?".*?>.*?<\/a>/i', $content, $linkList);
        $linkList = $linkList[0];
        $str = preg_replace('/<a.*?href=".*?".*?>.*?<\/a>/i', '<{link}>', $content);
        //提取替换出所有的IMG标签（统一标记<{img}>）
        preg_match_all('/<img[^>]+>/im', $content, $imgList);
        $imgList = $imgList[0];
        $str = preg_replace('/<img[^>]+>/im', '<{img}>', $str);
        $str = preg_replace('/(((http|https):\/\/)[a-z0-9;&#@=_~%\?\/\.\,\+\-\!\:]+)/ie', "self::strip_link('$1')", $str);
        if (strpos($str, "http") === FALSE) {
            $str = preg_replace('/(www.[a-z0-9;&#@=_~%\?\/\.\,\+\-\!\:]+)/ie', "self::strip_link('$1')", $str);
        } else {
            $str = preg_replace('/([[:space:]()[{}])(www.[a-z0-9;&#@=_~%\?\/\.\,\+\-\!\:]+)/i', '\1<a href="http://\2" target=_blank rel=nofollow>\2</a>', $str);
        }
        //还原A统一标记为原来的A标签
        $arrLen = count($linkList);
        for ($i = 0; $i < $arrLen; $i++) {
            $str = preg_replace('/<{link}>/', $linkList[$i], $str, 1);
        }
        //还原IMG统一标记为原来的IMG标签
        $arrLen2 = count($imgList);
        for ($i = 0; $i < $arrLen2; $i++) {
            $str = preg_replace('/<{img}>/', $imgList[$i], $str, 1);
        }
        return $str;
    }

    public static function strip_link($link) {
        $link = trim(htmlspecialchars_decode($link));
        return '<a href="' . $link . '" target=_blank rel=nofollow >' . $link . '</a>';
    }

    public static function getUnits($return = '') {
        $units = array(
            'CNY' => '人民币',
            'HKD' => '港币',
            'THB' => '泰铢',
            'EUR' => '欧元',
            'USD' => '美元',
            'EGP' => '埃及镑',
            'KRW' => '韩元',
            'MOP' => '澳门元',
            'GBP' => '英镑',
            'CAD' => '加拿大元',
            'ZAR' => '兰特',
            'TWD' => '台币',
            'JPY' => '日元',
            'MXN' => '墨西哥比索',
            'MUR' => '毛里求斯卢比',
            'MYR' => '马来西亚元',
            'CHF' => '瑞士法郎',
            'CUP' => '古巴比索',
            'SGD' => '新加坡元',
            'IDR' => '印尼盾',
            'PHP' => '菲律宾比索',
            'VND' => '越南盾',
            'TRY' => '土耳其里拉',
            'AUD' => '澳大利亚元',
            'KHR' => '瑞尔',
            'BRL' => '巴西里尔',
            'RUB' => '卢布',
            'NZD' => '新西兰元',
            'NPR' => '尼泊尔卢比',
            'ARS' => '阿根廷比索',
            'FJD' => '斐济元',
            'LKR' => '斯里兰卡卢比',
            'PEN' => '新索尔',
            'INR' => '印度卢比',
        );
        if ($return != '') {
            $return = strtoupper($return);
            return $units[$return];
        } else {
            return $units;
        }
    }

    public static function getHotelStar($return = '') {
        $stars = array(
            '1' => '公寓',
            '2' => '经济酒店',
            '3' => '舒适酒店',
            '4' => '高档酒店',
            '5' => '豪华酒店',
        );
        if ($return != '') {
            return $stars[$return];
        } else {
            return $stars;
        }
    }

    /**
     * 返回ftp错误码对应错误信息
     * @param type $code
     * @return string
     */
    public static function ftpError($code) {
        switch ($code) {
            case -100:
                return 'SERVER_DISABLED';
            case -101:
                return 'CONFIG_OFF';
            case -102:
                return '无法连接FTP服务器';
            case -103:
                return 'FTP用户无法登陆';
            case -104:
                return 'FTP切换目录错误';
            case -105:
                return 'FTP创建目录错误';
            case -106:
                return 'ERR_SOURCE_READ';
            case -107:
                return 'ERR_TARGET_WRITE';
            default:
                return '未知错误';
        }
    }

    public static function ftpUpload($files) {
        $ftpconfig = array(
            'host' => zmf::config('ftp_host'),
            'username' => zmf::config('ftp_username'),
            'password' => zmf::config('ftp_password'),
            'on' => 1,
            'attachdir' => zmf::config('ftp_attachdir')
        );
        $ftp = & discuz_ftp::instance($ftpconfig);
        $ftp->connect();
        $succ = array();
        foreach ($files as $file) {
            $toimgpath = $ftpconfig['attachdir'] . $file['to'];
            $ftp->upload($file['from'], $toimgpath);
            if ($ftp->error()) {
                $errinfo = tools::ftpError($ftp->error()); //ftpError
                //MsgController::jsonOutPut(0, $errinfo);
            } else {
                $succ[] = $file;
            }
        }
        return $succ;
    }

    /**
     * 
     * @param type $email 接收者邮件
     * @param type $subject 邮件主题
     * @param type $message 邮件内容
     * @return boolean
     */
    public function sendMail($to, $toname, $subject, $message) {
        $host = zmf::config('email_host');
        $display = zmf::config('email_fromname');
        $username = zmf::config('email_username');
        $passwd = zmf::config('email_password');
        $charset = zmf::config('email_chartset');
        $port = zmf::config('email_port');
        $replyto = zmf::config('email_replyto');
        $replyname = zmf::config('email_replyname');
        if (!$host || !$display || !$username || !$passwd || !$charset || !$port || !$replyname || !$replyto) {
            return false;
        }
        Yii::import('application.vendors.*');
        include 'class.phpmailer.php';
        include 'class.smtp.php';
        $mail = new PHPMailer();
        $mail->CharSet = $charset;  //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
        $mail->IsSMTP();                            // 设定使用SMTP服务
        $mail->SMTPAuth = true;                   // 启用 SMTP 验证功能
        $mail->SMTPSecure = "ssl";                  // SMTP 安全协议
        $mail->Port = $port;   // SMTP服务器的端口号，465

        $mail->Host = $host;       // SMTP 服务器        
        $mail->Username = $username;  // SMTP服务器用户名
        $mail->Password = $passwd;        // SMTP服务器密码
        $mail->SetFrom($username, $display);    // 设置发件人地址和名称
        $mail->AddReplyTo($replyto, $replyname);

        // 设置邮件回复人地址和名称
        $mail->Subject = $subject;                     // 设置邮件标题
        $mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
        // 可选项，向下兼容考虑
        $mail->MsgHTML($message);                         // 设置邮件内容
        $mail->AddAddress($to, $toname);
        $mail->SMTPDebug = 0;
        if (!$mail->Send()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取传参
     * @param type $key，参数的键名
     * @param type $ttype,传参类型,n:数字,t:文本
     * @param type $textonly,是否纯文本
     * @return boolean
     */
    public static function val($key, $ttype = 'n', $textonly = 1) {
        $return = zmf::filterInput(Yii::app()->request->getParam($key), $ttype, $textonly);
        return $return;
    }

    public static function randMykeys($length, $type = '') {
        if (!$type) {
            $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';    //字符池,可任意修改
            $len = 61;
        } elseif ($type == 'n') {
            $pattern = '1234567890';    //字符池,可任意修改
            $len = 9;
        } else {
            $pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';    //字符池,可任意修改
            $len = 51;
        }

        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, $len)};    //生成php随机数
        }
        return $key;
    }

}
