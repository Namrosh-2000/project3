<?php

/** @var yii\web\View $this */
/** @var array $properties */
/** @var string $status */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Property Moderation';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-shield-check text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Review and verify property listings submitted by owners and agents.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= Url::to(['/admin/dashboard']) ?>" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="btn-group el-tabs" role="group">
                <a class="btn <?= $status === 'pending' ? 'btn-warning text-dark font-weight-bold' : 'btn-light border' ?>" href="?status=pending">
                    <i class="bi bi-hourglass-split me-1"></i> Pending Review
                    <?php if ($countPending = count(array_filter($properties, fn($p) => $p->status === 'pending'))): ?>
                        <span class="badge bg-dark ms-1"><?= $countPending ?></span>
                    <?php endif; ?>
                </a>
                <a class="btn <?= $status === 'verified' ? 'btn-success font-weight-bold' : 'btn-light border' ?>" href="?status=verified">
                    <i class="bi bi-patch-check-fill me-1"></i> Verified
                </a>
                <a class="btn <?= $status === 'rejected' ? 'btn-danger font-weight-bold' : 'btn-light border' ?>" href="?status=rejected">
                    <i class="bi bi-x-circle me-1"></i> Rejected
                </a>
            </div>
            <div class="text-muted small">
                Showing <strong><?= count($properties) ?></strong> listings with status "<strong><?= ucfirst($status) ?></strong>"
            </div>
        </div>
    </div>
</div>

<?php if (empty($properties)): ?>
    <div class="card py-5 text-center">
        <div class="card-body">
            <i class="bi bi-inbox text-muted display-4 mb-3 d-block"></i>
            <h5>No Properties Found</h5>
            <p class="text-muted">There are currently no property listings under the "<?= Html::encode($status) ?>" status queue.</p>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($properties as $p): ?>
            <div class="col-12">
                <div class="card shadow-sm border overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-3 position-relative">
                            <?php if ($p->coverImage): ?>
                                <img src="<?= $p->coverImage->getImageUrl() ?>" class="img-fluid h-100 w-100" style="object-fit:cover; min-height:200px">
                            <?php else: ?>
                                <div class="bg-light text-muted d-flex align-items-center justify-content-center h-100 min-vh-25" style="min-height:200px">
                                    <div class="text-center">
                                        <i class="bi bi-image fs-1 d-block"></i>
                                        <small>No image provided</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute top-0 start-0 m-3">
                                <?php
                                $badgeClass = [
                                    'verified' => 'badge-status-verified',
                                    'pending' => 'badge-status-pending',
                                    'rejected' => 'badge-status-rejected',
                                ][$p->status] ?? 'bg-secondary';
                                ?>
                                <span class="badge <?= $badgeClass ?> shadow-sm fs-6">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i><?= ucfirst($p->status) ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                        <div>
                                            <h4 class="card-title mb-1 text-dark fw-bold"><?= Html::encode($p->title) ?></h4>
                                            <div class="text-muted small">
                                                <i class="bi bi-geo-alt-fill text-danger"></i> <?= Html::encode(($p->location->ward ?? '') . ', ' . ($p->location->municipality ?? 'Kinondoni')) ?>
                                                &bull; <span class="badge bg-light text-dark border"><?= Html::encode($p->category->name ?? 'General') ?></span>
                                                &bull; <span class="badge bg-dark text-white"><?= ucfirst($p->listing_type) ?></span>
                                            </div>
                                        </div>
                                        <h4 class="text-primary font-weight-bold mb-0">
                                            TSh <?= number_format((float)$p->price) ?>
                                            <small class="text-muted fs-6 fw-normal">/ <?= $p->price_period ?: 'month' ?></small>
                                        </h4>
                                    </div>

                                    <p class="text-secondary small mb-3">
                                        <?= Html::encode(mb_substr($p->description ?? '', 0, 220)) ?><?= mb_strlen($p->description ?? '') > 220 ? '...' : '' ?>
                                    </p>

                                    <div class="row g-2 p-2 bg-light rounded text-muted small mb-3 border">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="el-avatar" style="width:28px; height:28px; font-size:0.75rem">
                                                    <?= Html::encode(mb_strtoupper(mb_substr($p->owner->username ?? 'U', 0, 1))) ?>
                                                </div>
                                                <div>
                                                    <strong><?= Html::encode($p->owner->username ?? 'Unknown') ?></strong>
                                                    <?php if ($p->owner->phone ?? null): ?>
                                                        <div class="text-dark"><i class="bi bi-telephone"></i> <?= Html::encode($p->owner->phone) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div><strong>Bedrooms:</strong> <?= $p->bedrooms ?? '0' ?></div>
                                            <div><strong>Furnished:</strong> <?= $p->is_furnished ? '✓ Yes' : '✗ No' ?></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div><strong>Water:</strong> <?= $p->has_water ? '✓ Yes' : '✗ No' ?></div>
                                            <div><strong>Parking:</strong> <?= $p->has_parking ? '✓ Yes' : '✗ No' ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <span class="text-muted small"><i class="bi bi-clock"></i> Submitted: <?= date('M d, Y H:i', $p->created_at) ?></span>
                                    <div class="d-flex gap-2">
                                        <a href="<?= Url::to(['/property/view', 'id' => $p->id]) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Preview Listing
                                        </a>
                                        <?php if ($p->status !== 'verified'): ?>
                                            <a href="<?= Url::to(['verify', 'id' => $p->id]) ?>" class="btn btn-sm btn-success px-3">
                                                <i class="bi bi-check-lg me-1"></i> Approve &amp; Verify
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($p->status !== 'rejected'): ?>
                                            <a href="<?= Url::to(['reject', 'id' => $p->id]) ?>" class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Are you sure you want to reject this property listing?')">
                                                <i class="bi bi-x-lg me-1"></i> Reject
                                            </a>
                                        <?php endif; ?>
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