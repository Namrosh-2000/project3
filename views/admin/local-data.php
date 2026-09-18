<?php

/** @var yii\web\View $this */
/** @var app\models\LocalDataSource[] $sources */
/** @var app\models\MarketIndicator[] $indicators */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Local Data — Sources & Market Indicators';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-database-fill-gear text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>
                Manage where Fursa's local-market context comes from. Every reading here must point to a
                source, and anything not yet verified must stay flagged as Demo — this is what powers the
                "Muktadha wa Eneo" panel entrepreneurs see on their market analysis.
            </p>
        </div>
        <a href="<?= Url::to(['/admin']) ?>" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i> Dashboard
        </a>
    </div>
</div>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success"><?= Yii::$app->session->getFlash('success') ?></div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger"><?= Yii::$app->session->getFlash('error') ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="el-heading mb-0">Data Sources (<?= count($sources) ?>)</h3>
    <a href="<?= Url::to(['/admin/local-data-source-create']) ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Source
    </a>
</div>

<?php if (empty($sources)): ?>
    <div class="alert alert-info">No data sources yet. Add one before creating an indicator.</div>
<?php else: ?>
    <div class="table-responsive mb-5">
        <table class="table table-striped table-sm align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Collected On</th>
                    <th>Demo?</th>
                    <th>Used By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sources as $s): ?>
                    <tr>
                        <td><?= Html::encode($s->name) ?></td>
                        <td><?= Html::encode($s->type) ?></td>
                        <td><?= Html::encode($s->collected_at ?: '—') ?></td>
                        <td>
                            <?= $s->is_demo
                                ? '<span class="badge bg-warning text-dark">Demo</span>'
                                : '<span class="badge bg-success">Verified</span>' ?>
                        </td>
                        <td><?= count($s->indicators) ?> indicator(s)</td>
                        <td class="d-flex gap-2">
                            <a href="<?= Url::to(['/admin/local-data-source-update', 'id' => $s->id]) ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                            <?= Html::beginForm(['/admin/local-data-source-delete', 'id' => $s->id], 'post', ['style' => 'display:inline']) ?>
                                <?= Html::submitButton('Delete', ['class' => 'btn btn-outline-danger btn-sm', 'data-confirm' => 'Delete this data source?']) ?>
                            <?= Html::endForm() ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="el-heading mb-0">Market Indicators (<?= count($indicators) ?>)</h3>
    <a href="<?= Url::to(['/admin/indicator-create']) ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Indicator
    </a>
</div>

<?php if (empty($indicators)): ?>
    <div class="alert alert-info">
        No indicators yet — Fursa results will show listing-based match scores only, with no ward-context
        panel, until at least one is added here.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-sm align-middle">
            <thead>
                <tr>
                    <th>Ward</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Confidence</th>
                    <th>Source</th>
                    <th>Demo?</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($indicators as $ind): ?>
                    <tr>
                        <td><?= Html::encode($ind->location->ward ?? '—') ?></td>
                        <td><?= Html::encode($ind->category->name ?? 'Whole ward') ?></td>
                        <td><?= Html::encode($ind->typeLabel) ?></td>
                        <td><?= Html::encode($ind->displayValue ?? '—') ?></td>
                        <td><?= Html::encode($ind->confidenceLabel) ?></td>
                        <td><?= Html::encode($ind->dataSource->name ?? '—') ?></td>
                        <td>
                            <?= $ind->is_demo
                                ? '<span class="badge bg-warning text-dark">Demo</span>'
                                : '<span class="badge bg-success">Verified</span>' ?>
                        </td>
                        <td class="d-flex gap-2">
                            <a href="<?= Url::to(['/admin/indicator-update', 'id' => $ind->id]) ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                            <?= Html::beginForm(['/admin/indicator-delete', 'id' => $ind->id], 'post', ['style' => 'display:inline']) ?>
                                <?= Html::submitButton('Delete', ['class' => 'btn btn-outline-danger btn-sm', 'data-confirm' => 'Delete this indicator?']) ?>
                            <?= Html::endForm() ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
