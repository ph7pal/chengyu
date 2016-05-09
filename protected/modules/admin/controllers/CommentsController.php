<?php

class CommentsController extends Admin {
public function init() {
        parent::init();
        $this->checkPower('comments');
    }
    public function actionIndex() {
        $type = zmf::val('type', 1);
        if (!$type || $type == 'staycheck') {
            $status = Posts::STATUS_STAYCHECK;
        } else {
            $status = Posts::STATUS_PASSED;
        }
        $sql = "SELECT c.id,c.uid,c.content,c.cTime,p.title,c.logid,c.status FROM {{comments}} c,{{posts}} p WHERE c.status={$status} AND c.logid=p.id ORDER BY c.cTime DESC";
        Posts::getAll(array('sql' => $sql), $pager, $items);
        if (!empty($items)) {
            $uids = array_filter(array_keys(CHtml::listData($items, 'uid', '')));
            $uidsStr = join(',', $uids);
            if ($uidsStr != '') {
                $usernames = Yii::app()->db->createCommand("SELECT id,truename FROM {{users}} WHERE id IN($uidsStr)")->queryAll();
                if (!empty($usernames)) {
                    foreach ($items as $k => $val) {
                        foreach ($usernames as $val2) {
                            if ($val['uid'] > 0 && $val['uid'] == $val2['id']) {
                                $items[$k]['loginUsername'] = $val2['truename'];
                            }
                        }
                    }
                }
            }
        }
        $this->render('index', array(
            'pages' => $pager,
            'posts' => $items,
        ));
    }

    public function loadModel($id) {
        $model = Comments::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
