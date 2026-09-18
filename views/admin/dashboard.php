<?php

/** @var yii\web\View $this */
/** @var array $stats */
/** @var array $recentProperties */

use yii\bootstrap5\Html;
use app\models\Inquiry;
use app\models\Report;

$newInquiries = Inquiry::find()->where(['status' => Inquiry::STATUS_NEW])->count();
$pendingReports = Report::find()->where(['status' => Report::STATUS_PENDING])->count();

$this->title = 'Admin Dashboard';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-speedometer2 text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Overview of system metrics, property verification, reports, and user activities.</p>
        </div>
        <div>
            <a href="<?= Yii::$app->homeUrl ?>admin/properties?status=pending" class="btn btn-warning shadow-sm">
                <i class="bi bi-shield-check me-1"></i> Pending Verifications (<?= $stats['properties_pending'] ?>)
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="el-stat-card">
            <div class="el-stat-icon blue">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="el-stat-val"><?= number_format($stats['users']) ?></div>
                <div class="el-stat-lbl">Total Users</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="el-stat-card">
            <div class="el-stat-icon purple">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="el-stat-val"><?= number_format($stats['properties_total']) ?></div>
                <div class="el-stat-lbl">Properties</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="el-stat-card">
            <div class="el-stat-icon amber">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="el-stat-val"><?= number_format($stats['properties_pending']) ?></div>
                <div class="el-stat-lbl">Pending Review</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="el-stat-card">
            <div class="el-stat-icon emerald">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div>
                <div class="el-stat-val"><?= number_format($stats['properties_verified']) ?></div>
                <div class="el-stat-lbl">Verified Listings</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="el-stat-card">
            <div class="el-stat-icon blue">
                <i class="bi bi-chat-left-dots-fill"></i>
            </div>
            <div>
                <div class="el-stat-val"><?= number_format($newInquiries) ?></div>
                <div class="el-stat-lbl">New Inquiries</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="el-stat-card">
            <div class="el-stat-icon rose">
                <i class="bi bi-flag-fill"></i>
            </div>
            <div>
                <div class="el-stat-val"><?= number_format($pendingReports) ?></div>
                <div class="el-stat-lbl">Pending Reports</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fs-5"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Property Submissions</span>
                <a href="<?= Yii::$app->homeUrl ?>admin/properties" class="btn btn-sm btn-outline-primary">Manage All Properties</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Property Title</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Date Submitted</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentProperties)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No recent properties submitted.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentProperties as $p): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark"><?= Html::encode(mb_substr($p->title, 0, 32)) ?><?= mb_strlen($p->title) > 32 ? '...' : '' ?></div>
                                            <small class="text-muted"><i class="bi bi-geo-alt"></i> <?= Html::encode($p->location->ward ?? 'Kinondoni') ?> &bull; TSh <?= number_format((float)$p->price) ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="el-avatar" style="width:30px; height:30px; font-size:0.75rem">
                                                    <?= Html::encode(mb_strtoupper(mb_substr($p->owner->username ?? 'U', 0, 1))) ?>
                                                </div>
                                                <span class="small font-weight-bold"><?= Html::encode($p->owner->username ?? 'Unknown') ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = [
                                                'verified' => 'badge-status-verified',
                                                'pending' => 'badge-status-pending',
                                                'rejected' => 'badge-status-rejected',
                                            ][$p->status] ?? 'bg-secondary';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>">
                                                <i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i><?= ucfirst($p->status) ?>
                                            </span>
                                        </td>
                                        <td class="small text-muted"><?= date('M d, Y', $p->created_at) ?></td>
                                        <td class="text-end">
                                            <a href="<?= Yii::$app->homeUrl ?>property/<?= $p->id ?>" class="btn btn-sm btn-light border" target="_blank">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <span class="fs-5"><i class="bi bi-lightning-charge me-2 text-warning"></i>Quick Management</span>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="<?= Yii::$app->homeUrl ?>admin/properties?status=pending" class="btn btn-warning p-3 text-start d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="d-block text-dark"><i class="bi bi-check2-circle me-1"></i> Verify Pending Properties</strong>
                            <small class="text-dark-50"><?= $stats['properties_pending'] ?> listings awaiting your approval</small>
                        </div>
                        <i class="bi bi-chevron-right fs-5 text-dark"></i>
                    </a>

                    <a href="<?= Yii::$app->homeUrl ?>admin/reports" class="btn btn-danger p-3 text-start d-flex justify-content-between align-items-center <?= $pendingReports == 0 ? 'disabled opacity-50' : '' ?>">
                        <div>
                            <strong class="d-block"><i class="bi bi-exclamation-triangle me-1"></i> Review Reported Listings</strong>
                            <small><?= $pendingReports ?> pending report cases</small>
                        </div>
                        <i class="bi bi-chevron-right fs-5"></i>
                    </a>

                    <a href="<?= Yii::$app->homeUrl ?>admin/users" class="btn btn-outline-dark p-3 text-start d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="d-block"><i class="bi bi-people me-1"></i> User Directory</strong>
                            <small class="text-muted">Manage roles, permissions and user accounts</small>
                        </div>
                        <i class="bi bi-chevron-right fs-5"></i>
                    </a>

                    <a href="<?= Yii::$app->homeUrl ?>property-submission/create" class="btn btn-outline-primary p-3 text-start d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="d-block"><i class="bi bi-plus-circle me-1"></i> Add Property (Demo)</strong>
                            <small class="text-muted">Test new listing creation workflow</small>
                        </div>
                        <i class="bi bi-chevron-right fs-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>