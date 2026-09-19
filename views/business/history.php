<?php

/** @var yii\web\View $this */
/** @var app\models\BusinessAnalysis[] $items */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'fursa.history.title') . ' — MachoMtaa';
?>

<div class="mm-fursa-hero">
    <div class="mm-fursa-hero-inner">
        <div class="mm-fursa-eyebrow">
            <i class="bi bi-clock-history"></i> <?= Html::encode(Yii::t('app', 'fursa.history.title')) ?>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mm-fursa-hero-title mb-2"><?= Html::encode(Yii::t('app', 'fursa.history.title')) ?></h1>
                <p class="mm-fursa-hero-lead mb-0"><?= Html::encode(Yii::t('app', 'fursa.history.sub')) ?></p>
            </div>
            <a href="<?= Url::to(['/business/index']) ?>" class="mm-submit-btn text-decoration-none py-2 px-4 fs-6">
                <i class="bi bi-lightbulb-fill"></i> <?= Yii::t('app', 'fursa.history.new_analysis') ?>
            </a>
        </div>
    </div>
</div>

<?php if (empty($items)): ?>
    <div class="mm-empty-state">
        <i class="bi bi-inboxes"></i>
        <h5><?= Yii::t('app', 'fursa.history.empty_title') ?></h5>
        <p><?= Yii::t('app', 'fursa.history.empty_body') ?></p>
        <a href="<?= Url::to(['/business/index']) ?>" class="mm-btn-primary"><?= Yii::t('app', 'fursa.history.new_analysis') ?></a>
    </div>
<?php else: ?>
    <div class="mm-history-list">
        <?php foreach ($items as $item): ?>
            <div class="mm-history-row">
                <div class="mm-history-main">
                    <strong><?= Html::encode($item->business_type) ?></strong>
                    <span class="mm-history-ward"><i class="bi bi-geo-alt-fill text-danger"></i> Kata ya <?= Html::encode($item->ward) ?></span>
                </div>
                <div class="mm-history-meta">
                    <span class="mm-history-date"><?= Yii::$app->formatter->asDate($item->created_at, 'medium') ?></span>
                    <span class="mm-history-status"><?= Html::encode($item->statusLabel) ?></span>
                </div>
                <a href="<?= Url::to(['/business/view', 'id' => $item->id]) ?>" class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3">
                    <i class="bi bi-eye-fill me-1"></i> <?= Yii::t('app', 'fursa.history.open') ?> &rarr;
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
