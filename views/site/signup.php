<?php

/** @var yii\web\View $this */
/** @var app\models\SignupForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->registerCssFile('@web/css/auth.css');

$this->title = Yii::t('app', 'auth.signup_title') . ' | MachoMtaa';
$returnUrl = Yii::$app->request->get('returnUrl');
?>
<div class="el-auth-wrap">
    <div class="el-auth-side">
        <h2><?= Html::encode(Yii::t('app', 'auth.join_brand')) ?></h2>
        <p><?= Html::encode(Yii::t('app', 'auth.signup_side_sub')) ?></p>

        <div class="el-auth-perk">
            <i class="bi bi-graph-up-arrow text-warning"></i>
            <div>
                <strong><?= Html::encode(Yii::t('app', 'auth.role_seeker_title')) ?></strong>
                <span><?= Html::encode(Yii::t('app', 'auth.role_seeker_desc')) ?></span>
            </div>
        </div>
        <div class="el-auth-perk">
            <i class="bi bi-building text-warning"></i>
            <div>
                <strong><?= Html::encode(Yii::t('app', 'auth.role_owner_title')) ?></strong>
                <span><?= Html::encode(Yii::t('app', 'auth.role_owner_desc')) ?></span>
            </div>
        </div>
        <div class="el-auth-perk">
            <i class="bi bi-briefcase text-warning"></i>
            <div>
                <strong><?= Html::encode(Yii::t('app', 'auth.role_agent_title')) ?></strong>
                <span><?= Html::encode(Yii::t('app', 'auth.role_agent_desc')) ?></span>
            </div>
        </div>
    </div>

    <div class="el-auth-panel">
        <h2 class="el-heading"><?= Html::encode(Yii::t('app', 'auth.signup_title')) ?></h2>
        <p class="el-auth-sub"><?= Html::encode(Yii::t('app', 'auth.signup_side_sub')) ?></p>

        <?php $form = ActiveForm::begin(); ?>

            <div class="mb-3">
                <label class="form-label fw-bold"><?= Html::encode(Yii::t('app', 'Wadhifa')) ?></label>
                <?= $form->field($model, 'role')->radioList([
                    'seeker' => Yii::t('app', 'auth.role_seeker_title'),
                    'owner' => Yii::t('app', 'auth.role_owner_title'),
                    'agent' => Yii::t('app', 'auth.role_agent_title'),
                ], [
                    'class' => 'el-role-group',
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $icons = ['seeker' => 'bi-graph-up-arrow', 'owner' => 'bi-building', 'agent' => 'bi-briefcase'];
                        $icon = $icons[$value] ?? 'bi-person';
                        $id = 'role-' . $value;
                        $input = \yii\bootstrap5\Html::radio($name, $checked, ['value' => $value, 'id' => $id, 'label' => false]);
                        return $input . \yii\bootstrap5\Html::label(
                            '<span class="el-role-card"><i class="bi ' . $icon . '"></i><span>' . Html::encode($label) . '</span></span>',
                            $id,
                            ['encode' => false]
                        );
                    },
                ])->label(false) ?>
            </div>

            <?= $form->field($model, 'username')->textInput([
                'autofocus' => true,
                'placeholder' => 'mfano: mjasiriamali1',
            ])->label(Yii::t('app', 'auth.username')) ?>

            <?= $form->field($model, 'email')->textInput([
                'type' => 'email',
                'placeholder' => 'mfano: wazo@machomtaa.co.tz',
            ])->label(Yii::t('app', 'auth.email')) ?>

            <?= $form->field($model, 'phone')->textInput([
                'placeholder' => '+255 7...',
            ])->label(Yii::t('app', 'auth.phone')) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => '••••••••',
            ])->label(Yii::t('app', 'auth.password')) ?>

            <div class="form-group mt-3">
                <?= Html::submitButton(Html::encode(Yii::t('app', 'auth.btn_signup')), [
                    'class' => 'btn el-auth-submit w-100',
                ]) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <div class="el-auth-switch">
            <?= Html::encode(Yii::t('app', 'auth.have_account')) ?>
            <?= Html::a(Html::encode(Yii::t('app', 'Login')), array_merge(['/site/login'], $returnUrl ? ['returnUrl' => $returnUrl] : [])) ?>
        </div>
    </div>
</div>