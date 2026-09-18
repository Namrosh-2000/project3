<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
new yii\web\Application($config);

$s = new app\models\PropertySearch();
$dp = $s->search([]);
echo "Total Count: " . $dp->getTotalCount() . PHP_EOL;
echo "Models Count: " . count($dp->getModels()) . PHP_EOL;

$dpFiltered = $s->search(['PropertySearch' => ['listing_type' => 'rent', 'keyword' => 'Sinza']]);
echo "Filtered Rent Count: " . $dpFiltered->getTotalCount() . PHP_EOL;
echo "All OK!" . PHP_EOL;
