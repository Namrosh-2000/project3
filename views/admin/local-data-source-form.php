<?php

/** @var yii\web\View $this */
/** @var app\models\LocalDataSource $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$isNew = $model->isNewRecord;
$this->title = $isNew ? 'New Data Source' : 'Edit Data Source';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-journal-plus text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Where does this data actually come from? Be specific — this name is what entrepreneurs see under "Vyanzo vya Takwimu".</p>
        </div>
        <a href="<?= Url::to(['/admin/local-data']) ?>" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i> Back to Local Data
        </a>
    </div>
</div>

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

        <?= $form->field($model, 'name')->textInput(['placeholder' => 'e.g. Uchunguzi wa Mtaani — Sinza, Aug 2026']) ?>
        <?= $form->field($model, 'type')->dropDownList(\app\models\LocalDataSource::typeOptions()) ?>
        <?= $form->field($model, 'collected_at')->input('date') ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'is_demo')->checkbox([
            'label' => 'This is demo/placeholder data (uncheck only once this has been genuinely verified in the field)',
        ]) ?>

        <div class="d-flex gap-2 mt-4">
            <?= Html::submitButton('<i class="bi bi-check-lg me-1"></i> Save', ['class' => 'btn btn-primary']) ?>
            <a href="<?= Url::to(['/admin/local-data']) ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
