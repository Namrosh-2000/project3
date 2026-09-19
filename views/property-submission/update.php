<?php

/** @var yii\web\View $this */
/** @var app\models\Property $model */
/** @var array $categories */
/** @var array $wards */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = Yii::t('app', 'submission.title_update') . ' — MachoMtaa';
$currentWard = $model->location->ward ?? '';
?>

<!-- Stripe-Grade Hero Header -->
<div class="mm-fursa-hero">
    <div class="mm-fursa-hero-inner">
        <div class="mm-fursa-eyebrow">
            <i class="bi bi-pencil-square"></i> <?= Html::encode(Yii::t('app', 'submission.eyebrow')) ?>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mm-fursa-hero-title mb-2"><?= Html::encode(Yii::t('app', 'submission.title_update')) ?></h1>
                <p class="mm-fursa-hero-lead mb-0">
                    <?= Html::encode(Yii::t('app', 'submission.sub_update')) ?>
                </p>
            </div>
            <a href="<?= Url::to(['/account/listings']) ?>" class="btn btn-outline-light fw-bold px-3 py-2 rounded-3">
                <i class="bi bi-arrow-left me-1"></i> <?= Yii::t('app', 'submission.back_to_listings') ?>
            </a>
        </div>
    </div>
</div>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Tafadhali rekebisha makosa yafuatayo:</h5>
        <ul class="mb-0">
            <?php foreach ($model->getErrors() as $attr => $errors): ?>
                <?php foreach ($errors as $err): ?>
                    <li><strong><?= Html::encode(ucfirst($attr)) ?>:</strong> <?= Html::encode($err) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- 3-Step Stripe-Grade Form Wrapper -->
<div class="mm-fursa-form-wrapper">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <!-- Step 1: Basic Details & Pricing -->
    <div class="mm-form-section">
        <div class="mm-section-header">
            <span class="mm-section-number">01</span>
            <div>
                <h3 class="mm-section-title"><?= Yii::t('app', 'submission.step1_title') ?></h3>
                <p class="mm-section-sub"><?= Yii::t('app', 'submission.step1_sub') ?></p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-8">
                <?= $form->field($model, 'title', [
                    'inputOptions' => ['class' => 'form-control mm-form-control', 'placeholder' => Yii::t('app', 'submission.field_title_ph')]
                ])->label('<i class="bi bi-shop me-1 text-teal"></i> ' . Yii::t('app', 'submission.field_title') . ' <span class="text-danger">*</span>', ['class' => 'mm-input-label']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'listing_type', [
                    'inputOptions' => ['class' => 'form-select mm-form-select']
                ])->dropDownList([
                    'rent' => Yii::t('app', 'fremu.filter_type_rent'),
                    'sale' => Yii::t('app', 'fremu.filter_type_sale'),
                ])->label('<i class="bi bi-tag me-1 text-teal"></i> ' . Yii::t('app', 'submission.field_listing_type') . ' <span class="text-danger">*</span>', ['class' => 'mm-input-label']) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'category_id', [
                    'inputOptions' => ['class' => 'form-select mm-form-select']
                ])->dropDownList(
                    ArrayHelper::map($categories, 'id', 'name'),
                    ['prompt' => '-- Chagua Kategoria --']
                )->label('<i class="bi bi-grid me-1 text-teal"></i> ' . Yii::t('app', 'submission.field_category') . ' <span class="text-danger">*</span>', ['class' => 'mm-input-label']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'price', [
                    'inputOptions' => ['class' => 'form-control mm-form-control', 'type' => 'number', 'step' => '5000']
                ])->label('<i class="bi bi-cash-stack me-1 text-success"></i> ' . Yii::t('app', 'submission.field_price') . ' <span class="text-danger">*</span>', ['class' => 'mm-input-label']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'price_period', [
                    'inputOptions' => ['class' => 'form-select mm-form-select']
                ])->dropDownList([
                    'monthly' => 'Kwa Mwezi (Monthly)',
                    'yearly' => 'Kwa Mwaka (Yearly)',
                    'one_time' => 'Kuuza Moja kwa Moja',
                ])->label('<i class="bi bi-calendar-range me-1 text-teal"></i> ' . Yii::t('app', 'submission.field_price_period'), ['class' => 'mm-input-label']) ?>
            </div>
        </div>
    </div>

    <!-- Step 2: Location & Space Specifications -->
    <div class="mm-form-section">
        <div class="mm-section-header">
            <span class="mm-section-number">02</span>
            <div>
                <h3 class="mm-section-title"><?= Yii::t('app', 'submission.step2_title') ?></h3>
                <p class="mm-section-sub"><?= Yii::t('app', 'submission.step2_sub') ?></p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="mm-input-label"><i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= Yii::t('app', 'submission.field_ward') ?> <span class="text-danger">*</span></label>
                <select name="Property[ward]" class="form-select mm-form-select" required>
                    <option value="">-- Chagua Kata ya Kinondoni --</option>
                    <?php foreach ($wards as $w): ?>
                        <option value="<?= Html::encode($w) ?>" <?= $w === $currentWard ? 'selected' : '' ?>>📍 <?= Html::encode($w) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'bedrooms', [
                    'inputOptions' => ['class' => 'form-control mm-form-control', 'type' => 'number', 'min' => 0]
                ])->label('<i class="bi bi-door-open me-1 text-teal"></i> ' . Yii::t('app', 'submission.field_bedrooms'), ['class' => 'mm-input-label']) ?>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded-3 border mt-3">
                    <?= $form->field($model, 'is_available')->checkbox([
                        'label' => '<strong>Ipo Wazi kwa Sasa</strong> (Ondoa tiki kama imeshakodishwa)'
                    ]) ?>
                </div>
            </div>

            <div class="col-12">
                <?= $form->field($model, 'description', [
                    'inputOptions' => ['class' => 'form-control mm-form-control', 'rows' => 4]
                ])->label('<i class="bi bi-file-text me-1 text-teal"></i> ' . Yii::t('app', 'submission.field_description'), ['class' => 'mm-input-label']) ?>
            </div>
        </div>
    </div>

    <!-- Step 3: Amenities, Contract Terms & Photo Uploads -->
    <div class="mm-form-section">
        <div class="mm-section-header">
            <span class="mm-section-number">03</span>
            <div>
                <h3 class="mm-section-title"><?= Yii::t('app', 'submission.step3_title') ?></h3>
                <p class="mm-section-sub"><?= Yii::t('app', 'submission.step3_sub') ?></p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <label class="mm-input-label"><i class="bi bi-check2-square text-teal me-1"></i> Miundombinu &amp; Huduma Zilizopo</label>
                <div class="row g-3 p-3 bg-light rounded-4 border">
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'has_water')->checkbox(['label' => 'Maji Safi ya Uhakika']) ?></div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'has_electricity')->checkbox(['label' => 'Umeme wa LUKU']) ?></div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'has_parking')->checkbox(['label' => 'Maegesho ya Magari']) ?></div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'has_security')->checkbox(['label' => 'Ulinzi &amp; Fensi']) ?></div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'has_internet')->checkbox(['label' => 'Mtandao / WiFi']) ?></div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'is_furnished')->checkbox(['label' => 'Fremu Imekarabatiwa (Furnished)']) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <?= $form->field($model, 'contract_terms', [
                    'inputOptions' => ['class' => 'form-control mm-form-control', 'rows' => 4]
                ])->label('<i class="bi bi-file-earmark-ruled me-1 text-teal"></i> ' . Yii::t('app', 'submission.field_contract_terms'), ['class' => 'mm-input-label']) ?>
            </div>

            <div class="col-12">
                <label class="mm-input-label"><i class="bi bi-images text-teal me-1"></i> Ongeza Picha za Ziada</label>
                <div class="p-4 border rounded-4 text-center bg-light" style="border: 2px dashed #cbd5e1 !important;">
                    <i class="bi bi-cloud-arrow-up text-teal display-4 d-block mb-2"></i>
                    <input type="file" name="images[]" multiple accept="image/*" class="form-control mm-form-control mx-auto" style="max-width: 400px;">
                    <small class="text-muted d-block mt-2">Picha zilizopo zitabaki salama. Picha mpya zitaongezwa kwenye tangazo lako.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Area -->
    <div class="mm-fursa-submit-area">
        <button type="submit" class="mm-submit-btn">
            <span><?= Yii::t('app', 'submission.btn_save_changes') ?></span>
            <i class="bi bi-check-circle-fill fs-5"></i>
        </button>
    </div>

    <?php ActiveForm::end(); ?>
</div>