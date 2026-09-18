<?php

/** @var yii\web\View $this */
/** @var array $featured */
/** @var array $categories */
/** @var array $wards */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'MachoMtaa — Local Business Intelligence & Commercial Space Discovery';
?>

<section class="el-landing-hero">
    <h1 class="el-landing-headline">
        <span class="visually-hidden"><?= Html::encode(Yii::t('app', 'hero.headline_line1') . ' ' . Yii::t('app', 'hero.headline_line2')) ?></span>
        <span aria-hidden="true">
            <span class="el-kw el-kw--left" style="animation-delay:.05s"><?= Html::encode(Yii::t('app', 'hero.headline_line1')) ?></span><br>
            <span class="el-kw el-kw--pop el-kw--accent" style="animation-delay:.3s"><?= Html::encode(Yii::t('app', 'hero.headline_line2')) ?></span>
        </span>
    </h1>

    <p class="el-landing-sub"><?= Html::encode(Yii::t('app', 'hero.sub')) ?></p>

    <div class="mm-hero-ctas">
        <?= Html::a(Html::encode(Yii::t('app', 'hero.cta_primary')), ['/business/index'], ['class' => 'mm-btn-primary']) ?>
        <?= Html::a(Html::encode(Yii::t('app', 'hero.cta_secondary')), ['/property/index'], ['class' => 'mm-btn-secondary']) ?>
    </div>
</section>

<div class="el-landing-breathe" aria-hidden="true"></div>

<!-- Identity splitter -->
<section class="mm-section mm-splitter">
    <div class="mm-splitter-card">
        <h3><?= Html::encode(Yii::t('app', 'splitter.entrepreneur_heading')) ?></h3>
        <p><?= Html::encode(Yii::t('app', 'splitter.entrepreneur_body')) ?></p>
        <?= Html::a(Html::encode(Yii::t('app', 'splitter.entrepreneur_cta')), ['/business/index'], ['class' => 'mm-btn-primary']) ?>
    </div>
    <div class="mm-splitter-card mm-splitter-card--owner">
        <h3><?= Html::encode(Yii::t('app', 'splitter.owner_heading')) ?></h3>
        <p><?= Html::encode(Yii::t('app', 'splitter.owner_body')) ?></p>
        <?= Html::a(Html::encode(Yii::t('app', 'splitter.owner_cta')), ['/property-submission/create'], ['class' => 'mm-btn-primary']) ?>
    </div>
</section>

<!-- Old system vs new system -->
<section class="mm-section">
    <div class="mm-section-head">
        <h2><?= Html::encode(Yii::t('app', 'compare.heading')) ?></h2>
    </div>
    <div class="mm-compare">
        <div class="mm-compare-col mm-compare-old">
            <div class="mm-compare-label"><?= Html::encode(Yii::t('app', 'compare.old_label')) ?></div>
            <p><?= Html::encode(Yii::t('app', 'compare.old_body')) ?></p>
        </div>
        <div class="mm-compare-arrow"><i class="bi bi-arrow-right"></i></div>
        <div class="mm-compare-col mm-compare-new">
            <div class="mm-compare-label"><?= Html::encode(Yii::t('app', 'compare.new_label')) ?></div>
            <p><?= Html::encode(Yii::t('app', 'compare.new_body')) ?></p>
        </div>
    </div>
</section>

<!-- How MachoMtaa works -->
<section class="mm-section">
    <div class="mm-section-head">
        <h2><?= Html::encode(Yii::t('app', 'how.heading')) ?></h2>
    </div>
    <div class="el-steps">
        <div class="el-step">
            <div class="el-step-num">01</div>
            <div><h3 class="h6 mb-1"><?= Html::encode(Yii::t('app', 'how.step1_title')) ?></h3><p class="mb-0 text-body-secondary"><?= Html::encode(Yii::t('app', 'how.step1_body')) ?></p></div>
        </div>
        <div class="el-step">
            <div class="el-step-num">02</div>
            <div><h3 class="h6 mb-1"><?= Html::encode(Yii::t('app', 'how.step2_title')) ?></h3><p class="mb-0 text-body-secondary"><?= Html::encode(Yii::t('app', 'how.step2_body')) ?></p></div>
        </div>
        <div class="el-step">
            <div class="el-step-num">03</div>
            <div><h3 class="h6 mb-1"><?= Html::encode(Yii::t('app', 'how.step3_title')) ?></h3><p class="mb-0 text-body-secondary"><?= Html::encode(Yii::t('app', 'how.step3_body')) ?></p></div>
        </div>
        <div class="el-step">
            <div class="el-step-num">04</div>
            <div><h3 class="h6 mb-1"><?= Html::encode(Yii::t('app', 'how.step4_title')) ?></h3><p class="mb-0 text-body-secondary"><?= Html::encode(Yii::t('app', 'how.step4_body')) ?></p></div>
        </div>
    </div>
</section>

<!-- Local data differentiator -->
<section class="mm-section">
    <div class="mm-localdata">
        <h2><?= Html::encode(Yii::t('app', 'localdata.heading')) ?></h2>
        <p><?= Html::encode(Yii::t('app', 'localdata.body')) ?></p>
        <span class="mm-demo-badge"><?= Html::encode(Yii::t('app', 'localdata.demo_badge')) ?></span>
    </div>
</section>

<!-- Fursa preview -->
<section class="mm-section">
    <div class="mm-section-head">
        <h2><?= Html::encode(Yii::t('app', 'fursa.heading')) ?></h2>
    </div>
    <div class="mm-fursa-grid">
        <div class="mm-fursa-chip"><?= Html::encode(Yii::t('app', 'fursa.item_demand')) ?></div>
        <div class="mm-fursa-chip"><?= Html::encode(Yii::t('app', 'fursa.item_competition')) ?></div>
        <div class="mm-fursa-chip"><?= Html::encode(Yii::t('app', 'fursa.item_market_strength')) ?></div>
        <div class="mm-fursa-chip"><?= Html::encode(Yii::t('app', 'fursa.item_gaps')) ?></div>
        <div class="mm-fursa-chip"><?= Html::encode(Yii::t('app', 'fursa.item_fit')) ?></div>
        <div class="mm-fursa-chip"><?= Html::encode(Yii::t('app', 'fursa.item_risk')) ?></div>
        <div class="mm-fursa-chip"><?= Html::encode(Yii::t('app', 'fursa.item_density')) ?></div>
    </div>
</section>

<!-- Fremu Soko preview (existing gallery, retitled) -->
<section class="el-gallery">
    <div class="el-gallery-head">
        <h2><?= Html::encode(Yii::t('app', 'fremu.heading')) ?></h2>
        <p><?= Html::encode(Yii::t('app', 'fremu.body')) ?></p>
    </div>

    <?php if (empty($featured)): ?>
        <div class="el-gallery-grid">
            <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="el-gallery-item">
                    <div class="el-gallery-placeholder">
                        <i class="bi bi-image"></i>
                        <small>Picha zinakuja hivi karibuni</small>
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
                            <small>No image uploaded</small>
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
        <?= Html::a(Html::encode(Yii::t('app', 'fremu.cta')), ['/property/index'], ['class' => 'mm-btn-secondary']) ?>
    </div>
</section>

<!-- Final CTA -->
<section class="mm-section">
    <div class="mm-final">
        <h2><?= Html::encode(Yii::t('app', 'final.heading')) ?></h2>
        <div class="mm-hero-ctas">
            <?= Html::a(Html::encode(Yii::t('app', 'final.cta_primary')), ['/business/index'], ['class' => 'mm-btn-primary']) ?>
            <?= Html::a(Html::encode(Yii::t('app', 'final.cta_secondary')), Yii::$app->user->isGuest ? ['/site/login'] : ['/profile/index'], ['class' => 'mm-btn-secondary']) ?>
        </div>
    </div>
</section>
