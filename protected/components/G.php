<?php

class G extends T {
  function init() {
    parent::init();
    Yii::app()->theme='games';
    $this->layout = '//layouts/main';
  }

}
