<?php

/**
 * This is the model class for table "{{tag_relation}}".
 *
 * The followings are the available columns in table '{{tag_relation}}':
 * @property string $id
 * @property string $logid
 * @property string $tagid
 * @property string $classify
 */
class TagRelation extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{tag_relation}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('logid, tagid, classify', 'required'),
            array('logid, tagid', 'length', 'max' => 11),
            array('classify', 'length', 'max' => 32),
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
            'logid' => 'Logid',
            'tagid' => 'Tagid',
            'classify' => 'Classify',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TagRelation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 检查传入的标签是否已存在，不存在则创建，并检查是否已存在对应关系，不存在则创建
     * @param type $id
     * @param type $crumb
     */
    public function checkAndWriteTag($id, $crumb, $tagid = 0) {
        if (!$tagid) {
            $_crumb = strip_tags(trim($crumb));
            $_taginfo = Tags::model()->find('title=:title', array(':title' => $_crumb));
            if (!$_taginfo) {
                $_tagdata = array(
                    'title' => $_crumb,
                    'name' => zmf::pinyin($_crumb),
                    'classify' => 'posts',
                    'cTime' => time(),
                    'status' => 1
                );
                $model_tag = new Tags;
                $model_tag->attributes = $_tagdata;
                $_tagid = $model_tag->save(false);
            } else {
                $_tagid = $_taginfo['id'];
            }
        } else {
            $_tagid = $tagid;
        }
        $_tagrel = array(
            'logid' => $id,
            'tagid' => $_tagid,
            'classify' => 'posts'
        );
        $reinfo = TagRelation::model()->find('logid=:logid AND tagid=:tagid AND classify="posts"', array(':logid' => $id, ':tagid' => $_tagid));
        if (!$reinfo) {
            $model_tagrel = new TagRelation;
            $model_tagrel->attributes = $_tagrel;
            $model_tagrel->save(false);
        }
    }

}
