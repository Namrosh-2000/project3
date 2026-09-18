<?php

$db = require __DIR__ . '/db.php';
$sock = file_exists('/tmp/mysqldb/mysql.sock') ? ';unix_socket=/tmp/mysqldb/mysql.sock' : '';
$db['dsn'] = 'mysql:host=localhost' . $sock . ';dbname=yii2basic_test';

return $db;
