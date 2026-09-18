<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var app\models\ProfileForm $profileForm */
/** @var app\models\ChangePasswordForm $passwordForm */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Account Settings';
?>

<div class="el-page-header">
    <div class="d-flex align-items-center gap-4 flex-wrap">
        <div class="el-avatar shadow-lg" style="width:72px; height:72px; font-size:2rem; border:3px solid #fff">
            <?= Html::encode(mb_strtoupper(mb_substr($user->username, 0, 1))) ?>
        </div>
        <div>
            <h1 class="mb-1"><?= Html::encode($user->username) ?></h1>
            <p class="mb-0">
                <span class="badge badge-role-admin me-2 fs-6"><i class="bi bi-shield-lock me-1"></i><?= ucfirst($user->role) ?></span>
                <a href="<?= Url::to(['/profile/index']) ?>" class="text-white-50"><i class="bi bi-arrow-left me-1"></i>Rudi kwenye Profile / Mapendekezo</a>
            </p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-bounding-box me-2 text-primary"></i>Personal Information</h5>
            </div>
            <div class="card-body p-4">
                <?php $form = ActiveForm::begin(['id' => 'profile-form']); ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark"><i class="bi bi-person me-1"></i> Username</label>
                        <input type="text" class="form-control bg-light" value="<?= Html::encode($user->username) ?>" readonly disabled>
                        <small class="text-muted">Username cannot be changed.</small>
                    </div>

                    <?= $form->field($profileForm, 'email', [
                        'inputOptions' => ['class' => 'form-control', 'type' => 'email', 'placeholder' => 'name@example.com']
                    ])->label('<i class="bi bi-envelope me-1"></i> Email Address') ?>

                    <?= $form->field($profileForm, 'phone', [
                        'inputOptions' => ['class' => 'form-control', 'placeholder' => 'e.g. +255 700 000 000']
                    ])->label('<i class="bi bi-telephone me-1"></i> Phone Number') ?>

                    <div class="pt-2">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Save Changes
                        </button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-key-fill me-2 text-warning"></i>Security &amp; Password</h5>
            </div>
            <div class="card-body p-4">
                <?php $form = ActiveForm::begin(['id' => 'password-form']); ?>
                    <?= $form->field($passwordForm, 'currentPassword', [
                        'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Enter current password']
                    ])->passwordInput()->label('<i class="bi bi-lock me-1"></i> Current Password') ?>

                    <?= $form->field($passwordForm, 'newPassword', [
                        'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Enter new password']
                    ])->passwordInput()->label('<i class="bi bi-shield-lock me-1"></i> New Password') ?>

                    <?= $form->field($passwordForm, 'confirmPassword', [
                        'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Confirm new password']
                    ])->passwordInput()->label('<i class="bi bi-check2-circle me-1"></i> Confirm New Password') ?>

                    <div class="pt-2">
                        <button type="submit" class="btn btn-warning px-4 shadow-sm fw-bold">
                            <i class="bi bi-shield-check me-1"></i> Update Password
                        </button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
