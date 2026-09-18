<?php

/** @var yii\web\View $this */
/** @var array $properties */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'My Property Listings';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-houses-fill text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Manage your uploaded properties, track verification status, and publish new listings.</p>
        </div>
        <div>
            <a href="<?= Url::to(['/property-submission/create']) ?>" class="btn btn-warning btn-lg shadow-sm fw-bold">
                <i class="bi bi-plus-circle-fill me-1"></i> Add New Property
            </a>
        </div>
    </div>
</div>

<?php if (empty($properties)): ?>
    <div class="card py-5 text-center shadow-sm border-0">
        <div class="card-body">
            <i class="bi bi-house-add text-primary display-2 d-block mb-3 opacity-75"></i>
            <h4 class="fw-bold">No Property Listings Yet</h4>
            <p class="text-muted max-w-md mx-auto mb-4">You haven't submitted any properties. Start listing your room, apartment, or business space to reach tenants in Kinondoni.</p>
            <a href="<?= Url::to(['/property-submission/create']) ?>" class="btn btn-primary btn-lg px-4">
                <i class="bi bi-plus-lg me-2"></i> Create Your First Listing
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($properties as $p): ?>
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm border overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-4 position-relative">
                            <?php if ($p->coverImage): ?>
                                <img src="<?= $p->coverImage->getImageUrl() ?>" alt="<?= Html::encode($p->title) ?>" class="w-100 h-100" style="object-fit:cover; min-height:160px">
                            <?php else: ?>
                                <div class="bg-light text-muted d-flex align-items-center justify-content-center h-100" style="min-height:160px">
                                    <i class="bi bi-image fs-2"></i>
                                </div>
                            <?php endif; ?>

                            <div class="position-absolute top-0 start-0 m-2">
                                <?php
                                $badgeClass = [
                                    'pending' => 'badge-status-pending',
                                    'verified' => 'badge-status-verified',
                                    'rejected' => 'badge-status-rejected',
                                ][$p->status] ?? 'bg-secondary';
                                ?>
                                <span class="badge <?= $badgeClass ?> shadow-sm">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.45rem;"></i><?= ucfirst($p->status) ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card-body p-3 d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                        <h5 class="fw-bold mb-0 text-dark line-clamp-1"><?= Html::encode($p->title) ?></h5>
                                        <?php if (!$p->is_available): ?>
                                            <span class="badge bg-secondary">Unavailable</span>
                                        <?php endif; ?>
                                    </div>

                                    <small class="text-muted d-block mb-2">
                                        <i class="bi bi-geo-alt-fill text-danger"></i> <?= Html::encode($p->location->ward ?? 'Kinondoni') ?>
                                        &bull; <?= Html::encode($p->category->name ?? 'Property') ?>
                                    </small>

                                    <div class="fw-bold text-primary fs-5 mb-2">
                                        TSh <?= number_format((float)$p->price) ?>
                                        <small class="text-muted fw-normal fs-6">/ <?= $p->price_period ?: 'month' ?></small>
                                    </div>
                                </div>

                                <div class="pt-2 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <a href="<?= Url::to(['/property/view', 'id' => $p->id]) ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <div class="d-flex gap-2">
                                        <a href="<?= Url::to(['/property-submission/update', 'id' => $p->id]) ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <?= Html::beginForm(['/property-submission/delete', 'id' => $p->id], 'post', ['class' => 'd-inline', 'onsubmit' => 'return confirm("Are you sure you want to delete this property listing?");']) ?>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        <?= Html::endForm() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>