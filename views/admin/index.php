<?php

/** @var yii\web\View $this */
/** @var array $pendingProperties */
/** @var array $stats */

use yii\bootstrap5\Html;

$this->title = 'Admin Dashboard';
?>

<h2 class="el-heading mb-4">Admin Dashboard</h2>

<div class="mb-4">
    <a href="<?= \yii\helpers\Url::to(['/admin/local-data']) ?>" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-database-fill-gear me-1"></i> Manage Local Data (Fursa)
    </a>
</div>


<div class="el-admin-stats">
    <div class="el-stat-card is-pending">
        <div class="num"><?= $stats['pending'] ?></div>
        <div class="label">Pending Approval</div>
    </div>
    <div class="el-stat-card">
        <div class="num"><?= $stats['verified'] ?></div>
        <div class="label">Verified</div>
    </div>
    <div class="el-stat-card">
        <div class="num"><?= $stats['rejected'] ?></div>
        <div class="label">Rejected</div>
    </div>
    <div class="el-stat-card">
        <div class="num"><?= $stats['total'] ?></div>
        <div class="label">Total Properties</div>
    </div>
    <div class="el-stat-card">
        <div class="num"><?= $stats['total_users'] ?></div>
        <div class="label">Total Users</div>
    </div>
</div>

<h3 class="el-heading mb-3">Pending Approvals (<?= count($pendingProperties) ?>)</h3>

<?php if (empty($pendingProperties)): ?>
    <div class="alert alert-info">Hakuna properties zinazosubiri uthibitisho kwa sasa. Zote zimeshapitiwa.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="el-admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Owner</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingProperties as $p): ?>
                    <tr>
                        <td><?= Html::encode($p->title) ?></td>
                        <td><?= Html::encode($p->category->name ?? '—') ?></td>
                        <td><?= Html::encode($p->location->ward ?? '—') ?></td>
                        <td>TSh <?= number_format((float)$p->price) ?></td>
                        <td><?= Html::encode($p->owner->username ?? '—') ?></td>
                        <td><?= Yii::$app->formatter->asRelativeTime($p->created_at) ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <?= Html::beginForm(['/admin/approve', 'id' => $p->id], 'post', ['style' => 'display:inline']) ?>
                                    <button class="el-btn-approve" onclick="return confirm('Thibitisha kupitisha property hii?')">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                <?= Html::endForm() ?>
                                <?= Html::beginForm(['/admin/reject', 'id' => $p->id], 'post', ['style' => 'display:inline']) ?>
                                    <button class="el-btn-reject" onclick="return confirm('Thibitisha kukataa property hii?')">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                <?= Html::endForm() ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>