<?php

/**
 * This is the model class for table "{{admins}}".
 * 后台管理员
 * The followings are the available columns in table '{{admins}}':
 * @property string $id
 * @property string $uid
 * @property string $powers
 */
class Admins extends CActiveRecord {

    public function tableName() {
        return '{{admins}}';
    }

    public function rules() {
        return array(
            array('uid, powers', 'required'),
            array('uid', 'length', 'max' => 11),
            array('powers', 'length', 'max' => 25),
            array('uid, powers', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'userInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '用户ID',
            'powers' => '用户权限',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria;
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('powers', $this->powers, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Admins the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 权限描述
     * @param type $type 操作类型
     * @param type $name 获取某权限的描述
     * @return type]
     */
    public static function getDesc($type = 'admin', $name = '') {
        $lang['users']['desc'] = '用户相关，包括更新、删除等';
        $lang['users']['detail'] = array(
            'users' => '用户列表',
            'addUser' => '新增用户',
            'updateUser' => '更新用户',
        );
        $lang['posts']['desc'] = '文章相关，包括增删改等';
        $lang['posts']['detail'] = array(
            'posts' => '文章列表',
            'delPost' => '删除文章',
        );
        $lang['comments']['desc'] = '评论相关，包括增删改等';
        $lang['comments']['detail'] = array(
            'comments' => '评论列表',
            'delComment' => '删除评论',
        );
        $lang['tags']['desc'] = '标签相关，包括增删改等';
        $lang['tags']['detail'] = array(
            'tags' => '标签列表',
            'addTag' => '新增标签',
            'delTag' => '删除标签',
        );
        $lang['attachments']['desc'] = '图片相关';
        $lang['attachments']['detail'] = array(
            'attachments' => '图片列表',
        );
        $lang['feedback']['desc'] = '意见反馈';
        $lang['feedback']['detail'] = array(
            'feedback' => '意见反馈',
        );
        $lang['siteInfo']['desc'] = '站点文章';
        $lang['siteInfo']['detail'] = array(
            'siteInfo' => '站点文章',
            'addSiteInfo' => '新增站点文章',
            'updateSiteInfo' => '更新站点文章',
        );
        $lang['admins']['desc'] = '后台管理员';
        $lang['admins']['detail'] = array(
            'admins' => '后台管理员',
            'setAdmin' => '设置管理员',
            'delAdmin' => '删除管理员',
        );
        $lang['config']['desc'] = '系统设置';
        $lang['config']['detail'] = array(
            'config' => '系统设置列表',
            'setConfig' => '系统设置',
        );
        if ($type === 'admin') {
            $items = array();
            foreach ($lang as $key => $val) {
                $items = array_merge($items, $val['detail']);
            }
            unset($lang);
            $lang['admin'] = $items;
        } elseif ($type == 'super') {
            return $lang;
        }
        if (!empty($name)) {
            return $lang[$type][$name];
        } else {
            return $lang[$type];
        }
    }

}
