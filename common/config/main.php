<?php

use common\util\Tool;
use common\config\BaseConfig;

$config =  [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [

        'redis'=>[
            'class' => 'yii\redis\Connection',
            'hostname' => BaseConfig::REDIS_HOST_1,
            'port' => BaseConfig::REDIS_PORT_1,
            'database' => BaseConfig::REDIS_DATABASE_1,
        ],

        'cache' => BaseConfig::CACHE_WAY === 'file' ? [
            'class' => 'yii\caching\FileCache',
        ] : [
            'class' => 'yii\caching\DbCache',
        ],

        //use seastorage for assets使用sae发布资源
        'assetManager' => BaseConfig::IS_IN_SAE === true ? [
                'class'=>'yii\web\SaeAssetManager',
                'assetDomain'=>'assets',
                'converter' => [
                    'class' => 'yii\web\AssetConverter',
                ]
        ] : [
            ''
        ],

        'urlManager'=>[
            'class'=>'yii\web\UrlManager',
            'showScriptName'=>false,
            'enablePrettyUrl'=>true,
            'suffix'=>'.html',
            'rules'=>[
              ['class'=>'yii\rest\UrlRule','controller'=>'Queue'],
            ],
        ],

    ],
];

Tool::arrayFilter($config);
return $config;
