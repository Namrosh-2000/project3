<?php

$sock = file_exists('/tmp/mysqldb/mysql.sock') ? ';unix_socket=/tmp/mysqldb/mysql.sock' : '';

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost' . $sock . ';dbname=eneo_link',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
