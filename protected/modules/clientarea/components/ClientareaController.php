<?php

class ClientareaController extends Controller
{	
	public $layout = '//layouts/main';
	
	public function filters()
	{
		return array(
			'accessControl',
		);
	}	
	
	// all actions require auth user
	public function accessRules()
	{
		return array(
			array('deny',
				'users' => array('?'),
			),
			array('allow',
				'users' => array('@'),
			),
		);
	}
	
	public function filterAjaxRequest( $filterChain )
	{
		// block all requests which are not ajax
		if( ! Yii::app()->request->isAjaxRequest )
		{
			throw new CHttpException(400, 'Bad request.');
		}
		
		$filterChain->run();
	}
}