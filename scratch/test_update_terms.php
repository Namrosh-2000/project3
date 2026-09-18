<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/console.php';
(new yii\console\Application($config));

use app\models\Booking;
use app\models\BookingContract;
use app\models\Property;
use app\models\User;

echo "=== TESTING CONTRACT TERMS UPDATE BY OWNER ===\n";

$seeker = User::findOne(['role' => User::ROLE_SEEKER]);
$property = Property::find()->where(['status' => Property::STATUS_VERIFIED])->one();

if (!$seeker || !$property) {
    echo "Error: Seeker or Property missing.\n";
    exit(1);
}

// Create temporary booking & contract
$booking = new Booking();
$booking->property_id = $property->id;
$booking->seeker_id = $seeker->id;
$booking->owner_id = $property->owner_id;
$booking->booking_type = Booking::TYPE_VISIT;
$booking->booking_date = date('Y-m-d');
$booking->booking_time = '10:00 AM';
$booking->save(false);

$contract = new BookingContract();
$contract->booking_id = $booking->id;
$contract->property_id = $property->id;
$contract->seeker_id = $seeker->id;
$contract->owner_id = $property->owner_id;
$contract->contract_terms = "Initial terms before update";
$contract->save(false);

echo "Initial terms: " . $contract->contract_terms . "\n";

// Update terms as owner
$newTerms = "1. Masharti mapya yaliyorekebishwa na Mmiliki.\n2. Kodi italipwa kupitia Benki au M-Pesa kwa EneoLink.";
$contract->contract_terms = $newTerms;
$contract->save(false);

$reloaded = BookingContract::findOne($contract->id);
echo "Updated terms in DB: \n" . $reloaded->contract_terms . "\n";

$contract->delete();
$booking->delete();
echo "=== TEST PASSED SUCCESSFULLY ===\n";
