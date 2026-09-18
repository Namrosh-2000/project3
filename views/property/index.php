<?php

/** @var yii\web\View $this */
/** @var app\models\PropertySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->registerCssFile('@web/css/listings.css');

$this->title = 'Find Properties in Kinondoni';

$currentParams = Yii::$app->request->queryParams;
$sortUrl = function ($sortValue) use ($currentParams) {
    return Url::to(array_merge(['/property/index'], $currentParams, ['sort' => $sortValue]));
};
?>

<div class="row g-4 el-listing-page">
    <div class="col-lg-3">
        <div class="el-filter-card sticky-top shadow-sm" style="top: 90px">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="el-heading mb-0"><i class="bi bi-funnel-fill text-primary me-2"></i>Filter Properties</h5>
                <a href="<?= Url::to(['/property/index']) ?>" class="small text-danger font-weight-bold">Reset</a>
            </div>

            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['/property/index'],
                'id' => 'filter-form',
            ]); ?>

            <?= $form->field($searchModel, 'keyword', [
                'inputOptions' => ['placeholder' => 'e.g. Sinza, room, apartment']
            ])->label('<i class="bi bi-search me-1"></i> Keyword / Ward') ?>

            <?= $form->field($searchModel, 'listing_type')->dropDownList([
                '' => 'Rent or Sale (Any)',
                'rent' => 'Kukodisha (Rent)',
                'sale' => 'Kuuza (Sale)',
            ])->label('<i class="bi bi-tag me-1"></i> Listing Type') ?>

            <div class="row g-2">
                <div class="col-6"><?= $form->field($searchModel, 'min_price')->textInput(['placeholder' => 'Min TSh', 'type' => 'number']) ?></div>
                <div class="col-6"><?= $form->field($searchModel, 'max_price')->textInput(['placeholder' => 'Max TSh', 'type' => 'number']) ?></div>
            </div>

            <?= $form->field($searchModel, 'bedrooms')->dropDownList([
                '' => 'Any bedrooms',
                1 => '1+ Bedrooms',
                2 => '2+ Bedrooms',
                3 => '3+ Bedrooms',
            ]) ?>

            <div class="el-filter-section-label"><i class="bi bi-check2-square me-1"></i> Desired Amenities</div>
            <div class="d-flex flex-column gap-2 mb-3">
                <div class="form-check"><?= $form->field($searchModel, 'is_furnished', ['options' => ['tag' => false]])->checkbox(['label' => 'Furnished']) ?></div>
                <div class="form-check"><?= $form->field($searchModel, 'has_parking', ['options' => ['tag' => false]])->checkbox(['label' => 'Parking Space']) ?></div>
                <div class="form-check"><?= $form->field($searchModel, 'has_water', ['options' => ['tag' => false]])->checkbox(['label' => 'Running Water']) ?></div>
                <div class="form-check"><?= $form->field($searchModel, 'has_electricity', ['options' => ['tag' => false]])->checkbox(['label' => 'Electricity']) ?></div>
                <div class="form-check"><?= $form->field($searchModel, 'has_security', ['options' => ['tag' => false]])->checkbox(['label' => 'Security Fence']) ?></div>
                <div class="form-check"><?= $form->field($searchModel, 'has_internet', ['options' => ['tag' => false]])->checkbox(['label' => 'WiFi / Internet']) ?></div>
            </div>

            <button class="btn btn-primary w-100 fw-bold shadow-sm">
                <i class="bi bi-funnel me-1"></i> Apply Filters
            </button>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="el-listing-header p-3 bg-white border rounded-3 mb-4 shadow-sm">
            <h4 class="el-heading el-listing-count mb-0">
                <strong><?= number_format($dataProvider->getTotalCount()) ?></strong> Properties Available
            </h4>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <select class="form-select form-select-sm el-sort-select" onchange="location.href=this.value">
                    <option value="<?= $sortUrl('-created_at') ?>">Newest Listed</option>
                    <option value="<?= $sortUrl('price') ?>">Price: Low to High</option>
                    <option value="<?= $sortUrl('-price') ?>">Price: High to Low</option>
                </select>
            </div>
        </div>

        <?php if ($dataProvider->getTotalCount() === 0): ?>
            <div class="card py-5 text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="bi bi-search text-muted display-3 d-block mb-3"></i>
                    <h5 class="fw-bold">No Matching Properties Found</h5>
                    <p class="text-muted">Try adjusting your filter search criteria or explore nearby wards in Kinondoni.</p>
                    <a href="<?= Url::to(['/property/index']) ?>" class="btn btn-outline-primary px-4">
                        Reset All Filters
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($dataProvider->getModels() as $p): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="el-prop h-100 position-relative shadow-sm hover-lift">
                            <?php if (!empty($p->coverImage)): ?>
                                <img src="<?= $p->coverImage->getImageUrl() ?>" alt="<?= Html::encode($p->title) ?>">
                            <?php else: ?>
                                <div class="el-prop-noimg d-flex align-items-center justify-content-center">
                                    <i class="bi bi-house-door text-muted" style="font-size:2rem"></i>
                                </div>
                            <?php endif; ?>

                            <div class="el-prop-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-primary-subtle text-primary border border-primary">
                                        <?= Html::encode($p->category->name ?? 'Property') ?>
                                    </span>
                                    <span class="badge bg-dark text-white"><?= ucfirst($p->listing_type) ?></span>
                                </div>

                                <h5 class="fw-bold mb-1 text-dark line-clamp-1"><?= Html::encode($p->title) ?></h5>
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= Html::encode($p->location->ward ?? 'Kinondoni') ?>
                                </small>

                                <div class="mt-2 el-prop-price fs-5">
                                    TSh <?= number_format((float)$p->price) ?>
                                    <small class="text-muted fw-normal fs-6">/ <?= $p->price_period ?: 'month' ?></small>
                                </div>

                                <div class="mt-3 d-flex flex-wrap gap-1">
                                    <?php if ($p->is_furnished): ?><span class="el-badge">Furnished</span><?php endif; ?>
                                    <?php if ($p->bedrooms): ?><span class="el-badge"><?= $p->bedrooms ?> BR</span><?php endif; ?>
                                    <span class="el-verified"><i class="bi bi-patch-check-fill"></i> Verified</span>
                                </div>
                            </div>

                            <div class="el-prop-footer">
                                <a href="<?= Url::to(['/property/view', 'id' => $p->id]) ?>" class="btn btn-sm btn-primary px-3">View Details</a>
                                <div class="d-flex gap-2">
                                    <?php if (!Yii::$app->user->isGuest): ?>
                                        <?= Html::beginForm(['/property/toggle-favorite', 'id' => $p->id], 'post', ['style' => 'display:inline']) ?>
                                            <button class="el-fav-btn" title="Save to favorites"><i class="bi bi-heart"></i></button>
                                        <?= Html::endForm() ?>
                                    <?php endif; ?>
                                    <a href="<?= Url::to(['/property/compare', 'ids[]' => $p->id]) ?>" class="el-fav-btn" title="Add to Compare">
                                        <i class="bi bi-bar-chart-steps"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="el-pager mt-4 d-flex justify-content-center">
                <?= LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
                ]) ?>
            </div>
        <?php endif; ?>
    </div>
</div>