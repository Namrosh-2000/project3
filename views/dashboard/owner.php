<?php

/** @var yii\web\View $this */
/** @var app\models\User $identity */
/** @var int $listingsCount */
/** @var int $activeListingsCount */
/** @var int $pendingBookingsCount */
/** @var int $totalBookingsCount */
/** @var int $inquiriesCount */
/** @var array $recentListings */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'dash.owner.title') . ' | MachoMtaa';
$isAgent = ($identity->role === app\models\User::ROLE_AGENT);
?>

<div class="mm-dash-head d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h1 class="h3 mb-0"><?= Html::encode(Yii::t('app', 'dash.owner.title')) ?></h1>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                <i class="bi bi-building me-1"></i><?= Html::encode($isAgent ? Yii::t('app', 'dash.badge_agent') : Yii::t('app', 'Property Owner')) ?>
            </span>
        </div>
        <p class="text-muted mb-0"><?= Html::encode(Yii::t('app', 'dash.owner.sub')) ?></p>
    </div>
    <div class="d-flex gap-2">
        <?= Html::a(
            '<i class="bi bi-plus-square me-1"></i> ' . Html::encode(Yii::t('app', 'Add Listing')),
            ['/property-submission/create'],
            ['class' => 'btn btn-warning fw-bold']
        ) ?>
    </div>
</div>

<!-- Muhtasari (Overview Stat Cards) -->
<div class="row g-3 my-3">
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white border rounded-3 h-100 shadow-sm">
            <div class="text-muted small text-uppercase fw-bold"><?= Html::encode(Yii::t('app', 'dash.stat_listings')) ?></div>
            <div class="d-flex align-items-baseline justify-content-between mt-2">
                <span class="h2 mb-0 fw-bold text-dark"><?= (int)$listingsCount ?></span>
                <i class="bi bi-building text-teal fs-3"></i>
            </div>
            <div class="small mt-1">
                <?= Html::a(Html::encode(Yii::t('app', 'My Listings')) . ' →', ['/account/listings'], ['class' => 'text-decoration-none']) ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white border rounded-3 h-100 shadow-sm">
            <div class="text-muted small text-uppercase fw-bold"><?= Html::encode(Yii::t('app', 'dash.stat_active_listings')) ?></div>
            <div class="d-flex align-items-baseline justify-content-between mt-2">
                <span class="h2 mb-0 fw-bold text-dark"><?= (int)$activeListingsCount ?></span>
                <i class="bi bi-check-circle text-success fs-3"></i>
            </div>
            <div class="small mt-1">
                <span class="badge bg-success bg-opacity-10 text-success"><?= Html::encode(Yii::t('app', 'Available')) ?></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white border rounded-3 h-100 shadow-sm">
            <div class="text-muted small text-uppercase fw-bold"><?= Html::encode(Yii::t('app', 'dash.stat_pending_bookings')) ?></div>
            <div class="d-flex align-items-baseline justify-content-between mt-2">
                <span class="h2 mb-0 fw-bold text-dark"><?= (int)$pendingBookingsCount ?></span>
                <i class="bi bi-calendar-event text-warning fs-3"></i>
            </div>
            <div class="small mt-1">
                <?= Html::a(Html::encode(Yii::t('app', 'Booking')) . ' →', ['/booking/owner'], ['class' => 'text-decoration-none']) ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white border rounded-3 h-100 shadow-sm">
            <div class="text-muted small text-uppercase fw-bold"><?= Html::encode(Yii::t('app', 'dash.stat_inquiries')) ?></div>
            <div class="d-flex align-items-baseline justify-content-between mt-2">
                <span class="h2 mb-0 fw-bold text-dark"><?= (int)$inquiriesCount ?></span>
                <i class="bi bi-chat-dots text-primary fs-3"></i>
            </div>
            <div class="small mt-1">
                <?= Html::a(Html::encode(Yii::t('app', 'Inquiry')) . ' →', ['/account/inquiries'], ['class' => 'text-decoration-none']) ?>
            </div>
        </div>
    </div>
</div>

<!-- Main Action Modules -->
<div class="mm-dash-grid my-4">
    <?= Html::a(
        '<i class="bi bi-building"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.listings')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.listings_desc')) . '</span>',
        ['/account/listings'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-plus-square"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.add_listing')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.add_listing_desc')) . '</span>',
        ['/property-submission/create'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-chat-dots"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.inquiries')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.inquiries_desc')) . '</span>',
        ['/account/inquiries'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-door-open"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.viewings')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.viewings_desc')) . '</span>',
        ['/account/inquiries'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-envelope"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.messages')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.messages_desc')) . '</span>',
        ['/account/inquiries'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-calendar-check"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.bookings')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.bookings_desc')) . '</span>',
        ['/booking/owner'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <div class="mm-dash-card">
        <i class="bi bi-patch-check text-success"></i>
        <strong><?= Html::encode(Yii::t('app', 'dash.owner.verification')) ?></strong>
        <span><?= Html::encode(Yii::t('app', 'dash.owner.verification_desc')) ?></span>
        <span class="badge bg-success bg-opacity-10 text-success align-self-start mt-2">
            <i class="bi bi-shield-check me-1"></i><?= Html::encode(Yii::t('app', 'dash.stat_verified')) ?>
        </span>
    </div>

    <?= Html::a(
        '<i class="bi bi-person"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.profile')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.profile_desc')) . '</span>',
        ['/profile/index'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>
</div>

<!-- Quick Section: Recent Listings -->
<?php if (!empty($recentListings)): ?>
    <div class="mt-4 p-3 bg-white border rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold"><?= Html::encode(Yii::t('app', 'dash.owner.listings')) ?></h5>
            <?= Html::a(Html::encode(Yii::t('app', 'dash.overview')) . ' →', ['/account/listings'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
        </div>
        <div class="list-group list-group-flush">
            <?php foreach ($recentListings as $prop): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                    <div>
                        <strong><?= Html::encode($prop->title) ?></strong>
                        <div class="text-muted small">
                            <i class="bi bi-geo-alt"></i> <?= Html::encode($prop->location->ward ?? 'Kinondoni') ?> &bull; 
                            <?= number_format($prop->price) ?> TZS / <?= Html::encode($prop->price_period) ?>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <?= Html::a(Html::encode(Yii::t('app', 'Edit')), ['/property-submission/update', 'id' => $prop->id], ['class' => 'btn btn-sm btn-light border']) ?>
                        <?= Html::a(Html::encode(Yii::t('app', 'fursa.history.open')), ['/property/view', 'id' => $prop->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
