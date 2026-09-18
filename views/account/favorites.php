<?php

/** @var yii\web\View $this */
/** @var array $favorites */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'My Favorites';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-heart-fill text-rose me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Your saved properties for easy comparison and quick access.</p>
        </div>
        <div>
            <a href="<?= Url::to(['/property/index']) ?>" class="btn btn-warning shadow-sm fw-bold">
                <i class="bi bi-search me-1"></i> Browse Properties
            </a>
        </div>
    </div>
</div>

<?php if (empty($favorites)): ?>
    <div class="card py-5 text-center shadow-sm">
        <div class="card-body">
            <div class="mb-3 text-danger opacity-75">
                <i class="bi bi-heartbreak display-1"></i>
            </div>
            <h4 class="fw-bold">No Saved Favorites Yet</h4>
            <p class="text-muted max-w-md mx-auto mb-4">You haven't added any properties to your favorites list. Explore available listings and click the heart icon to save them here.</p>
            <a href="<?= Url::to(['/property/index']) ?>" class="btn btn-primary btn-lg px-4">
                <i class="bi bi-search me-2"></i>Find Properties in Kinondoni
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($favorites as $fav): ?>
            <?php if ($fav->property): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="el-prop h-100 position-relative shadow-sm hover-lift">
                        <?php if ($fav->property->coverImage): ?>
                            <img src="<?= $fav->property->coverImage->getImageUrl() ?>" alt="<?= Html::encode($fav->property->title) ?>" style="height:210px; object-fit:cover">
                        <?php else: ?>
                            <div class="el-prop-noimg d-flex align-items-center justify-content-center text-muted" style="height:210px">
                                <i class="bi bi-house-door fs-1"></i>
                            </div>
                        <?php endif; ?>

                        <div class="el-prop-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary-subtle text-primary border border-primary">
                                    <?= Html::encode($fav->property->category->name ?? 'Property') ?>
                                </span>
                                <span class="badge bg-dark text-white"><?= ucfirst($fav->property->listing_type) ?></span>
                            </div>

                            <h5 class="fw-bold mb-2 text-dark line-clamp-1"><?= Html::encode($fav->property->title) ?></h5>

                            <p class="text-muted small mb-3">
                                <i class="bi bi-geo-alt-fill text-danger"></i> <?= Html::encode($fav->property->location->ward ?? 'Kinondoni') ?>
                            </p>

                            <div class="el-prop-price fs-5 mb-3">
                                TSh <?= number_format((float)($fav->property->price ?? 0)) ?>
                                <small class="text-muted fw-normal fs-6">/ <?= $fav->property->price_period ?: 'month' ?></small>
                            </div>

                            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                <a href="<?= Url::to(['/property/view', 'id' => $fav->property->id]) ?>" class="btn btn-sm btn-primary px-3">
                                    <i class="bi bi-eye me-1"></i> View Details
                                </a>
                                <?= Html::beginForm(['/property/toggle-favorite', 'id' => $fav->property->id], 'post', ['class' => 'd-inline']) ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove from favorites">
                                        <i class="bi bi-trash me-1"></i> Remove
                                    </button>
                                <?= Html::endForm() ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>