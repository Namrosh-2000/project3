<?php

/** @var yii\web\View $this */
/** @var app\models\Booking[] $bookings */
/** @var string|null $currentStatus */

use yii\bootstrap5\Html;
use yii\helpers\Url;
use app\models\Booking;

$this->title = 'Miadi Yangu | MachoMtaa';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-calendar-range-fill text-primary me-2"></i>Miadi Yangu (Uhifadhi)</h2>
            <p class="text-muted mb-0">Simamia maombi yako yote ya kutembelea na kukagua fremu pamoja na nafasi ulizoomba.</p>
        </div>
        <a href="<?= Url::to(['/property/index']) ?>" class="btn btn-outline-primary fw-bold">
            <i class="bi bi-search me-1"></i> Tafuta Fremu Nyingine
        </a>
    </div>

    <!-- Status Filters -->
    <div class="mb-4 d-flex gap-2 flex-wrap">
        <a href="<?= Url::to(['index']) ?>" class="btn btn-sm <?= empty($currentStatus) ? 'btn-dark' : 'btn-outline-dark' ?>">
            Zote
        </a>
        <a href="<?= Url::to(['index', 'status' => Booking::STATUS_PENDING]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_PENDING ? 'btn-warning' : 'btn-outline-warning' ?>">
            Inasubiri
        </a>
        <a href="<?= Url::to(['index', 'status' => Booking::STATUS_CONFIRMED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_CONFIRMED ? 'btn-success' : 'btn-outline-success' ?>">
            Zilizothibitishwa
        </a>
        <a href="<?= Url::to(['index', 'status' => Booking::STATUS_RESCHEDULED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_RESCHEDULED ? 'btn-info' : 'btn-outline-info' ?>">
            Zilizobadilishwa Tarehe
        </a>
        <a href="<?= Url::to(['index', 'status' => Booking::STATUS_COMPLETED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_COMPLETED ? 'btn-primary' : 'btn-outline-primary' ?>">
            Zilizokamilika
        </a>
        <a href="<?= Url::to(['index', 'status' => Booking::STATUS_CANCELLED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_CANCELLED ? 'btn-secondary' : 'btn-outline-secondary' ?>">
            Zilizoghairishwa
        </a>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="card shadow-sm p-5 text-center border-0 rounded-3">
            <div class="mb-3 text-muted">
                <i class="bi bi-calendar-x display-1"></i>
            </div>
            <h4 class="fw-bold text-dark">Hujaweka ombi la miadi au uhifadhi kwa sasa</h4>
            <p class="text-muted">Tafuta fremu ya biashara kisha bonyeza "Kukagua Eneo / Weka Oda" kwenye ukurasa wa fremu husika.</p>
            <div>
                <a href="<?= Url::to(['/property/index']) ?>" class="btn btn-primary fw-bold px-4">
                    Tafuta Fremu Sasa
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($bookings as $booking): ?>
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 rounded-3 h-100 overflow-hidden">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                            <span class="badge bg-dark font-monospace fs-6">
                                <i class="bi bi-ticket-perforated me-1"></i> <?= Html::encode($booking->booking_code) ?>
                            </span>
                            <span class="badge <?= $booking->getStatusBadgeClass() ?> fs-6 px-3 py-2">
                                <?= Html::encode($booking->getStatusLabel()) ?>
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex gap-3 align-items-start mb-3">
                                <?php if ($booking->property && $booking->property->coverImage): ?>
                                    <img src="<?= $booking->property->coverImage->getImageUrl() ?>" alt="Property Image" class="rounded" style="width: 90px; height: 90px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
                                        <i class="bi bi-building fs-2"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1">
                                        <a href="<?= Url::to(['/property/view', 'id' => $booking->property_id]) ?>" class="text-dark text-decoration-none">
                                            <?= Html::encode($booking->property->title ?? 'Property Deleted') ?>
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        <?= Html::encode(($booking->property->location->ward ?? 'Kinondoni') . ', Dar es Salaam') ?>
                                    </p>
                                    <span class="badge bg-light text-dark border">
                                        <?= Html::encode($booking->getTypeLabel()) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded mb-3">
                                <div class="row g-2 small">
                                    <div class="col-6">
                                        <strong class="text-muted d-block uppercase">Tarehe na Muda:</strong>
                                        <span class="fw-bold text-dark"><i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($booking->booking_date)) ?> @ <?= Html::encode($booking->booking_time) ?></span>
                                    </div>
                                    <div class="col-6">
                                        <strong class="text-muted d-block uppercase">Mmiliki / Dalali:</strong>
                                        <span class="fw-bold text-dark"><i class="bi bi-person-fill me-1"></i> <?= Html::encode($booking->owner->username ?? 'N/A') ?></span>
                                    </div>

                                    <?php if ($booking->offered_price > 0): ?>
                                        <div class="col-12 mt-2 pt-2 border-top">
                                            <strong class="text-muted d-block uppercase">Offa Yako ya Bei (Bargain Offer):</strong>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <span class="fs-6 fw-bold text-warning-emphasis bg-warning-subtle px-2 py-1 rounded border border-warning">
                                                    <i class="bi bi-tag-fill me-1"></i> TSh <?= number_format((float)$booking->offered_price) ?>
                                                </span>
                                                <span class="badge <?= $booking->bargain_status === 'accepted' ? 'bg-success' : ($booking->bargain_status === 'rejected' ? 'bg-danger' : 'bg-dark') ?> ms-auto">
                                                    <?= Html::encode($booking->getBargainStatusLabel()) ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if ($booking->status === Booking::STATUS_RESCHEDULED && !empty($booking->proposed_date)): ?>
                                <div class="alert alert-info border-info mb-3">
                                    <h6 class="fw-bold mb-1"><i class="bi bi-info-circle-fill me-1"></i> Mmiliki amependekeza tarehe na muda mpya:</h6>
                                    <p class="mb-2"><strong>Tarehe Mpya:</strong> <?= date('d M Y', strtotime($booking->proposed_date)) ?> @ <?= Html::encode($booking->proposed_time) ?></p>
                                    <?php if (!empty($booking->owner_response_notes)): ?>
                                        <p class="small text-muted mb-2"><em>"<?= Html::encode($booking->owner_response_notes) ?>"</em></p>
                                    <?php endif; ?>
                                    <?= Html::beginForm(['/booking/accept-reschedule', 'id' => $booking->id], 'post') ?>
                                    <button class="btn btn-info btn-sm fw-bold me-2">
                                        <i class="bi bi-check-circle-fill me-1"></i> Kubali Tarehe Hii Mpya
                                    </button>
                                    <?= Html::endForm() ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($booking->owner_response_notes) && $booking->status !== Booking::STATUS_RESCHEDULED): ?>
                                <div class="small text-secondary bg-light p-2 rounded mb-3 border-start border-3 border-primary">
                                    <strong>Ujumbe wa Mmiliki:</strong> <?= Html::encode($booking->owner_response_notes) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <?php if (in_array($booking->status, [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED], true)): ?>
                                <a href="<?= Url::to(['/booking/contract', 'booking_id' => $booking->id]) ?>" class="btn btn-sm btn-outline-dark fw-bold">
                                    <i class="bi bi-file-earmark-text-fill text-primary me-1"></i> 📄 Mkataba (Contract)
                                </a>
                            <?php else: ?>
                                <span class="badge bg-light text-muted border py-2 px-3 fw-normal">
                                    <i class="bi bi-lock-fill me-1"></i> Mkataba Unapatikana Baada ya Kuthibitishwa
                                </span>
                            <?php endif; ?>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php if ($booking->status === Booking::STATUS_CONFIRMED): ?>
                                    <a href="<?= Url::to(['/payment/view', 'booking_id' => $booking->id]) ?>" class="btn btn-sm btn-success fw-bold">
                                        <i class="bi bi-cash-coin me-1"></i> Malipo / Lipa
                                    </a>
                                <?php endif; ?>

                                <?php if ($booking->status === Booking::STATUS_CONFIRMED): ?>
                                    <?= Html::beginForm(['/booking/complete', 'id' => $booking->id], 'post') ?>
                                    <button class="btn btn-sm btn-outline-success fw-bold">
                                        <i class="bi bi-check2-all me-1"></i> Weka Kama Imehakikishwa &amp; Kukamilika
                                    </button>
                                    <?= Html::endForm() ?>
                                <?php endif; ?>

                                <?php if (in_array($booking->status, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED, Booking::STATUS_RESCHEDULED])): ?>
                                    <?= Html::beginForm(['/booking/cancel', 'id' => $booking->id], 'post') ?>
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Je, una uhakika unataka kuahirisha miadi hii?');">
                                        <i class="bi bi-x-circle me-1"></i> Ahirisha
                                    </button>
                                    <?= Html::endForm() ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
