<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Booking;
use app\models\BookingContract;
use app\models\Property;
use app\models\User;

class BookingController extends Controller
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
                    'create' => ['post'],
                    'confirm' => ['post'],
                    'reschedule' => ['post'],
                    'accept-reschedule' => ['post'],
                    'reject' => ['post'],
                    'cancel' => ['post'],
                    'complete' => ['post'],
                    'sign-contract' => ['post'],
                    'owner-sign-contract' => ['post'],
                    'update-contract-terms' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Seeker's bookings page.
     */
    public function actionIndex($status = null)
    {
        $query = Booking::find()
            ->where(['seeker_id' => Yii::$app->user->id]);

        if ($status && in_array($status, [
            Booking::STATUS_PENDING,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_RESCHEDULED,
            Booking::STATUS_REJECTED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_CANCELLED,
        ], true)) {
            $query->andWhere(['status' => $status]);
        }

        $bookings = $query->orderBy(['created_at' => SORT_DESC])
            ->with(['property.coverImage', 'property.location', 'owner'])
            ->all();

        return $this->render('index', [
            'bookings' => $bookings,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Owner/Agent's received bookings page.
     */
    public function actionOwner($status = null)
    {
        $query = Booking::find()
            ->where(['owner_id' => Yii::$app->user->id]);

        if ($status && in_array($status, [
            Booking::STATUS_PENDING,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_RESCHEDULED,
            Booking::STATUS_REJECTED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_CANCELLED,
        ], true)) {
            $query->andWhere(['status' => $status]);
        }

        $bookings = $query->orderBy(['created_at' => SORT_DESC])
            ->with(['property.coverImage', 'property.location', 'seeker'])
            ->all();

        return $this->render('owner', [
            'bookings' => $bookings,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Create a new booking request.
     */
    public function actionCreate($property_id)
    {
        $property = Property::findOne($property_id);
        if (!$property) {
            throw new NotFoundHttpException('Nyumba au eneo halikupatikana.');
        }

        if ($property->owner_id === Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'Huwezi kuweka booking kwenye eneo lako mwenyewe.');
            return $this->redirect(['/property/view', 'id' => $property->id]);
        }

        if (!$property->is_available) {
            Yii::$app->session->setFlash('error', 'Eneo hili kwa sasa halipatikani tena kwa ajili ya booking au ukaguzi.');
            return $this->redirect(['/property/view', 'id' => $property->id]);
        }

        $model = new Booking();
        $model->property_id = $property->id;
        $model->seeker_id = Yii::$app->user->id;
        $model->owner_id = $property->owner_id;

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->booking_date) && strtotime($model->booking_date) < strtotime('today')) {
                Yii::$app->session->setFlash('error', 'Tarehe ya miadi haiwezi kuwa ya siku zilizopita.');
                return $this->redirect(['/property/view', 'id' => $property->id]);
            }

            if (!empty($model->offered_price) && (float)$model->offered_price > 0) {
                $model->bargain_status = Booking::BARGAIN_STATUS_PENDING;
            } else {
                $model->offered_price = null;
                $model->bargain_status = Booking::BARGAIN_STATUS_NONE;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Ombi lako la booking na miadi limetumwa kikamilifu!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Tafadhali jaza taarifa zote zinazohitajika za booking.');
            }
        }

        return $this->redirect(['/property/view', 'id' => $property->id]);
    }

    /**
     * Owner confirms booking.
     */
    public function actionConfirm($id)
    {
        $booking = $this->findOwnerBooking($id);

        if (!in_array($booking->status, [Booking::STATUS_PENDING, Booking::STATUS_RESCHEDULED], true)) {
            Yii::$app->session->setFlash('warning', 'Booking hii haiwezi kuthibitishwa katika hali yake ya sasa.');
            return $this->redirect(Yii::$app->request->referrer ?: ['owner']);
        }

        $booking->status = Booking::STATUS_CONFIRMED;

        $acceptBargain = Yii::$app->request->post('accept_bargain');
        if ($booking->bargain_status === Booking::BARGAIN_STATUS_PENDING) {
            if ($acceptBargain == 1 && $booking->offered_price > 0) {
                $booking->agreed_price = $booking->offered_price;
                $booking->bargain_status = Booking::BARGAIN_STATUS_ACCEPTED;
            } else {
                $booking->agreed_price = $booking->property->price;
                $booking->bargain_status = Booking::BARGAIN_STATUS_REJECTED;
            }
        } elseif ($booking->agreed_price === null) {
            $booking->agreed_price = $booking->property->price;
        }

        $notes = Yii::$app->request->post('owner_response_notes');
        if (!empty($notes)) {
            $booking->owner_response_notes = trim($notes);
        }

        if ($booking->save(false)) {
            Yii::$app->session->setFlash('success', 'Booking (' . $booking->booking_code . ') imethibitishwa kikamilifu!');
        } else {
            Yii::$app->session->setFlash('error', 'Imeshindikana kuthibitisha booking.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['owner']);
    }

    /**
     * Owner proposes rescheduled date & time.
     */
    public function actionReschedule($id)
    {
        $booking = $this->findOwnerBooking($id);

        if (!in_array($booking->status, [Booking::STATUS_PENDING, Booking::STATUS_RESCHEDULED], true)) {
            Yii::$app->session->setFlash('warning', 'Booking hii haiwezi kubadilishwa tarehe katika hali yake ya sasa.');
            return $this->redirect(Yii::$app->request->referrer ?: ['owner']);
        }

        $proposedDate = Yii::$app->request->post('proposed_date');
        $proposedTime = Yii::$app->request->post('proposed_time');
        $notes = Yii::$app->request->post('owner_response_notes');

        if (empty($proposedDate) || empty($proposedTime)) {
            Yii::$app->session->setFlash('error', 'Tafadhali chagua tarehe na muda mpya wa kupendekeza.');
            return $this->redirect(Yii::$app->request->referrer ?: ['owner']);
        }

        if (strtotime($proposedDate) < strtotime('today')) {
            Yii::$app->session->setFlash('error', 'Tarehe mpya ya miadi haiwezi kuwa ya siku zilizopita.');
            return $this->redirect(Yii::$app->request->referrer ?: ['owner']);
        }

        $booking->status = Booking::STATUS_RESCHEDULED;
        $booking->proposed_date = $proposedDate;
        $booking->proposed_time = $proposedTime;
        $booking->owner_response_notes = trim($notes);

        if ($booking->save(false)) {
            Yii::$app->session->setFlash('success', 'Tarehe mpya ya miadi imependekezwa kwa mtafutaji.');
        } else {
            Yii::$app->session->setFlash('error', 'Imeshindikana kusasisha tarehe.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['owner']);
    }

    /**
     * Seeker accepts rescheduled proposal.
     */
    public function actionAcceptReschedule($id)
    {
        $booking = Booking::findOne(['id' => $id, 'seeker_id' => Yii::$app->user->id]);
        if (!$booking) {
            throw new NotFoundHttpException('Booking haikupatikana au huna ruhusa.');
        }

        if ($booking->status !== Booking::STATUS_RESCHEDULED) {
            Yii::$app->session->setFlash('warning', 'Booking hii haina tarehe mpya inayongoja kukubaliwa.');
            return $this->redirect(['index']);
        }

        if (!empty($booking->proposed_date)) {
            $booking->booking_date = $booking->proposed_date;
            $booking->booking_time = $booking->proposed_time;
            $booking->proposed_date = null;
            $booking->proposed_time = null;
            $booking->status = Booking::STATUS_CONFIRMED;

            if ($booking->save(false)) {
                Yii::$app->session->setFlash('success', 'Umekubali tarehe mpya iliyopendekezwa! Miadi imethibitishwa kikamilifu.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Taarifa za tarehe iliyopendekezwa hazikupatikana.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Owner rejects booking request.
     */
    public function actionReject($id)
    {
        $booking = $this->findOwnerBooking($id);

        if (!in_array($booking->status, [Booking::STATUS_PENDING, Booking::STATUS_RESCHEDULED], true)) {
            Yii::$app->session->setFlash('warning', 'Booking hii haiwezi kukataliwa katika hali yake ya sasa.');
            return $this->redirect(Yii::$app->request->referrer ?: ['owner']);
        }

        $booking->status = Booking::STATUS_REJECTED;
        $notes = Yii::$app->request->post('owner_response_notes');
        if (!empty($notes)) {
            $booking->owner_response_notes = trim($notes);
        }

        if ($booking->save(false)) {
            Yii::$app->session->setFlash('info', 'Booking (' . $booking->booking_code . ') imekataliwa.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['owner']);
    }

    /**
     * Seeker or Owner cancels booking.
     */
    public function actionCancel($id)
    {
        $myId = Yii::$app->user->id;
        $booking = Booking::find()
            ->where(['id' => $id])
            ->andWhere(['or', ['seeker_id' => $myId], ['owner_id' => $myId]])
            ->one();

        if (!$booking) {
            throw new NotFoundHttpException('Booking haikupatikana.');
        }

        if (in_array($booking->status, [Booking::STATUS_COMPLETED, Booking::STATUS_REJECTED, Booking::STATUS_CANCELLED], true)) {
            Yii::$app->session->setFlash('warning', 'Booking hii haiwezi kuahirishwa katika hali yake ya sasa.');
            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
        }

        $booking->status = Booking::STATUS_CANCELLED;
        if ($booking->save(false)) {
            Yii::$app->session->setFlash('warning', 'Booking (' . $booking->booking_code . ') imeghirishwa.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Mark booking visit as completed.
     */
    public function actionComplete($id)
    {
        $myId = Yii::$app->user->id;
        $booking = Booking::find()
            ->where(['id' => $id])
            ->andWhere(['or', ['seeker_id' => $myId], ['owner_id' => $myId]])
            ->one();

        if (!$booking) {
            throw new NotFoundHttpException('Booking haikupatikana.');
        }

        if ($booking->status !== Booking::STATUS_CONFIRMED) {
            Yii::$app->session->setFlash('warning', 'Ni booking zilizothibitishwa tu zinazoweza kuwekwa kama Zimekamilika.');
            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
        }

        $booking->status = Booking::STATUS_COMPLETED;
        if ($booking->save(false)) {
            $property = Property::findOne($booking->property_id);
            if ($property) {
                $property->is_available = false;
                $property->save(false);
            }
            Yii::$app->session->setFlash('success', 'Booking (' . $booking->booking_code . ') imewekwa kama Imekamilika! Eneo limewekwa kama Imeshakodishwa / Kuuzwa.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * View / Generate Contract for a booking.
     */
    public function actionContract($booking_id)
    {
        $myId = Yii::$app->user->id;
        $booking = Booking::find()
            ->where(['id' => $booking_id])
            ->andWhere(['or', ['seeker_id' => $myId], ['owner_id' => $myId]])
            ->with(['property.location', 'seeker', 'owner'])
            ->one();

        if (!$booking) {
            // Allow admin access
            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === User::ROLE_ADMIN) {
                $booking = Booking::find()
                    ->where(['id' => $booking_id])
                    ->with(['property.location', 'seeker', 'owner'])
                    ->one();
            }
        }

        if (!$booking) {
            throw new NotFoundHttpException('Booking au mkataba haukupatikana.');
        }

        $isAdmin = (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === User::ROLE_ADMIN);
        if (!$isAdmin && !in_array($booking->status, [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED], true)) {
            Yii::$app->session->setFlash('warning', 'Mkataba utapatikana tu mara baada ya miadi/booking kuthibitishwa na Mmiliki.');
            return $this->redirect(Yii::$app->user->identity->role === User::ROLE_OWNER ? ['/booking/owner'] : ['/booking/index']);
        }

        // Check if contract exists, otherwise generate one
        $contract = BookingContract::findOne(['booking_id' => $booking->id]);
        if (!$contract) {
            $contract = new BookingContract();
            $contract->booking_id = $booking->id;
            $contract->property_id = $booking->property_id;
            $contract->seeker_id = $booking->seeker_id;
            $contract->owner_id = $booking->owner_id;
            $contract->contract_terms = $booking->property->contract_terms ?: "1. Kodi inalipwa kwa kufuata makubaliano ya muda uliopangwa.\n2. Mpangaji anapaswa kutunza usafi na amani ya eneo.\n3. Hakuna mabadiliko ya muundo wa jengo bila idhini ya mmiliki.\n4. Mkataba huu unaanza kutumika tarehe iliyobainishwa.";
            $contract->status = BookingContract::STATUS_PENDING_SIGNATURE;
            $contract->save(false);
        }

        return $this->render('contract', [
            'booking' => $booking,
            'contract' => $contract,
        ]);
    }

    /**
     * Seeker signs contract with typed full name signature.
     */
    public function actionSignContract($id)
    {
        $contract = BookingContract::findOne(['id' => $id, 'seeker_id' => Yii::$app->user->id]);
        if (!$contract) {
            throw new NotFoundHttpException('Mkataba haukupatikana au huna ruhusa.');
        }

        $signature = trim((string)Yii::$app->request->post('seeker_signature'));
        if (empty($signature)) {
            Yii::$app->session->setFlash('error', 'Tafadhali andika Saini Yako (Jina Kamili) ili kuthibitisha Mkataba.');
            return $this->redirect(['contract', 'booking_id' => $contract->booking_id]);
        }

        $contract->seeker_signature = $signature;
        $contract->seeker_signed_at = time();

        if ($contract->owner_signature) {
            $contract->status = BookingContract::STATUS_COMPLETED;
        } else {
            $contract->status = BookingContract::STATUS_SIGNED_BY_SEEKER;
        }

        if ($contract->save(false)) {
            if ($contract->status === BookingContract::STATUS_COMPLETED) {
                $property = Property::findOne($contract->property_id);
                if ($property) {
                    $property->is_available = false;
                    $property->save(false);
                }
            }
            Yii::$app->session->setFlash('success', 'Umesaini Mkataba kikamilifu! Saini yako imeunganishwa na tarehe & muda.');
        }

        return $this->redirect(['contract', 'booking_id' => $contract->booking_id]);
    }

    /**
     * Owner signs contract with typed full name signature.
     */
    public function actionOwnerSignContract($id)
    {
        $contract = BookingContract::findOne(['id' => $id, 'owner_id' => Yii::$app->user->id]);
        if (!$contract) {
            throw new NotFoundHttpException('Mkataba haukupatikana au huna ruhusa.');
        }

        $signature = trim((string)Yii::$app->request->post('owner_signature'));
        if (empty($signature)) {
            Yii::$app->session->setFlash('error', 'Tafadhali andika Saini Yako kama Mmiliki.');
            return $this->redirect(['contract', 'booking_id' => $contract->booking_id]);
        }

        $contract->owner_signature = $signature;
        $contract->owner_signed_at = time();

        if ($contract->seeker_signature) {
            $contract->status = BookingContract::STATUS_COMPLETED;
        } else {
            $contract->status = BookingContract::STATUS_SIGNED_BY_OWNER;
        }

        if ($contract->save(false)) {
            if ($contract->status === BookingContract::STATUS_COMPLETED) {
                $property = Property::findOne($contract->property_id);
                if ($property) {
                    $property->is_available = false;
                    $property->save(false);
                }
            }
            Yii::$app->session->setFlash('success', 'Mmiliki umesaini Mkataba kikamilifu!');
        }

        return $this->redirect(['contract', 'booking_id' => $contract->booking_id]);
    }

    /**
     * Owner updates custom lease terms and conditions for a contract.
     */
    public function actionUpdateContractTerms($id)
    {
        $contract = BookingContract::findOne(['id' => $id, 'owner_id' => Yii::$app->user->id]);
        if (!$contract) {
            throw new NotFoundHttpException('Mkataba haukupatikana au huna ruhusa.');
        }

        $terms = trim((string)Yii::$app->request->post('contract_terms'));
        if (empty($terms)) {
            Yii::$app->session->setFlash('error', 'Tafadhali weka masharti na kanuni za mkataba.');
            return $this->redirect(['contract', 'booking_id' => $contract->booking_id]);
        }

        $contract->contract_terms = $terms;
        if ($contract->save(false)) {
            // Also sync back to property default contract_terms so future bookings inherit this updated version
            $property = Property::findOne($contract->property_id);
            if ($property && $property->owner_id === Yii::$app->user->id) {
                $property->contract_terms = $terms;
                $property->save(false);
            }

            Yii::$app->session->setFlash('success', 'Masharti na Kanuni za Mkataba zimesasishwa kikamilifu!');
        }

        return $this->redirect(['contract', 'booking_id' => $contract->booking_id]);
    }

    protected function findOwnerBooking($id)
    {
        $booking = Booking::findOne(['id' => $id, 'owner_id' => Yii::$app->user->id]);
        if (!$booking) {
            throw new NotFoundHttpException('Booking haikupatikana au huna ruhusa.');
        }
        return $booking;
    }
}
