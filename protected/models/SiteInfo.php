<?php

/**
 * This is the model class for table "{{site_info}}".
 * 站点信息，如关于我们等
 * The followings are the available columns in table '{{site_info}}':
 * @property string $id
 * @property string $uid
 * @property string $colid
 * @property string $faceimg
 * @property string $code
 * @property string $title
 * @property string $content
 * @property string $hits
 * @property string $cTime
 * @property string $updateTime
 * @property integer $status
 */
class SiteInfo extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{site_info}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('uid, code, content', 'required'),
            array('code','unique'),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('status', 'numerical', 'integerOnly' => true),
            array('cTime,updateTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),            
            array('uid, colid, faceimg, hits, cTime, updateTime', 'length', 'max' => 10),
            array('code', 'length', 'max' => 16),
            array('title', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, colid, faceimg, code, title, content, hits, cTime, updateTime, status', 'safe', 'on' => 'search'),
        );
    }                         

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authorInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '作者',
            'colid' => '所属分类',
            'faceimg' => '封面图',
            'code' => '唯一码',
            'title' => '标题',
            'content' => '正文',
            'hits' => '点击',
            'cTime' => '创建时间',
            'updateTime' => '更新时间',
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
        $criteria->compare('colid', $this->colid, true);
        $criteria->compare('faceimg', $this->faceimg, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('updateTime', $this->updateTime, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SiteInfo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
