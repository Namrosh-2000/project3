<?php

/** @var yii\web\View $this */
/** @var app\models\LocalDataSource[] $sources */
/** @var app\models\MarketIndicator[] $indicators */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Local Data Engine — Sources & Market Indicators';
?>

<div class="mm-fursa-hero">
    <div class="mm-fursa-hero-inner">
        <div class="mm-fursa-eyebrow">
            <i class="bi bi-database-fill-gear"></i> MachoMtaa Intelligence Architecture • Phase 4
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mm-fursa-hero-title mb-2"><?= Html::encode($this->title) ?></h1>
                <p class="mm-fursa-hero-lead mb-0">
                    Manage where Fursa's local-market context comes from. Every reading points to a verified or demo source, powering the "Muktadha wa Eneo" panel for entrepreneurs.
                </p>
            </div>
            <a href="<?= Url::to(['/admin']) ?>" class="btn btn-outline-light fw-bold px-3 py-2 rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <span><?= Yii::$app->session->getFlash('success') ?></span>
    </div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
        <span><?= Yii::$app->session->getFlash('error') ?></span>
    </div>
<?php endif; ?>

<!-- Data Sources Section -->
<div class="card border-0 shadow-sm rounded-4 mb-5 p-4" style="border: 1px solid #e2e8f0;">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="bi bi-journal-text text-teal me-2"></i>Data Sources (<?= count($sources) ?>)</h4>
            <p class="text-muted small mb-0">All registry origins of data (surveys, census estimates, foot traffic readings).</p>
        </div>
        <a href="<?= Url::to(['/admin/local-data-source-create']) ?>" class="btn btn-primary btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Data Source
        </a>
    </div>

    <?php if (empty($sources)): ?>
        <div class="alert alert-info rounded-3">No data sources yet. Add one before creating an indicator.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0">Source Name</th>
                        <th class="border-0">Type</th>
                        <th class="border-0">Collected Date</th>
                        <th class="border-0">Verification Status</th>
                        <th class="border-0">Active Indicators</th>
                        <th class="border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sources as $s): ?>
                        <tr>
                            <td class="fw-bold text-dark"><?= Html::encode($s->name) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= Html::encode($s->type) ?></span></td>
                            <td class="text-muted small"><?= Html::encode($s->collected_at ?: '—') ?></td>
                            <td>
                                <?= $s->is_demo
                                    ? '<span class="mm-demo-badge mm-demo-badge--sm">Demo Data</span>'
                                    : '<span class="mm-verified-badge">Verified</span>' ?>
                            </td>
                            <td><span class="fw-bold text-teal"><?= count($s->indicators) ?></span> reading(s)</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= Url::to(['/admin/local-data-source-update', 'id' => $s->id]) ?>" class="btn btn-outline-secondary btn-sm px-2 py-1 rounded-2">Edit</a>
                                    <?= Html::beginForm(['/admin/local-data-source-delete', 'id' => $s->id], 'post', ['style' => 'display:inline']) ?>
                                        <?= Html::submitButton('<i class="bi bi-trash"></i>', ['class' => 'btn btn-outline-danger btn-sm px-2 py-1 rounded-2', 'data-confirm' => 'Delete this data source?']) ?>
                                    <?= Html::endForm() ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Market Indicators Section -->
<div class="card border-0 shadow-sm rounded-4 mb-4 p-4" style="border: 1px solid #e2e8f0;">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="bi bi-pin-map-fill text-danger me-2"></i>Market Indicators (<?= count($indicators) ?>)</h4>
            <p class="text-muted small mb-0">Ward-level and category-level signals displayed in the Fursa Market Intelligence Engine.</p>
        </div>
        <a href="<?= Url::to(['/admin/indicator-create']) ?>" class="btn btn-warning btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm text-dark">
            <i class="bi bi-plus-lg me-1"></i> Add Indicator
        </a>
    </div>

    <?php if (empty($indicators)): ?>
        <div class="alert alert-info rounded-3">
            No indicators yet — Fursa results will show listing-based match scores only, with no ward-context
            panel, until at least one is added here.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0">Ward</th>
                        <th class="border-0">Category</th>
                        <th class="border-0">Indicator Type</th>
                        <th class="border-0">Reading / Value</th>
                        <th class="border-0">Confidence</th>
                        <th class="border-0">Attributed Source</th>
                        <th class="border-0">Badge</th>
                        <th class="border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($indicators as $ind): ?>
                        <tr>
                            <td class="fw-bold text-dark">📍 <?= Html::encode($ind->location->ward ?? '—') ?></td>
                            <td><?= Html::encode($ind->category->name ?? 'Whole Ward') ?></td>
                            <td><span class="badge bg-light text-dark border"><?= Html::encode($ind->typeLabel) ?></span></td>
                            <td class="fw-bold text-dark"><?= Html::encode($ind->displayValue ?? '—') ?></td>
                            <td>
                                <span class="badge <?= $ind->confidence_level === 'high' ? 'bg-success-subtle text-success' : ($ind->confidence_level === 'medium' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary') ?> px-2 py-1 rounded-pill">
                                    <?= Html::encode($ind->confidenceLabel) ?>
                                </span>
                            </td>
                            <td class="small text-muted"><?= Html::encode($ind->dataSource->name ?? '—') ?></td>
                            <td>
                                <?= $ind->is_demo
                                    ? '<span class="mm-demo-badge mm-demo-badge--sm">Demo Data</span>'
                                    : '<span class="mm-verified-badge">Verified</span>' ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= Url::to(['/admin/indicator-update', 'id' => $ind->id]) ?>" class="btn btn-outline-secondary btn-sm px-2 py-1 rounded-2">Edit</a>
                                    <?= Html::beginForm(['/admin/indicator-delete', 'id' => $ind->id], 'post', ['style' => 'display:inline']) ?>
                                        <?= Html::submitButton('<i class="bi bi-trash"></i>', ['class' => 'btn btn-outline-danger btn-sm px-2 py-1 rounded-2', 'data-confirm' => 'Delete this indicator?']) ?>
                                    <?= Html::endForm() ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
