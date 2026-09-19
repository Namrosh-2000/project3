<?php

/** @var yii\web\View $this */
/** @var app\models\LocalDataSource $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$isNew = $model->isNewRecord;
$this->title = $isNew ? 'New Data Source' : 'Edit Data Source';
?>

<div class="mm-fursa-hero">
    <div class="mm-fursa-hero-inner">
        <div class="mm-fursa-eyebrow">
            <i class="bi bi-journal-plus"></i> MachoMtaa Intelligence • Phase 4
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mm-fursa-hero-title mb-2"><?= Html::encode($this->title) ?></h1>
                <p class="mm-fursa-hero-lead mb-0">
                    Where does this data actually come from? Be specific — this name is what entrepreneurs see under "Vyanzo vya Takwimu".
                </p>
            </div>
            <a href="<?= Url::to(['/admin/local-data']) ?>" class="btn btn-outline-light fw-bold px-3 py-2 rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Local Data
            </a>
        </div>
    </div>
</div>

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
            <?= $form->field($model, 'name')->textInput(['class' => 'form-control mm-form-control', 'placeholder' => 'e.g. Uchunguzi wa Mtaani — Sinza, Aug 2026']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'type')->dropDownList(\app\models\LocalDataSource::typeOptions(), ['class' => 'form-select mm-form-select']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'collected_at')->input('date', ['class' => 'form-control mm-form-control']) ?>
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <div class="p-3 rounded-3 bg-light border w-100 mt-3">
                <?= $form->field($model, 'is_demo')->checkbox([
                    'label' => 'This is demo/placeholder data (uncheck only when genuinely verified in the field)',
                ]) ?>
            </div>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 3, 'class' => 'form-control mm-form-control', 'placeholder' => 'Detailed notes about methodology, survey area, or data provider.']) ?>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <?= Html::submitButton('<i class="bi bi-check-lg me-1"></i> Save Data Source', ['class' => 'btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm']) ?>
        <a href="<?= Url::to(['/admin/local-data']) ?>" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-3">Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>
</div>
