<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\models\Favorite;
use app\models\Inquiry;
use app\models\Property;
use app\models\PropertyImage;
use app\models\PropertySearch;
use app\models\Report;

class PropertyController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['toggle-favorite', 'report', 'compare', 'contact'],
                'rules' => [
                    [
                        'actions' => ['toggle-favorite', 'report', 'contact'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['compare'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'toggle-favorite' => ['post'],
                    'contact' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PropertySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->updateCounters(['views_count' => 1]);

        $isFavorite = false;
        if (!Yii::$app->user->isGuest) {
            $isFavorite = Favorite::find()
                ->where(['user_id' => Yii::$app->user->id, 'property_id' => $model->id])
                ->exists();
        }

        $related = Property::find()
            ->where(['status' => Property::STATUS_VERIFIED, 'is_available' => true])
            ->andWhere(['category_id' => $model->category_id])
            ->andWhere(['<>', 'id', $model->id])
            ->limit(4)
            ->with(['coverImage', 'category', 'location'])
            ->all();

        $reportModel = new Report();

        return $this->render('view', [
            'model' => $model,
            'isFavorite' => $isFavorite,
            'related' => $related,
            'reportModel' => $reportModel,
        ]);
    }

    public function actionToggleFavorite($id)
    {
        $property = $this->findModel($id);
        $existing = Favorite::find()
            ->where(['user_id' => Yii::$app->user->id, 'property_id' => $property->id])
            ->one();

        if ($existing) {
            $existing->delete();
            $favorited = false;
        } else {
            $fav = new Favorite();
            $fav->user_id = Yii::$app->user->id;
            $fav->property_id = $property->id;
            $fav->save();
            $favorited = true;
        }

        if (Yii::$app->request->isAjax) {
            return $this->asJson(['favorited' => $favorited]);
        }
        return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $property->id]);
    }

    public function actionReport($id)
    {
        $property = $this->findModel($id);
        $model = new Report();
        $model->property_id = $property->id;
        $model->reporter_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Asante kwa kutoa taarifa. Tutachunguza.');
            return $this->redirect(['view', 'id' => $property->id]);
        }

        Yii::$app->session->setFlash('error', 'Tafadhali jaza sababu ya taarifa.');
        return $this->redirect(['view', 'id' => $property->id]);
    }

    public function actionContact($id)
    {
        $property = $this->findModel($id);
        if ($property->owner_id === Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'Huwezi kutuma ujumbe au swali kwenye eneo lako mwenyewe.');
            return $this->redirect(['view', 'id' => $property->id]);
        }

        $inquiry = new Inquiry();
        $inquiry->property_id = $property->id;
        $inquiry->seeker_id = Yii::$app->user->id;
        $inquiry->owner_id = $property->owner_id;

        if ($inquiry->load(Yii::$app->request->post()) && $inquiry->save()) {
            Yii::$app->session->setFlash('success', 'Ujumbe wako umetumwa kikamilifu kwa mmiliki wa eneo hili.');
        } else {
            Yii::$app->session->setFlash('error', 'Tafadhali jaza ujumbe unaotaka kutuma.');
        }

        return $this->redirect(['view', 'id' => $property->id]);
    }

    public function actionCompare()
    {
        $ids = Yii::$app->request->get('ids', []);
        if (!is_array($ids)) {
            $ids = [];
        }
        $ids = array_filter(array_map('intval', $ids));

        $properties = [];
        if (!empty($ids)) {
            $properties = Property::find()
                ->where(['id' => $ids, 'status' => Property::STATUS_VERIFIED])
                ->with(['category', 'location', 'coverImage'])
                ->all();
        }

        return $this->render('compare', ['properties' => $properties]);
    }

    public function actionMap()
    {
        $properties = Property::find()
            ->where(['status' => Property::STATUS_VERIFIED, 'is_available' => true])
            ->with(['location', 'category', 'coverImage'])
            ->all();

        return $this->render('map', ['properties' => $properties]);
    }

    protected function findModel($id)
    {
        $query = Property::find()
            ->where(['id' => $id])
            ->with(['category', 'location', 'owner', 'images']);

        $isGuest = Yii::$app->user->isGuest;
        $user = !$isGuest ? Yii::$app->user->identity : null;
        $isAdmin = $user && $user->role === \app\models\User::ROLE_ADMIN;

        if (!$isAdmin) {
            $query->andWhere([
                'or',
                ['status' => Property::STATUS_VERIFIED],
                $user ? ['owner_id' => $user->id] : '0=1'
            ]);
        }

        $model = $query->one();
        if ($model === null) {
            throw new NotFoundHttpException('Property haikupatikana.');
        }
        return $model;
    }
}