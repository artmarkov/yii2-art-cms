<?php
return [
    'name' =>  'My Application',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['comments', 'art'],
    'language' => 'en-US',
    'sourceLanguage' => 'en-US',
    'components' => [
        'art' => [
            'class' => 'artsoft\Art',
            'languages' => [
              'en-US' => 'English',
              'ru' => 'Россия',
            ],
        ],
        'settings' => [
            'class' => 'artsoft\components\Settings'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'artsoft\components\User',
            'on afterLogin' => function ($event) {
                \artsoft\models\UserVisitLog::newVisitor($event->identity->id);
            }
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // DB connection component or its config
            'tableName' => '{{%queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex used to sync queries
            'as log' => \yii\queue\LogBehavior::class,
            'as quuemanager' => \ignatenkovnikita\queuemanager\behaviors\QueueManagerBehavior::class
            // Other driver options
        ],
        'db' => require __DIR__ . '/db.php',
        'mailer' => require __DIR__ . '/mailer.php',
    ],
    'modules' => [
        'comments' => [
            'class' => 'artsoft\comments\Comments',
            'userModel' => 'artsoft\models\User',
            'userAvatar' => function ($user_id) {
                $user = artsoft\models\User::findIdentity((int)$user_id);
                if ($user instanceof artsoft\models\User) {
                    return $user->getAvatar();
                }
                return false;
            }
        ],        
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['*'],
            'generators' => [
                'art-crud' => [
                    'class' => 'artsoft\generator\crud\Generator',
                    'templates' => [
                        'default' => '@vendor/artsoft/yii2-art-generator/crud/art-admin',
                    ]
                ],
            ],
        ],
    ],
];
