<?php

class Attachments extends CActiveRecord {

    public function tableName() {
        return '{{attachments}}';
    }

    public function rules() {
        return array(
            array('covered, status,areaid', 'numerical', 'integerOnly' => true),
            array('uid, logid, hits, cTime,areaid,comments', 'length', 'max' => 11),
            array('filePath, fileDesc, classify, width, height, size,remote', 'length', 'max' => 255),
            array('id, uid, logid, filePath, fileDesc, classify, width, height, size, covered, hits, cTime, status,remote,areaid', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'authorInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '作者',
            'logid' => '所属',
            'filePath' => '文件名',
            'fileDesc' => '描述',
            'classify' => '分类',
            'width' => '宽',
            'height' => '高',
            'size' => '大小',
            'covered' => '置顶',
            'hits' => '点击',
            'cTime' => '创建时间',
            'status' => '状态',
            'favor' => '赞',
            'remote' => '远程路径',
            'areaid' => '所属地区',
            'comments' => '评论数',
        );
    }

    public function search() {

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('logid', $this->logid, true);
        $criteria->compare('classify', $this->classify, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /**
     * 根据单条图片信息返回存放地址
     * @param type $data
     * @return string
     */
    public static function getUrl($data, $size = '170') {
        if($data['remote']!=''){
            $_imgurl=$data['remote'];
        }else{
            $_imgurl = zmf::uploadDirs($data['cTime'], 'site', $data['classify']) . $data['filePath'];
        }
        $reurl=  zmf::getThumbnailUrl($_imgurl, $size, $data['classify']);
        return $reurl;
    }

    /**
     * 返回坐标的封面图
     * @param type $poiInfo
     * @param type $size
     * @return string
     */
    public static function faceImg($poiInfo, $size = '170',$type='posts') {
        $url = '';
        if ($poiInfo['faceimg']) {
            $info = Attachments::getOne($poiInfo['faceimg']);
            if ($info) {
                if($info['remote']!=''){
                    $url=$info['remote'];
                }else{
                    $url = zmf::uploadDirs($info['cTime'], 'site', $info['classify']) . $info['filePath'];
                }
            }
        }
        if(!$url){
            return '';
        }
        $reurl=  zmf::getThumbnailUrl($url, $size, $type);
        return $reurl;
    }
    
    /**
     * 根据图片ID返回图片信息
     * @param type $id
     * @return boolean
     */
    public static function getOne($id){
        if(!$id || !is_numeric($id)){
            return false;
        }
        //todo，图片分表，将图片表分为attachments0~9
        return Attachments::model()->findByPk($id);
    }

}
