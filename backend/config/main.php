<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'backoffice' => [
            'class' => 'common\modules\backoffice\Module',
            'layout' => 'backend'
        ],
        'structure' => [
            'class' => 'common\modules\structure\Module',
            'layout' => 'backend'
        ],
        'test' => [
            'class' => 'common\modules\test\Module',
            'layout' => 'backend'
        ],
        'pages' => [
            'class' => 'common\modules\pages\Module',
            'layout' => 'backend'
        ],
        'mailing' => [
            'class' => 'common\modules\mailing\Module',
            'layout' => 'backend'
        ],
        'advertisement' => [
            'class' => 'common\modules\advertisement\Module',
            'layout' => 'backend'
        ],
        'seo' => [
            'class' => 'common\modules\seo\Module',
            'layout' => 'backend'
        ],
        'news' => [
			'class' => 'common\modules\news\Module',
			'layout' => 'backend'
		],
		'faq' => [
			'class' => 'common\modules\faq\Module',
			'layout' => 'backend'
		],
		'messages' => [
            'class' => 'common\modules\messages\Module',
            'layout' => 'backend'
        ],
        'payments' => [
            'class' => 'common\modules\payments\Module',
            'layout' => 'backend'
        ],
        'uploads' => [
            'class' => 'common\modules\uploads\Module',
            'layout' => 'main'
        ],
        'tickets' => [
            'class' => 'common\modules\tickets\Module',
            'layout' => 'backend'
        ],
        'slider' => [
            'class' => 'common\modules\slider\Module',
            'layout' => 'backend'
        ],
        'landings' => [
            'class' => 'common\modules\landings\Module',
            'layout' => 'backend'
        ],
        'feedback' => [
            'class' => 'common\modules\feedback\Module',
            'layout' => 'backend'
        ],
        'payeer' => [
            'class' => 'common\modules\payeer\Module',
        ],
    ],
    'defaultRoute' => 'site/index',
    //'homeUrl' => '/cms/backend',
    'components' => [
		'request' => [
			'csrfParam' => '_backendCSRF',
			'enableCsrfValidation' => false,
			'csrfCookie' => [
				'httpOnly' => true,
				'path' => '/admin',
			],
		],
		'user' => [
			'identityClass' => 'common\models\User',
            'class' => 'app\components\WebUser',
            'enableAutoLogin' => true,
			'identityCookie' => [
				'name' => '_backendIdentity',
				'httpOnly' => true,
			],
		],
		'session' => [
			'name' => 'BACKENDSESSID',
			'cookieParams' => [
				'path' => '/admin',
			],
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
			'theme' => [
				'pathMap' => ['@app/views' => '@app/themes/admin/views'],
				'baseUrl' => '@web/themes/admin/views',
			],
		],
		/*'assetManager' => [
			'linkAssets' => true,
			'basePath' => '@webroot/web/assets',
            'baseUrl' => '@web/web/assets' 
		], */
		//'<controller:(post|comment)>/<id:\d+>' => '<controller>/view',
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
				'users/profile/<id:\d+>'=>'users/view',
				'backoffice/backend-partners/matrix-structure/<structure:\d+>/<matrix:\d+>/<demo:\d+>'=>'backoffice/backend-partners/matrix-structure',
				'backoffice/backend-partners/matrix-structure/<structure:\d+>/<matrix:\d+>/<demo:\d+>/index'=>'backoffice/backend-partners/matrix-structure',
				'backoffice/backend-partners/top-leaders/index'=>'backoffice/backend-partners/top-leaders',
				'backoffice/backend-partners/top-leaders/partner-info'=>'backoffice/backend-partners/partner-info',
				'backoffice/backend-partners/partners-level/<id:\d+>/<level:\d+>/<demo:\d+>/<credit:\d+>'=>'backoffice/backend-partners/partners-level',
				'backoffice/backend-partners/partners-matrix/<id:\d+>/<structure:\d+>/<number:\d+>/<demo:\d+>'=>'backoffice/backend-partners/partners-matrix',
				'backoffice/backend-partners/partners-matrix/<id:\d+>/<structure:\d+>/<number:\d+>/<demo:\d+>/<list_view:\d+>'=>'backoffice/backend-partners/partners-matrix',
				'backoffice/backend-partners/sponsor-matrix/<id:\d+>/<structure:\d+>/<number:\d+>/<demo:\d+>/<list_view:\d+>'=>'backoffice/backend-partners/sponsor-matrix',
				'backoffice/backend-gold-token'=>'structure/backend-gold-token/index',
				'backoffice/backend-gold-token/index'=>'structure/backend-gold-token/index',
				'backoffice/backend-invite-pay-off'=>'structure/backend-invite-pay-off/index',
				'backoffice/backend-invite-pay-off/index'=>'structure/backend-invite-pay-off/index',
				'backoffice/backend-partners/admin-payoff-list'=>'structure/backend-admin-pay-off/index',
				'backoffice/backend-partners/admin-payoff-list/index'=>'structure/backend-admin-pay-off/index',
				'backoffice/backend-companies'=>'structure/backend-companies/index',
				'backoffice/backend-companies/index'=>'structure/backend-companies/index',
				'backoffice/backend-partners/compare-wallets/index'=>'backoffice/backend-partners/compare-wallets',
				'backoffice/backend-partners/compare-wallets'=>'backoffice/backend-partners/compare-wallets',
				'backoffice/backend-partners/matrix-payments-list/index'=>'structure/backend-matrix-payments/index',
				'backoffice/backend-partners/matrix-payments-list'=>'structure/backend-matrix-payments/index',
				'backoffice/backend-partners/backend-partners/make-pay-off'=>'structure/backend-matrix-payments/make-pay-off',
				'backoffice/backend-partners/matrix-payments-list/make-pay-off'=>'structure/backend-matrix-payments/make-pay-off',
				'matrix-payments-list'=>'structure/backend-matrix-payments/index',
				'matrix-payments-list/index'=>'structure/backend-matrix-payments/index',
				'matrix-payments-list/mark-pay-off'=>'structure/backend-matrix-payments/mark-pay-off',
				'matrix-payments-list/make-pay-off'=>'structure/backend-matrix-payments/make-pay-off',
				'backoffice/backend-partners/matrix-payments-list/mark-pay-off'=>'structure/backend-matrix-payments/mark-pay-off',
				'backoffice/backend-partners/invite-payoff-list/index'=>'structure/backend-invite-pay-off/index',
				'invite-payoff-list'=>'structure/backend-invite-pay-off/index',
				'backoffice/backend-partners/invite-payoff-list/mark-pay-off'=>'structure/backend-invite-pay-off/mark-pay-off',
				'invite-payoff-list/mark-pay-off'=>'structure/backend-invite-pay-off/mark-pay-off',
				'invite-payoff-list/make-pay-off'=>'structure/backend-invite-pay-off/make-pay-off',
				'backoffice/backend-partners/admin-payoff-list/index'=>'backoffice/backend-partners/admin-payoff-list',
				'payeer/make-pay-off'=>'payeer/payeer/make-pay-off',
				'payeer-payments-list'=>'payeer/payeer-payments/index',
				'payeer-payments-list/index'=>'payeer/payeer-payments/index',
				'auto-pay-off-logs-list'=>'structure/auto-pay-off-logs/index',
				'auto-pay-off-logs-list/index'=>'structure/auto-pay-off-logs/index',
				'auto-pay-off-logs/mark-pay-off'=>'structure/auto-pay-off-logs/mark-pay-off',
				'auto-pay-off-logs/make-pay-off'=>'structure/auto-pay-off-logs/make-pay-off',
				'balls'=>'structure/balls/index',
				'balls/index'=>'structure/balls/index',
				'structure/backend-payments-invoices/payment-history'=>'structure/backend-payments-invoices/payment-history',
				'structure/backend-payments-invoices/payment-history/index'=>'structure/backend-payments-invoices/payment-history',
				'content/uploads/files-upload/content-upload'=>'uploads/files-upload/content-file-upload',
				'static-content/uploads/files-upload/content-upload'=>'uploads/files-upload/content-file-upload',
				'news/backend-news/uploads/files-upload/content-upload'=>'uploads/files-upload/content-file-upload',
				'faq/backend-faq/uploads/files-upload/content-upload'=>'uploads/files-upload/content-file-upload',
				'feedback/backend-feedback/uploads/files-upload/content-upload'=>'uploads/files-upload/content-file-upload',
				'advertisement/backend-sponsor-advert/uploads/files-upload/content-upload'=>'uploads/files-upload/content-file-upload',
				'uploads/files-upload/upload'=>'uploads/files-upload/upload',
                'uploads/files-upload/delete-file'=>'uploads/files-upload/delete-file',
				'admin/uploads/files-upload/upload'=>'uploads/files-upload/upload',
                'admin/uploads/files-upload/delete-file'=>'uploads/files-upload/delete-file',
                'tickets/backend-tickets/ticket/<id:\d+>'=>'tickets/backend-tickets/ticket',
                'tickets/backend-tickets/ticket/send-message/<id:\d+>'=>'tickets/backend-tickets/send-message',
                'tickets/backend-tickets/ticket/delete/<id:\d+>/<ticket_id:\d+>'=>'tickets/backend-tickets/delete-message',
                'seo/backend-seo/counters/index'=>'seo/backend-seo/counters',
                'accounting/backend-accounting'=>'structure/backend-accounting/index',
                'accounting/backend-accounting/paid-partners/index'=>'structure/backend-accounting/index',
                'accounting/backend-accounting/earned-partners'=>'structure/backend-accounting/earned-partners',
                'accounting/backend-accounting/earned-partners/index'=>'structure/backend-accounting/earned-partners',
                'accounting/backend-accounting/earned-partners/earnings-list'=>'structure/backend-accounting/earnings-list',
                'accounting/backend-accounting/earned-partners/earnings-payment-list'=>'structure/backend-accounting/earnings-payment-list',
                'accounting/backend-accounting/earned-partners/earned-partners/earnings-payment-list'=>'structure/backend-accounting/earnings-payment-list',
                'accounting/backend-accounting/earnings-payment-list'=>'structure/backend-accounting/earnings-payment-list',
                'accounting/backend-accounting/earnings-list'=>'structure/backend-accounting/earnings-payment-list',
                'accounting/backend-accounting/payments-by-id'=>'structure/backend-accounting/payments-by-id',
                'accounting/backend-accounting/compare-payment-by-id'=>'structure/backend-accounting/compare-payment-by-id',
                'accounting/partner-info'=>'backoffice/backend-partners/partner-info',
                'accounting/referral-balls-list'=>'backoffice/backend-partners/referral-balls-list',
                'accounting/partners-balls-list'=>'structure/backend-accounting/partners-balls-list',
                'accounting/partners-balls-list/index'=>'structure/backend-accounting/partners-balls-list',
                'accounting/partners-balls-list/referral-balls-list'=>'backoffice/backend-partners/referral-balls-list',
                'accounting/transfer-balls-list'=>'structure/backend-accounting/transfer-balls-list',
                'accounting/transfer-balls-list/index'=>'structure/backend-accounting/transfer-balls-list',
                'accounting/payments-logs'=>'structure/backend-payment-logs/index',
                'accounting/payments-logs/index'=>'structure/backend-payment-logs/index',
                'slider'=>'slider/backend-slider/index',
                'slider/index'=>'slider/backend-slider/index',
                'landings/backend-landings'=>'landings/backend-landings/index',
                'landings/backend-landings/index'=>'landings/backend-landings/index',
                'payments-settings'=>'settings/payments-settings',
                'payments-settings/index'=>'settings/payments-settings',
                'modules-settings/index'=>'settings/modules-settings',
                'modules-settings'=>'settings/modules-settings',
                'seo/backend-seo/index-page/index'=>'seo/backend-seo/index-page',
                'test/create-demo-structure'=>'backoffice/backend-partners/create-demo-structure'
            ]
        ],
    ],
    'params' => $params,
    'aliases' => [
        '@theme' => '/themes/admin',
    ],
];
