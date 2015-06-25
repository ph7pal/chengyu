<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $truename
 * @property string $email
 * @property integer $groupid
 * @property string $register_ip
 * @property string $last_login_ip
 * @property string $register_time
 * @property string $last_login_time
 * @property string $login_count
 * @property integer $status
 * @property integer $email_status
 */
class Users extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{users}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('password, truename, email', 'required'),
            array('groupid, status, email_status', 'numerical', 'integerOnly' => true),
            array('username, password, truename, email', 'length', 'max' => 255),
            array('register_ip, last_login_ip', 'length', 'max' => 15),
            array('register_time, last_login_time, login_count', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, username, password, truename, email, groupid, register_ip, last_login_ip, register_time, last_login_time, login_count, status, email_status', 'safe', 'on' => 'search'),
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
            'username' => 'Username',
            'password' => 'Password',
            'truename' => 'Truename',
            'email' => 'Email',
            'groupid' => 'Groupid',
            'register_ip' => 'Register Ip',
            'last_login_ip' => 'Last Login Ip',
            'register_time' => 'Register Time',
            'last_login_time' => 'Last Login Time',
            'login_count' => 'Login Count',
            'status' => 'Status',
            'email_status' => 'Email Status',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('truename', $this->truename, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('groupid', $this->groupid);
        $criteria->compare('register_ip', $this->register_ip, true);
        $criteria->compare('last_login_ip', $this->last_login_ip, true);
        $criteria->compare('register_time', $this->register_time, true);
        $criteria->compare('last_login_time', $this->last_login_time, true);
        $criteria->compare('login_count', $this->login_count, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('email_status', $this->email_status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Users the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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

}
