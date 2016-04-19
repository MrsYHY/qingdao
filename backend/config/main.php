<?php

use backend\config\SystemConfig;

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
    'language'=>'zh-CN',
    'sourceLanguage' => 'en',
    'modules' => [],
    'as accessControl' => [
        'class'=>'backend\components\AccessControl',
        'allow'=>['site/login','site/error','site/test','queue/enqueue','queue/haveread','queue/push','queue/js-sdk-for-push-system']
    ],
    'components' => [
        'wechat' => [
            'class' => 'callmez\wechat\sdk\Wechat',
            'appId' => '微信公众平台中的appid',
            'appSecret' => '微信公众平台中的secret',
            'token' => '微信服务器对接您的服务器验证token'
        ],
        /*//多公众号使用方式
        $wechat = Yii::createObject([
            'class' => 'callmez\wechat\sdk\Wechat',
            'appId' => '微信公众平台中的appid',
            'appSecret' => '微信公众平台中的secret',
            'token' => '微信服务器对接您的服务器验证token'
        ]);*/
        'view' => [
            'theme' => [
                'pathMap' => ['@backend/views' => '@backend/views/themes/adminlte'],
                'baseUrl' => '@web/themes/adminlte',
            ],
        ],
        'authManager' => [
            'class'=>'yii\\rbac\\DbManager',
        ],
        'user' => [
            'identityClass' => 'common\activeRecords\User',
            'enableAutoLogin' => true,
        ],

        'log' => SystemConfig::LOG_WAY === 'file' ? [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                ],
            ]
        ] : [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error'],
                ],
            ]
        ],


        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'backend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'backend' => 'backend.php',
                    ],
                ],
            ],
        ],
        'imageUpload'=>[
            'class'=>'common\components\ImageUpload',
            'imageMaxSize'=>1024*1024*2,//默认2MB
        ]
    ],
    'params' => $params,
];
