<?php

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->registerCssFile('@web/css/auth.css');

$this->title = Yii::t('app', 'auth.login_title') . ' | MachoMtaa';
$returnUrl = Yii::$app->request->get('returnUrl');
?>
<div class="el-auth-wrap">
    <div class="el-auth-side">
        <h2><?= Html::encode(Yii::t('app', 'auth.welcome_back_brand')) ?></h2>
        <p><?= Html::encode(Yii::t('app', 'auth.login_side_sub')) ?></p>

        <div class="el-auth-perk">
            <i class="bi bi-graph-up-arrow text-warning"></i>
            <div>
                <strong><?= Html::encode(Yii::t('app', 'auth.perk_intelligence_title')) ?></strong>
                <span><?= Html::encode(Yii::t('app', 'auth.perk_intelligence_desc')) ?></span>
            </div>
        </div>
        <div class="el-auth-perk">
            <i class="bi bi-shop text-warning"></i>
            <div>
                <strong><?= Html::encode(Yii::t('app', 'auth.perk_spaces_title')) ?></strong>
                <span><?= Html::encode(Yii::t('app', 'auth.perk_spaces_desc')) ?></span>
            </div>
        </div>
        <div class="el-auth-perk">
            <i class="bi bi-speedometer2 text-warning"></i>
            <div>
                <strong><?= Html::encode(Yii::t('app', 'auth.perk_dashboard_title')) ?></strong>
                <span><?= Html::encode(Yii::t('app', 'auth.perk_dashboard_desc')) ?></span>
            </div>
        </div>
    </div>

    <div class="el-auth-panel">
        <h2 class="el-heading"><?= Html::encode(Yii::t('app', 'auth.login_title')) ?></h2>
        <p class="el-auth-sub"><?= Html::encode(Yii::t('app', 'auth.login_side_sub')) ?></p>

        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'username')->textInput([
                'autofocus' => true,
                'placeholder' => Yii::t('app', 'auth.username_or_email'),
            ])->label(Yii::t('app', 'auth.username_or_email')) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => '••••••••',
            ])->label(Yii::t('app', 'auth.password')) ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'label' => Yii::t('app', 'auth.remember_me'),
            ]) ?>

            <div class="form-group mt-3">
                <?= Html::submitButton(Html::encode(Yii::t('app', 'auth.btn_login')), [
                    'class' => 'btn el-auth-submit w-100',
                ]) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <div class="el-auth-switch">
            <?= Html::encode(Yii::t('app', 'auth.no_account')) ?>
            <?= Html::a(Html::encode(Yii::t('app', 'Sign Up')), array_merge(['/site/signup'], $returnUrl ? ['returnUrl' => $returnUrl] : [])) ?>
        </div>
    </div>
</div>