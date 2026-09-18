<?php

namespace tests\unit\models;

use app\models\Booking;
use app\models\Payment;
use Codeception\Test\Unit;

class PaymentTest extends Unit
{
    public function testDefaultValues()
    {
        $payment = new Payment();
        $payment->validate();

        $this->assertEquals('TZS', $payment->currency);
        $this->assertEquals(Payment::STATUS_PENDING, $payment->status);
    }

    public function testRequiredFields()
    {
        $payment = new Payment();
        $this->assertFalse($payment->validate());
        $this->assertArrayHasKey('booking_id', $payment->errors);
        $this->assertArrayHasKey('seeker_id', $payment->errors);
        $this->assertArrayHasKey('owner_id', $payment->errors);
        $this->assertArrayHasKey('amount', $payment->errors);
    }

    public function testAmountValidation()
    {
        $payment = new Payment();
        $payment->amount = -500;
        $payment->validate(['amount']);
        $this->assertArrayHasKey('amount', $payment->errors);

        $payment->amount = 0;
        $payment->validate(['amount']);
        $this->assertArrayHasKey('amount', $payment->errors);

        $payment->amount = 150000;
        $payment->validate(['amount']);
        $this->assertArrayNotHasKey('amount', $payment->errors);
    }

    public function testStatusRange()
    {
        $payment = new Payment();
        $payment->status = 'invalid_status';
        $payment->validate(['status']);
        $this->assertArrayHasKey('status', $payment->errors);

        foreach ([Payment::STATUS_PENDING, Payment::STATUS_PAID, Payment::STATUS_FAILED, Payment::STATUS_CANCELLED] as $valid) {
            $payment->status = $valid;
            $payment->validate(['status']);
            $this->assertArrayNotHasKey('status', $payment->errors);
        }
    }

    public function testMethodRange()
    {
        $payment = new Payment();
        $payment->payment_method = 'crypto_currency';
        $payment->validate(['payment_method']);
        $this->assertArrayHasKey('payment_method', $payment->errors);

        foreach ([
            Payment::METHOD_MPESA,
            Payment::METHOD_TIGOPESA,
            Payment::METHOD_AIRTEL_MONEY,
            Payment::METHOD_BANK,
            Payment::METHOD_CASH,
            Payment::METHOD_OTHER,
        ] as $valid) {
            $payment->payment_method = $valid;
            $payment->validate(['payment_method']);
            $this->assertArrayNotHasKey('payment_method', $payment->errors);
        }
    }

    public function testPresentationHelpers()
    {
        $payment = new Payment([
            'amount' => 250000.00,
            'currency' => 'TZS',
            'status' => Payment::STATUS_PENDING,
            'payment_method' => Payment::METHOD_MPESA,
        ]);

        $this->assertEquals('Inasubiri Malipo', $payment->getStatusLabel());
        $this->assertEquals('bg-warning text-dark', $payment->getStatusBadgeClass());
        $this->assertEquals('M-Pesa', $payment->getMethodLabel());
        $this->assertEquals('TZS 250,000', $payment->getFormattedAmount());

        $payment->status = Payment::STATUS_PAID;
        $this->assertEquals('Imelipwa', $payment->getStatusLabel());
        $this->assertEquals('bg-success text-white', $payment->getStatusBadgeClass());

        $payment->status = Payment::STATUS_FAILED;
        $this->assertEquals('Imeshindwa', $payment->getStatusLabel());
        $this->assertEquals('bg-danger text-white', $payment->getStatusBadgeClass());

        $payment->status = Payment::STATUS_CANCELLED;
        $this->assertEquals('Imefutwa', $payment->getStatusLabel());
        $this->assertEquals('bg-secondary text-white', $payment->getStatusBadgeClass());
    }

    public function testGeneratePaymentCode()
    {
        $code = Payment::generatePaymentCode();
        $this->assertStringStartsWith('PAY-', $code);
        $this->assertEquals(10, strlen($code)); // 'PAY-' + 6 uppercase chars
    }
}

