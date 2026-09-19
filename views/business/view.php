<?php

/** @var yii\web\View $this */
/** @var app\models\BusinessAnalysis $analysis */
/** @var array $results */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'fursa.view.title', ['type' => $analysis->business_type]) . ' — MachoMtaa';
?>

<div class="mm-fursa-hero">
    <div class="mm-fursa-hero-inner">
        <div class="mm-fursa-eyebrow">
            <i class="bi bi-file-earmark-bar-graph"></i> <?= Html::encode(Yii::t('app', 'fursa.history.title')) ?>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mm-fursa-hero-title mb-2"><?= Html::encode($analysis->business_type) ?></h1>
                <p class="mm-fursa-hero-lead mb-0">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> Kata ya <?= Html::encode($analysis->ward) ?>
                    &middot; <?= Yii::$app->formatter->asDate($analysis->created_at, 'medium') ?>
                    &middot; <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill"><?= Html::encode($analysis->statusLabel) ?></span>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= Url::to(['/business/history']) ?>" class="btn btn-outline-light btn-sm fw-bold px-3 py-2 rounded-3">
                    <i class="bi bi-arrow-left me-1"></i> <?= Yii::t('app', 'fursa.history.title') ?>
                </a>
                <a href="<?= Url::to(['/business/index']) ?>" class="btn btn-warning btn-sm fw-bold px-3 py-2 rounded-3 text-dark">
                    <i class="bi bi-plus-lg me-1"></i> <?= Yii::t('app', 'fursa.history.new_analysis') ?>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="mm-history-detail-meta">
    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-card-checklist text-teal me-2"></i><?= Yii::t('app', 'fursa.form_heading') ?></h5>
    <div class="row g-3">
        <?php if ($analysis->business_vision): ?>
            <div class="col-12">
                <p class="mb-1"><strong class="text-muted"><?= Yii::t('app', 'fursa.field.business_vision') ?>:</strong></p>
                <p class="text-dark bg-light p-2 rounded-3"><?= Html::encode($analysis->business_vision) ?></p>
            </div>
        <?php endif; ?>
        <?php if ($analysis->target_customers): ?>
            <div class="col-md-6">
                <p class="mb-0"><strong><?= Yii::t('app', 'fursa.field.target_customers') ?>:</strong> <?= Html::encode($analysis->target_customers) ?></p>
            </div>
        <?php endif; ?>
        <?php if ($analysis->starting_budget): ?>
            <div class="col-md-6">
                <p class="mb-0"><strong><?= Yii::t('app', 'fursa.field.starting_budget') ?>:</strong> <span class="text-success fw-bold">TSh <?= number_format($analysis->starting_budget) ?></span></p>
            </div>
        <?php endif; ?>
        <?php if ($analysis->space_size_needed): ?>
            <div class="col-md-6">
                <p class="mb-0"><strong><?= Yii::t('app', 'fursa.field.space_size') ?>:</strong> <?= Html::encode($analysis->space_size_needed) ?></p>
            </div>
        <?php endif; ?>
        <?php if ($analysis->special_requirements): ?>
            <div class="col-md-6">
                <p class="mb-0"><strong><?= Yii::t('app', 'fursa.field.special_requirements') ?>:</strong> <?= Html::encode($analysis->special_requirements) ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($results['categories'])): ?>
    <?= $this->render('_results', [
        'results' => $results,
        'ward' => $analysis->ward,
        'budget' => $analysis->rental_budget,
        'selectedLocationId' => $analysis->location_id,
    ]) ?>
<?php else: ?>
    <div class="alert alert-warning p-4 rounded-4 shadow-sm"><?= Yii::t('app', 'fursa.view.no_snapshot') ?></div>
<?php endif; ?>
