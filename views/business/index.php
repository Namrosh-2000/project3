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

$this->title = Yii::t('app', 'fursa.page_title');

$loadingMessages = [
    Yii::t('app', 'fursa.loading.step1'),
    Yii::t('app', 'fursa.loading.step2'),
    Yii::t('app', 'fursa.loading.step3'),
    Yii::t('app', 'fursa.loading.step4'),
    Yii::t('app', 'fursa.loading.step5'),
];
?>

<div class="el-page-header mm-fursa-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-graph-up-arrow text-warning me-2"></i><?= Html::encode(Yii::t('app', 'fursa.page_title')) ?></h1>
            <p><?= Yii::t('app', 'fursa.page_heading') ?></p>
        </div>
        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="d-flex gap-2">
                <a href="<?= Url::to(['/business/history']) ?>" class="btn btn-outline-light btn-sm fw-bold">
                    <i class="bi bi-clock-history me-1"></i> <?= Yii::t('app', 'dash.entrepreneur.history') ?>
                </a>
                <a href="<?= Url::to(['/profile/index']) ?>" class="btn btn-outline-light btn-sm fw-bold">
                    <i class="bi bi-gear-fill me-1"></i> <?= Yii::t('app', 'fursa.update_profile') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($user && (!empty($user->business_interests) || !empty($user->preferred_amenities))): ?>
    <div class="alert alert-info border-info shadow-sm rounded-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <i class="bi bi-person-check-fill text-primary fs-5 me-2"></i>
            <strong><?= Yii::t('app', 'fursa.profile_used') ?>:</strong>
            <?= Yii::t('app', 'fursa.profile_used_body', ['interests' => Html::encode($user->business_interests)]) ?>
        </div>
        <a href="<?= Url::to(['/profile/index']) ?>" class="btn btn-sm btn-primary fw-bold"><?= Yii::t('app', 'fursa.change_preferences') ?></a>
    </div>
<?php endif; ?>

<?php if ($saved): ?>
    <div class="alert alert-success shadow-sm rounded-3 mb-4">
        <i class="bi bi-check-circle-fill me-1"></i> <?= Yii::t('app', 'fursa.saved_to_history') ?>
    </div>
<?php endif; ?>

<div class="card shadow-md border-0 mb-5 mm-fursa-form-card">
    <div class="card-body p-4 p-md-5">
        <h4 class="fw-bold mb-3 text-dark"><i class="bi bi-sliders text-primary me-2"></i><?= Yii::t('app', 'fursa.form_heading') ?></h4>
        <p class="text-muted mb-4"><?= Yii::t('app', 'fursa.form_sub') ?></p>

        <form method="post" id="fursa-form">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark"><i class="bi bi-briefcase-fill text-primary me-1"></i> <?= Yii::t('app', 'fursa.field.business_type') ?></label>
                    <input type="text" name="business_type" class="form-control form-control-lg" required
                           value="<?= Html::encode($businessType ?: '') ?>" placeholder="<?= Yii::t('app', 'fursa.field.business_type_ph') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark"><i class="bi bi-people-fill text-primary me-1"></i> <?= Yii::t('app', 'fursa.field.target_customers') ?></label>
                    <input type="text" name="target_customers" class="form-control form-control-lg"
                           value="<?= Html::encode($targetCustomers ?: '') ?>" placeholder="<?= Yii::t('app', 'fursa.field.target_customers_ph') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold text-dark"><i class="bi bi-eye-fill text-primary me-1"></i> <?= Yii::t('app', 'fursa.field.business_vision') ?></label>
                    <textarea name="business_vision" class="form-control" rows="2" placeholder="<?= Yii::t('app', 'fursa.field.business_vision_ph') ?>"><?= Html::encode($businessVision ?: '') ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= Yii::t('app', 'fursa.field.ward') ?></label>
                    <select name="ward" class="form-select form-select-lg fw-bold" required>
                        <option value=""><?= Yii::t('app', 'fursa.field.ward_placeholder') ?></option>
                        <?php foreach ($wards as $w): ?>
                            <option value="<?= Html::encode($w) ?>" <?= $ward === $w ? 'selected' : '' ?>>📍 <?= Html::encode($w) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted d-block mt-2"><?= Yii::t('app', 'fursa.field.ward_hint') ?></small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark"><i class="bi bi-wallet2 text-success me-1"></i> <?= Yii::t('app', 'fursa.field.starting_budget') ?></label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light fw-bold text-muted">TSh</span>
                        <input type="number" name="starting_budget" class="form-control" min="0" step="50000"
                               value="<?= Html::encode($startingBudget ?: '') ?>" placeholder="<?= Yii::t('app', 'fursa.field.starting_budget_ph') ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark"><i class="bi bi-cash-stack text-success me-1"></i> <?= Yii::t('app', 'fursa.field.rental_budget') ?></label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light fw-bold text-muted">TSh</span>
                        <input type="number" name="budget" id="budgetInput" class="form-control fw-bold" required min="100000" step="50000"
                               value="<?= Html::encode($budget ?: (($user && $user->capital_budget) ? $user->capital_budget : '2000000')) ?>" placeholder="<?= Yii::t('app', 'fursa.field.rental_budget_ph') ?>">
                    </div>
                    <div class="d-flex gap-2 mt-2 flex-wrap">
                        <span class="badge bg-light text-dark border cursor-pointer" onclick="document.getElementById('budgetInput').value=500000">500k</span>
                        <span class="badge bg-light text-dark border cursor-pointer" onclick="document.getElementById('budgetInput').value=1000000">1M</span>
                        <span class="badge bg-light text-dark border cursor-pointer" onclick="document.getElementById('budgetInput').value=2500000">2.5M</span>
                        <span class="badge bg-light text-dark border cursor-pointer" onclick="document.getElementById('budgetInput').value=5000000">5M</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark"><i class="bi bi-rulers text-primary me-1"></i> <?= Yii::t('app', 'fursa.field.space_size') ?></label>
                    <input type="text" name="space_size_needed" class="form-control form-control-lg"
                           value="<?= Html::encode($spaceSizeNeeded ?: '') ?>" placeholder="<?= Yii::t('app', 'fursa.field.space_size_ph') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark"><i class="bi bi-tag-fill text-warning me-1"></i> <?= Yii::t('app', 'fursa.field.special_requirements') ?></label>
                    <input type="text" name="special_requirements" class="form-control form-control-lg"
                           value="<?= Html::encode($specialRequirements ?: '') ?>" placeholder="<?= Yii::t('app', 'fursa.field.special_requirements_ph') ?>">
                </div>

                <div class="col-12 text-end mt-4">
                    <button class="btn btn-warning btn-lg px-5 fw-bold shadow-sm text-dark" id="fursa-submit-btn">
                        <i class="bi bi-lightbulb-fill me-1"></i> <?= Yii::t('app', 'fursa.field.submit') ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mm-fursa-loading" class="mm-fursa-loading" hidden>
    <div class="mm-fursa-loading-box">
        <div class="mm-spinner"></div>
        <p id="mm-fursa-loading-text"><?= Html::encode($loadingMessages[0]) ?></p>
    </div>
</div>

<?php if (!empty($results['categories'])): ?>
    <?= $this->render('_results', [
        'results' => $results,
        'ward' => $ward,
        'budget' => $budget,
        'selectedLocationId' => $selectedLocationId,
    ]) ?>
<?php elseif (Yii::$app->request->isPost): ?>
    <div class="alert alert-warning p-4 rounded-3 shadow-sm mb-5">
        <div class="d-flex gap-3 align-items-center">
            <i class="bi bi-exclamation-triangle-fill display-5 text-warning"></i>
            <div>
                <h5 class="fw-bold mb-1"><?= Yii::t('app', 'fursa.no_results_title') ?></h5>
                <p class="mb-0"><?= Yii::t('app', 'fursa.no_results_body') ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card bg-primary-subtle border-primary-subtle p-4 rounded-3 mb-4">
    <div class="d-flex gap-3 align-items-start">
        <i class="bi bi-info-circle-fill fs-3 text-primary mt-1"></i>
        <div>
            <h6 class="fw-bold text-primary mb-1"><?= Yii::t('app', 'fursa.disclaimer_title') ?></h6>
            <p class="small text-secondary mb-0"><?= Yii::t('app', 'fursa.disclaimer_body') ?></p>
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
        }, 900);
    });
})();
JS;
$this->registerJs($js);
?>
<script type="application/json" id="mm-fursa-loading-messages"><?= $loadingMessagesJson ?></script>
