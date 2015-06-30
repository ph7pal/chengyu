<?php

/**
 * This is the model class for table "{{feedback}}".
 *
 * The followings are the available columns in table '{{feedback}}':
 * @property string $id
 * @property string $uid
 * @property string $contact
 * @property string $appinfo
 * @property string $sysinfo
 * @property string $content
 * @property string $type
 * @property string $ip
 * @property string $cTime
 * @property integer $status
 */
class Feedback extends CActiveRecord {

    const STATUS_STAYCHECK = 0;//未处理
    const STATUS_CHECKED = 1;//已处理

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{feedback}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content', 'required'),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('ip', 'default', 'setOnEmpty' => true, 'value' => ip2long(Yii::app()->request->userHostAddress)),
            array('status', 'numerical', 'integerOnly' => true),
            array('uid,cTime', 'length', 'max' => 10),
            array('ip', 'length', 'max' => 25),
            array('contact, appinfo, sysinfo, content', 'length', 'max' => 255),
            array('type', 'length', 'max' => 8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, contact, appinfo, sysinfo, content, type, ip, cTime, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'userInfo' => array(self::BELONGS_TO, 'User', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => '意见反馈ID',
            'uid' => '作者ID',
            'contact' => '联系方式',
            'appinfo' => '软件信息',
            'sysinfo' => '系统信息',
            'content' => '意见内容',
            'type' => '软件类型',
            'ip' => 'IP',
            'cTime' => '创建时间',
            'status' => '状态',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('contact', $this->contact, true);
        $criteria->compare('appinfo', $this->appinfo, true);
        $criteria->compare('sysinfo', $this->sysinfo, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Feedback the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
