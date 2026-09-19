<?php

/** @var yii\web\View $this */
/** @var array $properties */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'owner_listings.title') . ' — MachoMtaa';

$totalCount = count($properties);
$verifiedCount = 0;
$pendingCount = 0;

foreach ($properties as $p) {
    if ($p->status === \app\models\Property::STATUS_VERIFIED) {
        $verifiedCount++;
    } elseif ($p->status === \app\models\Property::STATUS_PENDING) {
        $pendingCount++;
    }
}
?>

<!-- Stripe-Grade Hero Header -->
<div class="mm-fursa-hero">
    <div class="mm-fursa-hero-inner">
        <div class="mm-fursa-eyebrow">
            <i class="bi bi-houses-fill"></i> <?= Html::encode(Yii::t('app', 'submission.eyebrow')) ?>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mm-fursa-hero-title mb-2"><?= Html::encode(Yii::t('app', 'owner_listings.title')) ?></h1>
                <p class="mm-fursa-hero-lead mb-3">
                    <?= Html::encode(Yii::t('app', 'owner_listings.sub')) ?>
                </p>
                <div class="mm-fursa-stats-strip">
                    <span class="mm-fursa-stat-pill">
                        <i class="bi bi-buildings"></i> <?= Yii::t('app', 'owner_listings.stat_all') ?>: <strong><?= $totalCount ?></strong>
                    </span>
                    <span class="mm-fursa-stat-pill">
                        <i class="bi bi-patch-check-fill text-teal"></i> <?= Yii::t('app', 'owner_listings.stat_verified') ?>: <strong><?= $verifiedCount ?></strong>
                    </span>
                    <span class="mm-fursa-stat-pill">
                        <i class="bi bi-hourglass-split text-warning"></i> <?= Yii::t('app', 'owner_listings.stat_pending') ?>: <strong><?= $pendingCount ?></strong>
                    </span>
                </div>
            </div>
            <a href="<?= Url::to(['/property-submission/create']) ?>" class="mm-submit-btn text-decoration-none py-3 px-4">
                <i class="bi bi-plus-circle-fill"></i> <?= Yii::t('app', 'owner_listings.btn_add') ?>
            </a>
        </div>
    </div>
</div>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <span><?= Yii::$app->session->getFlash('success') ?></span>
    </div>
<?php endif; ?>

<?php if (empty($properties)): ?>
    <div class="mm-empty-state">
        <i class="bi bi-house-add"></i>
        <h5><?= Yii::t('app', 'owner_listings.empty_title') ?></h5>
        <p class="max-w-md mx-auto"><?= Yii::t('app', 'owner_listings.empty_sub') ?></p>
        <a href="<?= Url::to(['/property-submission/create']) ?>" class="mm-submit-btn text-decoration-none">
            <i class="bi bi-plus-lg me-1"></i> <?= Yii::t('app', 'owner_listings.btn_create_first') ?>
        </a>
    </div>
<?php else: ?>
    <div class="row g-4 mb-5">
        <?php foreach ($properties as $p): ?>
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
                    <div class="row g-0 h-100">
                        <div class="col-md-5 position-relative">
                            <?php if ($p->coverImage): ?>
                                <img src="<?= $p->coverImage->getImageUrl() ?>" alt="<?= Html::encode($p->title) ?>" class="w-100 h-100" style="object-fit:cover; min-height:180px">
                            <?php else: ?>
                                <div class="bg-light text-muted d-flex align-items-center justify-content-center h-100" style="min-height:180px">
                                    <i class="bi bi-shop text-teal" style="font-size: 2.5rem"></i>
                                </div>
                            <?php endif; ?>

                            <div class="position-absolute top-0 start-0 m-2">
                                <?php if ($p->status === \app\models\Property::STATUS_VERIFIED): ?>
                                    <span class="badge bg-teal text-white shadow-sm px-2 py-1 rounded-pill">
                                        <i class="bi bi-patch-check-fill me-1"></i> Imethibitishwa
                                    </span>
                                <?php elseif ($p->status === \app\models\Property::STATUS_PENDING): ?>
                                    <span class="badge bg-warning text-dark shadow-sm px-2 py-1 rounded-pill">
                                        <i class="bi bi-hourglass-split me-1"></i> Inasubiri Ukaguzi
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white shadow-sm px-2 py-1 rounded-pill">
                                        <i class="bi bi-x-circle me-1"></i> Imekataliwa
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                        <h5 class="fw-bold mb-0 text-dark line-clamp-1"><?= Html::encode($p->title) ?></h5>
                                        <?php if (!$p->is_available): ?>
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">Imekodishwa</span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success rounded-pill">Ipo Wazi</span>
                                        <?php endif; ?>
                                    </div>

                                    <small class="text-muted d-block mb-2">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> Kata ya <?= Html::encode($p->location->ward ?? 'Kinondoni') ?>
                                        &bull; <?= Html::encode($p->category->name ?? 'Fremu') ?>
                                    </small>

                                    <div class="fw-bold text-success fs-5 mb-2">
                                        TSh <?= number_format((float)$p->price) ?>
                                        <small class="text-muted fw-normal fs-6">/ <?= $p->price_period ?: 'mwezi' ?></small>
                                    </div>
                                </div>

                                <div class="pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <a href="<?= Url::to(['/property/view', 'id' => $p->id]) ?>" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3" target="_blank">
                                        <i class="bi bi-eye"></i> Tazama
                                    </a>
                                    <div class="d-flex gap-2">
                                        <a href="<?= Url::to(['/property-submission/update', 'id' => $p->id]) ?>" class="btn btn-sm btn-outline-secondary fw-bold rounded-pill px-3">
                                            <i class="bi bi-pencil"></i> Hariri
                                        </a>
                                        <?= Html::beginForm(['/property-submission/delete', 'id' => $p->id], 'post', ['class' => 'd-inline', 'onsubmit' => 'return confirm("Je, una uhakika unataka kufuta tangazo hili la fremu?");']) ?>
                                            <button class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3">
                                                <i class="bi bi-trash"></i> Futa
                                            </button>
                                        <?= Html::endForm() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>