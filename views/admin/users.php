<?php

/** @var yii\web\View $this */
/** @var array $users */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'User Directory';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-people-fill text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Manage system users, roles, and access control permissions.</p>
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
                <span class="fs-5 fw-bold text-dark"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Registered Platform Users</span>
                <span class="badge bg-primary rounded-pill ms-2"><?= count($users) ?> total</span>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">Showing all active registered accounts in EneoLink</small>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">User</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No registered users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="el-avatar">
                                            <?= Html::encode(mb_strtoupper(mb_substr($u->username, 0, 1))) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= Html::encode($u->username) ?></div>
                                            <small class="text-muted">ID: #<?= $u->id ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-dark font-weight-500">
                                    <i class="bi bi-envelope text-muted me-1"></i> <?= Html::encode($u->email) ?>
                                </td>
                                <td>
                                    <?php
                                    $roleBadge = [
                                        'admin' => 'badge-role-admin',
                                        'owner' => 'badge-role-owner',
                                        'seeker' => 'badge-role-seeker',
                                        'agent' => 'badge-role-agent',
                                ][$u->role] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $roleBadge ?>">
                                        <i class="bi bi-shield-lock me-1"></i><?= ucfirst($u->role) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($u->status == 10 || $u->status == 'active'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success"><i class="bi bi-check-circle-fill me-1"></i>Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary"><i class="bi bi-slash-circle me-1"></i><?= Html::encode($u->status) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i> <?= date('M d, Y', $u->created_at) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>