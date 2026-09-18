<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

// Language switcher: a guest's/user's choice is remembered in a cookie
// (see SiteController::actionSetLanguage) and applied before the app boots.
if (isset($_COOKIE['machomtaa_lang']) && in_array($_COOKIE['machomtaa_lang'], ['sw', 'en'], true)) {
    $config['language'] = $_COOKIE['machomtaa_lang'];
}

(new yii\web\Application($config))->run();
