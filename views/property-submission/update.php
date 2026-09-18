<?php

/** @var yii\web\View $this */
/** @var app\models\Property $model */
/** @var array $categories */
/** @var array $wards */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = 'Update Property Listing';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-pencil-square text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Update property information, pricing, availability, and photo gallery.</p>
        </div>
        <div>
            <a href="<?= Url::to(['/account/listings']) ?>" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-1"></i> Back to My Listings
            </a>
        </div>
    </div>
</div>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger p-4 rounded-3 shadow-sm mb-4">
        <h5 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</h5>
        <ul class="mb-0">
            <?php foreach ($model->getErrors() as $attr => $errors): ?>
                <?php foreach ($errors as $err): ?>
                    <li><strong><?= Html::encode(ucfirst($attr)) ?>:</strong> <?= Html::encode($err) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-lg border-0 mb-5">
    <div class="card-body p-4 p-md-5">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4"><i class="bi bi-info-circle text-primary me-2"></i>Property Information</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <?= $form->field($model, 'title', [
                    'inputOptions' => ['class' => 'form-control form-control-lg']
                ])->label('<i class="bi bi-heading me-1"></i> Property Title *') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'listing_type', [
                    'inputOptions' => ['class' => 'form-select form-select-lg']
                ])->dropDownList([
                    'rent' => 'Rent (Kukodisha)',
                    'sale' => 'Sale (Kuuza)',
                ])->label('<i class="bi bi-tag me-1"></i> Listing Type *') ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'category_id', [
                    'inputOptions' => ['class' => 'form-select']
                ])->dropDownList(
                    ArrayHelper::map($categories, 'id', 'name'),
                    ['prompt' => '-- Select Category --']
                )->label('<i class="bi bi-building me-1"></i> Property Category *') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'price', [
                    'inputOptions' => ['class' => 'form-control', 'type' => 'number', 'step' => '5000']
                ])->label('<i class="bi bi-cash me-1"></i> Asking Price (TSh) *') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'price_period', [
                    'inputOptions' => ['class' => 'form-select']
                ])->dropDownList([
                    'monthly' => 'Per Month',
                    'yearly' => 'Per Year',
                    'one_time' => 'One-time Sale',
                ])->label('<i class="bi bi-calendar-range me-1"></i> Price Period') ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'bedrooms', [
                    'inputOptions' => ['class' => 'form-control', 'type' => 'number', 'min' => 0]
                ])->label('<i class="bi bi-door-open me-1"></i> Bedrooms Count') ?>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded border mt-4">
                    <?= $form->field($model, 'is_available')->checkbox([
                        'label' => '<strong>Available for Rent/Sale</strong> (Uncheck if occupied or reserved)'
                    ]) ?>
                </div>
            </div>

            <div class="col-12">
                <?= $form->field($model, 'description', [
                    'inputOptions' => ['class' => 'form-control', 'rows' => 4]
                ])->label('<i class="bi bi-file-text me-1"></i> Property Description') ?>
            </div>
        </div>

        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4"><i class="bi bi-stars text-primary me-2"></i>Features &amp; Photos</h5>
        <div class="row g-3 mb-4">
            <div class="col-12">
                <label class="form-label fw-bold text-dark mb-2">Features &amp; Amenities</label>
                <div class="row g-3 p-3 bg-light rounded border">
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'is_furnished')->checkbox(['label' => 'Furnished Room']) ?></div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'has_parking')->checkbox(['label' => 'Parking Space']) ?></div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'has_water')->checkbox(['label' => 'Running Water']) ?></div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'has_electricity')->checkbox(['label' => 'Electricity LUKU']) ?></div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'has_security')->checkbox(['label' => 'Security Fence']) ?></div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check"><?= $form->field($model, 'has_internet')->checkbox(['label' => 'WiFi / Internet']) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold text-dark"><i class="bi bi-cloud-arrow-up me-1"></i> Add More Photos</label>
                <input type="file" name="images[]" multiple accept="image/*" class="form-control">
            </div>

            <?php if (!empty($model->images)): ?>
                <div class="col-12">
                    <label class="form-label fw-bold text-dark mb-2">Existing Uploaded Photos</label>
                    <div class="d-flex flex-wrap gap-3">
                        <?php foreach ($model->images as $img): ?>
                            <div class="position-relative shadow-sm rounded overflow-hidden">
                                <img src="<?= $img->getImageUrl() ?>" style="width:140px; height:100px; object-fit:cover">
                                <?php if ($img->is_cover): ?>
                                    <span class="badge bg-success position-absolute top-0 start-0 m-1">Cover</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4"><i class="bi bi-file-earmark-text text-primary me-2"></i>Lease Contract Terms &amp; Rules (Masharti ya Mkataba)</h5>
        <div class="row g-3 mb-4">
            <div class="col-12">
                <?= $form->field($model, 'contract_terms', [
                    'inputOptions' => [
                        'class' => 'form-control', 
                        'rows' => 4, 
                        'placeholder' => "Weka masharti ya mkataba wako hapa..."
                    ]
                ])->label('<i class="bi bi-card-checklist me-1"></i> Custom Lease Terms &amp; Rules (Masharti ya Mkataba)')->hint('Masharti haya yataingizwa moja kwa moja kwenye Mkataba rasmi (Digital Contract).') ?>
            </div>
        </div>

        <div class="pt-3 border-top d-flex gap-3 align-items-center">
            <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow-sm">
                <i class="bi bi-check-circle-fill me-1"></i> Save Property Changes
            </button>
            <a href="<?= Url::to(['/account/listings']) ?>" class="btn btn-link text-muted">Cancel</a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>