<?php

/** @var yii\web\View $this */
/** @var app\models\BusinessAnalysis[] $items */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'fursa.history.title');
?>

<div class="el-page-header mm-fursa-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-clock-history text-warning me-2"></i><?= Html::encode(Yii::t('app', 'fursa.history.title')) ?></h1>
            <p><?= Yii::t('app', 'fursa.history.sub') ?></p>
        </div>
        <a href="<?= Url::to(['/business/index']) ?>" class="btn btn-warning fw-bold">
            <i class="bi bi-lightbulb-fill me-1"></i> <?= Yii::t('app', 'fursa.history.new_analysis') ?>
        </a>
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
                    <span class="mm-history-ward"><i class="bi bi-geo-alt-fill"></i> <?= Html::encode($item->ward) ?></span>
                </div>
                <div class="mm-history-meta">
                    <span class="mm-history-date"><?= Yii::$app->formatter->asDate($item->created_at, 'medium') ?></span>
                    <span class="mm-history-status"><?= Html::encode($item->statusLabel) ?></span>
                </div>
                <a href="<?= Url::to(['/business/view', 'id' => $item->id]) ?>" class="mm-btn-secondary mm-history-open">
                    <?= Yii::t('app', 'fursa.history.open') ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
