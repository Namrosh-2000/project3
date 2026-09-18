<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Property;
use app\models\User;
use app\models\LocalDataSource;
use app\models\MarketIndicator;
use app\models\Location;
use app\models\PropertyCategory;

use app\models\Booking;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index', 'dashboard', 'approve', 'reject', 'verify', 'bookings', 'cancel-booking',
                            'properties', 'reports', 'users',
                            'local-data', 'local-data-source-create', 'local-data-source-update', 'local-data-source-delete',
                            'indicator-create', 'indicator-update', 'indicator-delete',
                        ],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->isGuest) {
                                return false;
                            }
                            return Yii::$app->user->identity->role === User::ROLE_ADMIN;
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'approve' => ['post'],
                    'reject' => ['post'],
                    'cancel-booking' => ['post'],
                    'local-data-source-delete' => ['post'],
                    'indicator-delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->actionDashboard();
    }

    /**
     * Main admin dashboard.
     * The dedicated dashboard view already exists in this release; this
     * action supplies all of the data it expects.
     */
    public function actionDashboard()
    {
        $recentProperties = Property::find()
            ->with(['category', 'location', 'owner'])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(10)
            ->all();

        $stats = [
            'users' => User::find()->count(),
            'properties_total' => Property::find()->count(),
            'properties_pending' => Property::find()->where(['status' => Property::STATUS_PENDING])->count(),
            'properties_verified' => Property::find()->where(['status' => Property::STATUS_VERIFIED])->count(),
            'properties_rejected' => Property::find()->where(['status' => Property::STATUS_REJECTED])->count(),
            'bookings_total' => Booking::find()->count(),
            'bookings_pending' => Booking::find()->where(['status' => Booking::STATUS_PENDING])->count(),
        ];

        return $this->render('dashboard', [
            'stats' => $stats,
            'recentProperties' => $recentProperties,
        ]);
    }

    public function actionProperties($status = null)
    {
        $allowed = [
            Property::STATUS_PENDING,
            Property::STATUS_VERIFIED,
            Property::STATUS_REJECTED,
        ];
        $status = in_array($status, $allowed, true) ? $status : Property::STATUS_PENDING;

        $properties = Property::find()
            ->where(['status' => $status])
            ->with(['category', 'location', 'owner', 'coverImage'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('properties', [
            'properties' => $properties,
            'status' => $status,
        ]);
    }

    public function actionVerify($id)
    {
        return $this->actionApprove($id);
    }

    public function actionReports()
    {
        $reports = \app\models\Report::find()
            ->where(['status' => \app\models\Report::STATUS_PENDING])
            ->with(['property', 'reporter'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('reports', ['reports' => $reports]);
    }

    public function actionUsers()
    {
        $users = User::find()
            ->where(['status' => User::STATUS_ACTIVE])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('users', ['users' => $users]);
    }

    public function actionApprove($id)
    {
        $property = $this->findAnyModel($id);
        $property->status = Property::STATUS_VERIFIED;
        $property->save(false);

        Yii::$app->session->setFlash('success', 'Property "' . $property->title . '" imepitishwa (verified).');
        return $this->redirect(['index']);
    }

    public function actionReject($id)
    {
        $property = $this->findAnyModel($id);
        $property->status = Property::STATUS_REJECTED;
        $property->save(false);

        Yii::$app->session->setFlash('success', 'Property "' . $property->title . '" imekataliwa (rejected).');
        return $this->redirect(['index']);
    }

    public function actionBookings($status = null)
    {
        $query = Booking::find();

        if ($status && in_array($status, [
            Booking::STATUS_PENDING,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_RESCHEDULED,
            Booking::STATUS_REJECTED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_CANCELLED,
        ], true)) {
            $query->where(['status' => $status]);
        }

        $bookings = $query->orderBy(['created_at' => SORT_DESC])
            ->with(['property', 'seeker', 'owner'])
            ->all();

        $stats = [
            'total' => Booking::find()->count(),
            'pending' => Booking::find()->where(['status' => Booking::STATUS_PENDING])->count(),
            'confirmed' => Booking::find()->where(['status' => Booking::STATUS_CONFIRMED])->count(),
            'completed' => Booking::find()->where(['status' => Booking::STATUS_COMPLETED])->count(),
            'cancelled' => Booking::find()->where(['status' => Booking::STATUS_CANCELLED])->count(),
        ];

        return $this->render('bookings', [
            'bookings' => $bookings,
            'stats' => $stats,
            'currentStatus' => $status,
        ]);
    }

    public function actionCancelBooking($id)
    {
        $booking = Booking::findOne($id);
        if (!$booking) {
            throw new NotFoundHttpException('Booking haikupatikana.');
        }

        $booking->status = Booking::STATUS_CANCELLED;
        if ($booking->save(false)) {
            Yii::$app->session->setFlash('success', 'Booking (' . $booking->booking_code . ') imeghirishwa na Admin.');
        }

        return $this->redirect(['bookings']);
    }

    /**
     * Local Data — admin view of all LocalDataSource + MarketIndicator
     * rows (Phase 4). This is the only place these get created; Fursa
     * only ever reads them.
     */
    public function actionLocalData()
    {
        $sources = LocalDataSource::find()->orderBy(['created_at' => SORT_DESC])->all();
        $indicators = MarketIndicator::find()
            ->with(['location', 'category', 'dataSource'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('local-data', [
            'sources' => $sources,
            'indicators' => $indicators,
        ]);
    }

    public function actionLocalDataSourceCreate($id = null)
    {
        $model = $id ? LocalDataSource::findOne($id) : new LocalDataSource();
        if ($model === null) {
            throw new NotFoundHttpException('Data source not found.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data source "' . $model->name . '" saved.');
            return $this->redirect(['local-data']);
        }

        return $this->render('local-data-source-form', ['model' => $model]);
    }

    public function actionLocalDataSourceUpdate($id)
    {
        return $this->actionLocalDataSourceCreate($id);
    }

    public function actionLocalDataSourceDelete($id)
    {
        $model = LocalDataSource::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Data source not found.');
        }

        if (!empty($model->indicators)) {
            Yii::$app->session->setFlash('error', 'Cannot delete "' . $model->name . '" — it is still used by ' . count($model->indicators) . ' indicator(s). Delete those first.');
        } else {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Data source deleted.');
        }

        return $this->redirect(['local-data']);
    }

    public function actionIndicatorCreate($id = null)
    {
        $model = $id ? MarketIndicator::findOne($id) : new MarketIndicator();
        if ($model === null) {
            throw new NotFoundHttpException('Indicator not found.');
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->category_id === '' || $model->category_id === 0) {
                $model->category_id = null;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Indicator saved.');
                return $this->redirect(['local-data']);
            }
        }

        return $this->render('indicator-form', [
            'model' => $model,
            'wards' => Location::find()->orderBy(['ward' => SORT_ASC])->all(),
            'categories' => PropertyCategory::find()->where(['type' => 'business_space'])->orderBy(['name' => SORT_ASC])->all(),
            'sources' => LocalDataSource::find()->orderBy(['name' => SORT_ASC])->all(),
        ]);
    }

    public function actionIndicatorUpdate($id)
    {
        return $this->actionIndicatorCreate($id);
    }

    public function actionIndicatorDelete($id)
    {
        $model = MarketIndicator::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Indicator not found.');
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Indicator deleted.');

        return $this->redirect(['local-data']);
    }

    protected function findAnyModel($id)
    {
        $model = Property::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Property haikupatikana.');
        }
        return $model;
    }
}