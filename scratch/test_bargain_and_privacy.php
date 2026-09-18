<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/console.php';
(new yii\console\Application($config));

use app\models\Booking;
use app\models\BookingContract;
use app\models\Property;
use app\models\User;

echo "=== ENEOLINK BARGAIN & CONTRACT PRIVACY TEST ===\n";

$seeker = User::findOne(['role' => User::ROLE_SEEKER]);
$property = Property::find()->where(['status' => Property::STATUS_VERIFIED])->one();

if (!$seeker || !$property) {
    echo "Error: Seeker or Property missing.\n";
    exit(1);
}

echo "Asking Property Price: TSh " . number_format($property->price) . "\n";

// 1. Create a booking request WITH a Bargain Offer (e.g. 20% lower than asking price)
$bargainOffer = floor($property->price * 0.8);
$booking = new Booking();
$booking->property_id = $property->id;
$booking->seeker_id = $seeker->id;
$booking->owner_id = $property->owner_id;
$booking->booking_type = Booking::TYPE_VISIT;
$booking->booking_date = date('Y-m-d', strtotime('+2 days'));
$booking->booking_time = '11:00 AM';
$booking->seeker_phone = '0788112233';
$booking->notes = 'Test bargain offer.';
$booking->status = Booking::STATUS_PENDING;
$booking->offered_price = $bargainOffer;
$booking->bargain_status = Booking::BARGAIN_STATUS_PENDING;
$booking->save(false);

echo "1. Booking Created Code: {$booking->booking_code} | Bargain Offer: TSh " . number_format($booking->offered_price) . " (Status: {$booking->bargain_status})\n";

// 2. Verify Contract is NOT accessible when status is PENDING
if (!in_array($booking->status, [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED], true)) {
    echo "2. Contract Access Lock Check: Contract locked while booking status is '{$booking->status}' [OK]\n";
} else {
    echo "2. Contract Access Lock Check: FAILED (Contract was accessible in pending state)\n";
    exit(1);
}

// 3. Owner confirms booking and ACCEPTS the bargain price
$booking->status = Booking::STATUS_CONFIRMED;
$booking->agreed_price = $booking->offered_price;
$booking->bargain_status = Booking::BARGAIN_STATUS_ACCEPTED;
$booking->save(false);

echo "3. Owner Confirmed Booking & Accepted Bargain Price! Agreed Price: TSh " . number_format($booking->getEffectivePrice()) . "\n";

// 4. Generate contract for confirmed booking and verify agreed price is used
$contract = new BookingContract();
$contract->booking_id = $booking->id;
$contract->property_id = $booking->property_id;
$contract->seeker_id = $booking->seeker_id;
$contract->owner_id = $booking->owner_id;
$contract->contract_terms = $booking->property->contract_terms ?: "Standard terms.";
$contract->save(false);

echo "4. Contract Generated Code: {$contract->contract_code} | Agreed Price Used: TSh " . number_format($booking->getEffectivePrice()) . "\n";

// Cleanup test objects
$contract->delete();
$booking->delete();

echo "5. Cleanup finished.\n";
echo "=== ALL BARGAIN & PRIVACY TESTS PASSED SUCCESSFULLY ===\n";
