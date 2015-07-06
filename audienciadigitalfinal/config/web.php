<?php

use yii\base\Event;
use yii\db\ActiveRecord;
use app\models\FilaEnvio;
$params = require(__DIR__ . '/params.php');


/*Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_INSERT, function ($event) {
	if($event->sender->tableName() !== 'fila_envio')
	{
		$filaEnvio = new FilaEnvio();
		$filaEnvio->id_registro = $event->sender->id;
		$filaEnvio->tabela = $event->sender->tableName();
		$filaEnvio->save();
	}
});*/

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
	'aliases' => array(
						'@upload' => dirname(__DIR__).'/upload',
						'@ffmpeg' => dirname(__DIR__).'/web/ffmpeg/windows/bin/ffmpeg',
	),
	'language' => 'pt_BR',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Irn17P3Kkh9dhAw8s0b8mvXRUJkgc2cT',
        		'parsers' => [
        				'application/json' => 'yii\web\JsonParser',
        		]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Usuario',
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    	'urlManager' => [
    			'enablePrettyUrl' => true,
    			'showScriptName' => false,
    			//'enableStrictParsing' => true,
    			'rules' => [
    						[
    							'class' => 'yii\rest\UrlRule',
    							'pluralize' => false,
    							'controller' => 'tema',
    							'extraPatterns' => [
    									'GET lista' => 'lista',
    							],
    						],
	    					[
		    					'class' => 'yii\rest\UrlRule',
		    					'pluralize' => false,
		    					'controller' => 'juiz',
		    					'extraPatterns' => 
	    						[
		    							'GET lista' => 'lista',
		    					],
	    					],
	    					[
		    					'class' => 'yii\rest\UrlRule',
		    					'pluralize' => false,
		    					'controller' => 'tipo-audiencia',
		    					'extraPatterns' =>
		    					[
		    							'GET getflag' => 'getflag',
		    					],
	    					],
    			],
    	],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
