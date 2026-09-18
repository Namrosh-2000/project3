<?php

/** @var yii\web\View $this */
/** @var app\models\Booking[] $bookings */
/** @var string|null $currentStatus */

use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use app\models\Booking;

$this->title = 'Maombi ya Miadi na Uhifadhi | MachoMtaa';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-inbox-fill text-primary me-2"></i>Maombi ya Miadi na Uhifadhi</h2>
            <p class="text-muted mb-0">Kagua, thibitisha, badilisha tarehe, au simamia maombi ya kukagua fremu kutoka kwa wajasiriamali.</p>
        </div>
        <a href="<?= Url::to(['/account/listings']) ?>" class="btn btn-outline-secondary fw-bold">
            <i class="bi bi-building me-1"></i> Fremu Zangu
        </a>
    </div>

    <!-- Status Filters -->
    <div class="mb-4 d-flex gap-2 flex-wrap">
        <a href="<?= Url::to(['owner']) ?>" class="btn btn-sm <?= empty($currentStatus) ? 'btn-dark' : 'btn-outline-dark' ?>">
            Zote
        </a>
        <a href="<?= Url::to(['owner', 'status' => Booking::STATUS_PENDING]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_PENDING ? 'btn-warning' : 'btn-outline-warning' ?>">
            Inasubiri
        </a>
        <a href="<?= Url::to(['owner', 'status' => Booking::STATUS_CONFIRMED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_CONFIRMED ? 'btn-success' : 'btn-outline-success' ?>">
            Zilizothibitishwa
        </a>
        <a href="<?= Url::to(['owner', 'status' => Booking::STATUS_RESCHEDULED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_RESCHEDULED ? 'btn-info' : 'btn-outline-info' ?>">
            Zilizobadilishwa Tarehe
        </a>
        <a href="<?= Url::to(['owner', 'status' => Booking::STATUS_COMPLETED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_COMPLETED ? 'btn-primary' : 'btn-outline-primary' ?>">
            Zilizokamilika
        </a>
        <a href="<?= Url::to(['owner', 'status' => Booking::STATUS_CANCELLED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_CANCELLED ? 'btn-secondary' : 'btn-outline-secondary' ?>">
            Zilizoghairishwa
        </a>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="card shadow-sm p-5 text-center border-0 rounded-3">
            <div class="mb-3 text-muted">
                <i class="bi bi-inbox display-1"></i>
            </div>
            <h4 class="fw-bold text-dark">Hujapokea maombi yoyote ya miadi kwa sasa</h4>
            <p class="text-muted">Wajasiriamali wanapoandika maombi ya kutembelea au kuhifadhi fremu zako, yataonekana hapa.</p>
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
                                    <img src="<?= $booking->property->coverImage->getImageUrl() ?>" alt="Property Image" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="bi bi-building fs-2"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1">
                                        <a href="<?= Url::to(['/property/view', 'id' => $booking->property_id]) ?>" class="text-dark text-decoration-none">
                                            <?= Html::encode($booking->property->title ?? 'Property Deleted') ?>
                                        </a>
                                    </h5>
                                    <span class="badge bg-light text-dark border me-2">
                                        <?= Html::encode($booking->getTypeLabel()) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded mb-3">
                                <div class="row g-2 small">
                                    <div class="col-6">
                                        <strong class="text-muted d-block uppercase">Mtafutaji (Seeker):</strong>
                                        <span class="fw-bold text-dark"><i class="bi bi-person-circle me-1"></i> <?= Html::encode($booking->seeker->username ?? 'N/A') ?></span>
                                    </div>
                                    <div class="col-6">
                                        <strong class="text-muted d-block uppercase">Mawasiliano:</strong>
                                        <span class="badge bg-light text-secondary border"><i class="bi bi-shield-lock-fill text-primary me-1"></i> Mfumo wa EneoLink</span>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <strong class="text-muted d-block uppercase">Tarehe na Muda Ulioombwa:</strong>
                                        <span class="fw-bold text-primary fs-6"><i class="bi bi-calendar-event me-1"></i> <?= date('d M Y', strtotime($booking->booking_date)) ?> @ <?= Html::encode($booking->booking_time) ?></span>
                                    </div>

                                    <?php if ($booking->offered_price > 0): ?>
                                        <div class="col-12 mt-2 pt-2 border-top">
                                            <strong class="text-muted d-block uppercase">Offa ya Punguzo ya Mteja (Bargain Offer):</strong>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <span class="fs-6 fw-bold text-warning-emphasis bg-warning-subtle px-2 py-1 rounded border border-warning">
                                                    <i class="bi bi-tag-fill me-1"></i> TSh <?= number_format((float)$booking->offered_price) ?>
                                                </span>
                                                <small class="text-muted">(Bei ya Kawaida: TSh <?= number_format((float)$booking->property->price) ?>)</small>
                                                <span class="badge bg-dark ms-auto"><?= Html::encode($booking->getBargainStatusLabel()) ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if (!empty($booking->notes)): ?>
                                <div class="small text-muted bg-white p-2 rounded mb-3 border">
                                    <strong>Ujumbe wa Mtafutaji:</strong> <?= Html::encode($booking->notes) ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($booking->owner_response_notes)): ?>
                                <div class="small text-secondary bg-light p-2 rounded mb-3 border-start border-3 border-info">
                                    <strong>Maelezo yako (Response):</strong> <?= Html::encode($booking->owner_response_notes) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-footer bg-white border-top p-3">
                            <div class="mb-2 text-end">
                                <?php if (in_array($booking->status, [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED], true)): ?>
                                    <a href="<?= Url::to(['/booking/contract', 'booking_id' => $booking->id]) ?>" class="btn btn-sm btn-outline-dark fw-bold">
                                        <i class="bi bi-file-earmark-text-fill text-primary me-1"></i> 📄 Mkataba wa Pango (View Contract)
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border py-2 px-3 fw-normal">
                                        <i class="bi bi-lock-fill me-1"></i> Mkataba Unapatikana Baada ya Kuthibitisha
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php if ($booking->status === Booking::STATUS_PENDING): ?>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-sm btn-success fw-bold flex-grow-1" data-bs-toggle="modal" data-bs-target="#confirmModal-<?= $booking->id ?>">
                                        <i class="bi bi-check-circle-fill me-1"></i> Thibitisha (Confirm)
                                    </button>
                                    <button class="btn btn-sm btn-outline-info fw-bold" data-bs-toggle="modal" data-bs-target="#rescheduleModal-<?= $booking->id ?>">
                                        <i class="bi bi-clock-history me-1"></i> Badili Tarehe
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal-<?= $booking->id ?>">
                                        <i class="bi bi-x-lg me-1"></i> Kataa
                                    </button>
                                </div>
                            <?php elseif ($booking->status === Booking::STATUS_CONFIRMED): ?>
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <small class="text-success fw-bold"><i class="bi bi-check-all me-1"></i> Miadi imethibitishwa</small>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="<?= Url::to(['/payment/view', 'booking_id' => $booking->id]) ?>" class="btn btn-sm btn-outline-success fw-bold">
                                            <i class="bi bi-cash-coin me-1"></i> Angalia / Thibitisha Malipo
                                        </a>
                                        <?= Html::beginForm(['/booking/complete', 'id' => $booking->id], 'post') ?>
                                        <button class="btn btn-sm btn-primary fw-bold">
                                            <i class="bi bi-flag-fill me-1"></i> Weka Kama Imehakikishwa na Kukamilika
                                        </button>
                                        <?= Html::endForm() ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <small class="text-muted">Hali: <?= Html::encode($booking->getStatusLabel()) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Confirm Modal -->
                <?php Modal::begin(['id' => 'confirmModal-' . $booking->id, 'title' => 'Thibitisha Miadi - Code: ' . $booking->booking_code]); ?>
                <?= Html::beginForm(['/booking/confirm', 'id' => $booking->id], 'post') ?>
                    <p>Je, unathibitisha miadi ya tarehe <strong><?= date('d M Y', strtotime($booking->booking_date)) ?> @ <?= Html::encode($booking->booking_time) ?></strong> na mteja <strong><?= Html::encode($booking->seeker->username ?? '') ?></strong>?</p>
                    
                    <?php if ($booking->offered_price > 0 && $booking->bargain_status === Booking::BARGAIN_STATUS_PENDING): ?>
                        <div class="p-3 bg-light rounded border mb-3">
                            <label class="form-label fw-bold text-dark"><i class="bi bi-tag-fill text-warning me-1"></i> Mteja ameomba Punguzo la Bei (Bargain Offer):</label>
                            <div class="fs-5 fw-bold text-primary mb-2">TSh <?= number_format((float)$booking->offered_price) ?> <small class="fs-6 text-muted font-normal">(Bei Halisi: TSh <?= number_format((float)$booking->property->price) ?>)</small></div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="accept_bargain" id="acceptBargainYes-<?= $booking->id ?>" value="1" checked>
                                <label class="form-check-label fw-bold text-success" for="acceptBargainYes-<?= $booking->id ?>">
                                    Kubali Offa ya Mteja (TSh <?= number_format((float)$booking->offered_price) ?>)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="accept_bargain" id="acceptBargainNo-<?= $booking->id ?>" value="0">
                                <label class="form-check-label fw-bold text-danger" for="acceptBargainNo-<?= $booking->id ?>">
                                    Kataa Offa &amp; Tumia Bei Halisi (TSh <?= number_format((float)$booking->property->price) ?>)
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Maelekezo au Ujumbe kwa Mteja (Optional)</label>
                        <textarea name="owner_response_notes" class="form-control" rows="3" placeholder="Mfano: Karibu sana, tutakutana geti kuu la eneo..."></textarea>
                    </div>
                    <button class="btn btn-success w-100 fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Thibitisha Miadi (Confirm Booking)</button>
                <?= Html::endForm() ?>
                <?php Modal::end(); ?>

                <!-- Reschedule Modal -->
                <?php Modal::begin(['id' => 'rescheduleModal-' . $booking->id, 'title' => 'Pendekeza Tarehe Mpya - Code: ' . $booking->booking_code]); ?>
                <?= Html::beginForm(['/booking/reschedule', 'id' => $booking->id], 'post') ?>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tarehe Mpya (New Date)</label>
                            <input type="date" name="proposed_date" class="form-control" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Muda Mpya (New Time Slot)</label>
                            <select name="proposed_time" class="form-select" required>
                                <option value="09:00 AM">09:00 AM (Asubuhi)</option>
                                <option value="11:00 AM">11:00 AM (Mchana wa Mapema)</option>
                                <option value="02:00 PM">02:00 PM (Mchana)</option>
                                <option value="04:00 PM">04:00 PM (Jioni)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sababu ya Kubadili Tarehe (Message to Seeker)</label>
                        <textarea name="owner_response_notes" class="form-control" rows="3" placeholder="Mfano: Siku hiyo nitakuwa safarini, naomba tukutane tarehe hii mpya..." required></textarea>
                    </div>
                    <button class="btn btn-info w-100 fw-bold"><i class="bi bi-clock-history me-1"></i> Tuma Pendekezo la Tarehe Mpya</button>
                <?= Html::endForm() ?>
                <?php Modal::end(); ?>

                <!-- Reject Modal -->
                <?php Modal::begin(['id' => 'rejectModal-' . $booking->id, 'title' => 'Kataa Ombi la Booking - Code: ' . $booking->booking_code]); ?>
                <?= Html::beginForm(['/booking/reject', 'id' => $booking->id], 'post') ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sababu ya Kukataa (Reason for Rejection)</label>
                        <textarea name="owner_response_notes" class="form-control" rows="3" placeholder="Mfano: Eneo tayari limekodishwa au halipatikani kwa sasa..." required></textarea>
                    </div>
                    <button class="btn btn-danger w-100 fw-bold"><i class="bi bi-x-circle me-1"></i> Kataa Ombi Hili</button>
                <?= Html::endForm() ?>
                <?php Modal::end(); ?>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
