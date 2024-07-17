<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=orders',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

     // Performance Tuning
     'enableSchemaCache' => true,
     'schemaCacheDuration' => 3600,
     'schemaCache' => 'cache'

];
