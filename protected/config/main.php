<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Test Application',
	'theme' => 'public',
	'defaultController' => 'site',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.components.*',
		'application.models.*',
		'application.helpers.*',
		'ext.components.yii-mail.YiiMailMessage',
	),

	'modules'=>array(
		'clientarea',

		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'parola',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','192.168.177.210','::1'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'mail' => array(
			'class' => 'ext.yii-mail.YiiMail',
			'transportType' => 'php',
			'viewPath' => 'webroot.themes.slimmemetermanager.views.mail',
			'logging' => true,
			'dryRun' => false
		),
		
		'format' => array(
			'class' => 'application.components.Formatter',
			'dateFormat' => 'd/m/Y',
			'timeFormat' => 'H:i',
			'datetimeFormat' => 'd/m/Y H:i',
		),

		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false, 
//			'urlSuffix'=>'.html',
			'rules' => require( dirname(__FILE__).DIRECTORY_SEPARATOR. 'routes.php' ),
		),


		'db' => array_merge(
			require( dirname(__FILE__).DIRECTORY_SEPARATOR. 'db.php' ),
			require( dirname(__FILE__).DIRECTORY_SEPARATOR. 'db-local.php' )
		),

		'clientScript' => array(
			'class' => 'ext.core.NLSClientScript',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		/*'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
					'ipFilters'=>array('127.0.0.1','192.168.1.215'),
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),

			),
		),*/
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'office@nego-solutions.com',
	),
);