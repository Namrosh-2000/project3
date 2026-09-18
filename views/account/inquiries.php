<?php

/** @var yii\web\View $this */
/** @var array $sent */
/** @var array $received */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Inquiries & Messages';
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-chat-left-text-fill text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Manage incoming property inquiries from seekers and track your sent messages.</p>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <ul class="nav nav-pills el-tabs gap-2" role="tablist">
            <li class="nav-item">
                <button class="nav-link active px-4 py-2" data-bs-toggle="tab" data-bs-target="#received">
                    <i class="bi bi-inbox-fill me-2 text-primary"></i> Received Inquiries
                    <?php if (count($received) > 0): ?>
                        <span class="badge bg-danger ms-2"><?= count($received) ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#sent">
                    <i class="bi bi-send-fill me-2 text-info"></i> Sent Messages
                    <?php if (count($sent) > 0): ?>
                        <span class="badge bg-secondary ms-2"><?= count($sent) ?></span>
                    <?php endif; ?>
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4 bg-light-subtle">
        <div class="tab-content">
            <!-- Received Inquiries Tab -->
            <div class="tab-pane fade show active" id="received">
                <?php if (empty($received)): ?>
                    <div class="card py-5 text-center bg-white shadow-sm border-0">
                        <div class="card-body">
                            <i class="bi bi-inbox text-muted display-3 d-block mb-3"></i>
                            <h5 class="fw-bold">No Inquiries Received</h5>
                            <p class="text-muted">You have not received any property inquiry messages yet.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($received as $i): ?>
                            <div class="card shadow-sm border">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="el-avatar">
                                                <?= Html::encode(mb_strtoupper(mb_substr($i->seeker->username ?? 'S', 0, 1))) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark"><?= Html::encode($i->seeker->username ?? 'Property Seeker') ?></h6>
                                                <small class="text-muted"><i class="bi bi-shield-lock-fill text-primary me-1"></i> EneoLink In-App Messaging</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary-subtle text-primary border border-primary mb-1"><?= ucfirst($i->status) ?></span>
                                            <div class="small text-muted"><i class="bi bi-clock me-1"></i><?= date('M d, Y H:i', $i->created_at) ?></div>
                                        </div>
                                    </div>

                                    <div class="p-3 bg-light rounded border mb-3">
                                        <div class="small fw-bold text-muted mb-1">
                                            Property Listing:
                                            <a href="<?= Url::to(['/property/view', 'id' => $i->property_id]) ?>" target="_blank" class="text-primary text-decoration-underline">
                                                <?= Html::encode($i->property->title ?? 'View Property') ?>
                                            </a>
                                        </div>
                                        <div class="el-chat-bubble mt-2">
                                            <i class="bi bi-quote fs-4 text-muted me-1"></i><?= nl2br(Html::encode($i->message)) ?>
                                        </div>
                                    </div>

                                    <?php if ($i->reply): ?>
                                        <div class="el-chat-reply">
                                            <strong class="d-block text-success mb-1"><i class="bi bi-reply-fill me-1"></i> Your Sent Reply:</strong>
                                            <?= nl2br(Html::encode($i->reply)) ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="pt-2 border-top">
                                            <?= Html::beginForm(['/account/reply-inquiry', 'id' => $i->id], 'post') ?>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold text-dark"><i class="bi bi-reply me-1"></i> Write Your Reply to Seeker:</label>
                                                    <textarea name="reply" class="form-control" rows="2" placeholder="Type your reply here..." required></textarea>
                                                </div>
                                                <button class="btn btn-sm btn-primary px-4">
                                                    <i class="bi bi-send me-1"></i> Send Reply
                                                </button>
                                            <?= Html::endForm() ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sent Inquiries Tab -->
            <div class="tab-pane fade" id="sent">
                <?php if (empty($sent)): ?>
                    <div class="card py-5 text-center bg-white shadow-sm border-0">
                        <div class="card-body">
                            <i class="bi bi-send text-muted display-3 d-block mb-3"></i>
                            <h5 class="fw-bold">No Sent Messages</h5>
                            <p class="text-muted">You haven't sent any inquiry messages to property owners.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card shadow-sm border-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Target Property</th>
                                        <th>Property Owner</th>
                                        <th>Your Message</th>
                                        <th>Owner Reply</th>
                                        <th>Status</th>
                                        <th class="pe-4">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sent as $i): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold">
                                                <a href="<?= Url::to(['/property/view', 'id' => $i->property_id]) ?>" target="_blank" class="text-dark">
                                                    <?= Html::encode($i->property->title ?? '—') ?>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="el-avatar" style="width:28px; height:28px; font-size:0.75rem">
                                                        <?= Html::encode(mb_strtoupper(mb_substr($i->owner->username ?? 'O', 0, 1))) ?>
                                                    </div>
                                                    <span class="small font-weight-bold"><?= Html::encode($i->owner->username ?? 'Owner') ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-secondary d-block" style="max-width:220px">
                                                    <?= Html::encode(mb_substr($i->message, 0, 60)) ?>...
                                                </small>
                                            </td>
                                            <td>
                                                <?php if ($i->reply): ?>
                                                    <span class="badge bg-success-subtle text-success border border-success">
                                                        <i class="bi bi-chat-check me-1"></i>Replied
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary"><i class="bi bi-clock me-1"></i>Awaiting Reply</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge bg-info-subtle text-info border border-info"><?= $i->status ?></span></td>
                                            <td class="pe-4 text-muted small"><?= date('M d, Y', $i->created_at) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>