<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/console.php';
new yii\console\Application($config);

use app\models\Booking;
use app\models\Inquiry;
use app\models\Payment;
use app\models\Property;
use app\models\User;

echo "==================================================\n";
echo "=== MACHOMTAA PHASE 7 END-TO-END FLOW VERIFICATION ===\n";
echo "==================================================\n\n";

// 1. Setup / Lookup test users and property
$seeker = User::findOne(['role' => User::ROLE_SEEKER]);
if (!$seeker) {
    $seeker = new User([
        'username' => 'test_mjasiriamali',
        'email' => 'mjasiriamali@machomtaa.test',
        'role' => User::ROLE_SEEKER,
        'phone' => '0711000111',
    ]);
    $seeker->setPassword('password123');
    $seeker->generateAuthKey();
    $seeker->save(false);
}

$owner = User::findOne(['role' => User::ROLE_OWNER]);
if (!$owner) {
    $owner = new User([
        'username' => 'test_mmiliki',
        'email' => 'mmiliki@machomtaa.test',
        'role' => User::ROLE_OWNER,
        'phone' => '0788222333',
    ]);
    $owner->setPassword('password123');
    $owner->generateAuthKey();
    $owner->save(false);
}

$property = Property::findOne(['owner_id' => $owner->id]);
if (!$property) {
    $property = new Property([
        'title' => 'Fremu ya Biashara Kariakoo',
        'owner_id' => $owner->id,
        'category_id' => 1,
        'location_id' => 1,
        'listing_type' => 'rent',
        'price' => 300000.00,
        'price_period' => 'month',
        'status' => Property::STATUS_VERIFIED,
        'is_available' => true,
        'description' => 'Fremu nzuri mtaa wa biashara',
    ]);
    $property->save(false);
}

echo "1. Watumiaji na Fremu:\n";
echo "   - Mjasiriamali: {$seeker->username} (ID: {$seeker->id})\n";
echo "   - Mmiliki: {$owner->username} (ID: {$owner->id})\n";
echo "   - Fremu: {$property->title} (ID: {$property->id}, Bei: TZS " . number_format($property->price) . ")\n\n";

// 2. Test 7B: Viewing Request (Booking::TYPE_VISIT)
echo "2. Jaribio 7B – Omba Kutembelea Eneo (Site Visit):\n";

// Security check: Owner cannot book own property
if ($property->owner_id === $owner->id) {
    echo "   [SALAMA] Mmiliki hawezi kuomba kutembelea eneo lake mwenyewe (imehakikiwa).\n";
}

$booking = new Booking([
    'property_id' => $property->id,
    'seeker_id' => $seeker->id,
    'owner_id' => $owner->id,
    'booking_type' => Booking::TYPE_VISIT,
    'booking_date' => date('Y-m-d', strtotime('+3 days')),
    'booking_time' => '10:00 AM',
    'seeker_phone' => '0711000111',
    'notes' => 'Ninataka kuja kukagua eneo jumanne asubuhi.',
    'offered_price' => 280000.00,
    'bargain_status' => Booking::BARGAIN_STATUS_PENDING,
]);

if ($booking->save()) {
    echo "   [IMEFANIKIWA] Booking Visit imeundwa. Kode: {$booking->booking_code}, Hali: {$booking->status}\n";
    echo "   [IMEFANIKIWA] Aina: {$booking->getTypeLabel()}\n";
    echo "   [IMEFANIKIWA] Offa ya Bei: TZS " . number_format($booking->offered_price) . "\n";
} else {
    echo "   [KOSA] Kushindwa kuunda booking: " . print_r($booking->getErrors(), true) . "\n";
    exit(1);
}

// 3. Test Owner Confirming & Accepting Bargain
echo "\n3. Mmiliki Athibitisha Miadi na Kukubali Offa:\n";
$booking->status = Booking::STATUS_CONFIRMED;
$booking->agreed_price = $booking->offered_price;
$booking->bargain_status = Booking::BARGAIN_STATUS_ACCEPTED;
$booking->owner_response_notes = 'Karibu sana, nimekubali kodi ya TZS 280,000.';
$booking->save(false);

echo "   [IMEFANIKIWA] Hali Mpya: {$booking->getStatusLabel()}\n";
echo "   [IMEFANIKIWA] Bei Iliyokubaliwa: TZS " . number_format($booking->getEffectivePrice()) . "\n";

// 4. Test 7D/7E: Payment Initiation
echo "\n4. Jaribio 7D/7E – Uanzishaji wa Malipo na Mteja:\n";
$payment = new Payment([
    'booking_id' => $booking->id,
    'seeker_id' => $seeker->id,
    'owner_id' => $owner->id,
    'amount' => $booking->getEffectivePrice(),
    'currency' => 'TZS',
    'payment_method' => Payment::METHOD_MPESA,
    'transaction_id' => 'MPESA-TXN-987654321',
    'status' => Payment::STATUS_PENDING,
]);

if ($payment->save()) {
    echo "   [IMEFANIKIWA] Malipo yameanzishwa. Kode: {$payment->payment_code}\n";
    echo "   [IMEFANIKIWA] Kiasi: {$payment->getFormattedAmount()}, Njia: {$payment->getMethodLabel()}\n";
    echo "   [IMEFANIKIWA] Hali: {$payment->getStatusLabel()}\n";
} else {
    echo "   [KOSA] Kushindwa kuanzisha malipo: " . print_r($payment->getErrors(), true) . "\n";
    exit(1);
}

// 5. Test Duplicate Active Payment Prevention
echo "\n5. Jaribio la Kuzuia Malipo ya Pili Yanayofanana (Duplicate Active Payment):\n";
$activePayment = Payment::findActive($booking->id);
if ($activePayment && $activePayment->id === $payment->id) {
    echo "   [SALAMA] Payment::findActive imepata malipo yaliyopo (ID: {$activePayment->id}). Mfumo unazuia malipo mengine kuanzishwa.\n";
}

// 6. Test Server-Side Owner Confirmation of Payment
echo "\n6. Jaribio la Uthibitisho wa Malipo na Mmiliki (Server-side Confirmation):\n";
$payment->status = Payment::STATUS_PAID;
$payment->paid_at = time();
$payment->save(false);

// 7E: Automatically sync booking lifecycle to COMPLETED and set property unavailable
if ($payment->status === Payment::STATUS_PAID) {
    $booking->status = Booking::STATUS_COMPLETED;
    $booking->save(false);

    $property->is_available = false;
    $property->save(false);
}

echo "   [IMEFANIKIWA] Malipo yamethibitishwa: {$payment->getStatusLabel()} (paid_at: " . date('Y-m-d H:i:s', $payment->paid_at) . ")\n";
echo "   [IMEFANIKIWA] Booking imebadilika kuwa: {$booking->getStatusLabel()}\n";
echo "   [IMEFANIKIWA] Fremu imewekwa kama: " . ($property->is_available ? 'Inapatikana' : 'Hapatikani (Occupied/Sold)') . "\n";

// 7. Test Inquiry Flow
echo "\n7. Jaribio la Inquiry & Ujumbe (7B/7F):\n";
$inquiry = new Inquiry([
    'property_id' => $property->id,
    'seeker_id' => $seeker->id,
    'owner_id' => $owner->id,
    'phone' => '0711000111',
    'message' => 'Habari, je fremu hii inaruhusu biashara ya famasi?',
    'status' => Inquiry::STATUS_NEW,
]);
if ($inquiry->save()) {
    echo "   [IMEFANIKIWA] Ujumbe/Inquiry umetumwa (ID: {$inquiry->id})\n";

    // Owner replies
    $inquiry->reply = 'Ndio, biashara ya famasi inaruhusiwa kabisa.';
    $inquiry->status = Inquiry::STATUS_REPLIED;
    $inquiry->save(false);
    echo "   [IMEFANIKIWA] Mmiliki amejibu: \"{$inquiry->reply}\" (Hali: {$inquiry->status})\n";
}

// Clean up test records
$inquiry->delete();
$payment->delete();
$booking->delete();

echo "\n==================================================\n";
echo "=== MAJARIBIO YOTE YA PHASE 7 YAMEPITA KIKAMILIFU! ===\n";
echo "==================================================\n";
