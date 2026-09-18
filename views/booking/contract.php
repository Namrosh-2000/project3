<?php

/** @var yii\web\View $this */
/** @var app\models\Booking $booking */
/** @var app\models\BookingContract $contract */

use yii\bootstrap5\Html;
use yii\helpers\Url;
use app\models\BookingContract;

$this->title = 'Mkataba wa Pango (Contract) - Code: ' . $contract->contract_code;

$isSeeker = (Yii::$app->user->id === $booking->seeker_id);
$isOwner = (Yii::$app->user->id === $booking->owner_id);
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 d-print-none">
        <div>
            <a href="<?= Url::to([$isOwner ? '/booking/owner' : '/booking/index']) ?>" class="btn btn-outline-secondary btn-sm fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kurudi kwenye Miadi (Back to Bookings)
            </a>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print();" class="btn btn-dark btn-sm fw-bold">
                <i class="bi bi-printer-fill me-1"></i> Print / Pakua Copy ya Mkataba (PDF)
            </button>
        </div>
    </div>

    <!-- Official Contract Document Card -->
    <div class="card shadow-lg border-2 border-dark rounded-3 overflow-hidden bg-white p-4 p-md-5">
        <!-- Header & Stamp -->
        <div class="border-bottom border-3 border-dark pb-4 mb-4 text-center position-relative">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="text-start">
                    <h2 class="fw-bold text-dark mb-0"><i class="bi bi-geo-alt-fill text-danger me-1"></i> EneoLink</h2>
                    <small class="text-muted uppercase fw-bold">Verified Real Estate Platform - Kinondoni</small>
                </div>
                <div class="text-end">
                    <span class="badge bg-dark font-monospace fs-6">Code: <?= Html::encode($contract->contract_code) ?></span>
                    <div class="small text-muted mt-1">Ref Booking: <?= Html::encode($booking->booking_code) ?></div>
                </div>
            </div>
            
            <h3 class="fw-bold text-dark mt-3 uppercase tracking-wide">
                MKATABA WA PANGO NA MAKUBALIANO YA ENEO<br>
                <small class="fs-6 fw-normal text-muted">(LEASE &amp; PROPERTY OCCUPANCY AGREEMENT)</small>
            </h3>

            <div class="mt-3">
                <span class="badge <?= $contract->getStatusBadgeClass() ?> fs-6 px-4 py-2">
                    <i class="bi bi-patch-check-fill me-1"></i> <?= Html::encode($contract->getStatusLabel()) ?>
                </span>
            </div>
        </div>

        <!-- Section 1: Parties to Agreement -->
        <div class="mb-4 p-3 bg-light rounded border">
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">1. WAHUSIKA WA MKATABA (PARTIES TO THE AGREEMENT)</h5>
            <div class="row g-3">
                <div class="col-md-6 border-end">
                    <div class="fw-bold text-primary mb-1 uppercase">UPANDE A: MMILIKI / DALALI (LANDLORD / AGENT)</div>
                    <div class="fs-6"><strong>Jina:</strong> <?= Html::encode($booking->owner->username ?? 'N/A') ?></div>
                    <div class="small text-muted mb-1"><strong>Mawasiliano:</strong> <span class="badge bg-secondary-subtle text-dark border"><i class="bi bi-shield-lock-fill text-primary me-1"></i> EneoLink In-App Protected</span></div>
                    <div class="small text-muted"><strong>Role:</strong> <?= ucfirst(Html::encode($booking->owner->role ?? 'owner')) ?></div>
                </div>
                <div class="col-md-6">
                    <div class="fw-bold text-success mb-1 uppercase">UPANDE B: MPANGAJI / MTEJA (TENANT / BUYER)</div>
                    <div class="fs-6"><strong>Jina:</strong> <?= Html::encode($booking->seeker->username ?? 'N/A') ?></div>
                    <div class="small text-muted mb-1"><strong>Mawasiliano:</strong> <span class="badge bg-secondary-subtle text-dark border"><i class="bi bi-shield-lock-fill text-success me-1"></i> EneoLink In-App Protected</span></div>
                    <div class="small text-muted"><strong>Tarehe ya Miadi:</strong> <?= date('d M Y', strtotime($booking->booking_date)) ?> @ <?= Html::encode($booking->booking_time) ?></div>
                </div>
            </div>
        </div>

        <!-- Section 2: Property & Financial Details -->
        <div class="mb-4">
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">2. TAARIFA ZA ENEO NA MALIPO (PROPERTY &amp; FINANCIAL TERMS)</h5>
            <table class="table table-bordered align-middle">
                <tbody>
                    <tr>
                        <td class="bg-light fw-bold" style="width: 30%;">Jina la Eneo / Nyumba:</td>
                        <td><strong><?= Html::encode($booking->property->title ?? 'N/A') ?></strong></td>
                    </tr>
                    <tr>
                        <td class="bg-light fw-bold">Eneo / Kata:</td>
                        <td><?= Html::encode(($booking->property->location->ward ?? 'Kinondoni') . ', Dar es Salaam') ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light fw-bold">Aina ya Booking:</td>
                        <td><?= Html::encode($booking->getTypeLabel()) ?></td>
                    </tr>
                    <tr>
                        <td class="bg-light fw-bold">Gharama ya Pango / Bei Iliyokubaliwa:</td>
                        <td class="fs-5 text-primary fw-bold">
                            TSh <?= number_format((float)$booking->getEffectivePrice()) ?>
                            <small class="text-muted fs-6 fw-normal">/ <?= Html::encode($booking->property->price_period ?: 'month') ?></small>
                            <?php if ($booking->bargain_status === 'accepted'): ?>
                                <span class="badge bg-success fs-6 ms-2"><i class="bi bi-tag-fill me-1"></i> Bei Iliyokubaliwa (Bargained Price)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Section 3: Terms and Conditions -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="fw-bold text-dark mb-0">3. MASHARTI NA KANUNI ZA MKATABA (TERMS &amp; CONDITIONS)</h5>
                <?php if ($isOwner): ?>
                    <button type="button" class="btn btn-sm btn-outline-primary fw-bold d-print-none" data-bs-toggle="modal" data-bs-target="#editTermsModal">
                        <i class="bi bi-pencil-square me-1"></i> Badili Masharti (Edit Terms)
                    </button>
                <?php endif; ?>
            </div>
            <div class="p-3 bg-light rounded border leading-relaxed text-secondary font-monospace small mb-3" style="white-space: pre-line;">
                <?= Html::encode($contract->contract_terms) ?>
            </div>
            <div class="small text-muted">
                <em>* Mkataba huu umezalishwa rasmi na mfumo wa EneoLink kulingana na masharti yaliyowekwa na Mmiliki na kuthibitishwa na Mpangaji.</em>
            </div>
        </div>

        <!-- Section 4: Signatures Section -->
        <div class="mt-5 border-top border-2 border-dark pt-4">
            <h5 class="fw-bold text-dark mb-4 text-center uppercase">4. UTHIBITISHO NA SAINI ZA WAHUSIKA (EXECUTION &amp; E-SIGNATURES)</h5>
            
            <div class="row g-4">
                <!-- Seeker Signature Card -->
                <div class="col-md-6">
                    <div class="p-4 border rounded-3 text-center bg-light h-100">
                        <h6 class="fw-bold text-success mb-3"><i class="bi bi-pen-fill me-1"></i> SAINI YA MPANGAJI (SEEKER / TENANT)</h6>
                        
                        <?php if ($contract->seeker_signature): ?>
                            <div class="p-3 bg-white border border-success rounded mb-2">
                                <div class="font-monospace fs-4 fw-bold text-dark border-bottom pb-2 mb-2" style="font-style: italic;">
                                    <?= Html::encode($contract->seeker_signature) ?>
                                </div>
                                <small class="text-success fw-bold d-block">
                                    <i class="bi bi-check-circle-fill me-1"></i> Verified Digital Signature
                                </small>
                                <small class="text-muted d-block">
                                    Tarehe: <?= date('d M Y, H:i:s', $contract->seeker_signed_at) ?>
                                </small>
                            </div>
                        <?php else: ?>
                            <?php if ($isSeeker): ?>
                                <?= Html::beginForm(['/booking/sign-contract', 'id' => $contract->id], 'post') ?>
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold">Andika Jina Lako Kamili Kama Saini (Full Legal Name):</label>
                                        <input type="text" name="seeker_signature" class="form-control form-control-lg" placeholder="Mfano: Juma Shabani Ally" value="<?= Html::encode($booking->seeker->username ?? '') ?>" required>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" type="checkbox" id="agreeCheckSeeker" required>
                                        <label class="form-check-label small" for="agreeCheckSeeker">
                                            Nimesoma na kukubaliana na kila kipengele na masharti ya mkataba huu.
                                        </label>
                                    </div>
                                    <button class="btn btn-success w-100 fw-bold">
                                        <i class="bi bi-pencil-fill me-1"></i> Tia Saini Mkataba Hapa (Sign Contract)
                                    </button>
                                <?= Html::endForm() ?>
                            <?php else: ?>
                                <div class="text-muted p-4 border rounded bg-white">
                                    <i class="bi bi-hourglass-split display-6 mb-2 d-block"></i>
                                    Inasubiri Mpangaji kutia saini...
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Owner Signature Card -->
                <div class="col-md-6">
                    <div class="p-4 border rounded-3 text-center bg-light h-100">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-shield-check me-1"></i> SAINI YA MMILIKI (OWNER / LANDLORD)</h6>
                        
                        <?php if ($contract->owner_signature): ?>
                            <div class="p-3 bg-white border border-primary rounded mb-2">
                                <div class="font-monospace fs-4 fw-bold text-dark border-bottom pb-2 mb-2" style="font-style: italic;">
                                    <?= Html::encode($contract->owner_signature) ?>
                                </div>
                                <small class="text-primary fw-bold d-block">
                                    <i class="bi bi-check-circle-fill me-1"></i> Verified Landlord Signature
                                </small>
                                <small class="text-muted d-block">
                                    Tarehe: <?= date('d M Y, H:i:s', $contract->owner_signed_at) ?>
                                </small>
                            </div>
                        <?php else: ?>
                            <?php if ($isOwner): ?>
                                <?= Html::beginForm(['/booking/owner-sign-contract', 'id' => $contract->id], 'post') ?>
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold">Andika Jina Lako Kamili Kama Mmiliki (Full Name):</label>
                                        <input type="text" name="owner_signature" class="form-control form-control-lg" placeholder="Mfano: Ally Kassim Omari" value="<?= Html::encode($booking->owner->username ?? '') ?>" required>
                                    </div>
                                    <button class="btn btn-primary w-100 fw-bold">
                                        <i class="bi bi-pencil-fill me-1"></i> Tia Saini Kama Mmiliki (Sign Contract)
                                    </button>
                                <?= Html::endForm() ?>
                            <?php else: ?>
                                <div class="text-muted p-4 border rounded bg-white">
                                    <i class="bi bi-hourglass-split display-6 mb-2 d-block"></i>
                                    Inasubiri Mmiliki kutia saini...
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($isOwner): ?>
    <!-- Modal for Editing Contract Terms -->
    <div class="modal fade" id="editTermsModal" tabindex="-1" aria-labelledby="editTermsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="editTermsModalLabel">
                        <i class="bi bi-file-earmark-text text-warning me-2"></i> Badili / Weka Masharti ya Mkataba
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= Html::beginForm(['/booking/update-contract-terms', 'id' => $contract->id], 'post') ?>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Kama Mmiliki, unaweza kuandika au kubadili masharti rasmi ya mkataba huu. Masharti haya yataonekana kwenye mkataba rasmi na mpangaji atatia saini akikubaliana nayo.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Masharti na Kanuni (Terms &amp; Conditions):</label>
                        <textarea name="contract_terms" class="form-control font-monospace" rows="8" required><?= Html::encode($contract->contract_terms) ?></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Ghairi</button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Hifadhi &amp; Sasisha Mkataba
                    </button>
                </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
<?php endif; ?>

