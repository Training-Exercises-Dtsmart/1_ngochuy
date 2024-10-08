<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'interactive' => false,
            'migrationPath' => [
                '@app/migrations', // path to your custom migrations directory
            ],
             'migrationNamespaces' => [
                  // ...
                  'yii\queue\db\migrations',
             ],
        ],
        'batch' => [
            'class' => 'schmunk42\giiant\commands\BatchController',
            'interactive' => false,
            'overwrite' => true,
            'skipTables' => ['system_db_migration', 'system_rbac_migration', 'migration'],
            'modelNamespace' => 'app\models',
            'crudTidyOutput' => false,
            'useTranslatableBehavior' => true,
            'useTimestampBehavior' => true,
            'enableI18N' => false,
            'modelQueryNamespace' => 'app\models',
            'modelBaseClass' => yii\db\ActiveRecord::class,
            'modelQueryBaseClass' => yii\db\ActiveQuery::class
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
         'mailer' => [
              'class' => 'yii\symfonymailer\Mailer',
              'viewPath' => '@app/mail',
              'useFileTransport' => false,
              'transport' => [
                   'dsn' => 'smtp://huysanti123456@gmail.com:buxkghayepzsgclb@smtp.gmail.com:587?encryption=tls',
              ],
         ],
         'queue' => [
              'class' => \yii\queue\db\Queue::class,
              'db' => 'db',
              'tableName' => '{{%queue}}',
              'channel' => 'default',
              'mutex' => \yii\mutex\MysqlMutex::class,
         ],
         'authManager' => [
              'class' => 'yii\rbac\DbManager',
              // uncomment if you want to cache RBAC items hierarchy
               'cache' => 'cache',
         ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
//         'queue' => [
//                   'class' => \yii\queue\Queue::class,
//         'as log' => \yii\queue\LogBehavior::class,
//         // Other driver options
//          ],
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    // configuration adjustments for 'dev' environment
    // requires version `2.1.21` of yii2-debug module
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
