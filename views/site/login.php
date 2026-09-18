<?php

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->registerCssFile('@web/css/auth.css');

$this->title = 'Login';
?>
<div class="el-auth-wrap">
    <div class="el-auth-side">
        <h2>Karibu tena EneoLink</h2>
        <p>Ingia kuendelea kutafuta properties, kufuatilia inquiries zako, na kupata fursa mpya za biashara Kinondoni.</p>

        <div class="el-auth-perk">
            <i class="bi bi-search"></i>
            <div><strong>Endelea kutafuta</strong><span>Rudi kwenye search zako na favorites</span></div>
        </div>
        <div class="el-auth-perk">
            <i class="bi bi-chat-dots"></i>
            <div><strong>Fuatilia inquiries</strong><span>Ona majibu kutoka kwa wamiliki na mawakala</span></div>
        </div>
        <div class="el-auth-perk">
            <i class="bi bi-lightbulb"></i>
            <div><strong>Fursa za biashara</strong><span>Pata mapendekezo mapya ya biashara eneo lako</span></div>
        </div>
    </div>

    <div class="el-auth-panel">
        <h2 class="el-heading"><?= Html::encode($this->title) ?></h2>
        <p class="el-auth-sub">Ingiza taarifa zako kuendelea na akaunti yako.</p>

        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Username au Email'])->label('Username or Email') ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => '••••••••']) ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="form-group mt-2">
                <?= Html::submitButton('Login', ['class' => 'btn el-auth-submit w-100']) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <div class="el-auth-switch">
            Huna akaunti? <?= Html::a('Sign up', ['/site/signup']) ?>
        </div>
    </div>
</div>