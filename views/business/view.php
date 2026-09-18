<?php

/** @var yii\web\View $this */
/** @var app\models\BusinessAnalysis $analysis */
/** @var array $results */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'fursa.view.title', ['type' => $analysis->business_type]);
?>

<div class="el-page-header mm-fursa-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-file-earmark-bar-graph text-warning me-2"></i><?= Html::encode($analysis->business_type) ?></h1>
            <p>
                <i class="bi bi-geo-alt-fill"></i> <?= Html::encode($analysis->ward) ?>
                &middot; <?= Yii::$app->formatter->asDate($analysis->created_at, 'medium') ?>
                &middot; <?= Html::encode($analysis->statusLabel) ?>
            </p>
        </div>
        <a href="<?= Url::to(['/business/history']) ?>" class="btn btn-outline-light btn-sm fw-bold">
            <i class="bi bi-arrow-left me-1"></i> <?= Yii::t('app', 'fursa.history.title') ?>
        </a>
    </div>
</div>

<div class="mm-history-detail-meta">
    <?php if ($analysis->business_vision): ?>
        <p><strong><?= Yii::t('app', 'fursa.field.business_vision') ?>:</strong> <?= Html::encode($analysis->business_vision) ?></p>
    <?php endif; ?>
    <?php if ($analysis->target_customers): ?>
        <p><strong><?= Yii::t('app', 'fursa.field.target_customers') ?>:</strong> <?= Html::encode($analysis->target_customers) ?></p>
    <?php endif; ?>
    <?php if ($analysis->starting_budget): ?>
        <p><strong><?= Yii::t('app', 'fursa.field.starting_budget') ?>:</strong> TSh <?= number_format($analysis->starting_budget) ?></p>
    <?php endif; ?>
    <?php if ($analysis->space_size_needed): ?>
        <p><strong><?= Yii::t('app', 'fursa.field.space_size') ?>:</strong> <?= Html::encode($analysis->space_size_needed) ?></p>
    <?php endif; ?>
    <?php if ($analysis->special_requirements): ?>
        <p><strong><?= Yii::t('app', 'fursa.field.special_requirements') ?>:</strong> <?= Html::encode($analysis->special_requirements) ?></p>
    <?php endif; ?>
</div>

<?php if (!empty($results['categories'])): ?>
    <?= $this->render('_results', [
        'results' => $results,
        'ward' => $analysis->ward,
        'budget' => $analysis->rental_budget,
        'selectedLocationId' => $analysis->location_id,
    ]) ?>
<?php else: ?>
    <div class="alert alert-warning"><?= Yii::t('app', 'fursa.view.no_snapshot') ?></div>
<?php endif; ?>
