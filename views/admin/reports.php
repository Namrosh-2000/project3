<?php

/** @var yii\web\View $this */
/** @var array $reports */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Reported Listings Queue';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-flag-fill text-danger me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Review community complaints, flagged properties, and user safety reports.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= Url::to(['/admin/dashboard']) ?>" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="fs-5 fw-bold text-dark"><i class="bi bi-exclamation-octagon-fill me-2 text-danger"></i>Flagged Content Submissions</span>
                <span class="badge bg-danger rounded-pill ms-2"><?= count($reports) ?> cases</span>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">High priority items requiring moderation</small>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Flagged Property</th>
                        <th>Reporter</th>
                        <th>Reason</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Reported Date</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reports)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-shield-check text-success display-4 d-block mb-3"></i>
                                <h5>No Pending Reports</h5>
                                <p class="text-muted mb-0">All community reports have been resolved or reviewed.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reports as $r): ?>
                            <tr>
                                <td class="ps-4">
                                    <strong class="text-dark d-block"><?= Html::encode($r->property->title ?? 'Deleted Property') ?></strong>
                                    <?php if ($r->property_id): ?>
                                        <small class="text-muted">ID: #<?= $r->property_id ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="el-avatar" style="width:28px; height:28px; font-size:0.75rem">
                                            <?= Html::encode(mb_strtoupper(mb_substr($r->reporter->username ?? 'R', 0, 1))) ?>
                                        </div>
                                        <span class="small font-weight-bold"><?= Html::encode($r->reporter->username ?? 'Anonymous') ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-danger-subtle text-danger border border-danger">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i><?= Html::encode(ucfirst($r->reason)) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-secondary d-block" style="max-width:250px">
                                        <?= Html::encode($r->details ?? 'No additional details provided.') ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i><?= Html::encode(ucfirst($r->status)) ?></span>
                                </td>
                                <td class="text-muted small">
                                    <?= date('M d, Y', $r->created_at) ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if ($r->property_id): ?>
                                        <a href="<?= Url::to(['/property/view', 'id' => $r->property_id]) ?>" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-eye"></i> View Property
                                        </a>
                                        <a href="<?= Url::to(['/admin/properties', 'status' => 'pending']) ?>" class="btn btn-sm btn-danger">
                                            <i class="bi bi-shield-x"></i> Moderate
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">N/A</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>