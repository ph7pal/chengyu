<?php

/**
 * This is the model class for table "{{chapters}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-18 11:31:18
 * The followings are the available columns in table '{{chapters}}':
 * @property string $id
 * @property string $chapter
 * @property string $cid
 * @property string $startId
 * @property string $endId
 * @property string $readId
 * @property string $rows
 */
class Chapters extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{chapters}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('chapter,cid', 'required'),
            array('chapter', 'length', 'max' => 255),
            array('cid, startId, endId, readId, rows', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, chapter, cid, startId, endId, readId, rows', 'safe', 'on' => 'search'),
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
            'chapter' => '章节标题',
            'cid' => '成语ID',
            'startId' => '起始成语ID',
            'endId' => '结束成语ID',
            'readId' => '已读成语ID',
            'rows' => '章节条数',
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
        $criteria->compare('chapter', $this->chapter, true);
        $criteria->compare('cid', $this->cid, true);
        $criteria->compare('startId', $this->startId, true);
        $criteria->compare('endId', $this->endId, true);
        $criteria->compare('readId', $this->readId, true);
        $criteria->compare('rows', $this->rows, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Chapters the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
