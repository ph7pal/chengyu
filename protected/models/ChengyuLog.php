<?php

/**
 * This is the model class for table "{{chengyu_log}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-12 03:35:07
 * The followings are the available columns in table '{{chengyu_log}}':
 * @property string $id
 * @property integer $table
 * @property string $logid
 * @property integer $action
 * @property string $cTime
 */
class ChengyuLog extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{chengyu_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('table, logid, action, cTime', 'required'),
            array('table, action', 'numerical', 'integerOnly' => true),
            array('logid, cTime', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, table, logid, action, cTime', 'safe', 'on' => 'search'),
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
            'table' => '所属表',
            'logid' => '所属对象ID',
            'action' => '操作分类',
            'cTime' => '时间时间',
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
        $criteria->compare('table', $this->table);
        $criteria->compare('logid', $this->logid, true);
        $criteria->compare('action', $this->action);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ChengyuLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function exTables($type) {
        $arr = array(
            'chengyu' => '1',
            'content' => '2',
            'ci' => '3',
        );
        if ($type == 'admin') {
            return $arr;
        }
        if(is_numeric($type)){
            $arr=  array_flip($arr);
        }
        return $arr[$type];
    }

    public static function exActions($type) {
        $arr = array(
            'add' => '1',
            'update' => '2',
            'del' => '3',
        );
        if ($type == 'admin') {
            return $arr;
        }
        if(is_numeric($type)){
            $arr=  array_flip($arr);
        }
        return $arr[$type];
    }

    public static function addLog($table, $action, $logid) {
        $now = zmf::now();
        $attr = array(
            'table' => ChengyuLog::exTables($table),
            //'action'=>  ChengyuLog::exActions($action),
            'logid' => $logid
        );
        $logInfo = ChengyuLog::model()->findByAttributes($attr);
        zmf::fp($attr,1);
        zmf::fp($logInfo,1);
        if ($logInfo) {
            //有日志，说明是对该条记录再操作
            return ChengyuLog::model()->updateByPk($logInfo['id'], array(
                        'action' => ChengyuLog::exActions($action),
                        'cTime' => $now,
            ));
        } else {
            //没有日志则新增
            $attr['action'] = ChengyuLog::exActions($action);
            $attr['cTime'] = $now;
            zmf::fp($attr,1);
            $model = new ChengyuLog;
            $model->attributes = $attr;
            return $model->save();
        }
    }

}
