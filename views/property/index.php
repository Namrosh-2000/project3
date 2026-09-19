<?php

/** @var yii\web\View $this */
/** @var app\models\PropertySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = Yii::t('app', 'fremu.page_title');

$currentParams = Yii::$app->request->queryParams;
$sortUrl = function ($sortValue) use ($currentParams) {
    return Url::to(array_merge(['/property/index'], $currentParams, ['sort' => $sortValue]));
};
?>

<!-- Stripe-Grade Marketplace Hero -->
<div class="mm-fursa-hero mb-4">
    <div class="mm-fursa-hero-inner">
        <div class="mm-fursa-eyebrow">
            <i class="bi bi-shop"></i> <?= Html::encode(Yii::t('app', 'fremu.hero_eyebrow')) ?>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mm-fursa-hero-title mb-2"><?= Html::encode(Yii::t('app', 'fremu.hero_heading')) ?></h1>
                <p class="mm-fursa-hero-lead mb-3"><?= Html::encode(Yii::t('app', 'fremu.hero_lead')) ?></p>
            </div>
        </div>

        <!-- Cross-Promotion / Intelligence Callout -->
        <div class="p-3 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(226, 163, 61, 0.3);">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-stars text-warning fs-4"></i>
                <span class="text-white small fw-bold"><?= Yii::t('app', 'fremu.intel_callout') ?></span>
            </div>
            <a href="<?= Url::to(['/business/index']) ?>" class="btn btn-warning btn-sm fw-bold px-3 py-1 rounded-pill text-dark shadow-sm">
                <i class="bi bi-graph-up-arrow me-1"></i> <?= Yii::t('app', 'nav.fursa') ?>
            </a>
        </div>
    </div>
</div>

<div class="row g-4 el-listing-page">
    <!-- Filter Sidebar -->
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px; border: 1px solid #e2e8f0;">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-funnel-fill text-teal me-2"></i><?= Yii::t('app', 'fremu.filter_title') ?></h5>
                <a href="<?= Url::to(['/property/index']) ?>" class="small text-danger fw-bold text-decoration-none"><?= Yii::t('app', 'fremu.filter_reset') ?></a>
            </div>

            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['/property/index'],
                'id' => 'filter-form',
            ]); ?>

            <?= $form->field($searchModel, 'keyword', [
                'inputOptions' => ['class' => 'form-control mm-form-control', 'placeholder' => 'mfano: Sinza, Mwenge, Mikocheni']
            ])->label('<i class="bi bi-search me-1 text-teal"></i> ' . Yii::t('app', 'fremu.filter_keyword'), ['class' => 'mm-input-label']) ?>

            <?= $form->field($searchModel, 'listing_type')->dropDownList([
                '' => Yii::t('app', 'fremu.filter_type_any'),
                'rent' => Yii::t('app', 'fremu.filter_type_rent'),
                'sale' => Yii::t('app', 'fremu.filter_type_sale'),
            ], ['class' => 'form-select mm-form-select'])->label('<i class="bi bi-tag me-1 text-teal"></i> ' . Yii::t('app', 'fremu.filter_type'), ['class' => 'mm-input-label']) ?>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <?= $form->field($searchModel, 'min_price')->textInput(['class' => 'form-control mm-form-control', 'placeholder' => 'Min TSh', 'type' => 'number'])->label(Yii::t('app', 'fremu.filter_price_min'), ['class' => 'small fw-bold text-muted']) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($searchModel, 'max_price')->textInput(['class' => 'form-control mm-form-control', 'placeholder' => 'Max TSh', 'type' => 'number'])->label(Yii::t('app', 'fremu.filter_price_max'), ['class' => 'small fw-bold text-muted']) ?>
                </div>
            </div>

            <label class="mm-input-label mb-2"><i class="bi bi-check2-square text-teal me-1"></i> <?= Yii::t('app', 'fremu.filter_amenities') ?></label>
            <div class="d-flex flex-column gap-2 mb-4 p-3 bg-light rounded-3 border">
                <div class="form-check"><?= $form->field($searchModel, 'has_water', ['options' => ['tag' => false]])->checkbox(['label' => 'Maji Safi ya Uhakika']) ?></div>
                <div class="form-check"><?= $form->field($searchModel, 'has_electricity', ['options' => ['tag' => false]])->checkbox(['label' => 'Umeme (LUKU)']) ?></div>
                <div class="form-check"><?= $form->field($searchModel, 'has_parking', ['options' => ['tag' => false]])->checkbox(['label' => 'Maegesho ya Magari']) ?></div>
                <div class="form-check"><?= $form->field($searchModel, 'has_security', ['options' => ['tag' => false]])->checkbox(['label' => 'Ulinzi / Fensi']) ?></div>
                <div class="form-check"><?= $form->field($searchModel, 'has_internet', ['options' => ['tag' => false]])->checkbox(['label' => 'Mtandao / WiFi']) ?></div>
            </div>

            <button class="btn btn-primary w-100 fw-bold py-2 shadow-sm rounded-3">
                <i class="bi bi-funnel me-1"></i> <?= Yii::t('app', 'fremu.filter_apply') ?>
            </button>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <!-- Listings Grid -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center p-3 bg-white border rounded-4 mb-4 shadow-sm flex-wrap gap-2">
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-grid-fill text-teal me-2"></i><?= Yii::t('app', 'fremu.total_available', ['count' => number_format($dataProvider->getTotalCount())]) ?>
            </h5>
            <div class="d-flex gap-2 align-items-center">
                <select class="form-select form-select-sm mm-form-select" style="width: auto;" onchange="location.href=this.value">
                    <option value="<?= $sortUrl('-created_at') ?>"><?= Yii::t('app', 'fremu.sort_newest') ?></option>
                    <option value="<?= $sortUrl('price') ?>"><?= Yii::t('app', 'fremu.sort_price_low') ?></option>
                    <option value="<?= $sortUrl('-price') ?>"><?= Yii::t('app', 'fremu.sort_price_high') ?></option>
                </select>
            </div>
        </div>

        <?php if ($dataProvider->getTotalCount() === 0): ?>
            <div class="card py-5 text-center shadow-sm border-0 rounded-4" style="border: 1px dashed #cbd5e1 !important;">
                <div class="card-body">
                    <i class="bi bi-search text-teal display-3 d-block mb-3"></i>
                    <h5 class="fw-bold text-dark"><?= Yii::t('app', 'fremu.no_results_title') ?></h5>
                    <p class="text-muted"><?= Yii::t('app', 'fremu.no_results_body') ?></p>
                    <a href="<?= Url::to(['/property/index']) ?>" class="btn btn-outline-primary px-4 rounded-pill fw-bold">
                        <?= Yii::t('app', 'fremu.filter_reset') ?>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($dataProvider->getModels() as $p): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-lift" style="border: 1px solid #e2e8f0 !important;">
                            <?php if (!empty($p->coverImage)): ?>
                                <img src="<?= $p->coverImage->getImageUrl() ?>" alt="<?= Html::encode($p->title) ?>" class="w-100" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="w-100 d-flex align-items-center justify-content-center bg-light text-muted" style="height: 200px">
                                    <i class="bi bi-shop text-muted" style="font-size: 2.5rem"></i>
                                </div>
                            <?php endif; ?>

                            <div class="card-body p-3 d-flex flex-direction-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-light text-dark border fw-bold small">
                                            <?= Html::encode($p->category->name ?? 'Fremu') ?>
                                        </span>
                                        <span class="badge bg-teal-subtle text-teal fw-bold">
                                            <i class="bi bi-patch-check-fill me-1"></i><?= Yii::t('app', 'fremu.verified_badge') ?>
                                        </span>
                                    </div>

                                    <h6 class="fw-bold mb-1 text-dark text-truncate" title="<?= Html::encode($p->title) ?>"><?= Html::encode($p->title) ?></h6>
                                    <small class="text-muted d-block mb-2">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= Html::encode($p->location->ward ?? 'Kinondoni') ?>
                                    </small>

                                    <div class="mt-2 text-success fw-bold fs-5">
                                        TSh <?= number_format((float)$p->price) ?>
                                        <small class="text-muted fw-normal fs-6">/ <?= $p->price_period ?: 'mwezi' ?></small>
                                    </div>
                                </div>

                                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                    <a href="<?= Url::to(['/property/view', 'id' => $p->id]) ?>" class="btn btn-sm btn-primary fw-bold px-3 rounded-pill">
                                        <?= Yii::t('app', 'fremu.view_details') ?> &rarr;
                                    </a>
                                    <div class="d-flex gap-1">
                                        <?php if (!Yii::$app->user->isGuest): ?>
                                            <?= Html::beginForm(['/property/toggle-favorite', 'id' => $p->id], 'post', ['style' => 'display:inline']) ?>
                                                <button class="btn btn-sm btn-light border rounded-circle" title="Hifadhi kwenye vipendwa"><i class="bi bi-heart"></i></button>
                                            <?= Html::endForm() ?>
                                        <?php endif; ?>
                                        <a href="<?= Url::to(['/property/compare', 'ids[]' => $p->id]) ?>" class="btn btn-sm btn-light border rounded-circle" title="Linganisha">
                                            <i class="bi bi-bar-chart-steps"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                <?= LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
                    'options' => ['class' => 'pagination justify-content-center'],
                    'linkContainerOptions' => ['class' => 'page-item'],
                    'linkOptions' => ['class' => 'page-link'],
                ]) ?>
            </div>
        <?php endif; ?>
    </div>
</div>