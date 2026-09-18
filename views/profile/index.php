<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var app\models\ProfileForm $profileForm */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Profile & Mapendekezo';

$interestsOptions = [
    'Mgahawa / Chakula' => ['label' => 'Mgahawa / Mgahawa wa Chakula / Fast Food', 'icon' => 'bi-cup-hot'],
    'Saluni / Beauty' => ['label' => 'Saluni ya Kike / Kinyozi / Body Beauty', 'icon' => 'bi-scissors'],
    'Duka la Nguo' => ['label' => 'Duka la Nguo / Viatu / Fashion', 'icon' => 'bi-bag-heart'],
    'Pharmacy / Dawa' => ['label' => 'Duka la Dawa (Pharmacy / ADDO)', 'icon' => 'bi-capsule'],
    'Hardware / Ujenzi' => ['label' => 'Hardware / Vifaa vya Ujenzi', 'icon' => 'bi-tools'],
    'Wakala / Ofisi' => ['label' => 'Ofisi ndogo / Wakala wa Fedha / Service Hub', 'icon' => 'bi-building'],
    'Car Wash / Garage' => ['label' => 'Car Wash / Auto Care', 'icon' => 'bi-car-front'],
    'Grocery / Mini Supermarket' => ['label' => 'Duka la Chakula / Grocery / Mini Market', 'icon' => 'bi-basket'],
];

$experienceOptions = [
    'beginner' => ['label' => 'Ndiyo Ninaanza', 'sub' => 'Sina uzoefu bado', 'icon' => 'bi-flag'],
    'under_1_year' => ['label' => 'Chini ya Mwaka 1', 'sub' => 'Bado najifunza', 'icon' => 'bi-hourglass-split'],
    '1_3_years' => ['label' => 'Miaka 1 - 3', 'sub' => 'Nina uzoefu fulani', 'icon' => 'bi-graph-up-arrow'],
    'over_3_years' => ['label' => 'Zaidi ya Miaka 3', 'sub' => 'Nina uzoefu mkubwa', 'icon' => 'bi-award'],
];

$amenitiesOptions = [
    'water' => ['label' => 'Maji ya Uhakika', 'icon' => 'bi-droplet-fill'],
    'electricity' => ['label' => 'Umeme LUKU', 'icon' => 'bi-lightning-charge-fill'],
    'parking' => ['label' => 'Nafasi ya Parking', 'icon' => 'bi-p-square-fill'],
    'security' => ['label' => 'Ulinzi / Fensi', 'icon' => 'bi-shield-lock-fill'],
    'internet' => ['label' => 'WiFi / Internet', 'icon' => 'bi-wifi'],
];
?>

<style>
    /* ---- Banner ya kushawishi (influence) ---- */
    .el-vision-banner {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: linear-gradient(120deg, #0d6efd, #6f42c1);
        color: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.6rem;
        margin-bottom: 1.75rem;
        box-shadow: 0 10px 30px rgba(13,110,253,.25);
    }
    .el-vision-banner i.bi-stars { font-size: 2rem; flex-shrink: 0; }
    .el-vision-banner .vision-title { font-size: 1.25rem; font-weight: 800; line-height: 1.3; }
    .el-vision-banner .vision-sub { font-size: .95rem; color: rgba(255,255,255,.9); margin-top: .15rem; }

    /* ---- Wizard ---- */
    .wizard-card { border: none; border-radius: 18px; overflow: hidden; }
    .wizard-progress { display: flex; align-items: center; gap: .5rem; padding: 1.25rem 1.5rem 0; }
    .wizard-progress .dot-wrap { flex: 1; }
    .wizard-progress .dot-bar { height: 6px; border-radius: 99px; background: #e9edf5; overflow: hidden; }
    .wizard-progress .dot-bar > span { display: block; height: 100%; background: linear-gradient(90deg,#0d6efd,#22c55e); width: 0%; transition: width .25s ease; }
    .wizard-progress .step-label { font-size: .8rem; font-weight: 700; color: #6c7a92; white-space: nowrap; }

    .wizard-body { padding: 1.75rem 1.5rem 2rem; min-height: 340px; }
    .wizard-step { display: none; animation: elFadeIn .28s ease; }
    .wizard-step.active { display: block; }
    @keyframes elFadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    .wizard-question { font-size: 1.15rem; font-weight: 800; color: #1a2233; margin-bottom: .25rem; }
    .wizard-hint { color: #6c7a92; font-size: .9rem; margin-bottom: 1.5rem; }

    .opt-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: .9rem; }
    .opt-card-input { position: absolute; width: 1px; height: 1px; opacity: 0; }
    .opt-card-body {
        display: flex; flex-direction: column; align-items: center; text-align: center; gap: .5rem;
        border: 2px solid #e6eaf2; border-radius: 14px; padding: 1.1rem .75rem; cursor: pointer;
        transition: all .15s ease; background: #fff; height: 100%;
    }
    .opt-card-body i { font-size: 1.6rem; color: #6c7a92; transition: color .15s ease; }
    .opt-card-body .opt-card-label { font-weight: 700; font-size: .88rem; color: #33415c; }
    .opt-card-body .opt-card-sub { font-size: .76rem; color: #8a95a8; }
    .opt-card-body:hover { border-color: #a9c4ff; transform: translateY(-2px); }
    .opt-card-input:checked + .opt-card-body { border-color: #0d6efd; background: #eef4ff; box-shadow: 0 6px 16px rgba(13,110,253,.15); }
    .opt-card-input:checked + .opt-card-body i,
    .opt-card-input:checked + .opt-card-body .opt-card-label { color: #0d6efd; }
    .opt-card-input:focus-visible + .opt-card-body { outline: 2px solid #0d6efd; outline-offset: 2px; }

    .chip-wrap { display: flex; flex-wrap: wrap; gap: .65rem; }
    .chip-input { position: absolute; width: 1px; height: 1px; opacity: 0; }
    .chip-body {
        display: inline-flex; align-items: center; gap: .45rem; padding: .55rem 1.1rem;
        border-radius: 99px; border: 2px solid #e6eaf2; background: #fff; cursor: pointer;
        font-weight: 700; font-size: .88rem; color: #33415c; transition: all .15s ease;
    }
    .chip-body:hover { border-color: #a9c4ff; }
    .chip-input:checked + .chip-body { background: #0d6efd; border-color: #0d6efd; color: #fff; }
    .chip-input:focus-visible + .chip-body { outline: 2px solid #0d6efd; outline-offset: 2px; }

    .wizard-nav { display: flex; justify-content: space-between; align-items: center; padding: 1.1rem 1.5rem; background: #f7f9fc; border-top: 1px solid #eef1f6; }
</style>

<div class="el-page-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <div class="el-avatar shadow-lg" style="width:72px; height:72px; font-size:2rem; border:3px solid #fff">
                <?= Html::encode(mb_strtoupper(mb_substr($user->username, 0, 1))) ?>
            </div>
            <div>
                <h1 class="mb-1"><?= Html::encode($user->username) ?></h1>
                <p class="mb-0">
                    <span class="badge badge-role-admin me-2 fs-6"><i class="bi bi-shield-lock me-1"></i><?= ucfirst($user->role) ?></span>
                    <span class="text-white-50"><i class="bi bi-calendar3 me-1"></i>Member since <?= date('M d, Y', $user->created_at) ?></span>
                </p>
            </div>
        </div>
        <a href="<?= Url::to(['/profile/settings']) ?>" class="btn btn-outline-light btn-sm fw-bold">
            <i class="bi bi-gear-fill me-1"></i> Account Settings
        </a>
    </div>
</div>

<div class="el-vision-banner">
    <i class="bi bi-stars"></i>
    <div>
        <div class="vision-title">Tuambie maono yako</div>
        <div class="vision-sub">kwa ajili ya mapendekezo bora kwa ndoto zako</div>
    </div>
</div>

<div class="card shadow-sm wizard-card">
    <div class="card-body p-0">
        <?php $form = ActiveForm::begin(['id' => 'profile-form']); ?>

            <div class="wizard-progress">
                <span class="step-label" id="wizardStepLabel">Hatua 1 ya 4</span>
                <div class="dot-wrap"><div class="dot-bar"><span id="wizardBar" style="width:25%"></span></div></div>
            </div>

            <div class="wizard-body">

                <!-- HATUA 1: Shauku Yako (KWANINI) -->
                <div class="wizard-step active" data-step="1">
                    <div class="wizard-question">Ni sekta gani ya biashara unayoipenda zaidi?</div>
                    <div class="wizard-hint">Unaweza kuchagua zaidi ya moja — hii itatusaidia kukupa mapendekezo sahihi.</div>
                    <div class="opt-grid">
                        <?php foreach ($interestsOptions as $key => $opt):
                            $checked = in_array($key, (array)$profileForm->business_interests, true) ? 'checked' : '';
                        ?>
                            <label>
                                <input class="opt-card-input" type="checkbox" name="ProfileForm[business_interests][]" value="<?= Html::encode($key) ?>" <?= $checked ?>>
                                <span class="opt-card-body">
                                    <i class="bi <?= $opt['icon'] ?>"></i>
                                    <span class="opt-card-label"><?= Html::encode($opt['label']) ?></span>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- HATUA 2: Uzoefu Wako -->
                <div class="wizard-step" data-step="2">
                    <div class="wizard-question">Una uzoefu wa muda gani kwenye biashara hii?</div>
                    <div class="wizard-hint">Chagua kinachokufaa zaidi kwa sasa.</div>
                    <div class="opt-grid">
                        <?php foreach ($experienceOptions as $key => $opt):
                            $checked = ($profileForm->experience_level === $key) ? 'checked' : '';
                        ?>
                            <label>
                                <input class="opt-card-input" type="radio" name="ProfileForm[experience_level]" value="<?= Html::encode($key) ?>" <?= $checked ?>>
                                <span class="opt-card-body">
                                    <i class="bi <?= $opt['icon'] ?>"></i>
                                    <span class="opt-card-label"><?= Html::encode($opt['label']) ?></span>
                                    <span class="opt-card-sub"><?= Html::encode($opt['sub']) ?></span>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- HATUA 3: Miundombinu (VIPI) -->
                <div class="wizard-step" data-step="3">
                    <div class="wizard-question">Ni miundombinu gani ya lazima ili biashara yako ifanikiwe?</div>
                    <div class="wizard-hint">Chagua zote zinazokufaa.</div>
                    <div class="chip-wrap">
                        <?php foreach ($amenitiesOptions as $key => $opt):
                            $checked = in_array($key, (array)$profileForm->preferred_amenities, true) ? 'checked' : '';
                        ?>
                            <label>
                                <input class="chip-input" type="checkbox" name="ProfileForm[preferred_amenities][]" value="<?= Html::encode($key) ?>" <?= $checked ?>>
                                <span class="chip-body"><i class="bi <?= $opt['icon'] ?>"></i> <?= Html::encode($opt['label']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- HATUA 4: Eneo na Mtaji -->
                <div class="wizard-step" data-step="4">
                    <div class="wizard-question">Eneo lako na mtaji ulionao</div>
                    <div class="wizard-hint">Hii itatusaidia kukuletea mapendekezo yanayolingana na uwezo wako.</div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark"><i class="bi bi-geo-alt-fill me-1 text-danger"></i> Eneo Unalopendelea</label>
                        <input type="text" class="form-control form-control-lg" name="ProfileForm[preferred_location]"
                               value="<?= Html::encode($profileForm->preferred_location) ?>" placeholder="mf. Kariakoo, Kinondoni, Dar es Salaam">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark"><i class="bi bi-cash-stack me-1 text-success"></i> Mtaji Ulionao (TZS)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light fw-bold text-muted">TSh</span>
                            <input type="number" min="0" step="10000" class="form-control" name="ProfileForm[capital_budget]"
                                   value="<?= Html::encode($profileForm->capital_budget) ?>" placeholder="mf. 1,500,000">
                        </div>
                    </div>
                </div>

            </div>

            <div class="wizard-nav">
                <button type="button" class="btn btn-outline-secondary px-4" id="wizardBackBtn" style="visibility:hidden;">
                    <i class="bi bi-arrow-left me-1"></i> Rudi Nyuma
                </button>
                <button type="button" class="btn btn-primary px-4 shadow-sm" id="wizardNextBtn">
                    Endelea <i class="bi bi-arrow-right ms-1"></i>
                </button>
                <button type="submit" class="btn btn-primary px-4 shadow-sm" id="wizardSubmitBtn" style="display:none;">
                    <i class="bi bi-check-circle me-1"></i> Hifadhi Mapendekezo
                </button>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$js = <<<JS
(function () {
    var steps = Array.prototype.slice.call(document.querySelectorAll('.wizard-step'));
    if (!steps.length) return;

    var total = steps.length;
    var current = 1;
    var label = document.getElementById('wizardStepLabel');
    var bar = document.getElementById('wizardBar');
    var backBtn = document.getElementById('wizardBackBtn');
    var nextBtn = document.getElementById('wizardNextBtn');
    var submitBtn = document.getElementById('wizardSubmitBtn');

    function render() {
        steps.forEach(function (el) {
            el.classList.toggle('active', parseInt(el.dataset.step, 10) === current);
        });
        label.textContent = 'Hatua ' + current + ' ya ' + total;
        bar.style.width = Math.round((current / total) * 100) + '%';
        backBtn.style.visibility = current === 1 ? 'hidden' : 'visible';
        var isLast = current === total;
        nextBtn.style.display = isLast ? 'none' : 'inline-block';
        submitBtn.style.display = isLast ? 'inline-block' : 'none';
        steps[current - 1].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }

    nextBtn.addEventListener('click', function () {
        if (current < total) { current++; render(); }
    });
    backBtn.addEventListener('click', function () {
        if (current > 1) { current--; render(); }
    });

    render();
})();
JS;
$this->registerJs($js);
?>
