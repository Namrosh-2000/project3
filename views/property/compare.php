<?php

/** @var yii\web\View $this */
/** @var array $properties */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Compare Properties';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-bar-chart-steps text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Side-by-side spec comparison to help you choose the best property.</p>
        </div>
        <div>
            <a href="<?= Url::to(['/property/index']) ?>" class="btn btn-outline-light">
                <i class="bi bi-plus-lg me-1"></i> Add More Properties
            </a>
        </div>
    </div>
</div>

<?php if (empty($properties)): ?>
    <div class="card py-5 text-center shadow-sm border-0">
        <div class="card-body">
            <i class="bi bi-bar-chart-line text-muted display-2 d-block mb-3"></i>
            <h4 class="fw-bold">No Properties Selected for Comparison</h4>
            <p class="text-muted max-w-md mx-auto mb-4">Select properties from the search listings page by clicking the compare button to evaluate them side-by-side here.</p>
            <a href="<?= Url::to(['/property/index']) ?>" class="btn btn-primary btn-lg px-4">
                <i class="bi bi-search me-2"></i> Browse Properties
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm border-0 overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="min-width:180px" class="ps-4">Specs &amp; Amenities</th>
                        <?php foreach ($properties as $p): ?>
                            <th style="min-width:260px" class="text-center">
                                <?php if ($p->coverImage): ?>
                                    <img src="<?= $p->coverImage->getImageUrl() ?>" class="rounded mb-2 shadow-sm" style="height:120px; width:100%; object-fit:cover">
                                <?php else: ?>
                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded mb-2" style="height:120px">
                                        <i class="bi bi-house-door fs-3"></i>
                                    </div>
                                <?php endif; ?>
                                <h6 class="fw-bold text-dark mb-1 line-clamp-1"><?= Html::encode($p->title) ?></h6>
                                <a href="<?= Url::to(['/property/view', 'id' => $p->id]) ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="bi bi-eye me-1"></i> View Details
                                </a>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="ps-4"><i class="bi bi-cash-stack text-success me-2"></i>Rental Price</th>
                        <?php foreach ($properties as $p): ?>
                            <td class="text-center fw-bold fs-5 text-primary">
                                TSh <?= number_format((float)$p->price) ?>
                                <small class="text-muted fs-6 fw-normal">/ <?= $p->price_period ?: 'month' ?></small>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th class="ps-4"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Ward Location</th>
                        <?php foreach ($properties as $p): ?>
                            <td class="text-center font-weight-bold"><?= Html::encode(($p->location->ward ?? 'Kinondoni')) ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th class="ps-4"><i class="bi bi-building me-2 text-primary"></i>Category</th>
                        <?php foreach ($properties as $p): ?>
                            <td class="text-center"><span class="badge bg-light text-dark border"><?= Html::encode($p->category->name ?? 'General') ?></span></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th class="ps-4"><i class="bi bi-door-open me-2 text-info"></i>Bedrooms</th>
                        <?php foreach ($properties as $p): ?>
                            <td class="text-center fw-bold"><?= $p->bedrooms ?? '0' ?> Bedroom(s)</td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th class="ps-4"><i class="bi bi-box me-2 text-warning"></i>Furnished</th>
                        <?php foreach ($properties as $p): ?>
                            <td class="text-center">
                                <?= $p->is_furnished ? '<span class="badge bg-success"><i class="bi bi-check-lg me-1"></i> Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th class="ps-4"><i class="bi bi-car-front me-2 text-secondary"></i>Parking</th>
                        <?php foreach ($properties as $p): ?>
                            <td class="text-center">
                                <?= $p->has_parking ? '<span class="badge bg-success"><i class="bi bi-check-lg me-1"></i> Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th class="ps-4"><i class="bi bi-droplet me-2 text-info"></i>Running Water</th>
                        <?php foreach ($properties as $p): ?>
                            <td class="text-center">
                                <?= $p->has_water ? '<span class="badge bg-success"><i class="bi bi-check-lg me-1"></i> Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th class="ps-4"><i class="bi bi-wifi me-2 text-primary"></i>Internet / WiFi</th>
                        <?php foreach ($properties as $p): ?>
                            <td class="text-center">
                                <?= $p->has_internet ? '<span class="badge bg-success"><i class="bi bi-check-lg me-1"></i> Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th class="ps-4"><i class="bi bi-patch-check me-2 text-success"></i>Verification Status</th>
                        <?php foreach ($properties as $p): ?>
                            <td class="text-center">
                                <span class="badge bg-success-subtle text-success border border-success px-3 py-2"><i class="bi bi-patch-check-fill me-1"></i><?= ucfirst($p->status) ?></span>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>