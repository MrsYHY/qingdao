<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'gMguM0BZ6ggB1VVEuYXB4-3IyN1sC6sY',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    $config['modules']['gridview'] = [
        'class' => 'kartik\grid\Module',
        'downloadAction'=>'export'
    ];
    $config['as accessControl']['allow'] =  [
        'debug/default/toolbar',
        'debug/default/view',
        'gii/default/view',
        'gii/default/index',
        'gii/default/preview',
        'test/index'
    ];
}

return $config;
