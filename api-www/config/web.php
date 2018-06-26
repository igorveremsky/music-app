<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'api-app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
    	'log',
	    [
		    'class' => 'yii\filters\ContentNegotiator',
		    'formats' => [
			    'application/json' => \yii\web\Response::FORMAT_JSON
		    ]
	    ]
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
	'modules' => [
		'v1' => [
			'basePath' => '@app/modules/v1',
			'class' => \app\modules\v1\Module::class,
		]
	],
    'components' => [
        'request' => [
	        'parsers' => [
		        'application/json' => 'yii\web\JsonParser'
	        ],
        ],
	    'response' => [
		    'class' => \yii\web\Response::className(),
		    'format' => yii\web\Response::FORMAT_JSON,
		    'formatters' => [
			    'json' => [
				    'class' => 'yii\web\JsonResponseFormatter',
				    'prettyPrint' => YII_DEBUG,
				    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
			    ],
		    ],
	    ],
        'user' => [
            'identityClass' => 'app\models\User',
	        'enableAutoLogin' => false,
	        'enableSession' => false,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['components']['request']['cookieValidationKey'] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
