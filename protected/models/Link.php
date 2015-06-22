<?php

class Link extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{link}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
          array('uid', 'default', 'setOnEmpty' => true, 'value' => Yii::app()->user->id),
          array('title, url, classify', 'required'),
          array('cTime', 'default', 'setOnEmpty' => true, 'value' => time()),
          array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
          array('attachid, status', 'numerical', 'integerOnly' => true),
          array('url', 'url'),
          array('title', 'length', 'max' => 100),
          array('url', 'length', 'max' => 255),
          array('classify', 'length', 'max' => 32),
          array('order, hits, cTime , uid', 'length', 'max' => 10),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, title, url, classify, attachid, status, order, hits, cTime ,uid', 'safe', 'on' => 'search'),
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
          'title' => '友链名称',
          'url' => '友链地址',
          'classify' => '友链类型',
          'attachid' => '使用图片',
          'status' => '状态',
          'order' => '排序',
          'hits' => '点击',
          'cTime' => '创建时间',
          'uid' => '作者',
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('attachid', $this->attachid);
        $criteria->compare('status', $this->status);
        $criteria->compare('order', $this->order, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('uid', $this->uid, true);

        return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function allLinks($limit = 10) {
        $sql = "SELECT * FROM {{link}} WHERE status=1 ORDER BY `order` LIMIT {$limit}";
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        return $items;
    }

}
