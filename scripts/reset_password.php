<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/console.php';
new yii\console\Application($config);

$username = $argv[1] ?? 'Emmanuel1';
$newPassword = $argv[2] ?? 'password123';

$user = \app\models\User::findByUsername($username);
if ($user === null) {
    echo "User '$username' not found.\n";
    exit(1);
}

$user->setPassword($newPassword);
$user->save(false);
echo "Password for '$username' set to '$newPassword'\n";