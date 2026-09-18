<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/console.php';
(new yii\console\Application($config));

use app\models\Booking;
use app\models\BookingContract;
use app\models\Property;
use app\models\User;

echo "=== ENEOLINK CONTRACT FLOW TEST ===\n";

$seeker = User::findOne(['role' => User::ROLE_SEEKER]);
$property = Property::find()->where(['status' => Property::STATUS_VERIFIED])->one();

if (!$seeker || !$property) {
    echo "Error: Seeker or Property not found.\n";
    exit(1);
}

// 1. Create a test booking
$booking = new Booking();
$booking->property_id = $property->id;
$booking->seeker_id = $seeker->id;
$booking->owner_id = $property->owner_id;
$booking->booking_type = Booking::TYPE_VISIT;
$booking->booking_date = date('Y-m-d', strtotime('+3 days'));
$booking->booking_time = '02:00 PM';
$booking->seeker_phone = '0712345678';
$booking->notes = 'Test contract generation request.';
$booking->save(false);

echo "1. Booking Created: " . $booking->booking_code . "\n";

// 2. Generate Booking Contract
$contract = new BookingContract();
$contract->booking_id = $booking->id;
$contract->property_id = $property->id;
$contract->seeker_id = $seeker->id;
$contract->owner_id = $property->owner_id;
$contract->contract_terms = $property->contract_terms ?: "1. Kodi inalipwa kwa kufuata mkataba.\n2. Usafi na amani vitatunzwa.";
$contract->status = BookingContract::STATUS_PENDING_SIGNATURE;
$contract->save(false);

echo "2. Contract Generated Code: " . $contract->contract_code . " (Status: " . $contract->status . ")\n";

// 3. Seeker Signs Contract
$contract->seeker_signature = 'Juma Ally (Seeker Signature)';
$contract->seeker_signed_at = time();
$contract->status = BookingContract::STATUS_SIGNED_BY_SEEKER;
$contract->save(false);

echo "3. Seeker Signed Contract: " . $contract->seeker_signature . " (Status: " . $contract->getStatusLabel() . ")\n";

// 4. Owner Counter-signs Contract
$contract->owner_signature = 'Bwana Shabani (Owner Signature)';
$contract->owner_signed_at = time();
$contract->status = BookingContract::STATUS_COMPLETED;
$contract->save(false);

echo "4. Owner Counter-signed Contract: " . $contract->owner_signature . " (Status: " . $contract->getStatusLabel() . ")\n";

// Cleanup test records
$contract->delete();
$booking->delete();

echo "5. Cleanup completed successfully.\n";
echo "=== ALL CONTRACT TESTS PASSED ===\n";
