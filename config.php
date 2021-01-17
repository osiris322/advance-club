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
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $result = $response->isSuccessful ? 'ok' : 'error';
                if ($response->data !== null) {
                    $response->data = [
                        'status' => $result,
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                }
            },
            'format' => 'json',
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // используем "pretty" в режиме отладки
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'events'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'event-times'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'event-costum'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'event-costum-times'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'event-bids'],
            ],
        ],
    ],
];

