<?php

/**
 * This is the model class for table "{{chengyu_ci}}".
 *
 * The followings are the available columns in table '{{chengyu_ci}}':
 * @property string $id
 * @property string $cid
 * @property string $tocid
 * @property integer $classify
 */
class ChengyuCi extends CActiveRecord {

    const CLASSIFY_FANYICI = 1;//反义词
    const CLASSIFY_TONGYICI = 2;//同义词

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{chengyu_ci}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cid, tocid, classify', 'required'),
            array('classify', 'numerical', 'integerOnly' => true),
            array('cid, tocid', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, cid, tocid, classify', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'chengyuInfo' => array(self::BELONGS_TO, 'Chengyu', 'tocid'),//反义词
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'cid' => '成语ID',
            'tocid' => '关联的成语ID',
            'classify' => '类型：反义词、同义词',
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
        $criteria->compare('cid', $this->cid, true);
        $criteria->compare('tocid', $this->tocid, true);
        $criteria->compare('classify', $this->classify);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ChengyuCi the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
