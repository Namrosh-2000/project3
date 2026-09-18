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

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-graph-up-arrow text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Only enter what you actually know. Leave "Value" blank if you only have a qualitative note — never fill in a guess to complete the form.</p>
        </div>
        <a href="<?= Url::to(['/admin/local-data']) ?>" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i> Back to Local Data
        </a>
    </div>
</div>

<?php if (empty($sources)): ?>
    <div class="alert alert-warning">
        You need at least one Data Source before you can add an indicator.
        <a href="<?= Url::to(['/admin/local-data-source-create']) ?>">Create one first</a>.
    </div>
<?php else: ?>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger p-3 mb-4">
        <ul class="mb-0">
            <?php foreach ($model->getErrors() as $attr => $errors): ?>
                <?php foreach ($errors as $err): ?>
                    <li><?= Html::encode($err) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row g-3">
            <div class="col-md-6">
                <?= $form->field($model, 'location_id')->dropDownList(
                    ArrayHelper::map($wards, 'id', 'ward'),
                    ['prompt' => '-- Chagua Kata --']
                ) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'category_id')->dropDownList(
                    ArrayHelper::map($categories, 'id', 'name'),
                    ['prompt' => '-- Whole ward (no specific category) --']
                ) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'indicator_type')->dropDownList(\app\models\MarketIndicator::typeOptions()) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'confidence_level')->dropDownList(\app\models\MarketIndicator::confidenceOptions()) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'value_label')->textInput(['placeholder' => 'e.g. Juu, Wastani, Chini']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'value_numeric')->textInput(['placeholder' => 'optional numeric reading']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'data_source_id')->dropDownList(
                    ArrayHelper::map($sources, 'id', 'name'),
                    ['prompt' => '-- Chagua Chanzo --']
                ) ?>
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <?= $form->field($model, 'is_demo')->checkbox([
                    'label' => 'This is demo/placeholder data',
                ]) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'notes')->textarea(['rows' => 3, 'placeholder' => 'Any context worth showing alongside this reading']) ?>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <?= Html::submitButton('<i class="bi bi-check-lg me-1"></i> Save', ['class' => 'btn btn-primary']) ?>
            <a href="<?= Url::to(['/admin/local-data']) ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php endif; ?>
