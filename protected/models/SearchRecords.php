<?php

class SearchRecords extends CActiveRecord {

    public function tableName() {
        return '{{search_records}}';
    }

    public function rules() {
        return array(
            array('title, times', 'required'),
            array('times', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 50),
            array('title, times', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => '搜索词',
            'times' => '次数',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('title', $this->title, true);
        $criteria->compare('times', $this->times);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function checkAndUpdate($keyword) {
        $keyword = trim($keyword);
        if (!$keyword) {
            return false;
        }
        $info = SearchRecords::model()->find('title=:title', array(':title' => $keyword));
        if ($info) {
            SearchRecords::model()->updateByPk($info['id'], array('times' => ($info['times'] + 1)));
        } else {
            $data = array(
                'title' => $keyword,
                'times' => 1
            );
            $model = new SearchRecords;
            $model->attributes = $data;
            $model->save();
        }
    }

    public static function tops() {
        $tops = zmf::getFCache("top-searchs");
        if (!$tops) {
            $sql = "SELECT * FROM {{search_records}} ORDER BY times DESC LIMIT 10";
            $tops = Yii::app()->db->createCommand($sql)->queryAll();
            zmf::setFCache("top-searchs", $tops, 360);
        }
        return $tops;
    }

    public static function getTops() {
        $keys = zmf::config('hotsearchs');
        if ($keys) {
            $arr = explode('#', $keys);
            return $arr;
        } else {
            return false;
        }
    }

    public static function getTypes($type) {
        $arr = array(
            'position' => '坐标',
            'post' => '文章',
            'question' => '问题',
            'answer' => '回答',
            'poipost' => '点评',
            'poitips' => '短评',
            'comments' => '评论',
            'user' => '用户'
        );
        if ($type == 'admin') {
            return $arr;
        } else {
            return $arr[$type];
        }
    }

}
