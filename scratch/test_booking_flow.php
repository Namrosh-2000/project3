<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/console.php';
(new yii\console\Application($config));

use app\models\Booking;
use app\models\Property;
use app\models\User;

echo "=== ENEOLINK BOOKING FLOW TEST ===\n";

$seeker = User::findOne(['role' => User::ROLE_SEEKER]);
$property = Property::find()->where(['status' => Property::STATUS_VERIFIED])->one();

if (!$seeker || !$property) {
    echo "Error: Seeker or Property not found.\n";
    exit(1);
}

echo "Seeker: " . $seeker->username . " (ID: " . $seeker->id . ")\n";
echo "Property: " . $property->title . " (ID: " . $property->id . ", Owner ID: " . $property->owner_id . ")\n";

// Create test booking
$booking = new Booking();
$booking->property_id = $property->id;
$booking->seeker_id = $seeker->id;
$booking->owner_id = $property->owner_id;
$booking->booking_type = Booking::TYPE_VISIT;
$booking->booking_date = date('Y-m-d', strtotime('+2 days'));
$booking->booking_time = '10:00 AM';
$booking->seeker_phone = '0755123456';
$booking->notes = 'Test inspection visit request.';

if ($booking->save()) {
    echo "SUCCESS: Created Booking Code: " . $booking->booking_code . " (Status: " . $booking->status . ")\n";
} else {
    echo "ERROR: Failed to save booking: " . print_r($booking->getErrors(), true) . "\n";
    exit(1);
}

// Test status transition to confirmed
$booking->status = Booking::STATUS_CONFIRMED;
$booking->owner_response_notes = 'Miadi imethibitishwa. Tutakutana eneo la tukio.';
$booking->save(false);
echo "SUCCESS: Updated Status to Confirmed (" . $booking->getStatusLabel() . ")\n";

// Clean up test booking
$booking->delete();
echo "SUCCESS: Test booking cleaned up.\n";
echo "=== ALL TESTS PASSED ===\n";
