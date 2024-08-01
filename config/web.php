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
          'redis' => [
               'class' => 'yii\redis\Connection',
               'hostname' => 'localhost',
               'port' => 6379,
               'database' => 0,
          ],
          'cache' => [
//               'class' => 'yii\caching\FileCache',
//               'class' => 'yii\caching\AppCache',
               'class' => 'yii\redis\Cache',
               'redis' => 'redis',
//               'keyPrefix' => 'myapp'
          ],
          'user' => [
               'identityClass' => 'app\models\User',
               'enableAutoLogin' => true
          ],
          'errorHandler' => [
//               'errorAction' => 'site/error',
               'class' => 'app\components\CustomErrorHandler',
          ],
          'mailer' => [
               'class' => 'yii\symfonymailer\Mailer',
               'viewPath' => '@app/mail',
               'useFileTransport' => false,
               'transport' => [
                    'dsn' => 'smtp://huysanti123456@gmail.com:buxkghayepzsgclb@smtp.gmail.com:587?encryption=tls',
               ],
          ],
          'nexmo' => [
               'class' => 'app\components\NexmoComponent',
               'apiKey' => '69bdfb28',
               'apiSecret' => 'SsU9J4N9b0mVqbJl',
               'fromNumber' => '84359221014',
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
          'fileStorage' => [
               'class' => 'trntv\filekit\Storage',
               'baseUrl' => '@web/uploads',
               'filesystem' => [
                    'class' => 'app\components\filesystem\LocalFlysystemBuilder',
                    'path' => '@webroot/uploads'
               ],
          ],
          'authManager' => [
               'class' => 'yii\rbac\DbManager',
               'cache' => 'cache',
          ],
          'common' => [
               'class' => 'app\components\CommonComponent'
          ],
          // Dùng hàm để đăng ký thành phần "search"
          'search' => function () {
               return new app\components\SolrService;
          },
     ],
     'modules' => [
          'api' => [
               'class' => 'yii\base\Module',
               'modules' => [
                    'v1' => [
                         'class' => 'app\modules\api\modules\v1\Module',
                         'modules' => [
                              'users' => [
                                   'class' => 'app\modules\users\Module',
                              ],
                              'shops' => [
                                   'class' => 'app\modules\shops\Module',
                              ],
                         ],
                    ],
               ],
          ],
          'admin' => [
               'class' => 'mdm\admin\Module',
          ],
     ],
//     'catchAll' => [
//          'offline/notice',
//          'param1' => 'value1',
//          'param2' => 'value2',
//     ],
//     'as access' => [
//          'class' => 'mdm\admin\components\AccessControl',
//          'allowActions' => [
//               'site/*', // add or remove allowed actions to this list
//
//          ,
//     ],
     'params' => $params,
     'timeZone' => 'Asia/Ho_Chi_Minh',

     // Add container definitions here
     'container' => [
          'definitions' => [
               'yii\caching\CacheInterface' => [
                    'class' => 'yii\caching\FileCache',
               ],
               'app\services\BrandService' => [
                    'class' => 'app\services\BrandService',
               ],
               'app\services\CommentService' => [
                    'class' => 'app\services\CommentService',
               ],
               'app\services\OrderService' => [
                    'class' => 'app\services\OrderService',
               ],
               'app\services\PaymentTypeService' => [
                    'class' => 'app\services\PaymentTypeService',
               ],
               'app\services\PostService' => [
                    'class' => 'app\services\PostService',
               ],
               'app\services\ProductService' => [
                    'class' => 'app\services\ProductService',
               ],
               'app\repositories\CommentRepository' => [
                    'class' => 'app\repositories\CommentRepository',
               ],
               'app\repositories\BrandRepository' => [
                    'class' => 'app\repositories\BrandRepository',
               ],
               'app\repositories\OrderRepository' => [
                    'class' => 'app\repositories\OrderRepository',
               ],
               'app\repositories\PaymentTypeRepository' => [
                    'class' => 'app\repositories\PaymentTypeRepository',
               ],
               'app\repositories\PostRepository' => [
                    'class' => 'app\repositories\PostRepository',
               ],
               'app\repositories\ProductRepository' => [
                    'class' => 'app\repositories\ProductRepository',
               ],
          ],
     ],

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
