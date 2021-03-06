<?php

/**
 * This is the model class for table "{{chengyu}}".
 *
 * The followings are the available columns in table '{{chengyu}}':
 * @property string $id
 * @property string $title
 * @property string $hash
 * @property string $title_tw
 * @property string $pinyin
 * @property string $firstChar
 * @property string $yufa
 * @property string $hits
 * @property string $cTime
 * @property integer $status
 */
class Chengyu extends CActiveRecord {

    public $fanyici;
    public $tongyici;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{chengyu}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('hash,title', 'unique', 'on' => 'create'),
            array('title, title_tw, pinyin, yufa,fayin', 'length', 'max' => 255),
            array('hash', 'length', 'max' => 32),
            array('firstChar,firstWord,secondWord,thirdWord,fourthWord,lastWord', 'length', 'max' => 1),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('status', 'numerical', 'integerOnly' => true),
            array('hits, cTime', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, hash, title_tw, pinyin, firstChar, yufa, hits, cTime, status', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        $this->title = zmf::twTozh($this->title);
        $this->title_tw = zmf::zhTotw($this->title);
        $this->hash = md5($this->title);
        $this->pinyin = tools::pinyin($this->title);
        $this->firstChar = substr($this->pinyin, 0, 1);
        $karr = zmf::chararray($this->title);
        $_last=end($karr[0]);
        $this->firstWord = $karr[0][0];
        $this->secondWord = $karr[0][1];
        $this->thirdWord = $karr[0][2];
        $this->fourthWord = $karr[0][3];
        $this->lastWord = $_last;
        return true;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'fanYiCis' => array(self::HAS_MANY, 'ChengyuCi', 'cid', 'condition' => 'classify="' . ChengyuCi::CLASSIFY_FANYICI . '"', 'order' => 'id ASC'), //反义词
            'tongYiCis' => array(self::HAS_MANY, 'ChengyuCi', 'cid', 'condition' => 'classify="' . ChengyuCi::CLASSIFY_TONGYICI . '"', 'order' => 'id ASC'), //同义词
            'jieShis' => array(self::HAS_MANY, 'ChengyuContent', 'cid', 'condition' => 'classify="' . ChengyuContent::CLASSIFY_JIESHI . '" AND `status`=' . Posts::STATUS_PASSED, 'order' => 'id ASC'), //解释
            'chuChus' => array(self::HAS_MANY, 'ChengyuContent', 'cid', 'condition' => 'classify="' . ChengyuContent::CLASSIFY_CHUCHU . '" AND `status`=' . Posts::STATUS_PASSED, 'order' => 'id ASC'), //出处
            'liJus' => array(self::HAS_MANY, 'ChengyuContent', 'cid', 'condition' => 'classify="' . ChengyuContent::CLASSIFY_LIJU . '" AND `status`=' . Posts::STATUS_PASSED, 'order' => 'id ASC'), //例句
            'guShis' => array(self::HAS_MANY, 'ChengyuContent', 'cid', 'condition' => 'classify="' . ChengyuContent::CLASSIFY_GUSHI . '" AND `status`=' . Posts::STATUS_PASSED, 'order' => 'id ASC'), //故事
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => '成语',
            'hash' => '名称MD5',
            'title_tw' => '繁体',
            'pinyin' => '拼音',
            'fayin' => '发音',
            'firstChar' => '拼音首字母',
            'yufa' => '语法',
            'hits' => '访问次数',
            'cTime' => '创建时间',
            'status' => '状态',
            'fanyici' => '反义词',
            'tongyici' => '同义词',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('hash', $this->hash, true);
        $criteria->compare('title_tw', $this->title_tw, true);
        $criteria->compare('pinyin', $this->pinyin, true);
        $criteria->compare('firstChar', $this->firstChar, true);
        $criteria->compare('yufa', $this->yufa, true);
        $criteria->compare('hits', $this->hits, true);
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
     * @return Chengyu the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getNew($limit = 50) {
        $items = Chengyu::model()->findAll(array(
            'order' => 'cTime DESC',
            'limit' => $limit,
            'select' => 'id,hash,title'
        ));
        return $items;
    }

    public static function getRelatedWords($word, $notInclude) {
        $return = array();
        $wordArr = zmf::chararray($word);
        $wordArr = $wordArr[0];
        if ($wordArr[0]) {
            $items = Chengyu::model()->findAll(array(
                'condition' => 'firstWord=:w AND status=' . Posts::STATUS_PASSED . ' AND id!=:id',
                'select' => 'id,title',
                'limit' => 5,
                'params' => array(
                    ':w' => $wordArr[0],
                    ':id' => $notInclude,
                )
            ));
            $return['firstWord'] = $items;
        }
        if ($wordArr[1]) {
            $items = Chengyu::model()->findAll(array(
                'condition' => 'secondWord=:w AND status=' . Posts::STATUS_PASSED . ' AND id!=:id',
                'select' => 'id,title',
                'limit' => 5,
                'params' => array(
                    ':w' => $wordArr[1],
                    ':id' => $notInclude,
                )
            ));
            $return['secondWord'] = $items;
        }
        if ($wordArr[2]) {
            $items = Chengyu::model()->findAll(array(
                'condition' => 'thirdWord=:w AND status=' . Posts::STATUS_PASSED . ' AND id!=:id',
                'select' => 'id,title',
                'limit' => 5,
                'params' => array(
                    ':w' => $wordArr[2],
                    ':id' => $notInclude,
                )
            ));
            $return['thirdWord'] = $items;
        }
        if ($wordArr[3]) {
            $items = Chengyu::model()->findAll(array(
                'condition' => 'fourthWord=:w AND status=' . Posts::STATUS_PASSED . ' AND id!=:id',
                'select' => 'id,title',
                'limit' => 5,
                'params' => array(
                    ':w' => $wordArr[3],
                    ':id' => $notInclude,
                )
            ));
            $return['fourthWord'] = $items;
        }
        $real=array();
        foreach ($return as $k => $v) {
            $tmp=array();
            foreach ($v as $k2 => $v2) {
                $tmp[]=array(
                    'id'=>$v2['id'],
                    'title'=>$v2['title'],
                );
            }
            $real[$k] = $tmp;
        }
        return $real;
    }

    private function filterValue($var) {
        return is_null($var) ? false : false;
    }

}
