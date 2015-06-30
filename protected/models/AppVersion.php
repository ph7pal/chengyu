<?php

/**
 * This is the model class for table "{{app_version}}".
 * 软件版本控制
 * The followings are the available columns in table '{{app_version}}':
 * @property string $id
 * @property integer $version
 * @property string $type
 * @property string $downurl
 * @property integer $status
 */
class AppVersion extends CActiveRecord {

    const STATUS_ISOK = 1; //正常
    const STATUS_EXPIRED = 2; //已过期
    const STATUS_NOTAVAILABLE = 3; //不可用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return '{{app_version}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('version, type', 'required'),
            array('status', 'default', 'setOnEmpty' => true, 'value' => AppVersion::STATUS_ISOK),
            array('type,version', 'length', 'max' => 8),
            array('downurl,content', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('version, type', 'safe', 'on' => 'search'),
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
            'id' => '版本记录ID',
            'version' => '版本号',
            'type' => '类型',
            'downurl' => '下载地址',
            'status' => '状态',
            'content' => '更新描述',
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
        $criteria->compare('version', $this->version);
        $criteria->compare('type', $this->type, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AppVersion the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function check($version, $type) {
        $info = AppVersion::model()->find('version=:ver AND type=:t', array(':ver' => $version, ':t' => $type));
        if (!$info) {
            return array(
                'status' => '1',
                'downurl' => '',
                'content' => '',
            );
        }
        if ($info['status'] != AppVersion::STATUS_ISOK) {
            $_info = AppVersion::model()->find(array(
                'condition' => 'type=:t',
                'select' => 'content',
                'order' => 'id DESC',
                'params' => array(
                    ':t' => $type
                )
            ));
            if ($_info) {
                $info['content'] = $_info['content'];
                $info['downurl'] = $_info['downurl'];
            }
        }
        return $info;
    }

    public static function types($return) {
        $arr = array(
            'ios' => 'IOS',
            'android' => '安卓'
        );
        if ($return == 'admin') {
            return $arr;
        }
        return $arr[$return];
    }

    public static function exStatus($return) {
        $arr = array(
            AppVersion::STATUS_ISOK => '正常',
            AppVersion::STATUS_EXPIRED => '已过期',
            AppVersion::STATUS_NOTAVAILABLE => '不可用',
        );
        if ($return == 'admin') {
            return $arr;
        }
        return $arr[$return];
    }

}
