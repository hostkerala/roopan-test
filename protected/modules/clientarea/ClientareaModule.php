<?php

class ClientareaModule extends CWebModule
{
	public $defaultController = 'dashboard';
	public $layout = '//layouts/main';
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			//'application.components.*', // Commented By Roopan - Its already inlcuded
			//'application.models.*', // Commented By Roopan - Its already inlcuded
			//'application.helpers.*', // Commented By Roopan - Its already inlcuded
			'clientarea.models.*',
			'clientarea.components.*',
		));
		
             
		$this->setComponents(array(
			'errorHandler' => array(
				'errorAction' => 'clientarea/dashboard/error'
			),
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			
			// all module has separate styling
			Yii::app()->theme = 'clientarea';
			
			return true;
		}
		else
			return false;
	}
 
}
