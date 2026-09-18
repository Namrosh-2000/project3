<?php

/** @var yii\web\View $this */
/** @var app\models\SignupForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->registerCssFile('@web/css/auth.css');

$this->title = 'Sign Up';
?>
<div class="el-auth-wrap">
    <div class="el-auth-side">
        <h2>Jiunge na EneoLink</h2>
        <p>Fungua akaunti kutafuta properties, kuongeza listing, au kupata ushauri wa biashara — kulingana na wewe ni nani.</p>

        <div class="el-auth-perk">
            <i class="bi bi-house-door"></i>
            <div><strong>Seeker</strong><span>Tafuta chumba, apartment, ofisi au duka</span></div>
        </div>
        <div class="el-auth-perk">
            <i class="bi bi-building"></i>
            <div><strong>Owner</strong><span>Orodhesha property yako ufikie wapangaji</span></div>
        </div>
        <div class="el-auth-perk">
            <i class="bi bi-briefcase"></i>
            <div><strong>Agent</strong><span>Simamia listings za wateja wako wote</span></div>
        </div>
    </div>

    <div class="el-auth-panel">
        <h2 class="el-heading"><?= Html::encode($this->title) ?></h2>
        <p class="el-auth-sub">Tafadhali jaza taarifa zako. Kwa mmiliki au wakala, utaweza kuongeza properties baada ya kuthibitishwa.</p>

        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'role')->radioList([
                'seeker' => 'Seeker',
                'owner' => 'Owner',
                'agent' => 'Agent',
            ], [
                'class' => 'el-role-group',
                'item' => function ($index, $label, $name, $checked, $value) {
                    $icons = ['seeker' => 'bi-house-door', 'owner' => 'bi-building', 'agent' => 'bi-briefcase'];
                    $icon = $icons[$value] ?? 'bi-person';
                    $id = 'role-' . $value;
                    $input = \yii\bootstrap5\Html::radio($name, $checked, ['value' => $value, 'id' => $id, 'label' => false]);
                    return $input . \yii\bootstrap5\Html::label(
                        '<span class="el-role-card"><i class="bi ' . $icon . '"></i><span>' . $label . '</span></span>',
                        $id,
                        ['encode' => false]
                    );
                },
            ])->label(false) ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>
            <?= $form->field($model, 'phone')->textInput(['placeholder' => '+255...']) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => '••••••••']) ?>

            <div class="form-group mt-3">
                <?= Html::submitButton('Create Account', ['class' => 'btn el-auth-submit w-100']) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <div class="el-auth-switch">
            Una akaunti tayari? <?= Html::a('Login', ['/site/login']) ?>
        </div>
    </div>
</div>