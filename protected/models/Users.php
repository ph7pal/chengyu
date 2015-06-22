<?php

class Users extends CActiveRecord {

    const USER_INACTIVE = 0; //未激活
    const USER_PASSED = 1; //正常状态
    const USER_BANNED_POST = 2; //禁止发言
    const USER_BANNED_VISIT = 3; //禁止访问
    const USER_BANNED = 4; //锁定用户

    public $desc; //用户的个人描述

    public function tableName() {
        return '{{users}}';
    }

    public function rules() {
        return array(
            array('password, truename, email', 'required'),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('register_time, last_login_time,last_update', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('groupid,status,email_status,reputation,badge,sex', 'numerical', 'integerOnly' => true),
            array('truename', 'limitUsername'),
            array('email', 'email'),
            array('truename,email', 'unique'),
            array('username, password, truename, email', 'length', 'max' => 255),
            array('register_ip, last_login_ip', 'length', 'max' => 15),
            array('register_time, last_login_time, login_count,posts,answers,tips,favors,fans,last_update,hits', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, username, truename, email', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'groupInfo' => array(self::BELONGS_TO, 'UserGroup', 'groupid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => zmf::t('username'),
            'password' => zmf::t('password'),
            'truename' => zmf::t('truename'),
            'email' => zmf::t('email'),
            'groupid' => '所属用户组',
            'register_ip' => '注册IP',
            'last_login_ip' => '最近登录IP',
            'register_time' => '注册时间',
            'last_login_time' => '最近登录',
            'login_count' => '登录次数',
            'status' => '用户状态',
            'email_status' => '邮件状态',
            'reputation' => '声望值',
            'badge' => '徽章',
            'posts' => '文章数',
            'answers' => '回答数',
            'tips' => '点评数',
            'favors' => '关注数',
            'fans' => '粉丝数',
            'last_update' => '最近更新',
            'hits' => '访问数',
            'extra' => '额外信息',
            'sex' => '性别',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('truename', $this->truename, true);
        $criteria->compare('email', $this->email, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 限制的用户名
     * @param type $attribute
     * @param type $params
     */
    public function limitUsername($attribute, $params) {
        $truename = $this->truename;
        if ($truename) {
            if (!$this->chkName($truename)) {
                $this->addError('truename', zmf::t('notAllowName'));
            }
        }
    }

    private function chkName($a) {
        if (!$a) {
            return false;
        }
        $names = zmf::config('limitUsernames');
        if (empty($names)) {
            return true;
        }
        $arr = explode('#', $names);
        $array = array_unique(array_filter($arr));
        $has = true;
        foreach ($array as $v) {
            if (stripos($a, $v) !== false) {
                $has = false;
                break;
            }
        }
        return $has;
    }

    /**
     * 获取用户信息
     * @param type $uid
     * @param type $type
     * @return boolean
     */
    public static function getUserInfo($uid, $type = '') {
        if (!$uid) {
            return false;
        }
        $info = Users::model()->findByPk($uid);
        if (!$info) {
            Yii::app()->user->logout();
            return false;
        }
        unset($info->password);
        unset($info->username);
        if (!empty($type)) {
            return $info->$type;
        } else {
            return $info;
        }
    }

    public static function delUser($uid) {
        if (Users::model()->deleteAll('id=:id', array(':id' => $uid))) {
            Admins::model()->deleteAll('uid=:uid', array(':uid' => $uid));
            Posts::delPost('user', $uid);
            Comments::delSth('user', $uid);
            Attachments::delAtt('user', $uid);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 权限判断
     * @param type $type 权限名
     * @param type $json 是否以json返回
     * @param type $return 是否不终止运行
     * @return boolean
     */
    public function checkPower($type, $json = false, $return = false) {
        if (Yii::app()->user->isGuest) {
            if ($return) {
                return false;
            } elseif (!$json AND ! Yii::app()->request->isAjaxRequest) {
                T::message(0, Yii::t('default', 'loginfirst'), Yii::app()->createUrl('site/login'));
            } else {
                T::jsonOutPut(0, Yii::t('default', 'loginfirst'));
            }
        } else {
            $uid = Yii::app()->user->id;
        }
        if ($type == 'login') {
            return true;
        }
        $userinfo = Users::getUserInfo($uid);
        if (!$userinfo) {
            if ($return) {
                return false;
            } elseif (!$json AND ! Yii::app()->request->isAjaxRequest) {
                T::message(0, '不存在的用户，请核实', Yii::app()->createUrl('site/logout'));
            } else {
                T::jsonOutPut(0, '不存在的用户，请核实');
            }
        }
        $gid = $userinfo['groupid'];
        $groupinfo = UserPower::getInfo($gid);
        if (!$groupinfo) {
            if ($return) {
                return false;
            } elseif (!$json AND ! Yii::app()->request->isAjaxRequest) {
                T::message(0, '您所在用户组不存在，请核实', Yii::app()->createUrl('site/logout'));
            } else {
                T::jsonOutPut(0, '您所在用户组不存在，请核实');
            }
        }
        $power = GroupPowers::model()->findByAttributes(array('powers' => $type), 'gid=:gid', array(':gid' => $gid));
        if (!$power) {
            $power = GroupPowers::model()->findByAttributes(array('powers' => 'all'), 'gid=:gid', array(':gid' => $gid));
        }
        if (!$power) {
            if ($return) {
                return false;
            } elseif (!$json AND ! Yii::app()->request->isAjaxRequest) {
                T::message(0, '您所在用户组【' . $groupinfo['title'] . '】无权该操作');
            } else {
                T::jsonOutPut(0, '您所在用户组【' . $groupinfo['title'] . '】无权该操作');
            }
        }
        return true;
    }

    public static function getNew() {
        $arr = array(
            'table' => 'users',
            'sql' => "SELECT id,truename FROM {{users}} ORDER BY register_time DESC LIMIT 8",
        );
        $news = Posts::tops($arr);
        return $news;
    }

    /**
     * 发送邮件
     * @param type $email 接收者邮件
     * @param type $subject 邮件主题
     * @param type $message 邮件内容
     * @return boolean
     */
    public static function sendMail($to, $toname, $subject, $message) {
        $host = zmf::config('email_host');
        $display = zmf::config('email_fromname');
        $username = zmf::config('email_username');
        $passwd = zmf::config('email_password');
        if (!$host || !$display || !$username || !$passwd) {
            return false;
        }
        Yii::import('application.vendors.*');
        include 'class.phpmailer.php';
        include 'class.smtp.php';
        $mail = new PHPMailer();
        $mail->CharSet = zmf::config('email_chartset');                 //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
        $mail->IsSMTP();                            // 设定使用SMTP服务
        $mail->SMTPAuth = true;                   // 启用 SMTP 验证功能
        $mail->SMTPSecure = "ssl";                  // SMTP 安全协议
        $mail->Port = zmf::config('email_port');                    // SMTP服务器的端口号

        $mail->Host = $host;       // SMTP 服务器        
        $mail->Username = $username;  // SMTP服务器用户名
        $mail->Password = $passwd;        // SMTP服务器密码
        $mail->SetFrom($username, $display);    // 设置发件人地址和名称
        $mail->AddReplyTo("no-reply@newsoul.cn", "no-reply@newsoul.cn");

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
     * 返回禁止用户状态
     */
    public static function userStatus($status = '') {
        $arr = array(
            self::USER_INACTIVE => '未激活',
            self::USER_PASSED => '正常状态',
            self::USER_BANNED_POST => '禁止发言',
            self::USER_BANNED_VISIT => '禁止访问',
            self::USER_BANNED => '锁定用户',
        );
        if (is_numeric($status)) {
            return $arr[$status];
        }
        return $arr;
    }

    /**
     * 返回禁止用户时的内容类型
     */
    public static function banTypes() {
        $arr = array(
            'posts' => '文章',
            'tips' => '点评',
            'questions' => '问题',
            'answers' => '回答',
            'comments' => '评论',
            'attaches' => '图片',
                //'records'=>'记录',
        );
        return $arr;
    }

    /**
     * 删除用户发布的内容
     * @param type $uid
     * @param type $type
     * @return boolean
     */
    public static function delUserContent($uid, $type) {
        if (!$uid || !$type) {
            return false;
        }
        switch ($type) {
            case 'posts':
                Posts::model()->updateAll(array('status' => Posts::STATUS_DELED), 'uid=:uid', array(':uid' => $uid));
                break;
            case 'tips':
                PoiPost::model()->updateAll(array('status' => Posts::STATUS_DELED), 'uid=:uid', array(':uid' => $uid));
                PoiTips::model()->updateAll(array('status' => Posts::STATUS_DELED), 'uid=:uid', array(':uid' => $uid));
                break;
            case 'questions':
                Question::model()->updateAll(array('status' => Posts::STATUS_DELED), 'uid=:uid', array(':uid' => $uid));
                break;
            case 'answers':
                Answer::model()->updateAll(array('status' => Posts::STATUS_DELED), 'uid=:uid', array(':uid' => $uid));
                break;
            case 'comments':
                Comments::model()->updateAll(array('status' => Posts::STATUS_DELED), 'uid=:uid', array(':uid' => $uid));
                break;
            case 'attaches':
                Attachments::model()->updateAll(array('status' => Posts::STATUS_DELED), 'uid=:uid', array(':uid' => $uid));
                break;
            case 'records':
                //UserAction::model()->updateAll(array('status'=>Posts::STATUS_DELED),'uid=:uid',array(':uid'=>$uid));
                break;
        }
        return true;
    }

    /**
     * 统计用户的内容数
     */
    public static function getCounts($uid, $info = '') {
        if (!$uid) {
            return array();
        }
        if ($info) {
            if ((zmf::now() - $info['last_update']) <= 3600) {
                $data = array(
                    'posts' => $info['posts'],
                    'answers' => $info['answers'],
                    'tips' => $info['tips'],
                );
                return $data;
            }
        }
        $data = array(
            'posts' => Posts::model()->count('uid=:uid AND status=' . Posts::STATUS_PASSED, array(':uid' => $uid)),
            'answers' => Answer::model()->count('uid=:uid AND status=' . Posts::STATUS_PASSED, array(':uid' => $uid)),
            'tips' => PoiPost::model()->count('uid=:uid AND status=' . Posts::STATUS_PASSED, array(':uid' => $uid)),
            'favors' => Favorites::model()->count('uid=:uid AND classify="user"', array(':uid' => $uid)),
            'fans' => Favorites::model()->count('logid=:uid AND classify="user"', array(':uid' => $uid)),
        );
        //将统计数据更新到用户统计表
        $data['last_update'] = zmf::now();
        Users::model()->updateByPk($uid, $data);
        return $data;
    }

    /**
     * 根据用户信息获取extra部分的内容
     * 返回数组
     */
    public static function getExtra($userInfo) {
        if (!$userInfo) {
            return array();
        }
        $extra = CJSON::decode($userInfo['extra'], true);
        return $extra;
    }

    /**
     * 更新用户extra部分
     * @param type $uid 用户ID
     * @param type $params extra数组
     * @return boolean
     */
    public static function updateExtra($uid, $params) {
        if (!$uid) {
            return false;
        }
        $json = CJSON::encode($params);
        return Users::model()->updateByPk($uid, array('extra' => $json));
    }

    /**
     * 搜索用户
     * @param type $keyword
     * @param type $limit
     * @return type
     */
    public static function suggest($keyword, $limit = 20) {
        $items = Users::model()->findAll(array(
            'condition' => '(truename LIKE :keyword)',
            'select' => 'id,truename',
            'limit' => $limit,
            'params' => array(
                ':keyword' => '%' . strtr($keyword, array('%' => '\%', '_' => '\_', '\\' => '\\\\')) . '%'
            ),
        ));
        return $items;
    }
    
    /**
     * 根据用户名来获取用户信息
     * @param type $keyword
     * @return type
     */
    public static function getInfoByName($keyword){
        $items = Users::model()->find(array(
            'condition' => "truename=:keyword",
            'select' => 'id,truename',
            'params' => array(
                ':keyword' => $keyword
            ),
        ));
        return $items;
    }

    /**
     * 获取用户最新产生的内容
     * @param type $type 某个类型，如post
     * @param type $uid 用户ID
     */
    public static function getNewUgc($type, $uid = '', $limit = 10, $field = '*') {
        if (!$uid) {
            $uid = zmf::uid();
        }
        if (!$uid) {
            return false;
        }
        switch ($type) {
            case 'post':
                $posts = Posts::model()->findAll(array(
                    'condition' => 'uid=:uid',
                    'order' => 'cTime DESC',
                    'select' => $field,
                    'limit' => $limit,
                    'params' => array(
                        ':uid' => $uid
                    )
                ));
                break;
            case 'comment':
                $posts = Comments::model()->findAll(array(
                    'condition' => 'uid=:uid',
                    'order' => 'cTime DESC',
                    'select' => $field,
                    'limit' => $limit,
                    'params' => array(
                        ':uid' => $uid
                    )
                ));
                break;
            case 'question':
                $posts = Question::model()->findAll(array(
                    'condition' => 'uid=:uid',
                    'order' => 'cTime DESC',
                    'select' => $field,
                    'limit' => $limit,
                    'params' => array(
                        ':uid' => $uid
                    )
                ));
                break;
            case 'answer':
                $posts = Answer::model()->findAll(array(
                    'condition' => 'uid=:uid',
                    'order' => 'cTime DESC',
                    'select' => $field,
                    'limit' => $limit,
                    'params' => array(
                        ':uid' => $uid
                    )
                ));
                break;
            default :
                $posts = array();
                break;
        }
        return $posts;
    }

    public static function quickLoginBar($action = 'login') {
        $arr = array(
            'weibo' => '微博',
//            'qq' => 'QQ',
            'weixin' => '微信',
        );
        if (!zmf::config('weibo_app_id') || !zmf::config('weibo_app_key') || !zmf::config('weibo_app_callback')) {
            unset($arr['weibo']);
        }
        if (!zmf::config('weixin_app_id') || !zmf::config('weixin_app_key') || !zmf::config('weixin_app_callback')) {
            unset($arr['weixin']);
        }
        switch ($action) {
            case 'login':
                $_title = '登录';
                break;
            case 'reg':
                $_title = '注册';
                break;
            case 'bind':
                $_title = '绑定已有账户';
                break;
            case 'admin':
                return $arr;
        }
        $longstr = '';
        foreach ($arr as $k => $t) {
            $longstr.= CHtml::link('<img src="' . zmf::config('baseurl') . 'common/images/' . $k . '.gif" alt="' . $t . '" title="使用' . $t . $_title . '" width="24px" class="pull-left">', array($k . '/index', 'action' => $action));
        }
        echo $longstr;
    }

    /**
     * 获取用户绑定的三方信息
     * @param type $type 微博 微信 还是扣扣
     * @param type $uid
     * @return string|array
     */
    public static function getBindInfo($type, $uid = '') {
        $arr = array();
        if (!$uid) {
            $uid = zmf::uid();
        }
        if (!$uid) {
            return $arr;
        }
        switch ($type) {
            case 'weibo':
                $info = UserSina::model()->findByPk($uid);
                if ($info && $info['data']) {
                    $data = unserialize($info['data']);
                    $arr['nickname'] = $data['screen_name'];
                    $arr['avatarurl'] = $data['avatarurl'];
                    $arr['profile_url'] = $data['profile_url'];
                }
                break;
            case 'qq':
                $info = UserQq::model()->findByPk($uid);
                if ($info && $info['data']) {
                    $data = unserialize($info['data']);
                    $arr['nickname'] = $data['nickname'];
                    $arr['avatarurl'] = $data['avatarurl'];
                    $arr['profile_url'] = '';
                }
                break;
            case 'weixin':
                $info = UserWeixin::model()->findByPk($uid);
                if ($info && $info['data']) {
                    $data = unserialize($info['data']);
                    $arr['nickname'] = $data['nickname'];
                    $arr['avatarurl'] = $data['headimgurl'];
                    $arr['profile_url'] = '';
                }
                break;
            default :
                break;
        }
        return $arr;
    }

    /**
     * 用户性别
     * @param type $type
     * @return string
     */
    public static function userSex($type = '') {
        $arr = array(
            '0' => '女',
            '1' => '男'
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

    /**
     * 根据用户性别获取不同的图示
     * @param type $sex
     * @return string
     */
    public static function getUserClass($sex) {
        switch ($sex) {
            case '0':
                return '<span class="icon-circle user-girl" title="女生"></span>';
            case '1':
                return '<span class="icon-circle user-boy" title="男生"></span>';
            default :
                return '';
        }
    }

}
