<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\ChangePasswordForm;
use app\models\ProfileForm;
use app\models\User;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $profileForm = new ProfileForm($user);

        $request = Yii::$app->request;

        if ($profileForm->load($request->post()) && $profileForm->save()) {
            Yii::$app->session->setFlash('success', 'Mapendekezo yako yamehifadhiwa.');
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'user' => $user,
            'profileForm' => $profileForm,
        ]);
    }

    public function actionSettings()
    {
        $user = Yii::$app->user->identity;
        $profileForm = new ProfileForm($user);
        $passwordForm = new ChangePasswordForm($user);

        $request = Yii::$app->request;

        if ($profileForm->load($request->post()) && $profileForm->save()) {
            Yii::$app->session->setFlash('success', 'Profile imesasishwa.');
            return $this->redirect(['settings']);
        }

        if ($passwordForm->load($request->post()) && $passwordForm->change()) {
            Yii::$app->session->setFlash('success', 'Password imebadilishwa. Tafadhali login tena.');
            Yii::$app->user->logout();
            return $this->redirect(['/site/login']);
        }

        return $this->render('settings', [
            'user' => $user,
            'profileForm' => $profileForm,
            'passwordForm' => $passwordForm,
        ]);
    }
}