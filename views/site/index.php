<?php

/** @var yii\web\View $this */
/** @var array $featured */
/** @var array $categories */
/** @var array $wards */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'MachoMtaa — ' . Yii::t('app', 'footer.tagline');

$isGuest = Yii::$app->user->isGuest;
$entrepreneurUrl = $isGuest ? ['/site/login', 'returnUrl' => Url::to(['/business/index'])] : ['/business/index'];
$ownerUrl = $isGuest ? ['/site/login', 'returnUrl' => Url::to(['/dashboard/owner'])] : ['/dashboard/owner'];
$dashUrl = $isGuest ? ['/site/login'] : Yii::$app->user->identity->getDashboardRoute();
?>

<!-- 1. Hero Section -->
<section class="el-landing-hero">
    <h1 class="el-landing-headline">
        <span class="visually-hidden"><?= Html::encode(Yii::t('app', 'hero.headline_line1') . ' ' . Yii::t('app', 'hero.headline_line2')) ?></span>
        <span aria-hidden="true">
            <span class="el-kw el-kw--left" style="animation-delay:.05s">
                <?= Yii::t('app', 'hero.headline_line1_html') ?>
            </span><br>
            <span class="el-kw el-kw--pop" style="animation-delay:.25s">
                <?= Yii::t('app', 'hero.headline_line2_html') ?>
            </span>
        </span>
    </h1>

    <p class="el-landing-sub"><?= Html::encode(Yii::t('app', 'hero.sub')) ?></p>

    <div class="mm-hero-ctas">
        <?= Html::a(
            '<i class="bi bi-graph-up-arrow"></i> ' . Html::encode(Yii::t('app', 'hero.cta_primary')),
            ['/business/index'],
            ['class' => 'mm-btn-primary']
        ) ?>
        <?= Html::a(
            '<i class="bi bi-search"></i> ' . Html::encode(Yii::t('app', 'hero.cta_secondary')),
            ['/property/index'],
            ['class' => 'mm-btn-secondary']
        ) ?>
    </div>
</section>

<div class="el-landing-breathe" aria-hidden="true"></div>

<!-- 2. Identity Splitter -->
<section class="mm-section">
    <div class="mm-splitter">
        <div class="mm-splitter-card">
            <span class="mm-identity-pill"><?= Html::encode(Yii::t('app', 'splitter.entrepreneur_role')) ?></span>
            <h3><?= Html::encode(Yii::t('app', 'splitter.entrepreneur_heading')) ?></h3>
            <p><?= Html::encode(Yii::t('app', 'splitter.entrepreneur_body')) ?></p>
            <?= Html::a(
                Html::encode(Yii::t('app', 'splitter.entrepreneur_cta')),
                $entrepreneurUrl,
                ['class' => 'mm-btn-primary']
            ) ?>
        </div>
        <div class="mm-splitter-card mm-splitter-card--owner">
            <span class="mm-identity-pill mm-identity-pill--owner"><?= Html::encode(Yii::t('app', 'splitter.owner_role')) ?></span>
            <h3><?= Html::encode(Yii::t('app', 'splitter.owner_heading')) ?></h3>
            <p><?= Html::encode(Yii::t('app', 'splitter.owner_body')) ?></p>
            <?= Html::a(
                Html::encode(Yii::t('app', 'splitter.owner_cta')),
                $ownerUrl,
                ['class' => 'mm-btn-primary']
            ) ?>
        </div>
    </div>
</section>

<!-- 3. Old System vs New System -->
<section class="mm-section">
    <div class="mm-section-head">
        <h2><?= Html::encode(Yii::t('app', 'compare.heading')) ?></h2>
    </div>
    <div class="mm-compare">
        <div class="mm-compare-col mm-compare-old">
            <div class="mm-compare-header">
                <span class="mm-compare-status-badge mm-badge-friction">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= Html::encode(Yii::t('app', 'compare.friction_label')) ?>
                </span>
                <div class="mm-compare-label"><?= Html::encode(Yii::t('app', 'compare.old_label')) ?></div>
            </div>
            <p><?= Html::encode(Yii::t('app', 'compare.old_body')) ?></p>
        </div>
        <div class="mm-compare-arrow" aria-hidden="true">
            <i class="bi bi-arrow-right"></i>
        </div>
        <div class="mm-compare-col mm-compare-new">
            <div class="mm-compare-header">
                <span class="mm-compare-status-badge mm-badge-clarity">
                    <i class="bi bi-check-circle-fill"></i> <?= Html::encode(Yii::t('app', 'compare.clarity_label')) ?>
                </span>
                <div class="mm-compare-label"><?= Html::encode(Yii::t('app', 'compare.new_label')) ?></div>
            </div>
            <p><?= Html::encode(Yii::t('app', 'compare.new_body')) ?></p>
        </div>
    </div>
</section>

<!-- 4. How MachoMtaa Works -->
<section class="mm-section">
    <div class="mm-section-head">
        <h2><?= Html::encode(Yii::t('app', 'how.heading')) ?></h2>
    </div>
    <div class="el-steps">
        <div class="el-step">
            <div class="el-step-num">01</div>
            <div>
                <h3 class="h6 mb-1"><?= Html::encode(Yii::t('app', 'how.step1_title')) ?></h3>
                <p class="mb-0 text-body-secondary"><?= Html::encode(Yii::t('app', 'how.step1_body')) ?></p>
            </div>
        </div>
        <div class="el-step">
            <div class="el-step-num">02</div>
            <div>
                <h3 class="h6 mb-1"><?= Html::encode(Yii::t('app', 'how.step2_title')) ?></h3>
                <p class="mb-0 text-body-secondary"><?= Html::encode(Yii::t('app', 'how.step2_body')) ?></p>
            </div>
        </div>
        <div class="el-step">
            <div class="el-step-num">03</div>
            <div>
                <h3 class="h6 mb-1"><?= Html::encode(Yii::t('app', 'how.step3_title')) ?></h3>
                <p class="mb-0 text-body-secondary"><?= Html::encode(Yii::t('app', 'how.step3_body')) ?></p>
            </div>
        </div>
        <div class="el-step">
            <div class="el-step-num">04</div>
            <div>
                <h3 class="h6 mb-1"><?= Html::encode(Yii::t('app', 'how.step4_title')) ?></h3>
                <p class="mb-0 text-body-secondary"><?= Html::encode(Yii::t('app', 'how.step4_body')) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- 5. Local Data Differentiator -->
<section class="mm-section">
    <div class="mm-localdata">
        <h2><?= Html::encode(Yii::t('app', 'localdata.heading')) ?></h2>
        <p><?= Html::encode(Yii::t('app', 'localdata.body')) ?></p>
        <span class="mm-demo-badge"><?= Html::encode(Yii::t('app', 'localdata.demo_badge')) ?></span>
    </div>
</section>

<!-- 6. Fursa (Intelligence) Preview -->
<section class="mm-section">
    <div class="mm-section-head">
        <h2><?= Html::encode(Yii::t('app', 'fursa.heading')) ?></h2>
        <p class="mm-fursa-intro"><?= Html::encode(Yii::t('app', 'fursa.preview_sub', [], Yii::$app->language)) ?></p>
    </div>
    <div class="mm-fursa-grid">
        <div class="mm-fursa-chip"><i class="bi bi-activity text-teal me-1"></i> <?= Html::encode(Yii::t('app', 'fursa.item_demand')) ?></div>
        <div class="mm-fursa-chip"><i class="bi bi-people text-teal me-1"></i> <?= Html::encode(Yii::t('app', 'fursa.item_competition')) ?></div>
        <div class="mm-fursa-chip"><i class="bi bi-lightning-charge text-teal me-1"></i> <?= Html::encode(Yii::t('app', 'fursa.item_market_strength')) ?></div>
        <div class="mm-fursa-chip"><i class="bi bi-pie-chart text-teal me-1"></i> <?= Html::encode(Yii::t('app', 'fursa.item_gaps')) ?></div>
        <div class="mm-fursa-chip"><i class="bi bi-geo text-teal me-1"></i> <?= Html::encode(Yii::t('app', 'fursa.item_fit')) ?></div>
        <div class="mm-fursa-chip"><i class="bi bi-shield-check text-teal me-1"></i> <?= Html::encode(Yii::t('app', 'fursa.item_risk')) ?></div>
        <div class="mm-fursa-chip"><i class="bi bi-shop text-teal me-1"></i> <?= Html::encode(Yii::t('app', 'fursa.item_density')) ?></div>
        <div class="mm-fursa-chip"><i class="bi bi-currency-dollar text-teal me-1"></i> <?= Html::encode(Yii::t('app', 'Price')) ?></div>
    </div>
</section>

<!-- 7. Fremu Soko Preview -->
<section class="el-gallery">
    <div class="el-gallery-head">
        <h2><?= Html::encode(Yii::t('app', 'fremu.heading')) ?></h2>
        <p><?= Html::encode(Yii::t('app', 'fremu.body')) ?></p>
    </div>

    <?php if (empty($featured)): ?>
        <div class="el-gallery-grid">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="el-gallery-item">
                    <div class="el-gallery-placeholder">
                        <i class="bi bi-shop"></i>
                        <small><?= Html::encode(Yii::t('app', 'Commercial Space')) ?></small>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    <?php else: ?>
        <div class="el-gallery-grid">
            <?php foreach ($featured as $p): ?>
                <div class="el-gallery-item">
                    <?php if ($p->coverImage): ?>
                        <img src="<?= $p->coverImage->getImageUrl() ?>" alt="<?= Html::encode($p->title) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="el-gallery-placeholder">
                            <i class="bi bi-image"></i>
                            <small><?= Html::encode(Yii::t('app', 'Commercial Space')) ?></small>
                        </div>
                    <?php endif; ?>
                    <a class="el-gallery-link" href="<?= Url::to(['/property/view', 'id' => $p->id]) ?>"
                       aria-label="<?= Html::encode($p->title) ?>"></a>
                    <div class="el-gallery-caption">
                        <strong><?= Html::encode($p->title) ?></strong>
                        <small><?= Html::encode(($p->location->ward ?? 'Kinondoni') . ', Dar es Salaam') ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="mm-fremu-cta">
        <?= Html::a(
            Html::encode(Yii::t('app', 'fremu.cta')),
            ['/property/index'],
            ['class' => 'mm-btn-secondary']
        ) ?>
    </div>
</section>

<!-- 8. Final CTA -->
<section class="mm-section">
    <div class="mm-final">
        <h2><?= Html::encode(Yii::t('app', 'final.heading')) ?></h2>
        <div class="mm-hero-ctas">
            <?= Html::a(
                '<i class="bi bi-graph-up-arrow"></i> ' . Html::encode(Yii::t('app', 'final.cta_primary')),
                ['/business/index'],
                ['class' => 'mm-btn-primary']
            ) ?>
            <?= Html::a(
                '<i class="bi bi-speedometer2"></i> ' . Html::encode(Yii::t('app', 'final.cta_secondary')),
                $dashUrl,
                ['class' => 'mm-btn-secondary text-white border-white-50']
            ) ?>
        </div>
    </div>
</section>

