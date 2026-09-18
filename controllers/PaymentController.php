<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\models\Booking;
use app\models\Payment;

/**
 * PaymentController – Phase 7D / 7E
 *
 * Security rules:
 *  - Only the booking seeker (entrepreneur) may initiate a payment.
 *  - Owner may only view payment for their bookings.
 *  - No POST action trusts a browser-supplied "paid" status.
 *  - Server validates all state transitions.
 */
class PaymentController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'initiate'  => ['post'],
                    'confirm'   => ['post'],
                    'fail'      => ['post'],
                    'cancel'    => ['post'],
                ],
            ],
        ];
    }

    // ----------------------------------------------------------------
    // View – seeker or owner can view a payment detail
    // ----------------------------------------------------------------

    /**
     * Show payment detail for booking.
     */
    public function actionView($booking_id)
    {
        $booking = $this->findAccessibleBooking((int)$booking_id);

        $payment = Payment::findActive((int)$booking_id);

        return $this->render('view', [
            'booking' => $booking,
            'payment' => $payment,
        ]);
    }

    // ----------------------------------------------------------------
    // Seeker actions
    // ----------------------------------------------------------------

    /**
     * Seeker initiates a new payment for a confirmed booking.
     * Guards: booking must be confirmed, no active payment exists,
     *         caller must be the seeker.
     */
    public function actionInitiate($booking_id)
    {
        $booking = $this->findSeekerBooking((int)$booking_id);

        // Only confirmed (or rescheduled-then-confirmed) bookings can be paid
        if (!in_array($booking->status, [Booking::STATUS_CONFIRMED], true)) {
            Yii::$app->session->setFlash('error', 'Malipo yanaweza kufanywa tu kwa booking zilizothibitishwa na Mmiliki.');
            return $this->redirectBookingIndex();
        }

        // Prevent duplicate active payment
        $existing = Payment::findActive((int)$booking_id);
        if ($existing) {
            Yii::$app->session->setFlash('warning', 'Tayari kuna malipo yanayoendelea kwa booking hii (Kode: ' . $existing->payment_code . ').');
            return $this->redirect(['view', 'booking_id' => $booking->id]);
        }

        // Read & validate posted fields
        $method = Yii::$app->request->post('payment_method');
        $txnId  = trim((string)Yii::$app->request->post('transaction_id', ''));
        $notes  = trim((string)Yii::$app->request->post('notes', ''));

        $allowedMethods = [
            Payment::METHOD_MPESA,
            Payment::METHOD_TIGOPESA,
            Payment::METHOD_AIRTEL_MONEY,
            Payment::METHOD_BANK,
            Payment::METHOD_CASH,
            Payment::METHOD_OTHER,
        ];
        if (!in_array($method, $allowedMethods, true)) {
            Yii::$app->session->setFlash('error', 'Tafadhali chagua njia sahihi ya malipo.');
            return $this->redirect(['view', 'booking_id' => $booking->id]);
        }

        // For mobile-money / bank, require a transaction ID
        $requiresTxn = in_array($method, [
            Payment::METHOD_MPESA,
            Payment::METHOD_TIGOPESA,
            Payment::METHOD_AIRTEL_MONEY,
            Payment::METHOD_BANK,
        ], true);

        if ($requiresTxn && empty($txnId)) {
            Yii::$app->session->setFlash('error', 'Tafadhali weka Nambari / Kumbukumbu ya Malipo (Transaction ID / Reference).');
            return $this->redirect(['view', 'booking_id' => $booking->id]);
        }

        $payment = new Payment();
        $payment->booking_id     = $booking->id;
        $payment->seeker_id      = $booking->seeker_id;
        $payment->owner_id       = $booking->owner_id;
        $payment->amount         = $booking->getEffectivePrice();
        $payment->currency       = 'TZS';
        $payment->payment_method = $method;
        $payment->transaction_id = $txnId ?: null;
        $payment->status         = Payment::STATUS_PENDING;
        // Store notes in failure_reason field temporarily (reused for general notes at pending stage)
        if (!empty($notes)) {
            $payment->failure_reason = $notes;
        }

        if ($payment->save()) {
            Yii::$app->session->setFlash('success',
                'Ombi la malipo (Kode: ' . $payment->payment_code . ') limetumwa. Mmiliki atakithibitisha mapema iwezekanavyo.');
        } else {
            Yii::$app->session->setFlash('error', 'Imeshindikana kuwasilisha ombi la malipo. Jaribu tena.');
        }

        return $this->redirect(['view', 'booking_id' => $booking->id]);
    }

    /**
     * Seeker cancels a pending payment.
     */
    public function actionCancel($id)
    {
        $payment = $this->findSeekerPayment((int)$id);

        if ($payment->status !== Payment::STATUS_PENDING) {
            Yii::$app->session->setFlash('error', 'Malipo ambayo hayako katika hali ya "Inasubiri" hayawezi kufutwa.');
            return $this->redirect(['view', 'booking_id' => $payment->booking_id]);
        }

        $reason = trim((string)Yii::$app->request->post('reason', ''));
        $payment->status         = Payment::STATUS_CANCELLED;
        $payment->failure_reason = $reason ?: 'Imefutwa na Mteja';

        if ($payment->save(false)) {
            Yii::$app->session->setFlash('warning', 'Ombi la malipo (Kode: ' . $payment->payment_code . ') limefutwa.');
        }

        return $this->redirect(['view', 'booking_id' => $payment->booking_id]);
    }

    // ----------------------------------------------------------------
    // Owner actions
    // ----------------------------------------------------------------

    /**
     * Owner confirms/verifies a pending payment as received.
     * This is the ONLY way status moves to "paid" – never from browser input.
     */
    public function actionConfirm($id)
    {
        $payment = $this->findOwnerPayment((int)$id);

        if ($payment->status !== Payment::STATUS_PENDING) {
            Yii::$app->session->setFlash('error', 'Malipo haya hayako katika hali ya kuthibitishwa.');
            return $this->redirect(['view', 'booking_id' => $payment->booking_id]);
        }

        $txnId = trim((string)Yii::$app->request->post('transaction_id', $payment->transaction_id ?? ''));

        $payment->status   = Payment::STATUS_PAID;
        $payment->paid_at  = time();
        if (!empty($txnId)) {
            $payment->transaction_id = $txnId;
        }
        // Clear any pending-stage notes from failure_reason
        if ($payment->failure_reason && !in_array($payment->status, [Payment::STATUS_FAILED, Payment::STATUS_CANCELLED])) {
            $payment->failure_reason = null;
        }

        if ($payment->save(false)) {
            // 7E: Sync booking → completed once payment is confirmed
            $booking = $payment->booking;
            if ($booking && $booking->status === Booking::STATUS_CONFIRMED) {
                $booking->status = Booking::STATUS_COMPLETED;
                $booking->save(false);

                // Mark property as no longer available
                $property = $booking->property;
                if ($property) {
                    $property->is_available = false;
                    $property->save(false);
                }
            }

            Yii::$app->session->setFlash('success',
                'Malipo (Kode: ' . $payment->payment_code . ') yamethibitishwa! Booking imewekwa kama Imekamilika.');
        } else {
            Yii::$app->session->setFlash('error', 'Imeshindikana kuthibitisha malipo.');
        }

        return $this->redirect(['/booking/owner']);
    }

    /**
     * Owner marks payment as failed / rejected.
     */
    public function actionFail($id)
    {
        $payment = $this->findOwnerPayment((int)$id);

        if ($payment->status !== Payment::STATUS_PENDING) {
            Yii::$app->session->setFlash('error', 'Malipo haya hayawezi kuwekwa kama "Imeshindwa" katika hali yake ya sasa.');
            return $this->redirect(['view', 'booking_id' => $payment->booking_id]);
        }

        $reason = trim((string)Yii::$app->request->post('failure_reason', ''));
        if (empty($reason)) {
            Yii::$app->session->setFlash('error', 'Tafadhali toa sababu ya kushindwa kwa malipo.');
            return $this->redirect(['view', 'booking_id' => $payment->booking_id]);
        }

        $payment->status         = Payment::STATUS_FAILED;
        $payment->failure_reason = $reason;

        if ($payment->save(false)) {
            Yii::$app->session->setFlash('warning',
                'Malipo (Kode: ' . $payment->payment_code . ') yamewekwa kama Imeshindwa. Mteja anaweza kuwasilisha tena.');
        }

        return $this->redirect(['/booking/owner']);
    }

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------

    /**
     * Find booking accessible by the current user (seeker OR owner).
     */
    private function findAccessibleBooking(int $bookingId): Booking
    {
        $myId    = Yii::$app->user->id;
        $booking = Booking::find()
            ->where(['id' => $bookingId])
            ->andWhere(['or', ['seeker_id' => $myId], ['owner_id' => $myId]])
            ->with(['property.location', 'seeker', 'owner'])
            ->one();

        if (!$booking) {
            throw new NotFoundHttpException('Booking haikupatikana au huna ruhusa.');
        }
        return $booking;
    }

    /**
     * Find booking where current user is the seeker.
     */
    private function findSeekerBooking(int $bookingId): Booking
    {
        $booking = Booking::findOne(['id' => $bookingId, 'seeker_id' => Yii::$app->user->id]);
        if (!$booking) {
            throw new NotFoundHttpException('Booking haikupatikana au huna ruhusa.');
        }
        return $booking;
    }

    /**
     * Find a payment that belongs to the current seeker.
     */
    private function findSeekerPayment(int $paymentId): Payment
    {
        $payment = Payment::findOne(['id' => $paymentId, 'seeker_id' => Yii::$app->user->id]);
        if (!$payment) {
            throw new NotFoundHttpException('Malipo hayakupatikana au huna ruhusa.');
        }
        return $payment;
    }

    /**
     * Find a payment that belongs to the current owner.
     */
    private function findOwnerPayment(int $paymentId): Payment
    {
        $payment = Payment::findOne(['id' => $paymentId, 'owner_id' => Yii::$app->user->id]);
        if (!$payment) {
            throw new NotFoundHttpException('Malipo hayakupatikana au huna ruhusa.');
        }
        return $payment;
    }

    private function redirectBookingIndex()
    {
        $identity = Yii::$app->user->identity;
        if ($identity && in_array($identity->role, [\app\models\User::ROLE_OWNER, \app\models\User::ROLE_AGENT], true)) {
            return $this->redirect(['/booking/owner']);
        }
        return $this->redirect(['/booking/index']);
    }
}

