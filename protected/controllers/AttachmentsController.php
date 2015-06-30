<?php

class AttachmentsController extends Q {

    public function actionUpload() {
        $uptype = zmf::filterInput($_GET['type'], 't', 1);
        $logid = zmf::filterInput($_GET['id']); //所属对象
        $reImgsize = zmf::filterInput($_GET['imgsize']); //返回图片的尺寸
        $fileholder = zmf::filterInput($_GET['fileholder'], 't', 1); //上传控件的ID
        //将ads替换为flash
        if (!isset($uptype) OR ! in_array($uptype, array('siteinfo'))) {
            $this->jsonOutPut(0, '请设置上传所属类型' . $uptype);
        }
        if (Yii::app()->request->getParam('PHPSESSID')) {
            Yii::app()->session->close();
            $res = Yii::app()->session->setSessionID(Yii::app()->request->getParam('PHPSESSID'));
            Yii::app()->session->open();
        }
        if (Yii::app()->user->isGuest) {
            $this->jsonOutPut(0, Yii::t('default', 'loginfirst'));
        }
        if (!$fileholder) {
            $fileholder = 'filedata';
        }
        if (!isset($_FILES[$fileholder]) || !is_uploaded_file($_FILES[$fileholder]["tmp_name"]) || $_FILES[$fileholder]["error"] != 0) {
            $this->jsonOutPut(0, '无效上传，请重试');
        }
        $model = new Attachments();
        $img = CUploadedFile::getInstanceByName($fileholder);
        $ext = $img->getExtensionName();
        $size = $img->getSize();
        if ($size > zmf::config('imgMaxSize')) {
            $this->jsonOutPut(0, '上传文件最大尺寸为：' . tools::formatBytes(zmf::config('imgMaxSize')));
        }
        $upExt = zmf::config("imgAllowTypes");
        if (!preg_match('/^(' . str_replace('*.', '|', str_replace(';', '', $upExt)) . ')$/i', $ext)) {
            $this->jsonOutPut(0, '上传文件扩展名必需为：' . $upExt);
        }
        $sizeinfo = getimagesize($_FILES[$fileholder]["tmp_name"]);
        if ($sizeinfo['0'] < zmf::config('imgMinWidth') OR $sizeinfo[1] < zmf::config('imgMinHeight')) {
            $this->jsonOutPut(0, "要求上传的图片尺寸，宽不能不小于" . zmf::config('imgMinWidth') . "px，高不能小于" . zmf::config('imgMinHeight') . "px.");
        }
        $ctime = zmf::now();
        $dirs = zmf::uploadDirs($ctime, 'app', $uptype, null, true);
        $fileName = uniqid() . '.' . $ext;
        $origin = $dirs['origin'];
        unset($dirs['origin']);
        $uploadedFiles = array();
        $uploadedFiles[] = array(
            'from' => $origin . $fileName,
            'to' => zmf::ftpPath($ctime, $uptype, 'origin') . $fileName
        );
        if (move_uploaded_file($_FILES[$fileholder]["tmp_name"], $origin . $fileName)) {
            $data = array();
            if ($uptype == 'posts') {
                $status = Posts::STATUS_DELED;
            } else {
                $status = Posts::STATUS_PASSED;
            }
            $data['uid'] = Yii::app()->user->id;
            $data['logid'] = $logid;
            $data['filePath'] = $fileName;
            $data['fileDesc'] = $fileName;
            $data['classify'] = $uptype;
            $data['covered'] = '0';
            $data['cTime'] = time();
            $data['status'] = $status;
            $data['width'] = $sizeinfo[0];
            $data['height'] = $sizeinfo[1];
            $data['size'] = $size;
            $model->attributes = $data;
            if ($model->validate()) {
                if ($model->save()) {
                    $image = Yii::app()->image->load($origin . $fileName);
                    $_quality = zmf::config('imgQuality');
                    $quality = isset($quality) ? $quality : 100;
                    foreach ($dirs as $dk => $_dir) {
                        if ($dk < 600) {
                            $image->smart_resize($dk, $dk * 0.75)->quality($quality);
                        } else {
                            $image->resize($dk, $dk)->quality($quality);
                        }
                        $image->save($_dir . $fileName);
                        $_todir = zmf::ftpPath($ctime, $uptype, $dk);
                        $uploadedFiles[] = array(
                            'id' => $model->id,
                            'from' => $_dir . $fileName,
                            'to' => $_todir . $fileName
                        );
                    }
                    $imgsize = $reImgsize > 0 ? $reImgsize : 170;
                    $returnimg = zmf::uploadDirs($ctime, 'site', $uptype, $imgsize) . $fileName;
                    $outPutData = array(
                        'status' => 1,
                        'attachid' => $model->id,
                        'imgsrc' => $returnimg
                    );
                    $json = CJSON::encode($outPutData);
                    echo $json;
                } else {
                    $this->jsonOutPut(0, '写入数据库错误');
                }
            } else {
                $this->jsonOutPut(0, '数据验证错误');
            }
        }
    }

    /**
     * 不入数据库的上传
     * 不做压缩和缩略图处理
     */
    public function actionSimpleUpload() {
        $uptype = zmf::filterInput($_GET['type'], 't', 1);
        $fileholder = zmf::filterInput($_GET['fileholder'], 't', 1); //上传控件的ID
        $fileName = zmf::filterInput($_GET['fileName'], 't', 1); //上传后保存名字
        $keyid = zmf::filterInput($_GET['keyid']); //所属对象ID        
        if (!isset($uptype) OR ! in_array($uptype, array('topArea', 'avatar'))) {
            $this->jsonOutPut(0, '请设置上传所属类型' . $uptype);
        }
        if (Yii::app()->request->getParam('PHPSESSID')) {
            Yii::app()->session->close();
            Yii::app()->session->setSessionID(Yii::app()->request->getParam('PHPSESSID'));
            Yii::app()->session->open();
        }
        if (Yii::app()->user->isGuest) {
            $this->jsonOutPut(0, Yii::t('default', 'loginfirst'));
        }
        if ($uptype == 'avatar' && !$keyid) {
            $this->jsonOutPut(0, '缺少参数');
        }
        $checkInfo = UserPower::check('addImage', true);
        if (!$checkInfo['status']) {
            $this->jsonOutPut(0, $checkInfo['msg']);
        }

        if (!$fileholder) {
            $fileholder = 'filedata';
        }
        if (!isset($_FILES[$fileholder]) || !is_uploaded_file($_FILES[$fileholder]["tmp_name"]) || $_FILES[$fileholder]["error"] != 0) {
            $this->jsonOutPut(0, '无效上传，请重试');
        }
        $img = CUploadedFile::getInstanceByName($fileholder);
        $ext = $img->getExtensionName();
        $size = $img->getSize();
        if ($size > zmf::config('imgMaxSize')) {
            $this->jsonOutPut(0, '上传文件最大尺寸为：' . tools::formatBytes(zmf::config('imgMaxSize')));
        }
        $upExt = zmf::config("imgAllowTypes");
        if (!preg_match('/^(' . str_replace('*.', '|', str_replace(';', '', $upExt)) . ')$/i', $ext)) {
            $this->jsonOutPut(0, '上传文件扩展名必需为：' . $upExt);
        }
        if (!$fileName) {
            $fileName = uniqid() . '.' . $ext;
        }
        if ($uptype == 'avatar') {
            $fileName = $keyid . '.jpg';
        }
        if ($uptype == 'topArea') {
            $todir = zmf::attachBase('app') . '/daodao/';
            $returnDir = zmf::attachBase('site') . '/daodao/';
        } elseif ($uptype == 'avatar') {
            $todir = zmf::attachBase('app') . '/avatar/origin/' . $keyid . '/';
            $todir2 = zmf::attachBase('app') . '/avatar/big/' . $keyid . '/';
            $todir3 = zmf::attachBase('app') . '/avatar/small/' . $keyid . '/';
            $returnDir = zmf::attachBase('site') . '/avatar/small/' . $keyid . '/';
        }
        zmf::createUploadDir($todir);
        if (move_uploaded_file($_FILES[$fileholder]["tmp_name"], $todir . $fileName)) {
            if ($uptype == 'avatar') {
                $image = Yii::app()->image->load($todir . $fileName);
                zmf::createUploadDir($todir2);
                zmf::createUploadDir($todir3);
                $image->smart_resize(100, 100)->quality(95);
                $image->save($todir2 . $fileName);
                $image->smart_resize(50, 50)->quality(95);
                $image->save($todir3 . $fileName);
            }
            $outPutData = array(
                'status' => 1,
                'imgsrc' => $returnDir . $fileName,
                'attachid' => '',
            );
            $json = CJSON::encode($outPutData);
            echo $json;
        } else {
            $this->jsonOutPut(0, '上传失败');
        }
    }

}
