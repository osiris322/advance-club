<?php

require __DIR__ . '/db_cfg.php';
return [
    'id' => 'advance-club',
    'basePath' => __DIR__,
    'controllerNamespace' => 'advance\controllers',
    'aliases' => [
        '@advance' => __DIR__,
    ],
    'components' => [
        
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'charset' => 'utf8',
        ],
    ],
];

