<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\User;

/**
 * Role-based dashboard entry points.
 *
 * Phase 2 scope: routing + access control only. Each dashboard page
 * currently links out to the account/booking/property-submission
 * actions that already exist; the richer dashboard content described
 * in the product spec (analysis history, notifications, verification
 * status, etc.) lands in later phases as those features are built.
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
     * Mjasiriamali (seeker) dashboard. Owners/agents/admins who land
     * here are sent to the dashboard that actually matches their role
     * instead of seeing a page that isn't theirs.
     */
    public function actionEntrepreneur()
    {
        $identity = Yii::$app->user->identity;

        if (in_array($identity->role, [User::ROLE_OWNER, User::ROLE_AGENT, User::ROLE_ADMIN], true)) {
            return $this->redirect($identity->getDashboardRoute());
        }

        return $this->render('entrepreneur', [
            'identity' => $identity,
        ]);
    }

    /**
     * Mmiliki wa Eneo (owner/agent) dashboard.
     */
    public function actionOwner()
    {
        $identity = Yii::$app->user->identity;

        if (!in_array($identity->role, [User::ROLE_OWNER, User::ROLE_AGENT], true)) {
            return $this->redirect($identity->getDashboardRoute());
        }

        return $this->render('owner', [
            'identity' => $identity,
        ]);
    }
}
