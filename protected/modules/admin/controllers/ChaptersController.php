<?php

/**
 * @filename ChaptersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-18  17:21:21 
 */
class ChaptersController extends Admin {

    public function actionInto() {
        $sql = "INSERT INTO {{chapters}}(cid) SELECT cid FROM {{chengyu_content}} WHERE classify=".ChengyuContent::CLASSIFY_GUSHI." AND status=".Posts::STATUS_PASSED;
        Yii::app()->db->createCommand($sql)->execute();
        $chapters = Chapters::model()->findAll();
        $maxid = 0;
        $step = 100;
        $cid=1;
        foreach ($chapters as $chapter) {
            $_minInfo = Chengyu::model()->findAll(array(
                'condition' => 'id>' . $maxid . ' AND status=' . Posts::STATUS_PASSED,
                'select' => 'id',
                'limit' => $step,
                'order' => 'id ASC'
            ));
            if(empty($_minInfo)){
                break;
            }
            $arr = array_keys(CHtml::listData($_minInfo, 'id', ''));
            $cur = current($arr);
            $end = end($arr);
            $attr = array(
                'chapter' => '第'.$this->ch_num($cid).'章',
                'cid' => $chapter['cid'],
                'startId' => $cur,
                'endId' => $end,
                'rows' => count($arr),
            );
            if(Chapters::model()->updateByPk($chapter['id'], $attr)){
                $maxid=$end;
                $cid+=1;
            }
        }
        $this->message(1, '已新增');
    }

    function ch_num($num, $mode = true) {
//        $char = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖");
//        $dw = array("", "拾", "佰", "仟", "", "萬", "億", "兆");
        $char = array("〇","一", "二", "三", "四", "五", "六", "七", "八", "九");
        $dw = array("", "十", "百", "千", "", "万", "亿", "兆");
        $dec = "點";
        $retval = "";
        if ($mode) {
            preg_match_all("/^0*(\d*)\.?(\d*)/", $num, $ar);
        } else {
            preg_match_all("/(\d*)\.?(\d*)/", $num, $ar);
        }
        if ($ar[2][0] != "") {
            $retval = $dec . ch_num($ar[2][0], false); //如果有小数，先递归处理小数 
        } elseif ($ar[1][0] != "") {
            $str = strrev($ar[1][0]);
            for ($i = 0; $i < strlen($str); $i++) {
                $out[$i] = $char[$str[$i]];
                if ($mode) {
                    $out[$i] .= $str[$i] != "0" ? $dw[$i % 4] : "";
                    if ($str[$i] + $str[$i - 1] == 0)
                        $out[$i] = "";
                    if ($i % 4 == 0)
                        $out[$i] .= $dw[4 + floor($i / 4)];
                }
            }
            $retval = join("", array_reverse($out)) . $retval;
        }
        return $retval;
    }

}
