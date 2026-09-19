<?php

/** @var yii\web\View $this */
/** @var app\models\MarketIndicator $model */
/** @var app\models\Location[] $wards */
/** @var app\models\PropertyCategory[] $categories */
/** @var app\models\LocalDataSource[] $sources */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$isNew = $model->isNewRecord;
$this->title = $isNew ? 'New Market Indicator' : 'Edit Market Indicator';
?>

<div class="mm-fursa-hero">
    <div class="mm-fursa-hero-inner">
        <div class="mm-fursa-eyebrow">
            <i class="bi bi-pin-map-fill"></i> MachoMtaa Intelligence • Phase 4
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mm-fursa-hero-title mb-2"><?= Html::encode($this->title) ?></h1>
                <p class="mm-fursa-hero-lead mb-0">
                    Only enter what you actually know. Leave "Value" blank if you only have a qualitative note — never fill in a guess to complete the form.
                </p>
            </div>
            <a href="<?= Url::to(['/admin/local-data']) ?>" class="btn btn-outline-light fw-bold px-3 py-2 rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Local Data
            </a>
        </div>
    </div>
</div>

<?php if (empty($sources)): ?>
    <div class="alert alert-warning shadow-sm rounded-4 p-3 mb-4">
        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
        You need at least one Data Source before you can add an indicator.
        <a href="<?= Url::to(['/admin/local-data-source-create']) ?>" class="fw-bold text-dark text-decoration-underline">Create one first</a>.
    </div>
<?php else: ?>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger shadow-sm rounded-4 p-3 mb-4">
        <ul class="mb-0">
            <?php foreach ($model->getErrors() as $attr => $errors): ?>
                <?php foreach ($errors as $err): ?>
                    <li><?= Html::encode($err) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 mb-5 p-4 p-md-5" style="border: 1px solid #e2e8f0;">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row g-4">
        <div class="col-md-6">
            <?= $form->field($model, 'location_id')->dropDownList(
                ArrayHelper::map($wards, 'id', 'ward'),
                ['prompt' => '-- Chagua Kata --', 'class' => 'form-select mm-form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map($categories, 'id', 'name'),
                ['prompt' => '-- Whole ward (no specific category) --', 'class' => 'form-select mm-form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'indicator_type')->dropDownList(\app\models\MarketIndicator::typeOptions(), ['class' => 'form-select mm-form-select']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'confidence_level')->dropDownList(\app\models\MarketIndicator::confidenceOptions(), ['class' => 'form-select mm-form-select']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'value_label')->textInput(['class' => 'form-control mm-form-control', 'placeholder' => 'e.g. Juu Sana, Wastani, Chini']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'value_numeric')->textInput(['class' => 'form-control mm-form-control', 'placeholder' => 'optional numeric reading']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'data_source_id')->dropDownList(
                ArrayHelper::map($sources, 'id', 'name'),
                ['prompt' => '-- Chagua Chanzo --', 'class' => 'form-select mm-form-select']
            ) ?>
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <div class="p-3 rounded-3 bg-light border w-100 mt-3">
                <?= $form->field($model, 'is_demo')->checkbox([
                    'label' => 'This is demo/placeholder data',
                ]) ?>
            </div>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'notes')->textarea(['rows' => 3, 'class' => 'form-control mm-form-control', 'placeholder' => 'Any context worth showing alongside this reading']) ?>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <?= Html::submitButton('<i class="bi bi-check-lg me-1"></i> Save Indicator', ['class' => 'btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm']) ?>
        <a href="<?= Url::to(['/admin/local-data']) ?>" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-3">Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php endif; ?>
