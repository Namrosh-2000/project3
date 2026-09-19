<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\models\Location;
use app\models\Property;
use app\models\PropertyCategory;
use app\models\PropertyImage;
use app\models\User;

class PropertySubmissionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->isGuest) {
                                return false;
                            }
                            $role = Yii::$app->user->identity->role;
                            return in_array($role, [User::ROLE_OWNER, User::ROLE_AGENT, User::ROLE_ADMIN], true);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new Property();
        $model->status = Property::STATUS_PENDING;
        $model->owner_id = Yii::$app->user->id;
        if (empty($model->contract_terms)) {
            $model->contract_terms = "1. Kodi inalipwa kwa kufuata makubaliano ya muda uliopangwa.\n2. Mpangaji anapaswa kutunza usafi na amani ya eneo.\n3. Hakuna mabadiliko au ukarabati mkubwa wa jengo bila kibali cha maandishi cha mmiliki.\n4. Amana ya usafi/uharibifu itarejeshwa mwisho wa mkataba baada ya ukaguzi.\n5. Mkataba huu unaanza kutumika rasmi tarehe iliyobainishwa na kukubaliwa.";
        }

        $categories = PropertyCategory::find()
            ->where(['is_active' => true])
            ->orderBy(['type' => SORT_ASC, 'name' => SORT_ASC])
            ->all();

        $wards = Location::find()
            ->select(['ward'])
            ->distinct()
            ->orderBy(['ward' => SORT_ASC])
            ->column();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $wardName = trim($post['Property']['ward'] ?? '');

            $location = Location::find()->where(['ward' => $wardName])->one();
            if ($location === null && $wardName !== '') {
                $location = new Location();
                $location->region = 'Dar es Salaam';
                $location->municipality = 'Kinondoni';
                $location->ward = $wardName;
                $location->save();
            }

            if ($location) {
                $model->location_id = $location->id;
            }

            if ($model->load($post) && $model->save()) {
                $this->handleImages($model);
                Yii::$app->session->setFlash('success', Yii::t('app', 'submission.success_create'));
                return $this->redirect(['/account/listings']);
            } else {
                Yii::error('Property save failed: ' . print_r($model->getErrors(), true), __METHOD__);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'wards' => $wards,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $categories = PropertyCategory::find()
            ->where(['is_active' => true])
            ->orderBy(['type' => SORT_ASC, 'name' => SORT_ASC])
            ->all();

        $wards = Location::find()
            ->select(['ward'])
            ->distinct()
            ->orderBy(['ward' => SORT_ASC])
            ->column();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $wardName = trim($post['Property']['ward'] ?? '');

            if ($wardName !== '') {
                $location = Location::find()->where(['ward' => $wardName])->one();
                if ($location === null) {
                    $location = new Location();
                    $location->region = 'Dar es Salaam';
                    $location->municipality = 'Kinondoni';
                    $location->ward = $wardName;
                    $location->save();
                }
                if ($location) {
                    $model->location_id = $location->id;
                }
            }

            if ($model->load($post) && $model->save()) {
                $this->handleImages($model);
                Yii::$app->session->setFlash('success', Yii::t('app', 'submission.success_update'));
                return $this->redirect(['/account/listings']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'wards' => $wards,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'submission.success_delete'));
        return $this->redirect(['/account/listings']);
    }

    protected function findModel($id)
    {
        $model = Property::findOne(['id' => $id]);
        if ($model === null) {
            throw new NotFoundHttpException('Property haikupatikana.');
        }
        $user = Yii::$app->user->identity;
        $isAdmin = $user && $user->role === User::ROLE_ADMIN;
        if ($model->owner_id !== Yii::$app->user->id && !$isAdmin) {
            throw new \yii\web\ForbiddenHttpException('Huna ruhusa.');
        }
        return $model;
    }

    protected function handleImages(Property $property)
    {
        $files = UploadedFile::getInstancesByName('images');
        if (empty($files)) {
            return;
        }
        $uploadPath = Yii::getAlias('@webroot/uploads/properties/' . $property->id);
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $existing = PropertyImage::find()->where(['property_id' => $property->id])->count();
        $isFirst = $existing == 0;

        foreach ($files as $i => $file) {
            if ($file->error !== UPLOAD_ERR_OK) {
                continue;
            }
            $name = uniqid('img_') . '.' . $file->extension;
            $fullPath = $uploadPath . '/' . $name;
            if (!$file->saveAs($fullPath)) {
                continue;
            }
            $img = new PropertyImage();
            $img->property_id = $property->id;
            $img->image_path = '/uploads/properties/' . $property->id . '/' . $name;
            $img->is_cover = $isFirst && $i === 0;
            $img->sort_order = $i;
            $img->save();
        }
    }
}