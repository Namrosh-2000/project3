<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Favorite;
use app\models\Inquiry;
use app\models\Property;

class AccountController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'reply-inquiry' => ['post'],
                ],
            ],
        ];
    }

    public function actionFavorites()
    {
        $favorites = Favorite::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->with(['property.coverImage', 'property.category', 'property.location'])
            ->all();

        return $this->render('favorites', ['favorites' => $favorites]);
    }

    public function actionListings()
    {
        $properties = Property::find()
            ->where(['owner_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->with(['category', 'location', 'coverImage'])
            ->all();

        return $this->render('listings', ['properties' => $properties]);
    }

    public function actionInquiries()
    {
        $myId = Yii::$app->user->id;

        $sent = Inquiry::find()
            ->where(['seeker_id' => $myId])
            ->orderBy(['created_at' => SORT_DESC])
            ->with(['property', 'owner'])
            ->all();

        $received = Inquiry::find()
            ->where(['owner_id' => $myId])
            ->orderBy(['created_at' => SORT_DESC])
            ->with(['property', 'seeker'])
            ->all();

        return $this->render('inquiries', [
            'sent' => $sent,
            'received' => $received,
        ]);
    }

    public function actionReplyInquiry($id)
    {
        $inquiry = Inquiry::findOne(['id' => $id, 'owner_id' => Yii::$app->user->id]);
        if ($inquiry === null) {
            throw new NotFoundHttpException('Ujumbe haukupatikana au huna ruhusa.');
        }

        $reply = trim((string)Yii::$app->request->post('reply'));
        if (empty($reply)) {
            Yii::$app->session->setFlash('error', 'Tafadhali andika jibu kabla ya kutuma.');
            return $this->redirect(['inquiries']);
        }

        $inquiry->reply = $reply;
        $inquiry->status = Inquiry::STATUS_REPLIED;
        if ($inquiry->save(false)) {
            Yii::$app->session->setFlash('success', 'Jibu lako limetumwa kikamilifu kwa mteja.');
        } else {
            Yii::$app->session->setFlash('error', 'Imeshindikana kutuma jibu. Jaribu tena.');
        }

        return $this->redirect(['inquiries']);
    }
}