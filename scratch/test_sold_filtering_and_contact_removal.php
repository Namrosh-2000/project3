<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/console.php';
(new yii\console\Application($config));

use app\models\Booking;
use app\models\BookingContract;
use app\models\Property;
use app\models\PropertySearch;
use app\models\User;

echo "=== ENEOLINK SOLD ITEM FILTERING TEST ===\n";

$property = Property::find()->where(['status' => Property::STATUS_VERIFIED])->one();
if (!$property) {
    echo "Error: No verified property found.\n";
    exit(1);
}

// 1. Initial State: Property is available
$property->is_available = true;
$property->save(false);

$searchModel = new PropertySearch();
$providerBefore = $searchModel->search([]);
$countBefore = $providerBefore->getTotalCount();
echo "1. Available Properties count before marking sold: " . $countBefore . "\n";

// 2. Mark Property as Sold / Occupied (is_available = false)
$property->is_available = false;
$property->save(false);

$providerAfter = $searchModel->search([]);
$countAfter = $providerAfter->getTotalCount();
echo "2. Available Properties count after marking sold: " . $countAfter . "\n";

if ($countAfter < $countBefore) {
    echo "3. Sold/Occupied Property Filtering Check: PASSED [Sold item correctly hidden from available items]\n";
} else {
    echo "3. Sold/Occupied Property Filtering Check: FAILED\n";
    exit(1);
}

// Restore test property availability
$property->is_available = true;
$property->save(false);

echo "=== TEST COMPLETED SUCCESSFULLY ===\n";
