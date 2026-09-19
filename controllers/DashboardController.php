<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Booking;
use app\models\BusinessAnalysis;
use app\models\Favorite;
use app\models\Inquiry;
use app\models\Property;
use app\models\User;

/**
 * Role-based dashboard entry points.
 *
 * Phase 2: Centralized role-based access control, routing, and overview stats
 * for Mjasiriamali (Entrepreneur) and Mmiliki wa Eneo (Property Owner).
 */
class DashboardController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['entrepreneur', 'owner'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Mjasiriamali (Entrepreneur) dashboard.
     * Owners/agents/admins who land here are redirected to their designated dashboard.
     */
    public function actionEntrepreneur()
    {
        $identity = Yii::$app->user->identity;

        if (in_array($identity->role, [User::ROLE_OWNER, User::ROLE_AGENT, User::ROLE_ADMIN], true)) {
            return $this->redirect($identity->getDashboardRoute());
        }

        $analysisCount = BusinessAnalysis::find()->where(['user_id' => $identity->id])->count();
        $favoritesCount = Favorite::find()->where(['user_id' => $identity->id])->count();
        $bookingsCount = Booking::find()->where(['seeker_id' => $identity->id])->count();
        $inquiriesCount = Inquiry::find()->where(['user_id' => $identity->id])->count();

        $recentAnalyses = BusinessAnalysis::find()
            ->where(['user_id' => $identity->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(3)
            ->all();

        return $this->render('entrepreneur', [
            'identity' => $identity,
            'analysisCount' => $analysisCount,
            'favoritesCount' => $favoritesCount,
            'bookingsCount' => $bookingsCount,
            'inquiriesCount' => $inquiriesCount,
            'recentAnalyses' => $recentAnalyses,
        ]);
    }

    /**
     * Mmiliki wa Eneo (Property Owner / Agent) dashboard.
     * Seekers who land here are redirected to entrepreneur dashboard.
     */
    public function actionOwner()
    {
        $identity = Yii::$app->user->identity;

        if (!in_array($identity->role, [User::ROLE_OWNER, User::ROLE_AGENT], true)) {
            return $this->redirect($identity->getDashboardRoute());
        }

        $listingsCount = Property::find()->where(['owner_id' => $identity->id])->count();
        $activeListingsCount = Property::find()
            ->where(['owner_id' => $identity->id, 'status' => Property::STATUS_VERIFIED, 'is_available' => true])
            ->count();
        $pendingBookingsCount = Booking::find()
            ->where(['owner_id' => $identity->id, 'status' => Booking::STATUS_PENDING])
            ->count();
        $totalBookingsCount = Booking::find()->where(['owner_id' => $identity->id])->count();

        $propertyIds = Property::find()->select('id')->where(['owner_id' => $identity->id]);
        $inquiriesCount = Inquiry::find()->where(['property_id' => $propertyIds])->count();

        $recentListings = Property::find()
            ->where(['owner_id' => $identity->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(3)
            ->all();

        return $this->render('owner', [
            'identity' => $identity,
            'listingsCount' => $listingsCount,
            'activeListingsCount' => $activeListingsCount,
            'pendingBookingsCount' => $pendingBookingsCount,
            'totalBookingsCount' => $totalBookingsCount,
            'inquiriesCount' => $inquiriesCount,
            'recentListings' => $recentListings,
        ]);
    }
}
