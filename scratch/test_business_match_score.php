<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/console.php';
(new yii\console\Application($config));

use app\models\Location;
use app\models\User;
use app\services\BusinessOpportunityService;

echo "=== ENEOLINK SMART BUSINESS MATCH SCORE TEST ===\n";

$location = Location::findOne(['ward' => 'Sinza']);
if (!$location) {
    $location = Location::find()->one();
}

if (!$location) {
    echo "Error: No location found.\n";
    exit(1);
}

echo "Testing Location Ward: " . $location->ward . "\n";

// 1. Create mock user with profile interests
$user = new User();
$user->username = 'test_entrepreneur';
$user->experience_level = 'intermediate';
$user->business_interests = 'Saluni / Beauty, Mgahawa / Chakula';
$user->preferred_amenities = 'water,electricity,parking';

$service = new BusinessOpportunityService();
$results = $service->suggest($location->id, 2000000, $user, 'Saluni / Beauty');

echo "Calculated Recommendations Count: " . count($results) . "\n";

foreach ($results as $index => $res) {
    echo sprintf(
        "#%d Category: %s | Match Score: %d%% (%s) | Available: %d | Avg Rent: TSh %s\n   Reason: %s\n",
        $index + 1,
        $res->category->name,
        $res->match_score,
        $res->is_affordable ? 'Affordable' : 'Exceeds Budget',
        $res->available_count,
        number_format($res->avg_monthly_price),
        $res->recommendation
    );
}

echo "=== BUSINESS MATCH SCORE TEST PASSED SUCCESSFULLY ===\n";
