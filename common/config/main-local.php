<?php

use common\config\BaseConfig;
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host='.BaseConfig::DB_IP.';port='.BaseConfig::DB_PORT.';dbname='.BaseConfig::DB_NAME,
            'username' => BaseConfig::DB_USERNAME,
            'password' => BaseConfig::DB_PASSWORD,
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],

    ],
];
