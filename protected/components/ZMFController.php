<?php
class ZMFController extends CController{
	public $layout='admin';
    public $menu=array();
    public $breadcrumbs=array();
    protected $_theme;
    protected $_themePath;
    public function init(){
        //CheckController::loginCheck();
        //PowerController::checkPower('login'); 
         $this->_theme = Yii::app()->theme;
        $this->_themePath = str_replace(array('\\', '\\\\'), '/', Yii::app()->theme->basePath);        
        }       
}
