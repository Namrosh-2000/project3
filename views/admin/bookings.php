<?php

/** @var yii\web\View $this */
/** @var app\models\Booking[] $bookings */
/** @var array $stats */
/** @var string|null $currentStatus */

use yii\bootstrap5\Html;
use yii\helpers\Url;
use app\models\Booking;

$this->title = 'Admin - System Bookings | EneoLink';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-shield-lock-fill text-danger me-2"></i>Admin Booking Dashboard</h2>
            <p class="text-muted mb-0">Overview and system-wide monitoring of all property site visits and reservations.</p>
        </div>
        <div>
            <a href="<?= Url::to(['/admin/index']) ?>" class="btn btn-outline-dark fw-bold me-2">
                <i class="bi bi-speedometer2 me-1"></i> Admin Main Panel
            </a>
        </div>
    </div>

    <!-- System Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-2 col-6">
            <div class="card bg-dark text-white border-0 shadow-sm p-3">
                <small class="text-white-50 uppercase fw-bold">Total Bookings</small>
                <h3 class="fw-bold mb-0 mt-1"><?= number_format($stats['total'] ?? 0) ?></h3>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card bg-warning text-dark border-0 shadow-sm p-3">
                <small class="text-dark-50 uppercase fw-bold">Inasubiri (Pending)</small>
                <h3 class="fw-bold mb-0 mt-1"><?= number_format($stats['pending'] ?? 0) ?></h3>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-success text-white border-0 shadow-sm p-3">
                <small class="text-white-50 uppercase fw-bold">Zilizothibitishwa (Confirmed)</small>
                <h3 class="fw-bold mb-0 mt-1"><?= number_format($stats['confirmed'] ?? 0) ?></h3>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-primary text-white border-0 shadow-sm p-3">
                <small class="text-white-50 uppercase fw-bold">Zilizokamilika (Completed)</small>
                <h3 class="fw-bold mb-0 mt-1"><?= number_format($stats['completed'] ?? 0) ?></h3>
            </div>
        </div>
        <div class="col-md-2 col-12">
            <div class="card bg-secondary text-white border-0 shadow-sm p-3">
                <small class="text-white-50 uppercase fw-bold">Cancelled</small>
                <h3 class="fw-bold mb-0 mt-1"><?= number_format($stats['cancelled'] ?? 0) ?></h3>
            </div>
        </div>
    </div>

    <!-- Status Filter Buttons -->
    <div class="mb-4 d-flex gap-2 flex-wrap">
        <a href="<?= Url::to(['bookings']) ?>" class="btn btn-sm <?= empty($currentStatus) ? 'btn-dark' : 'btn-outline-dark' ?>">
            Zote (All)
        </a>
        <a href="<?= Url::to(['bookings', 'status' => Booking::STATUS_PENDING]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_PENDING ? 'btn-warning' : 'btn-outline-warning' ?>">
            Pending
        </a>
        <a href="<?= Url::to(['bookings', 'status' => Booking::STATUS_CONFIRMED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_CONFIRMED ? 'btn-success' : 'btn-outline-success' ?>">
            Confirmed
        </a>
        <a href="<?= Url::to(['bookings', 'status' => Booking::STATUS_RESCHEDULED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_RESCHEDULED ? 'btn-info' : 'btn-outline-info' ?>">
            Rescheduled
        </a>
        <a href="<?= Url::to(['bookings', 'status' => Booking::STATUS_COMPLETED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_COMPLETED ? 'btn-primary' : 'btn-outline-primary' ?>">
            Completed
        </a>
        <a href="<?= Url::to(['bookings', 'status' => Booking::STATUS_CANCELLED]) ?>" class="btn btn-sm <?= $currentStatus === Booking::STATUS_CANCELLED ? 'btn-secondary' : 'btn-outline-secondary' ?>">
            Cancelled
        </a>
    </div>

    <!-- Table of Bookings -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <?php if (empty($bookings)): ?>
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-calendar-x display-3 mb-3 d-block"></i>
                    <h5 class="fw-bold">Hakuna taarifa za booking zilizopatikana.</h5>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Property</th>
                                <th>Seeker (Mtafutaji)</th>
                                <th>Owner (Mmiliki)</th>
                                <th>Aina ya Booking</th>
                                <th>Tarehe &amp; Muda</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $b): ?>
                                <tr>
                                    <td>
                                        <span class="font-monospace fw-bold small text-dark">
                                            <?= Html::encode($b->booking_code) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= Url::to(['/property/view', 'id' => $b->property_id]) ?>" class="fw-bold text-dark text-decoration-none" target="_blank">
                                            <?= Html::encode(mb_strimwidth($b->property->title ?? 'N/A', 0, 30, '...')) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div><strong><?= Html::encode($b->seeker->username ?? 'N/A') ?></strong></div>
                                        <small class="text-muted"><i class="bi bi-telephone"></i> <?= Html::encode($b->seeker_phone ?: ($b->seeker->phone ?? 'N/A')) ?></small>
                                    </td>
                                    <td>
                                        <strong><?= Html::encode($b->owner->username ?? 'N/A') ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= Html::encode($b->getTypeLabel()) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="d-block fw-bold text-dark"><?= date('d M Y', strtotime($b->booking_date)) ?></small>
                                        <small class="text-muted"><?= Html::encode($b->booking_time) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge <?= $b->getStatusBadgeClass() ?> px-2 py-1">
                                            <?= Html::encode($b->getStatusLabel()) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= Url::to(['/booking/contract', 'booking_id' => $b->id]) ?>" class="btn btn-sm btn-outline-dark fw-bold me-1" target="_blank">
                                            📄 Contract
                                        </a>
                                        <?php if ($b->status !== Booking::STATUS_CANCELLED && $b->status !== Booking::STATUS_COMPLETED): ?>
                                            <?= Html::beginForm(['/admin/cancel-booking', 'id' => $b->id], 'post', ['class' => 'd-inline']) ?>
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Je, una uhakika unataka kuahirisha booking hii kama Admin?');">
                                                <i class="bi bi-x-circle"></i> Cancel
                                            </button>
                                            <?= Html::endForm() ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
