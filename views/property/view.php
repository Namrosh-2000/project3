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

$this->title = $model->title . ' — MachoMtaa';

$features = [
    'Maji Safi ya Uhakika' => $model->has_water,
    'Umeme wa Uhakika (LUKU)' => $model->has_electricity,
    'Maegesho ya Magari (Parking)' => $model->has_parking,
    'Ulinzi & Fensi (Security)' => $model->has_security,
    'Mtandao wa Kasi (WiFi)' => $model->has_internet,
    'Fremu Imerekebishwa (Furnished)' => $model->is_furnished,
];

$wardName = $model->location->ward ?? 'Kinondoni';
?>

<div class="row g-4 el-view-page">
    <div class="col-lg-8">
        <!-- Gallery / Carousel -->
        <div id="carouselImages" class="carousel slide mb-4 rounded-4 overflow-hidden shadow-sm" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php $i = 0; foreach ($model->images as $img): ?>
                    <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                        <img src="<?= $img->getImageUrl() ?>" class="d-block w-100" style="height:460px; object-fit:cover">
                    </div>
                <?php $i++; endforeach; ?>
                <?php if ($i === 0): ?>
                    <div class="carousel-item active">
                        <div class="d-flex flex-column align-items-center justify-content-center bg-light text-muted" style="height:460px">
                            <i class="bi bi-shop display-1 mb-2 text-teal"></i>
                            <span class="fw-bold">Picha za Fremu Hazijawekwa</span>
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

        <!-- Main Property Details Card -->
        <div class="card shadow-sm p-4 p-md-5 mb-4 border-0 rounded-4" style="border: 1px solid #e2e8f0 !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <?= Html::encode($model->title) ?>
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        <strong>Kata ya <?= Html::encode($wardName) ?>, <?= Html::encode($model->location->municipality ?? 'Kinondoni') ?></strong>
                        &middot; <span class="badge bg-light text-dark border"><?= Html::encode($model->category->name ?? 'Fremu ya Biashara') ?></span>
                        &middot; <span class="badge bg-dark text-white"><?= ucfirst($model->listing_type) ?></span>
                    </p>
                </div>
                <div>
                    <span class="badge bg-teal-subtle text-teal fs-6 px-3 py-2 rounded-pill fw-bold">
                        <i class="bi bi-patch-check-fill me-1"></i> <?= Yii::t('app', 'fremu.verified_badge') ?>
                    </span>
                </div>
            </div>

            <!-- Price & Availability Pill -->
            <div class="my-4 p-3 bg-light rounded-4 border d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <small class="text-muted d-block text-uppercase fw-bold"><?= Yii::t('app', 'fremu.detail_asking_price') ?></small>
                    <h3 class="text-success fw-bold mb-0">
                        TSh <?= number_format((float)$model->price) ?>
                        <small class="text-muted fs-6 fw-normal">/ <?= $model->price_period ?: 'mwezi' ?></small>
                    </h3>
                </div>
                <div>
                    <?php if ($model->is_available): ?>
                        <span class="badge bg-success fs-6 px-3 py-2 rounded-pill">
                            <i class="bi bi-check-circle-fill me-1"></i> <?= Yii::t('app', 'fremu.detail_available') ?>
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill">
                            <i class="bi bi-x-circle-fill me-1"></i> <?= Yii::t('app', 'fremu.detail_occupied') ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Market Intelligence Callout Box -->
            <div class="card p-3 rounded-4 mb-4 border-0" style="background: linear-gradient(135deg, rgba(14,110,92,0.08) 0%, rgba(226,163,61,0.08) 100%); border: 1px solid rgba(14,110,92,0.2) !important;">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-graph-up-arrow text-teal fs-4"></i>
                        <div>
                            <strong class="d-block text-dark"><?= Yii::t('app', 'fremu.detail_fursa_link') ?></strong>
                            <small class="text-muted">Tazama mahitaji, ushindani na viashiria vya kibiashara vya kata ya <?= Html::encode($wardName) ?> kabla ya kulipa kodi.</small>
                        </div>
                    </div>
                    <a href="<?= Url::to(['/business/index', 'ward' => $wardName, 'budget' => $model->price]) ?>" class="btn btn-warning btn-sm fw-bold px-3 py-2 rounded-pill text-dark shadow-sm">
                        <i class="bi bi-stars me-1"></i> <?= Yii::t('app', 'nav.fursa') ?>
                    </a>
                </div>
            </div>

            <hr class="my-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-text me-2 text-teal"></i><?= Yii::t('app', 'fremu.detail_description') ?></h5>
            <div class="text-secondary leading-relaxed mb-4">
                <?= nl2br(Html::encode($model->description ?? 'Hakuna maelezo ya ziada yaliyowekwa kwa fremu hii.')) ?>
            </div>

            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-grid-3x3-gap me-2 text-teal"></i><?= Yii::t('app', 'fremu.detail_features') ?></h5>
            <div class="row g-2 mb-4">
                <?php foreach ($features as $label => $val): ?>
                    <div class="col-md-4 col-6">
                        <div class="p-2 border rounded-3 d-flex align-items-center gap-2 <?= $val ? 'bg-success-subtle text-success border-success' : 'bg-light text-muted' ?>">
                            <i class="bi <?= $val ? 'bi-check-circle-fill' : 'bi-x-circle' ?>"></i>
                            <span class="small fw-bold"><?= $label ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge me-2 text-teal"></i><?= Yii::t('app', 'fremu.detail_owner') ?></h5>
            <div class="p-3 border rounded-4 bg-light d-flex align-items-center gap-3">
                <div class="el-avatar bg-teal text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:54px; height:54px; font-size:1.4rem">
                    <?= Html::encode(mb_strtoupper(mb_substr($model->owner->username ?? 'W', 0, 1))) ?>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark"><?= Html::encode($model->owner->username ?? 'Mmiliki wa Fremu') ?></h6>
                    <small class="text-muted"><i class="bi bi-shield-check text-success"></i> <?= Yii::t('app', 'fremu.detail_registered_owner') ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Sidebar -->
    <div class="col-lg-4">
        <div class="card shadow-sm p-4 sticky-top border-0 rounded-4" style="top:90px; border: 1px solid #e2e8f0 !important;">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-lightning-charge text-warning me-2"></i>Chukua Hatua</h5>

            <div class="d-grid gap-3">
                <?php if ($model->is_available): ?>
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <button class="btn btn-success btn-lg fw-bold shadow-sm rounded-3 py-3" data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <i class="bi bi-calendar-check-fill me-2"></i> <?= Yii::t('app', 'fremu.book_visit') ?>
                        </button>
                        <?php if ($model->owner_id !== Yii::$app->user->id): ?>
                            <button class="btn btn-outline-primary fw-bold rounded-3 py-2" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                                <i class="bi bi-chat-dots-fill me-2"></i> <?= Yii::t('app', 'fremu.ask_question') ?>
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= Url::to(['/site/login', 'returnUrl' => Url::to(['/property/view', 'id' => $model->id])]) ?>" class="btn btn-success btn-lg fw-bold shadow-sm rounded-3 py-3">
                            <i class="bi bi-calendar-check-fill me-2"></i> Ingia Kuweka Miadi
                        </a>
                        <a href="<?= Url::to(['/site/login', 'returnUrl' => Url::to(['/property/view', 'id' => $model->id])]) ?>" class="btn btn-outline-primary fw-bold rounded-3 py-2">
                            <i class="bi bi-chat-dots-fill me-2"></i> Ingia Kutuma Ujumbe
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg fw-bold shadow-sm rounded-3" disabled>
                        <i class="bi bi-x-circle-fill me-2"></i> <?= Yii::t('app', 'fremu.detail_occupied') ?>
                    </button>
                    <div class="alert alert-secondary small mb-0 text-center rounded-3">
                        Eneo hili kwa sasa halipatikani tena kwa booking (Imeshakodishwa / Imeshauzwa).
                    </div>
                <?php endif; ?>

                <?php if (!Yii::$app->user->isGuest): ?>
                    <?= Html::beginForm(['/property/toggle-favorite', 'id' => $model->id], 'post') ?>
                    <button class="btn <?= $isFavorite ? 'btn-outline-danger active' : 'btn-outline-secondary' ?> w-100 fw-bold rounded-3">
                        <i class="bi <?= $isFavorite ? 'bi-heart-fill' : 'bi-heart' ?> me-2"></i>
                        <?= $isFavorite ? 'Imehifadhiwa Kwenye Vipendwa' : 'Hifadhi Kwenye Vipendwa' ?>
                    </button>
                    <?= Html::endForm() ?>
                <?php endif; ?>

                <a class="btn btn-outline-teal w-100 fw-bold rounded-3" target="_blank"
                   href="https://www.google.com/maps/dir/?api=1&destination=<?= $model->location->latitude ?? -6.7924 ?>,<?= $model->location->longitude ?? 39.2083 ?>">
                    <i class="bi bi-signpost-2-fill me-2"></i> Maelekezo ya Ramani (GPS)
                </a>

                <a class="btn btn-outline-dark w-100 fw-bold rounded-3" href="<?= Url::to(['/property/compare', 'ids[]' => $model->id]) ?>">
                    <i class="bi bi-bar-chart-steps me-2"></i> Linganisha Fremu Hii
                </a>

                <?php if (!Yii::$app->user->isGuest): ?>
                    <button class="btn btn-link text-danger btn-sm text-center border-top pt-3 mt-2 text-decoration-none" data-bs-toggle="modal" data-bs-target="#reportModal">
                        <i class="bi bi-flag me-1"></i> Ripoti Tangazo Hili kwa Admin
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Related Properties -->
<?php if (!empty($related)): ?>
    <hr class="my-5">
    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-grid me-2 text-teal"></i><?= Yii::t('app', 'fremu.detail_related') ?></h4>
    <div class="row g-4">
        <?php foreach ($related as $r): ?>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-lift" style="border: 1px solid #e2e8f0 !important;">
                    <?php if ($r->coverImage): ?>
                        <img src="<?= $r->coverImage->getImageUrl() ?>" alt="<?= Html::encode($r->title) ?>" style="height:170px; object-fit:cover" class="w-100">
                    <?php endif; ?>
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-1 text-truncate"><?= Html::encode($r->title) ?></h6>
                        <div class="text-success fw-bold">TSh <?= number_format((float)$r->price) ?></div>
                        <a href="<?= Url::to(['/property/view', 'id' => $r->id]) ?>" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Booking Modal -->
<?php if (!Yii::$app->user->isGuest): ?>
<?php Modal::begin(['id' => 'bookingModal', 'title' => '📅 Weka Miadi / Book Property']); ?>
<?php $bookingForm = ActiveForm::begin(['action' => ['/booking/create', 'property_id' => $model->id]]); ?>
    <div class="mb-3">
        <label class="form-label fw-bold">Aina ya Booking (Booking Type)</label>
        <select name="Booking[booking_type]" class="form-select mm-form-select" required>
            <option value="visit">Kukagua Eneo (Site Visit / Inspection)</option>
            <option value="reservation">Kuweka Oda ya Kupanga (Property Reservation)</option>
        </select>
    </div>
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <label class="form-label fw-bold">Tarehe ya Miadi (Date)</label>
            <input type="date" name="Booking[booking_date]" class="form-control mm-form-control" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">Muda wa Miadi (Time Slot)</label>
            <select name="Booking[booking_time]" class="form-select mm-form-select" required>
                <option value="09:00 AM">09:00 AM (Asubuhi)</option>
                <option value="11:00 AM">11:00 AM (Mchana wa Mapema)</option>
                <option value="02:00 PM">02:00 PM (Mchana)</option>
                <option value="04:00 PM">04:00 PM (Jioni)</option>
            </select>
        </div>
    </div>
    <div class="mb-3 p-3 bg-light rounded-3 border border-warning">
        <label class="form-label fw-bold text-dark mb-1">
            <i class="bi bi-tag-fill text-warning me-1"></i> Offa Yako ya Bei / Bargain Price (TSh) - Optional
        </label>
        <div class="small text-muted mb-2">Bei inayotakiwa na mmiliki: <strong>TSh <?= number_format((float)$model->price) ?> / <?= Html::encode($model->price_period ?: 'mwezi') ?></strong>. Kama ungependa kuomba punguzo, andika kiasi hapa:</div>
        <input type="number" step="5000" name="Booking[offered_price]" class="form-control mm-form-control" placeholder="Mfano: <?= number_format((float)($model->price * 0.9)) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Nambari Yako ya Simu (Phone Number)</label>
        <input type="text" name="Booking[seeker_phone]" class="form-control mm-form-control" placeholder="Mfano: 0755 123 456" value="<?= Html::encode(Yii::$app->user->identity->phone ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Maelezo ya Ziada / Maombi (Notes for Owner)</label>
        <textarea name="Booking[notes]" class="form-control mm-form-control" rows="3" placeholder="Mfano: Nitakuja na mwenzangu kagua au nina maombi ya ziada..."></textarea>
    </div>
    <button class="btn btn-success w-100 fw-bold py-3 fs-6 rounded-3 shadow-sm">
        <i class="bi bi-calendar-plus-fill me-1"></i> Tuma Ombi la Booking (Submit Booking Request)
    </button>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>

<!-- Inquiry Modal -->
<?php if ($model->owner_id !== Yii::$app->user->id): ?>
<?php Modal::begin(['id' => 'inquiryModal', 'title' => '💬 Uliza Swali / Tuma Ujumbe kwa Mmiliki']); ?>
<?= Html::beginForm(['/property/contact', 'id' => $model->id], 'post') ?>
    <div class="mb-3">
        <label class="form-label fw-bold">Nambari ya Simu (Phone Number - Optional)</label>
        <input type="text" name="Inquiry[phone]" class="form-control mm-form-control" placeholder="Mfano: 0755 123 456" value="<?= Html::encode(Yii::$app->user->identity->phone ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Ujumbe / Swali Lako <span class="text-danger">*</span></label>
        <textarea name="Inquiry[message]" class="form-control mm-form-control" rows="4" placeholder="Andika ujumbe au swali lako kuhusu eneo hili..." required></textarea>
    </div>
    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3">
        <i class="bi bi-send-fill me-1"></i> Tuma Ujumbe (Send Message)
    </button>
<?= Html::endForm() ?>
<?php Modal::end(); ?>
<?php endif; ?>

<!-- Report Modal -->
<?php Modal::begin(['id' => 'reportModal', 'title' => 'Ripoti Tangazo Hili']); ?>
<?php $reportForm = ActiveForm::begin(['action' => ['/property/report', 'id' => $model->id]]); ?>
    <?= $reportForm->field($reportModel, 'reason')->dropDownList([
        'fake' => 'Tangazo la udanganyifu / Fake property',
        'duplicate' => 'Tangazo limerudiwa (Duplicate)',
        'misleading' => 'Bei au maelezo ya kupotosha',
        'other' => 'Sababu nyingine',
    ], ['class' => 'form-select mm-form-select'])->label('Sababu ya Kuripoti') ?>
    <?= $reportForm->field($reportModel, 'details')->textarea(['rows' => 3, 'class' => 'form-control mm-form-control'])->label('Maelezo ya Ziada') ?>
    <button class="btn btn-danger w-100 fw-bold py-2 rounded-3"><i class="bi bi-flag me-1"></i> Tuma Ripoti</button>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>
<?php endif; ?>