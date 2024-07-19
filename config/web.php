<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
     'id' => 'basic',
     'basePath' => dirname(__DIR__),
     'bootstrap' => ['log', 'queue'],
     'aliases' => [
          '@bower' => '@vendor/bower-asset',
          '@npm' => '@vendor/npm-asset',
     ],
     'components' => [
          'queue' => [
               'class' => \yii\queue\db\Queue::class,
               'db' => 'db',
               'tableName' => '{{%queue}}',
               'channel' => 'default',
               'mutex' => \yii\mutex\MysqlMutex::class,
          ],
          'request' => [
               // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
               'cookieValidationKey' => 'pm9Pni0IzFqGyUE0oDA64cLGQdde9kKc',
          ],
          // 'parsers' => [
          //     'application/json' => 'yii\web\JsonParser',
          // ],
          'cache' => [
               'class' => 'yii\caching\FileCache',
//               'class' => 'yii\caching\AppCache',
               'keyPrefix' => 'myapp'
          ],
          'user' => [
               'identityClass' => 'app\models\User',
               'enableAutoLogin' => true,
          ],
          'errorHandler' => [
               'errorAction' => 'site/error',
          ],
          'mailer' => [
               'class' => 'yii\swiftmailer\Mailer',
               'viewPath' => '@mail',
               'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => 'smtp.gmail.com',
                    'port' => '587',
                    'username' => 'huysanti123456@gmail.com',
                    'password' => 'buxk ghay epzs gclb',
                    'encryption' => 'tls',
               ],
          ],
          'log' => [
               'traceLevel' => YII_DEBUG ? 3 : 0,
               'targets' => [
                    [
                         'class' => 'yii\log\FileTarget',
                         'levels' => ['error', 'warning'],
                    ],
               ],
          ],
          'db' => $db,
          'urlManager' => [
               'enablePrettyUrl' => true,
               'showScriptName' => false,
               'rules' => [
                    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
               ],
          ],
          'authManager' => [
               'class' => 'yii\rbac\DbManager',
               // uncomment if you want to cache RBAC items hierarchy
               // 'cache' => 'cache',
          ],
          'common' => [
               'class' => 'app\components\CommonComponent'
          ],

     ],
     'modules' => [
          'api' => [
               'class' => 'yii\base\Module',
               'modules' => [
                    'v1' => [
                         'class' => 'app\modules\Module'
                    ]
               ]
          ],
          'admin' => [
               'class' => 'mdm\admin\Module',
          ],
     ],
//     'as access' => [
//          'class' => 'mdm\admin\components\AccessControl',
//          'allowActions' => [
//               'site/*', // add or remove allowed actions to this list
//
//          ,
//     ],
     'params' => $params,
];

if (YII_ENV_DEV) {
     // configuration adjustments for 'dev' environment
     $config['bootstrap'][] = 'debug';
     $config['modules']['debug'] = [
          'class' => 'yii\debug\Module',
          // uncomment the following to add your IP if you are not connecting from localhost.
          //'allowedIPs' => ['127.0.0.1', '::1'],
     ];

     $config['bootstrap'][] = 'gii';
     $config['modules']['gii'] = [
          'class' => 'yii\gii\Module',
          // uncomment the following to add your IP if you are not connecting from localhost.
          //'allowedIPs' => ['127.0.0.1', '::1'],
     ];
}

return $config;
