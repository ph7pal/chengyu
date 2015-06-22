<?php

class Config extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{config}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
          array('name, classify', 'required'),
          array('name,description', 'length', 'max' => 255),
          array('classify', 'length', 'max' => 16),
          array('hash', 'length', 'max' => 32),
          array('value', 'safe'),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, name, value, description, classify,hash', 'safe', 'on' => 'search'),
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
          'name' => '设置的键名',
          'value' => '设置的值',
          'description' => '设置的描述',
          'classify' => '设置的分类',
          'hash' => 'hash',
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('hash', $this->hash, true);

        return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
