<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['gii', 'log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'migrate' => [
            'class' => 'console\controllers\MigrateController'
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
        'enablePrettyUrl' => true,
        'scriptUrl' => 'https://artsoft.loc',
    ],
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'params' => $params,
];
