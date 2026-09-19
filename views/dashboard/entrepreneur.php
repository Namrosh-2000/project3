<?php

/** @var yii\web\View $this */
/** @var app\models\User $identity */
/** @var int $analysisCount */
/** @var int $favoritesCount */
/** @var int $bookingsCount */
/** @var int $inquiriesCount */
/** @var array $recentAnalyses */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'dash.entrepreneur.title') . ' | MachoMtaa';
?>

<div class="mm-dash-head d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h1 class="h3 mb-0"><?= Html::encode(Yii::t('app', 'dash.entrepreneur.title')) ?></h1>
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                <i class="bi bi-person-check-fill me-1"></i><?= Html::encode(Yii::t('app', 'Entrepreneur')) ?>
            </span>
        </div>
        <p class="text-muted mb-0"><?= Html::encode(Yii::t('app', 'dash.entrepreneur.sub')) ?></p>
    </div>
    <div class="d-flex gap-2">
        <?= Html::a(
            '<i class="bi bi-graph-up-arrow me-1"></i> ' . Html::encode(Yii::t('app', 'Analyze My Market')),
            ['/business/index'],
            ['class' => 'btn btn-warning fw-bold']
        ) ?>
    </div>
</div>

<!-- Muhtasari (Overview Stat Cards) -->
<div class="row g-3 my-3">
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white border rounded-3 h-100 shadow-sm">
            <div class="text-muted small text-uppercase fw-bold"><?= Html::encode(Yii::t('app', 'dash.stat_analyses')) ?></div>
            <div class="d-flex align-items-baseline justify-content-between mt-2">
                <span class="h2 mb-0 fw-bold text-dark"><?= (int)$analysisCount ?></span>
                <i class="bi bi-graph-up text-teal fs-3"></i>
            </div>
            <div class="small mt-1">
                <?= Html::a(Html::encode(Yii::t('app', 'fursa.history.open')) . ' →', ['/business/history'], ['class' => 'text-decoration-none']) ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white border rounded-3 h-100 shadow-sm">
            <div class="text-muted small text-uppercase fw-bold"><?= Html::encode(Yii::t('app', 'dash.stat_favorites')) ?></div>
            <div class="d-flex align-items-baseline justify-content-between mt-2">
                <span class="h2 mb-0 fw-bold text-dark"><?= (int)$favoritesCount ?></span>
                <i class="bi bi-heart text-danger fs-3"></i>
            </div>
            <div class="small mt-1">
                <?= Html::a(Html::encode(Yii::t('app', 'Search')) . ' →', ['/account/favorites'], ['class' => 'text-decoration-none']) ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white border rounded-3 h-100 shadow-sm">
            <div class="text-muted small text-uppercase fw-bold"><?= Html::encode(Yii::t('app', 'dash.stat_bookings')) ?></div>
            <div class="d-flex align-items-baseline justify-content-between mt-2">
                <span class="h2 mb-0 fw-bold text-dark"><?= (int)$bookingsCount ?></span>
                <i class="bi bi-calendar-check text-primary fs-3"></i>
            </div>
            <div class="small mt-1">
                <?= Html::a(Html::encode(Yii::t('app', 'dash.overview')) . ' →', ['/booking/index'], ['class' => 'text-decoration-none']) ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white border rounded-3 h-100 shadow-sm">
            <div class="text-muted small text-uppercase fw-bold"><?= Html::encode(Yii::t('app', 'dash.stat_inquiries')) ?></div>
            <div class="d-flex align-items-baseline justify-content-between mt-2">
                <span class="h2 mb-0 fw-bold text-dark"><?= (int)$inquiriesCount ?></span>
                <i class="bi bi-chat-dots text-warning fs-3"></i>
            </div>
            <div class="small mt-1">
                <?= Html::a(Html::encode(Yii::t('app', 'Messages')) . ' →', ['/account/inquiries'], ['class' => 'text-decoration-none']) ?>
            </div>
        </div>
    </div>
</div>

<!-- Main Action Modules -->
<div class="mm-dash-grid my-4">
    <?= Html::a(
        '<i class="bi bi-graph-up-arrow"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.opportunities')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.opportunities_desc')) . '</span>',
        ['/business/index'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-clock-history"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.history')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.history_desc')) . '</span>',
        ['/business/history'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-heart"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.saved')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.saved_desc')) . '</span>',
        ['/account/favorites'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-calendar-check"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.bookings')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.bookings_desc')) . '</span>',
        ['/booking/index'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-bell"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.notifications')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.notifications_desc')) . '</span>',
        ['/account/inquiries'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-person"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.profile')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.profile_desc')) . '</span>',
        ['/profile/index'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>
</div>

<!-- Quick Section: Recent Market Analyses -->
<?php if (!empty($recentAnalyses)): ?>
    <div class="mt-4 p-3 bg-white border rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold"><?= Html::encode(Yii::t('app', 'fursa.history.title')) ?></h5>
            <?= Html::a(Html::encode(Yii::t('app', 'fursa.history.new_analysis')) . ' →', ['/business/index'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
        </div>
        <div class="list-group list-group-flush">
            <?php foreach ($recentAnalyses as $ana): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                    <div>
                        <strong><?= Html::encode($ana->business_type ?: 'Biashara') ?></strong>
                        <div class="text-muted small">
                            <i class="bi bi-geo-alt"></i> <?= Html::encode($ana->ward ?: 'Kinondoni') ?> &bull; 
                            <?= date('d M Y, H:i', $ana->created_at) ?>
                        </div>
                    </div>
                    <?= Html::a(Html::encode(Yii::t('app', 'fursa.history.open')), ['/business/view', 'id' => $ana->id], ['class' => 'btn btn-sm btn-light border']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
