<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Property;
use app\models\PropertyCategory;
use app\models\Location;
use app\models\SignupForm;
use app\models\User;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $featured = Property::find()
            ->where(['status' => Property::STATUS_VERIFIED, 'is_available' => true])
            ->orderBy(['views_count' => SORT_DESC, 'created_at' => SORT_DESC])
            ->limit(8)
            ->with(['coverImage', 'category', 'location'])
            ->all();

        $categories = PropertyCategory::find()
            ->where(['is_active' => true])
            ->orderBy(['type' => SORT_ASC, 'name' => SORT_ASC])
            ->all();

        $wards = Location::find()
            ->select(['ward'])
            ->distinct()
            ->orderBy(['ward' => SORT_ASC])
            ->column();

        return $this->render('index', [
            'featured' => $featured,
            'categories' => $categories,
            'wards' => $wards,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', 'Karibu tena, ' . Yii::$app->user->identity->username . '!');
            // goBack() honors a returnUrl set when AccessControl redirected the
            // user here from a protected page; otherwise it falls back to the
            // dashboard that matches their role.
            return $this->goBack(Yii::$app->user->identity->getDashboardRoute());
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && ($user = $model->signup())) {
            Yii::$app->user->login($user, 0);
            Yii::$app->session->setFlash('success', 'Akaunti yako imeundwa. Karibu MachoMtaa!');
            return $this->redirect($user->getDashboardRoute());
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Switches the UI language (Kiswahili default, English optional).
     * Remembered via cookie for guests; if a logged-in user's profile
     * gains a language field in a later phase, this is where it would
     * also be saved to their account.
     */
    public function actionSetLanguage($code)
    {
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'machomtaa_lang',
            'value' => $code,
            'expire' => time() + 3600 * 24 * 365,
        ]));

        return $this->goBack();
    }
}