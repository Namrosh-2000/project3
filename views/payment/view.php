<?php

/** @var yii\web\View $this */
/** @var app\models\Booking $booking */
/** @var app\models\Payment|null $payment */

use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use app\models\Booking;
use app\models\Payment;

$myId    = Yii::$app->user->id;
$isOwner = ($myId === $booking->owner_id);
$isSeeker = ($myId === $booking->seeker_id);

$this->title = 'Malipo ya Booking – ' . Html::encode($booking->booking_code);
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <a href="<?= Url::to([$isOwner ? '/booking/owner' : '/booking/index']) ?>" class="btn btn-outline-secondary btn-sm fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kurudi kwenye Miadi Zangu
            </a>
        </div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-cash-coin text-success me-2"></i>Malipo – <?= Html::encode($booking->booking_code) ?>
        </h4>
    </div>

    <?php /* ——— Booking Summary Card ——— */ ?>
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <span class="badge bg-dark font-monospace fs-6">
                <i class="bi bi-ticket-perforated me-1"></i> <?= Html::encode($booking->booking_code) ?>
            </span>
            <span class="badge <?= $booking->getStatusBadgeClass() ?> fs-6 px-3 py-2">
                <?= Html::encode($booking->getStatusLabel()) ?>
            </span>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <strong class="text-muted d-block small uppercase">Eneo:</strong>
                    <span class="fw-bold"><?= Html::encode($booking->property->title ?? 'N/A') ?></span><br>
                    <small class="text-muted">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        <?= Html::encode(($booking->property->location->ward ?? 'Kinondoni') . ', Dar es Salaam') ?>
                    </small>
                </div>
                <div class="col-md-3">
                    <strong class="text-muted d-block small uppercase">Aina ya Booking:</strong>
                    <span><?= Html::encode($booking->getTypeLabel()) ?></span>
                </div>
                <div class="col-md-3">
                    <strong class="text-muted d-block small uppercase">Bei Iliyokubaliwa:</strong>
                    <span class="fs-5 fw-bold text-primary">
                        TZS <?= number_format((float)$booking->getEffectivePrice()) ?>
                    </span>
                    <?php if ($booking->bargain_status === 'accepted'): ?>
                        <span class="badge bg-success ms-1">Bei Iliyopunguzwa</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php /* ——— Payment Status Card ——— */ ?>
    <?php if ($payment): ?>
        <div class="card shadow border-0 rounded-3 mb-4">
            <div class="card-header d-flex justify-content-between align-items-center py-3 bg-white border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-success"></i>Hali ya Malipo</h5>
                <span class="badge <?= $payment->getStatusBadgeClass() ?> fs-6 px-3 py-2">
                    <?= Html::encode($payment->getStatusLabel()) ?>
                </span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <strong class="text-muted small d-block uppercase">Kode ya Malipo:</strong>
                        <span class="badge bg-dark font-monospace"><?= Html::encode($payment->payment_code) ?></span>
                    </div>
                    <div class="col-md-4">
                        <strong class="text-muted small d-block uppercase">Kiasi:</strong>
                        <span class="fs-5 fw-bold text-primary"><?= Html::encode($payment->getFormattedAmount()) ?></span>
                    </div>
                    <div class="col-md-4">
                        <strong class="text-muted small d-block uppercase">Njia ya Malipo:</strong>
                        <span><?= Html::encode($payment->getMethodLabel()) ?></span>
                    </div>

                    <?php if (!empty($payment->transaction_id)): ?>
                        <div class="col-12">
                            <strong class="text-muted small d-block uppercase">Nambari ya Muamala / Transaction ID:</strong>
                            <code class="fs-6"><?= Html::encode($payment->transaction_id) ?></code>
                        </div>
                    <?php endif; ?>

                    <?php if ($payment->status === Payment::STATUS_PAID && $payment->paid_at): ?>
                        <div class="col-12">
                            <div class="alert alert-success border-success mb-0">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>Malipo yamethibitishwa na Mmiliki:</strong>
                                <?= date('d M Y, H:i', $payment->paid_at) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (in_array($payment->status, [Payment::STATUS_FAILED, Payment::STATUS_CANCELLED]) && $payment->failure_reason): ?>
                        <div class="col-12">
                            <div class="alert alert-danger border-danger mb-0">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong><?= $payment->status === Payment::STATUS_FAILED ? 'Sababu ya Kushindwa:' : 'Sababu ya Kufuta:' ?></strong>
                                <?= Html::encode($payment->failure_reason) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($payment->status === Payment::STATUS_PENDING && !empty($payment->failure_reason)): ?>
                        <div class="col-12">
                            <div class="small text-muted bg-light p-2 rounded border-start border-3 border-primary">
                                <strong>Maelezo ya Ziada:</strong> <?= Html::encode($payment->failure_reason) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php /* ——— Owner Actions ——— */ ?>
                <?php if ($isOwner && $payment->status === Payment::STATUS_PENDING): ?>
                    <hr class="my-4">
                    <div class="d-flex gap-3 flex-wrap">
                        <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal">
                            <i class="bi bi-check-circle-fill me-1"></i> Thibitisha Malipo Haya Yamepokelewa
                        </button>
                        <button class="btn btn-outline-danger fw-bold" data-bs-toggle="modal" data-bs-target="#failPaymentModal">
                            <i class="bi bi-x-circle me-1"></i> Ripoti Malipo Kama Yameshindwa
                        </button>
                    </div>
                <?php endif; ?>

                <?php /* ——— Seeker Cancel ——— */ ?>
                <?php if ($isSeeker && $payment->status === Payment::STATUS_PENDING): ?>
                    <hr class="my-4">
                    <?= Html::beginForm(['/payment/cancel', 'id' => $payment->id], 'post') ?>
                    <button class="btn btn-outline-secondary btn-sm fw-bold"
                            onclick="return confirm('Je, una uhakika unataka kufuta ombi hili la malipo?')">
                        <i class="bi bi-x me-1"></i> Futa Ombi la Malipo Hili
                    </button>
                    <?= Html::endForm() ?>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>

    <?php /* ——— Initiate Payment (Seeker, confirmed booking, no active payment) ——— */ ?>
    <?php if ($isSeeker && $booking->status === Booking::STATUS_CONFIRMED && !$payment): ?>
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-cash-coin me-2"></i>Lipa Sasa / Wasilisha Uthibitisho wa Malipo</h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Kiasi Kinachohitajika:</strong>
                    <span class="fs-5 fw-bold text-primary ms-2">TZS <?= number_format((float)$booking->getEffectivePrice()) ?></span>
                    <?php if ($booking->bargain_status === 'accepted'): ?>
                        <span class="badge bg-success ms-1">Bei Iliyopunguzwa (Bargained)</span>
                    <?php endif; ?>
                    <hr class="my-2">
                    <small>Fanya malipo kupitia njia unayoipenda hapa chini, kisha ingiza kumbukumbu ya malipo. Mmiliki atakithibitisha.</small>
                </div>

                <?= Html::beginForm(['/payment/initiate', 'booking_id' => $booking->id], 'post') ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Njia ya Malipo <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">— Chagua Njia ya Malipo —</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="tigopesa">Tigo Pesa</option>
                            <option value="airtel_money">Airtel Money</option>
                            <option value="bank_transfer">Benki / Bank Transfer</option>
                            <option value="cash">Fedha Taslimu (Cash) – Kwa Mkono</option>
                            <option value="other">Njia Nyingine</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nambari / Kumbukumbu ya Malipo (Transaction ID / Reference)</label>
                        <input type="text" name="transaction_id" class="form-control"
                               placeholder="Mfano: MPESA-ABC123456 au REF-2026091800X">
                        <div class="form-text text-muted">Inahitajika kwa M-Pesa, Tigo Pesa, Airtel Money, na Benki.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Maelezo ya Ziada (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2"
                                  placeholder="Mfano: Nimelipa kupitia akaunti ya akina mama..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 fs-6">
                        <i class="bi bi-send-fill me-2"></i> Tuma Ombi la Malipo
                    </button>
                <?= Html::endForm() ?>
            </div>
        </div>
    <?php endif; ?>

    <?php /* ——— Re-initiate after failure ——— */ ?>
    <?php if ($isSeeker && $booking->status === Booking::STATUS_CONFIRMED && $payment && $payment->status === Payment::STATUS_FAILED): ?>
        <div class="alert alert-warning">
            <i class="bi bi-arrow-clockwise me-2"></i>
            Malipo yako ya awali yameshindwa. Unaweza kuwasilisha malipo mapya hapa chini.
        </div>
        <a href="<?= Url::to(['/payment/view', 'booking_id' => $booking->id]) ?>" class="btn btn-outline-primary mb-3">
            Onyesha Ukurasa wa Malipo (Ingiza Mapya)
        </a>
    <?php endif; ?>

    <?php /* ——— Info for owner when no payment initiated yet ——— */ ?>
    <?php if ($isOwner && !$payment && $booking->status === Booking::STATUS_CONFIRMED): ?>
        <div class="alert alert-info">
            <i class="bi bi-hourglass-split me-2"></i>
            Mteja bado hajawasilisha malipo. Ukurasa huu utasasishwa mara malipo yatakapowasilishwa.
        </div>
    <?php endif; ?>
</div>

<?php /* ——— Owner: Confirm Payment Modal ——— */ ?>
<?php if ($isOwner && $payment && $payment->status === Payment::STATUS_PENDING): ?>
    <?php Modal::begin(['id' => 'confirmPaymentModal', 'title' => 'Thibitisha Malipo Yamepokelewa']); ?>
    <?= Html::beginForm(['/payment/confirm', 'id' => $payment->id], 'post') ?>
        <p>Je, unathibitisha kwamba malipo ya <strong><?= Html::encode($payment->getFormattedAmount()) ?></strong>
            kutoka kwa <strong><?= Html::encode($booking->seeker->username ?? 'N/A') ?></strong> yamepokelewa?</p>
        <div class="mb-3">
            <label class="form-label fw-bold">Thibitisha / Sasisha Nambari ya Muamala (Optional)</label>
            <input type="text" name="transaction_id" class="form-control"
                   value="<?= Html::encode($payment->transaction_id ?? '') ?>"
                   placeholder="Transaction ID / Reference">
        </div>
        <div class="alert alert-warning small">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            <strong>Tahadhari:</strong> Baada ya kuthibitisha, booking itawekwa kama Imekamilika na eneo litawekwa kama Hazipatikani.
        </div>
        <button class="btn btn-success w-100 fw-bold">
            <i class="bi bi-check-circle-fill me-1"></i> Ndio, Malipo Yamepokelewa – Thibitisha
        </button>
    <?= Html::endForm() ?>
    <?php Modal::end(); ?>

    <?php Modal::begin(['id' => 'failPaymentModal', 'title' => 'Ripoti Malipo Kama Yameshindwa']); ?>
    <?= Html::beginForm(['/payment/fail', 'id' => $payment->id], 'post') ?>
        <p>Ikiwa malipo hayakufika au kuna tatizo, toa sababu hapa chini:</p>
        <div class="mb-3">
            <label class="form-label fw-bold">Sababu ya Kushindwa <span class="text-danger">*</span></label>
            <textarea name="failure_reason" class="form-control" rows="3" required
                      placeholder="Mfano: Fedha hazikuwasili katika akaunti yetu..."></textarea>
        </div>
        <button class="btn btn-danger w-100 fw-bold">
            <i class="bi bi-x-circle-fill me-1"></i> Thibitisha Malipo Yameshindwa
        </button>
    <?= Html::endForm() ?>
    <?php Modal::end(); ?>
<?php endif; ?>

