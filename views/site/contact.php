<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Contact EneoLink Support';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-envelope-paper-fill text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Have questions about property listings or business advising? We are here to help.</p>
        </div>
    </div>
</div>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
    <div class="card p-5 text-center shadow-lg border-0 mb-5">
        <div class="card-body">
            <i class="bi bi-check-circle-fill text-success display-1 mb-3"></i>
            <h3 class="fw-bold">Message Sent Successfully!</h3>
            <p class="text-muted max-w-md mx-auto mb-0">Thank you for reaching out to EneoLink support. Our team will review your message and get back to you shortly.</p>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4 mb-5">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100 border-0 p-4">
                <div class="card-body">
                    <h4 class="fw-bold text-dark mb-4"><i class="bi bi-headset me-2 text-primary"></i>Get in Touch</h4>

                    <div class="d-flex gap-3 mb-4 align-items-start">
                        <div class="p-3 bg-primary-subtle text-primary rounded-circle fs-4">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark">Office Location</strong>
                            <span class="text-muted small">Kinondoni Municipality, Dar es Salaam, Tanzania</span>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4 align-items-start">
                        <div class="p-3 bg-success-subtle text-success rounded-circle fs-4">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark">Phone &amp; WhatsApp Support</strong>
                            <span class="text-muted small">+255 700 000 000 (Mon - Sat, 8:00 AM - 6:00 PM)</span>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4 align-items-start">
                        <div class="p-3 bg-info-subtle text-info rounded-circle fs-4">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark">Email Inquiries</strong>
                            <span class="text-muted small">info@eneolink.co.tz</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="p-3 bg-light rounded border">
                        <h6 class="fw-bold text-dark mb-1"><i class="bi bi-clock me-1 text-warning"></i> Operating Hours</h6>
                        <small class="text-muted d-block">Monday &ndash; Friday: 8:00 AM &ndash; 6:00 PM</small>
                        <small class="text-muted d-block">Saturday: 9:00 AM &ndash; 3:00 PM</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm h-100 border-0 p-4">
                <div class="card-body">
                    <h4 class="fw-bold text-dark mb-2"><i class="bi bi-send me-2 text-primary"></i>Send Us a Direct Message</h4>
                    <p class="text-muted mb-4">Fill out the form below and our Kinondoni platform support team will respond promptly.</p>

                    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <?= $form->field($model, 'name', [
                                    'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Your Full Name', 'autofocus' => true]
                                ])->label('<i class="bi bi-person me-1"></i> Name *') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'email', [
                                    'inputOptions' => ['class' => 'form-control', 'type' => 'email', 'placeholder' => 'name@example.com']
                                ])->label('<i class="bi bi-envelope me-1"></i> Email Address *') ?>
                            </div>
                            <div class="col-12">
                                <?= $form->field($model, 'subject', [
                                    'inputOptions' => ['class' => 'form-control', 'placeholder' => 'e.g. Listing Verification Inquiry']
                                ])->label('<i class="bi bi-bookmark me-1"></i> Subject *') ?>
                            </div>
                            <div class="col-12">
                                <?= $form->field($model, 'body', [
                                    'inputOptions' => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Describe your question or feedback...']
                                ])->label('<i class="bi bi-chat-text me-1"></i> Message Body *') ?>
                            </div>
                            <div class="col-12">
                                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                                    'template' => '<div class="row align-items-center"><div class="col-md-4">{image}</div><div class="col-md-8">{input}</div></div>',
                                ])->label('Verification Code') ?>
                            </div>
                        </div>

                        <div class="mt-4">
                            <?= Html::submitButton('<i class="bi bi-send-fill me-1"></i> Submit Message', ['class' => 'btn btn-primary btn-lg px-4 fw-bold shadow-sm', 'name' => 'contact-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
