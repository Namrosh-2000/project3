<?php

/** @var yii\web\View $this */
/** @var app\models\Property $model */
/** @var bool $isFavorite */
/** @var array $related */
/** @var app\models\Report $reportModel */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\helpers\Url;

$this->registerCssFile('@web/css/property_view.css');

$this->title = $model->title;

$features = [
    'Furnished' => $model->is_furnished,
    'Parking Space' => $model->has_parking,
    'Running Water' => $model->has_water,
    'Electricity' => $model->has_electricity,
    'Security Fencing' => $model->has_security,
    'High-speed Internet' => $model->has_internet,
];
?>

<div class="row g-4 el-view-page">
    <div class="col-lg-8">
        <div id="carouselImages" class="carousel slide mb-4 el-carousel rounded-3 overflow-hidden shadow-sm" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php $i = 0; foreach ($model->images as $img): ?>
                    <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                        <img src="<?= $img->getImageUrl() ?>" class="d-block w-100" style="height:460px; object-fit:cover">
                    </div>
                <?php $i++; endforeach; ?>
                <?php if ($i === 0): ?>
                    <div class="carousel-item active">
                        <div class="el-noimg d-flex flex-column align-items-center justify-content-center bg-light text-muted" style="height:460px">
                            <i class="bi bi-image display-1 mb-2"></i>
                            <span>No property images uploaded</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($i > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon p-3 bg-dark rounded-circle"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                    <span class="carousel-control-next-icon p-3 bg-dark rounded-circle"></span>
                </button>
            <?php endif; ?>
        </div>

        <div class="card shadow-sm p-4 mb-4 border-0">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                <div>
                    <h2 class="el-view-title fw-bold text-dark mb-1">
                        <?= Html::encode($model->title) ?>
                    </h2>
                    <p class="el-view-meta text-muted mb-0">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        <strong><?= Html::encode(($model->location->ward ?? 'Kinondoni') . ', ' . ($model->location->municipality ?? 'Dar es Salaam')) ?></strong>
                        &middot; <span class="badge bg-light text-dark border"><?= Html::encode($model->category->name ?? 'General') ?></span>
                        &middot; <span class="badge bg-dark text-white"><?= ucfirst($model->listing_type) ?></span>
                    </p>
                </div>
                <div class="text-end">
                    <span class="el-verified fs-6 px-3 py-2"><i class="bi bi-patch-check-fill me-1"></i> Verified Listing</span>
                </div>
            </div>

            <div class="my-3 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted d-block uppercase fw-bold">Asking Rental Price</small>
                    <h3 class="el-view-price text-primary fw-bold mb-0">
                        TSh <?= number_format((float)$model->price) ?>
                        <small class="text-muted fs-6 fw-normal">/ <?= $model->price_period ?: 'month' ?></small>
                    </h3>
                </div>
                <div>
                    <?php if ($model->is_available): ?>
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi bi-check-circle-fill me-1"></i> Available Now
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="bi bi-x-circle-fill me-1"></i> Imeshakodishwa / Imeshauzwa (Occupied / Sold)
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <hr>
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-text me-2 text-primary"></i>Description</h5>
            <div class="text-secondary leading-relaxed mb-4">
                <?= nl2br(Html::encode($model->description ?? 'No detailed description provided for this property listing.')) ?>
            </div>

            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Property Features &amp; Amenities</h5>
            <div class="row g-2 mb-4">
                <?php foreach ($features as $label => $val): ?>
                    <div class="col-md-4 col-6">
                        <div class="p-2 border rounded d-flex align-items-center gap-2 <?= $val ? 'bg-success-subtle text-success border-success' : 'bg-light text-muted' ?>">
                            <i class="bi <?= $val ? 'bi-check-circle-fill' : 'bi-x-circle' ?>"></i>
                            <span class="small font-weight-bold"><?= $label ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge me-2 text-primary"></i>Property Owner &amp; Contact</h5>
            <div class="p-3 border rounded bg-light d-flex align-items-center gap-3">
                <div class="el-avatar" style="width:54px; height:54px; font-size:1.4rem">
                    <?= Html::encode(mb_strtoupper(mb_substr($model->owner->username ?? 'O', 0, 1))) ?>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark"><?= Html::encode($model->owner->username ?? 'Verified Listing Agent') ?></h6>
                    <small class="text-muted"><i class="bi bi-shield-check text-success"></i> Registered Landlord / Agent on EneoLink</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm p-4 sticky-top border-0" style="top:90px">
            <h5 class="fw-bold mb-3"><i class="bi bi-lightning-charge text-warning me-2"></i>Take Action</h5>

            <div class="d-grid gap-3">
                <?php if ($model->is_available): ?>
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <button class="btn btn-success btn-lg fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <i class="bi bi-calendar-check-fill me-2"></i> Book Site Visit / Reserve
                        </button>
                    <?php else: ?>
                        <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-success btn-lg fw-bold shadow-sm">
                            <i class="bi bi-calendar-check-fill me-2"></i> Login to Book Visit / Reserve
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg fw-bold shadow-sm" disabled>
                        <i class="bi bi-x-circle-fill me-2"></i> Eneo Limeshakodishwa / Kuuzwa
                    </button>
                    <div class="alert alert-secondary small mb-0 text-center">
                        Eneo hili kwa sasa halipatikani tena kwa booking (Imeshakodishwa / Imeshauzwa).
                    </div>
                <?php endif; ?>

                <?php if (!Yii::$app->user->isGuest): ?>
                    <?= Html::beginForm(['/property/toggle-favorite', 'id' => $model->id], 'post') ?>
                    <button class="btn <?= $isFavorite ? 'btn-outline-danger active' : 'btn-outline-secondary' ?> w-100 fw-bold">
                        <i class="bi <?= $isFavorite ? 'bi-heart-fill' : 'bi-heart' ?> me-2"></i>
                        <?= $isFavorite ? 'Saved in Favorites' : 'Save to Favorites' ?>
                    </button>
                    <?= Html::endForm() ?>
                <?php endif; ?>

                <a class="btn btn-outline-teal w-100 fw-bold" target="_blank"
                   href="https://www.google.com/maps/dir/?api=1&destination=<?= $model->location->latitude ?? -6.7924 ?>,<?= $model->location->longitude ?? 39.2083 ?>">
                    <i class="bi bi-signpost-2-fill me-2"></i> Get GPS Directions
                </a>

                <a class="btn btn-outline-dark w-100 fw-bold" href="<?= Url::to(['/property/compare', 'ids[]' => $model->id]) ?>">
                    <i class="bi bi-bar-chart-steps me-2"></i> Add to Compare
                </a>

                <?php if (!Yii::$app->user->isGuest): ?>
                    <button class="btn btn-link text-danger btn-sm text-center border-top pt-3 mt-2" data-bs-toggle="modal" data-bs-target="#reportModal">
                        <i class="bi bi-flag me-1"></i> Report this listing to Admin
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($related)): ?>
    <hr class="my-5">
    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-grid me-2 text-primary"></i>Similar Properties in Kinondoni</h4>
    <div class="row g-4">
        <?php foreach ($related as $r): ?>
            <div class="col-lg-3 col-md-6">
                <div class="el-prop h-100 shadow-sm border">
                    <?php if ($r->coverImage): ?>
                        <img src="<?= $r->coverImage->getImageUrl() ?>" alt="<?= Html::encode($r->title) ?>" style="height:170px; object-fit:cover">
                    <?php endif; ?>
                    <div class="el-prop-body">
                        <h6 class="fw-bold mb-1 line-clamp-1"><?= Html::encode($r->title) ?></h6>
                        <div class="text-primary fw-bold">TSh <?= number_format((float)$r->price) ?></div>
                        <a href="<?= Url::to(['/property/view', 'id' => $r->id]) ?>" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!Yii::$app->user->isGuest): ?>
<?php Modal::begin(['id' => 'bookingModal', 'title' => '📅 Weka Miadi / Book Property']); ?>
<?php $bookingForm = ActiveForm::begin(['action' => ['/booking/create', 'property_id' => $model->id]]); ?>
    <div class="mb-3">
        <label class="form-label fw-bold">Aina ya Booking (Booking Type)</label>
        <select name="Booking[booking_type]" class="form-select" required>
            <option value="visit">Kukagua Eneo (Site Visit / Inspection)</option>
            <option value="reservation">Kuweka Oda ya Kupanga (Property Reservation)</option>
        </select>
    </div>
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <label class="form-label fw-bold">Tarehe ya Miadi (Date)</label>
            <input type="date" name="Booking[booking_date]" class="form-control" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">Muda wa Miadi (Time Slot)</label>
            <select name="Booking[booking_time]" class="form-select" required>
                <option value="09:00 AM">09:00 AM (Asubuhi)</option>
                <option value="11:00 AM">11:00 AM (Mchana wa Mapema)</option>
                <option value="02:00 PM">02:00 PM (Mchana)</option>
                <option value="04:00 PM">04:00 PM (Jioni)</option>
            </select>
        </div>
    </div>
    <div class="mb-3 p-3 bg-light rounded border border-warning">
        <label class="form-label fw-bold text-dark mb-1">
            <i class="bi bi-tag-fill text-warning me-1"></i> Offa Yako ya Bei / Bargain Price (TSh) - Optional
        </label>
        <div class="small text-muted mb-2">Bei inayotakiwa na mmiliki: <strong>TSh <?= number_format((float)$model->price) ?> / <?= Html::encode($model->price_period ?: 'month') ?></strong>. Kama ungependa kuomba punguzo, andika kiasi hapa:</div>
        <input type="number" step="5000" name="Booking[offered_price]" class="form-control form-control-lg" placeholder="Mfano: <?= number_format((float)($model->price * 0.9)) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Nambari Yako ya Simu (Phone Number)</label>
        <input type="text" name="Booking[seeker_phone]" class="form-control" placeholder="Mfano: 0755 123 456" value="<?= Html::encode(Yii::$app->user->identity->phone ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Maelezo ya Ziada / Maombi (Notes for Owner)</label>
        <textarea name="Booking[notes]" class="form-control" rows="3" placeholder="Mfano: Nitakuja na mwenzangu kagua au nina maombi ya ziada..."></textarea>
    </div>
    <button class="btn btn-success w-100 fw-bold py-2 fs-6">
        <i class="bi bi-calendar-plus-fill me-1"></i> Tuma Ombi la Booking (Submit Booking Request)
    </button>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>

<?php Modal::begin(['id' => 'reportModal', 'title' => 'Report Listing']); ?>
<?php $reportForm = ActiveForm::begin(['action' => ['/property/report', 'id' => $model->id]]); ?>
    <?= $reportForm->field($reportModel, 'reason')->dropDownList([
        'fake' => 'Property fake / Fraudulent',
        'duplicate' => 'Duplicate listing',
        'misleading' => 'Misleading pricing or info',
        'other' => 'Other issue',
    ])->label('Reason for Report') ?>
    <?= $reportForm->field($reportModel, 'details')->textarea(['rows' => 3])->label('Additional Details') ?>
    <button class="btn btn-danger w-100 fw-bold"><i class="bi bi-flag me-1"></i> Submit Report</button>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>
<?php endif; ?>