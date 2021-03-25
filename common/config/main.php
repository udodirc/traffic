<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Europe/Moscow',
    'components' => [
		'formatter' => [  
            'dateFormat' => 'd.MM.yyyy',
            'timeFormat' => 'H:mm:ss',
            'datetimeFormat' => 'd.MM.yyyy H:mm',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
			'translations' => [
				'menu*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					'sourceLanguage' => 'ru-RU',
					'fileMap' => [
						'menu' => 'menu.php',
					],
				],
				'messages*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					'sourceLanguage' => 'ru-RU',
					'fileMap' => [
						'messages' => 'messages.php',
					],
				],
				'form*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					'sourceLanguage' => 'ru-RU',
					'fileMap' => [
						'messages' => 'form.php',
					],
				],
				'mail*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					'sourceLanguage' => 'ru-RU',
					'fileMap' => [
						'messages' => 'mail.php',
					],
				],
			],
		],
		'assetManager' => [
			'linkAssets' => true,
			//'appendTimestamp' => true,
		],
		 'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'ru-RU',
    //'language' => 'en-En',
    'bootstrap' => [
		'common\config\pagination',
		//'common\config\admin_top_menu',
		//'common\config\admin_left_menu',
	],
];
