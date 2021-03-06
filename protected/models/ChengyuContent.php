<?php

/**
 * This is the model class for table "{{chengyu_content}}".
 *
 * The followings are the available columns in table '{{chengyu_content}}':
 * @property string $id
 * @property string $cid
 * @property string $uid
 * @property string $content
 * @property integer $status
 * @property integer $top
 * @property string $hits
 * @property string $comments
 * @property string $favors
 * @property string $cTime
 * @property integer $classify
 */
class ChengyuContent extends CActiveRecord {

    const CLASSIFY_JIESHI = 1;
    const CLASSIFY_CHUCHU = 2;
    const CLASSIFY_LIJU = 3;
    const CLASSIFY_GUSHI = 4;
    const TYPE_ZC = 1; //正常解释
    const TYPE_WL = 2; //网络解释

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return '{{chengyu_content}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cid, content, classify', 'required'),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('status, top, classify,type', 'numerical', 'integerOnly' => true),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('cid, uid, hits, comments, favors, cTime', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, cid, uid, content, status, top, hits, comments, favors, cTime, classify', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'chengyuInfo' => array(self::BELONGS_TO, 'Chengyu', 'cid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'cid' => '成语ID',
            'uid' => '用户ID',
            'content' => '解释、释义',
            'status' => '状态',
            'top' => '是否推荐',
            'hits' => '点击次数',
            'comments' => '评论数',
            'favors' => '赞的数量',
            'cTime' => '创建时间',
            'classify' => '分类：解释、出处、例句、故事',
            'type' => '内容类型：正常解释、网络解释',
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
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('top', $this->top);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('favors', $this->favors, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('classify', $this->classify);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ChengyuContent the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getNew() {
        $items = ChengyuContent::model()->findAll(array(
            'condition' => "classify='" . ChengyuContent::CLASSIFY_GUSHI . "' AND type='".ChengyuContent::TYPE_ZC."' AND `status`=" . Posts::STATUS_PASSED,
            'order' => 'cTime DESC',
            'limit' => 20,
            'select' => 'id,cid,content'
        ));
        return $items;
    }
    public static function getXinJie() {
        $sql = "SELECT c.id,c.title,cc.content FROM {{chengyu}} c,{{chengyu_content}} cc WHERE cc.classify='" . ChengyuContent::CLASSIFY_JIESHI . "' AND cc.type='".ChengyuContent::TYPE_WL."' AND cc.cid=c.id AND cc.status=" . Posts::STATUS_PASSED . " ORDER BY cc.cTime DESC LIMIT 10";
        $items=  Yii::app()->db->createCommand($sql)->queryAll();
        return $items;
    }

    public static function getTypes($type) {
        $arr = array(
            ChengyuContent::TYPE_ZC => '原意',
            ChengyuContent::TYPE_WL => '新解',
        );
        if($type=='admin'){
            return $arr;
        }
        return $arr[$type];
    }

}
