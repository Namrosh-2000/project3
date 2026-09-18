<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'sw',
    'sourceLanguage' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '7gBUlpTEo-Wx1ulhhexuBxDyITOAD2x6',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '' => 'site/index',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'signup' => 'site/signup',
                'lang/<code:sw|en>' => 'site/set-language',
                'dashboard/entrepreneur' => 'dashboard/entrepreneur',
                'dashboard/owner' => 'dashboard/owner',
                'search' => 'property/index',
                'property/<id:\d+>' => 'property/view',
                'property/<id:\d+>/contact' => 'property/contact',
                'property/<id:\d+>/favorite' => 'property/toggle-favorite',
                'property/<id:\d+>/report' => 'property/report',
                'compare' => 'property/compare',
                'map' => 'property/map',
                'business-advice' => 'business/index',
                'my/analysis-history' => 'business/history',
                'business/view/<id:\d+>' => 'business/view',
                'account/reply-inquiry/<id:\d+>' => 'account/reply-inquiry',
                'my/favorites' => 'account/favorites',
                'my/listings' => 'account/listings',
                'my/inquiries' => 'account/inquiries',
                'my/submit' => 'property-submission/create',
                'profile' => 'profile/index',
                'property-submission/update/<id:\d+>' => 'property-submission/update',
                'property-submission/delete/<id:\d+>' => 'property-submission/delete',
                'admin' => 'admin/dashboard',
                'admin/properties' => 'admin/properties',
                'admin/properties/<id:\d+>/verify' => 'admin/verify',
                'admin/properties/<id:\d+>/reject' => 'admin/reject',
                'admin/reports' => 'admin/reports',
                'admin/users' => 'admin/users',
                'admin/local-data' => 'admin/local-data',
                'admin/local-data-source-create' => 'admin/local-data-source-create',
                'admin/local-data-source-update/<id:\d+>' => 'admin/local-data-source-update',
                'admin/local-data-source-delete/<id:\d+>' => 'admin/local-data-source-delete',
                'admin/indicator-create' => 'admin/indicator-create',
                'admin/indicator-update/<id:\d+>' => 'admin/indicator-update',
                'admin/indicator-delete/<id:\d+>' => 'admin/indicator-delete',
                // Phase 7D/7E – Payments
                'my/bookings' => 'booking/index',
                'my/bookings/owner' => 'booking/owner',
                'payment/booking/<booking_id:\d+>' => 'payment/view',
                'payment/<id:\d+>/confirm' => 'payment/confirm',
                'payment/<id:\d+>/fail' => 'payment/fail',
                'payment/<id:\d+>/cancel' => 'payment/cancel',
                'payment/initiate/<booking_id:\d+>' => 'payment/initiate',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
