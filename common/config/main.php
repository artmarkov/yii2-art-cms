<?php
return [
    'name' =>  'My Application',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['comments', 'art' , 'queue'],
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
        'db' => require __DIR__ . '/db.php',
        'mailer' => require __DIR__ . '/mailer.php',        
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // DB connection component or its config 
            'ttr' => 5 * 60, // Максимальное время выполнения задания 
            'attempts' => 3, // Максимальное кол-во попыток
//            'tableName' => '{{%queue_push}}', // Table name
//            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex used to sync queries
            'as jobMonitor' => \zhuravljov\yii\queue\monitor\JobMonitor::class,
            'as workerMonitor' => \zhuravljov\yii\queue\monitor\WorkerMonitor::class,
            'as queueSchedule' => \artsoft\queue\JobSchedule::class,
        ],  
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
                'job' => [
                    'class' => \yii\queue\gii\Generator::class,
                ],
            ],
        ],
    ],    
];
