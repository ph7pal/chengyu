<?php
header("access-control-allow-origin: *");
ini_set('memory_limit', '256M'); 
error_reporting(E_ERROR);
// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
require_once($yii);
Yii::createWebApplication($config)->run();