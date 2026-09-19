<?php

/** @var yii\web\View $this */
/** @var array $wards */
/** @var array $results */
/** @var float|null $budget */
/** @var string|null $ward */
/** @var string|null $selectedInterest */
/** @var int|null $selectedLocationId */
/** @var app\models\User|null $user */
/** @var string|null $businessType */
/** @var string|null $businessVision */
/** @var string|null $targetCustomers */
/** @var float|null $startingBudget */
/** @var string|null $spaceSizeNeeded */
/** @var string|null $specialRequirements */
/** @var app\models\BusinessAnalysis|null $saved */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'fursa.page_title') . ' — MachoMtaa';

$loadingMessages = [
    Yii::t('app', 'fursa.loading.step1'),
    Yii::t('app', 'fursa.loading.step2'),
    Yii::t('app', 'fursa.loading.step3'),
    Yii::t('app', 'fursa.loading.step4'),
    Yii::t('app', 'fursa.loading.step5'),
];

$quickBusinessTypes = [
    'Saluni ya Kike / Kiume',
    'Mgahawa & Fast Food',
    'Duka la Dawa (Pharmacy)',
    'Duka la Nguo & Viatu',
    'Vifaa vya Simu & Elektroniki',
    'Minimarket / Grocery',
];
?>

<!-- Stripe-Grade Hero Header -->
<div class="mm-fursa-hero">
    <div class="mm-fursa-hero-inner">
        <div class="mm-fursa-eyebrow">
            <i class="bi bi-stars"></i> <?= Html::encode(Yii::t('app', 'fursa.hero_eyebrow')) ?>
        </div>

        <h1 class="mm-fursa-hero-title"><?= Html::encode(Yii::t('app', 'fursa.page_title')) ?></h1>

        <div class="mm-fursa-quote-box">
            <p class="mm-fursa-quote-text">
                "<?= Html::encode(Yii::t('app', 'fursa.page_heading')) ?>"
            </p>
        </div>

        <p class="mm-fursa-hero-lead">
            <?= Html::encode(Yii::t('app', 'fursa.hero_lead')) ?>
        </p>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="mm-fursa-stats-strip">
                <span class="mm-fursa-stat-pill">
                    <i class="bi bi-geo-alt-fill"></i> <?= Html::encode(Yii::t('app', 'fursa.stat_data_real')) ?>
                </span>
                <span class="mm-fursa-stat-pill">
                    <i class="bi bi-bar-chart-fill"></i> <?= Html::encode(Yii::t('app', 'fursa.stat_match_scores')) ?>
                </span>
                <span class="mm-fursa-stat-pill">
                    <i class="bi bi-shop-window"></i> <?= Html::encode(Yii::t('app', 'fursa.stat_spaces_ready')) ?>
                </span>
            </div>

            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="d-flex gap-2">
                    <a href="<?= Url::to(['/business/history']) ?>" class="btn btn-outline-light btn-sm fw-bold px-3 py-2 rounded-3">
                        <i class="bi bi-clock-history me-1"></i> <?= Yii::t('app', 'fursa.history.title') ?>
                    </a>
                    <a href="<?= Url::to(['/profile/index']) ?>" class="btn btn-outline-light btn-sm fw-bold px-3 py-2 rounded-3">
                        <i class="bi bi-person-gear me-1"></i> <?= Yii::t('app', 'fursa.update_profile') ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Guest Onboarding / Value Banner -->
<?php if (Yii::$app->user->isGuest): ?>
    <div class="mm-guest-card d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h5 class="mm-guest-card-title">
                <i class="bi bi-shield-lock-fill text-primary me-2"></i><?= Yii::t('app', 'fursa.guest_banner_title') ?>
            </h5>
            <p class="mm-guest-card-desc"><?= Yii::t('app', 'fursa.guest_banner_desc') ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= Url::to(['/site/signup', 'returnUrl' => Url::to(['/business/index'])]) ?>" class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-pill shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> <?= Yii::t('app', 'fursa.guest_cta_signup') ?>
            </a>
            <a href="<?= Url::to(['/site/login', 'returnUrl' => Url::to(['/business/index'])]) ?>" class="btn btn-sm btn-outline-secondary fw-bold px-3 py-2 rounded-pill">
                <i class="bi bi-box-arrow-in-right me-1"></i> <?= Yii::t('app', 'fursa.guest_cta_login') ?>
            </a>
        </div>
    </div>
<?php endif; ?>

<!-- Saved Notification -->
<?php if ($saved): ?>
    <div class="alert alert-success shadow-sm rounded-4 mb-4 d-flex align-items-center gap-2 p-3">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div>
            <strong><?= Yii::t('app', 'fursa.saved_to_history') ?></strong>
            <a href="<?= Url::to(['/business/view', 'id' => $saved->id]) ?>" class="ms-2 fw-bold text-success text-decoration-underline"><?= Yii::t('app', 'fursa.history.open') ?> &rarr;</a>
        </div>
    </div>
<?php endif; ?>

<!-- Profile Preferences Applied Alert -->
<?php if ($user && (!empty($user->business_interests) || !empty($user->preferred_amenities))): ?>
    <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2 p-3" style="background: rgba(14, 110, 92, 0.08);">
        <div>
            <i class="bi bi-person-check-fill text-teal fs-5 me-2"></i>
            <strong class="text-dark"><?= Yii::t('app', 'fursa.profile_used') ?>:</strong>
            <span class="text-secondary"><?= Yii::t('app', 'fursa.profile_used_body', ['interests' => Html::encode($user->business_interests)]) ?></span>
        </div>
        <a href="<?= Url::to(['/profile/index']) ?>" class="btn btn-sm btn-outline-primary fw-bold rounded-pill"><?= Yii::t('app', 'fursa.change_preferences') ?></a>
    </div>
<?php endif; ?>

<!-- 3-Step Stripe-Grade Form -->
<div class="mm-fursa-form-wrapper">
    <form method="post" id="fursa-form">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">

        <!-- Step 1: Business Concept -->
        <div class="mm-form-section">
            <div class="mm-section-header">
                <span class="mm-section-number">01</span>
                <div>
                    <h3 class="mm-section-title"><?= Yii::t('app', 'fursa.section_concept') ?></h3>
                    <p class="mm-section-sub"><?= Yii::t('app', 'fursa.section_concept_sub') ?></p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="mm-input-label"><i class="bi bi-briefcase-fill"></i> <?= Yii::t('app', 'fursa.field.business_type') ?> <span class="text-danger">*</span></label>
                    <input type="text" name="business_type" id="businessTypeInput" class="form-control mm-form-control" required
                           value="<?= Html::encode($businessType ?: '') ?>" placeholder="<?= Yii::t('app', 'fursa.field.business_type_ph') ?>">
                    
                    <div class="mm-quick-chips">
                        <span class="mm-quick-chip-label"><?= Yii::t('app', 'fursa.quick_types_label') ?></span>
                        <?php foreach ($quickBusinessTypes as $qt): ?>
                            <span class="mm-quick-chip" onclick="document.getElementById('businessTypeInput').value='<?= Html::encode($qt) ?>'"><?= Html::encode($qt) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="mm-input-label"><i class="bi bi-people-fill"></i> <?= Yii::t('app', 'fursa.field.target_customers') ?></label>
                    <input type="text" name="target_customers" class="form-control mm-form-control"
                           value="<?= Html::encode($targetCustomers ?: '') ?>" placeholder="<?= Yii::t('app', 'fursa.field.target_customers_ph') ?>">
                </div>

                <div class="col-12">
                    <label class="mm-input-label"><i class="bi bi-eye-fill"></i> <?= Yii::t('app', 'fursa.field.business_vision') ?></label>
                    <textarea name="business_vision" class="form-control mm-form-control" rows="2" placeholder="<?= Yii::t('app', 'fursa.field.business_vision_ph') ?>"><?= Html::encode($businessVision ?: '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Step 2: Location & Space Specifications -->
        <div class="mm-form-section">
            <div class="mm-section-header">
                <span class="mm-section-number">02</span>
                <div>
                    <h3 class="mm-section-title"><?= Yii::t('app', 'fursa.section_location') ?></h3>
                    <p class="mm-section-sub"><?= Yii::t('app', 'fursa.section_location_sub') ?></p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="mm-input-label"><i class="bi bi-geo-alt-fill text-danger"></i> <?= Yii::t('app', 'fursa.field.ward') ?> <span class="text-danger">*</span></label>
                    <select name="ward" class="form-select mm-form-select" required>
                        <option value=""><?= Yii::t('app', 'fursa.field.ward_placeholder') ?></option>
                        <?php foreach ($wards as $w): ?>
                            <option value="<?= Html::encode($w) ?>" <?= $ward === $w ? 'selected' : '' ?>>📍 <?= Html::encode($w) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted d-block mt-2"><?= Yii::t('app', 'fursa.field.ward_hint') ?></small>
                </div>

                <div class="col-md-4">
                    <label class="mm-input-label"><i class="bi bi-rulers"></i> <?= Yii::t('app', 'fursa.field.space_size') ?></label>
                    <input type="text" name="space_size_needed" class="form-control mm-form-control"
                           value="<?= Html::encode($spaceSizeNeeded ?: '') ?>" placeholder="<?= Yii::t('app', 'fursa.field.space_size_ph') ?>">
                </div>

                <div class="col-md-4">
                    <label class="mm-input-label"><i class="bi bi-check2-circle"></i> <?= Yii::t('app', 'fursa.field.special_requirements') ?></label>
                    <input type="text" name="special_requirements" class="form-control mm-form-control"
                           value="<?= Html::encode($specialRequirements ?: '') ?>" placeholder="<?= Yii::t('app', 'fursa.field.special_requirements_ph') ?>">
                </div>
            </div>
        </div>

        <!-- Step 3: Financial Parameters -->
        <div class="mm-form-section">
            <div class="mm-section-header">
                <span class="mm-section-number">03</span>
                <div>
                    <h3 class="mm-section-title"><?= Yii::t('app', 'fursa.section_finance') ?></h3>
                    <p class="mm-section-sub"><?= Yii::t('app', 'fursa.section_finance_sub') ?></p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="mm-input-label"><i class="bi bi-wallet2 text-success"></i> <?= Yii::t('app', 'fursa.field.starting_budget') ?></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold text-muted border-end-0">TSh</span>
                        <input type="number" name="starting_budget" class="form-control mm-form-control" min="0" step="50000"
                               value="<?= Html::encode($startingBudget ?: '') ?>" placeholder="<?= Yii::t('app', 'fursa.field.starting_budget_ph') ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="mm-input-label"><i class="bi bi-cash-stack text-success"></i> <?= Yii::t('app', 'fursa.field.rental_budget') ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold text-muted border-end-0">TSh</span>
                        <input type="number" name="budget" id="budgetInput" class="form-control mm-form-control fw-bold" required min="100000" step="50000"
                               value="<?= Html::encode($budget ?: (($user && $user->capital_budget) ? $user->capital_budget : '2000000')) ?>" placeholder="<?= Yii::t('app', 'fursa.field.rental_budget_ph') ?>">
                    </div>
                    
                    <div class="mm-budget-pills">
                        <span class="mm-quick-chip-label"><?= Yii::t('app', 'fursa.quick_budget_label') ?></span>
                        <span class="mm-budget-pill" onclick="document.getElementById('budgetInput').value=500000">500k</span>
                        <span class="mm-budget-pill" onclick="document.getElementById('budgetInput').value=1000000">1M</span>
                        <span class="mm-budget-pill" onclick="document.getElementById('budgetInput').value=2000000">2M</span>
                        <span class="mm-budget-pill" onclick="document.getElementById('budgetInput').value=3000000">3M</span>
                        <span class="mm-budget-pill" onclick="document.getElementById('budgetInput').value=5000000">5M</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit CTA Area -->
        <div class="mm-fursa-submit-area">
            <button type="submit" class="mm-submit-btn" id="fursa-submit-btn">
                <span><?= Yii::t('app', 'fursa.field.submit') ?></span>
                <i class="bi bi-arrow-right-circle-fill fs-5"></i>
            </button>
            <p class="mm-submit-trust">
                <i class="bi bi-shield-check text-success"></i> <?= Yii::t('app', 'fursa.submit_trust') ?>
            </p>
        </div>
    </form>
</div>

<!-- Progressive Loading Simulation Overlay -->
<div id="mm-fursa-loading" class="mm-fursa-loading" hidden>
    <div class="mm-fursa-loading-box">
        <div class="mm-spinner"></div>
        <p id="mm-fursa-loading-text"><?= Html::encode($loadingMessages[0]) ?></p>
    </div>
</div>

<!-- Results Area -->
<?php if (!empty($results['categories'])): ?>
    <?= $this->render('_results', [
        'results' => $results,
        'ward' => $ward,
        'budget' => $budget,
        'selectedLocationId' => $selectedLocationId,
    ]) ?>
<?php elseif (Yii::$app->request->isPost): ?>
    <div class="alert alert-warning p-4 rounded-4 shadow-sm mb-5">
        <div class="d-flex gap-3 align-items-center">
            <i class="bi bi-exclamation-triangle-fill display-5 text-warning"></i>
            <div>
                <h5 class="fw-bold mb-1"><?= Yii::t('app', 'fursa.no_results_title') ?></h5>
                <p class="mb-0"><?= Yii::t('app', 'fursa.no_results_body') ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Methodology & Strategic Guidance Disclaimer -->
<div class="card border-0 shadow-sm p-4 rounded-4 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
    <div class="d-flex gap-3 align-items-start">
        <i class="bi bi-info-circle-fill fs-3 text-teal mt-1"></i>
        <div>
            <h6 class="fw-bold text-dark mb-1"><?= Yii::t('app', 'fursa.disclaimer_title') ?></h6>
            <p class="small text-muted mb-0"><?= Yii::t('app', 'fursa.disclaimer_body') ?></p>
        </div>
    </div>
</div>

<?php
$loadingMessagesJson = str_replace('/', '\/', json_encode($loadingMessages, JSON_UNESCAPED_UNICODE));
$js = <<<JS
(function () {
    var form = document.getElementById('fursa-form');
    var overlay = document.getElementById('mm-fursa-loading');
    var textEl = document.getElementById('mm-fursa-loading-text');
    var messages = JSON.parse(document.getElementById('mm-fursa-loading-messages').textContent);
    if (!form || !overlay || !textEl) { return; }

    form.addEventListener('submit', function () {
        overlay.hidden = false;
        var i = 0;
        setInterval(function () {
            i = (i + 1) % messages.length;
            textEl.textContent = messages[i];
        }, 850);
    });
})();
JS;
$this->registerJs($js);
?>
<script type="application/json" id="mm-fursa-loading-messages"><?= $loadingMessagesJson ?></script>
