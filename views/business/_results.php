<?php

/** @var yii\web\View $this */
/** @var array $results Normalized analysis array (see BusinessController::serializeAnalysis):
 *   ['categories' => [...], 'ward_indicators' => [...], 'data_sources' => [...], 'methodology' => string]
 */
/** @var string|null $ward */
/** @var float|null $budget */
/** @var int|null $selectedLocationId */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$categories = $results['categories'] ?? [];
$wardIndicators = $results['ward_indicators'] ?? [];
$dataSources = $results['data_sources'] ?? [];
$methodology = $results['methodology'] ?? '';

if (!function_exists('mm_indicator_chip')) {
    /**
     * One admin-entered local-data reading, rendered as a small chip:
     * label, the actual value (never invented), confidence, and a
     * Demo badge whenever the underlying source is flagged as such.
     */
    function mm_indicator_chip($ind)
    {
        $confidenceClass = 'mm-confidence--' . $ind['confidence_level'];
        $html = '<div class="mm-indicator-chip ' . $confidenceClass . '">';
        $html .= '<span class="mm-indicator-chip-label">' . Html::encode($ind['type_label']) . '</span>';
        if ($ind['display_value'] !== null) {
            $html .= '<strong class="mm-indicator-chip-value">' . Html::encode($ind['display_value']) . '</strong>';
        }
        $html .= '<span class="mm-indicator-chip-confidence">' . Html::encode($ind['confidence_label']) . '</span>';
        if (!empty($ind['is_demo'])) {
            $html .= '<span class="mm-demo-badge mm-demo-badge--sm">' . Yii::t('app', 'localdata.demo_badge') . '</span>';
        }
        if (!empty($ind['notes'])) {
            $html .= '<p class="mm-indicator-chip-notes">' . Html::encode($ind['notes']) . '</p>';
        }
        $html .= '</div>';
        return $html;
    }
}
?>

<div class="mm-fursa-dash">
    <div class="mm-fursa-dash-head">
        <h3 class="el-heading mb-0">
            <?= Yii::t('app', 'fursa.results.heading', ['ward' => Html::encode($ward)]) ?>
        </h3>
        <span class="mm-fursa-budget-pill">
            <?= Yii::t('app', 'fursa.results.budget_label') ?>: TSh <?= number_format((float)$budget) ?>
        </span>
    </div>

    <p class="mm-fursa-dash-sub">
        <i class="bi bi-info-circle"></i>
        <?= Yii::t('app', 'fursa.results.summary_note', ['count' => count($categories)]) ?>
    </p>

    <?php if (!empty($wardIndicators)): ?>
        <div class="mm-local-context mm-local-context--ward">
            <h4 class="mm-local-context-title">
                <i class="bi bi-geo-alt"></i>
                <?= Yii::t('app', 'localdata.ward_context_heading', ['ward' => Html::encode($ward)]) ?>
            </h4>
            <div class="mm-local-context-grid">
                <?php foreach ($wardIndicators as $ind): ?>
                    <?= mm_indicator_chip($ind) ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="mm-fursa-cards">
        <?php foreach ($categories as $r): ?>
            <?php
            $tier = $r['match_score'] >= 80 ? 'good' : ($r['match_score'] >= 65 ? 'mid' : 'low');
            ?>
            <div class="mm-fursa-card mm-fursa-card--<?= $tier ?>">
                <div class="mm-fursa-card-top">
                    <h4><i class="bi bi-shop"></i> <?= Html::encode($r['category_name']) ?></h4>
                    <span class="mm-score-badge mm-score-badge--<?= $tier ?>">
                        <?= $r['match_score'] ?>% <?= Yii::t('app', 'fursa.results.match_score') ?>
                    </span>
                </div>

                <div class="mm-score-bar">
                    <div class="mm-score-bar-fill mm-score-bar-fill--<?= $tier ?>" style="width: <?= min(100, max(15, $r['match_score'])) ?>%"></div>
                </div>

                <p class="mm-fursa-reco"><?= Html::encode($r['recommendation']) ?></p>

                <div class="mm-fursa-metrics">
                    <div>
                        <span class="mm-metric-label"><?= Yii::t('app', 'fursa.results.metric_density') ?></span>
                        <strong><?= $r['available_count'] ?> <?= Yii::t('app', 'fursa.results.spaces_listed') ?></strong>
                    </div>
                    <div>
                        <span class="mm-metric-label"><?= Yii::t('app', 'fursa.results.metric_price') ?></span>
                        <strong class="text-success">TSh <?= number_format($r['avg_monthly_price']) ?></strong>
                    </div>
                    <div>
                        <span class="mm-metric-label"><?= Yii::t('app', 'fursa.results.metric_gap') ?></span>
                        <strong>
                            <?= $r['available_count'] == 0
                                ? Yii::t('app', 'fursa.results.gap_yes')
                                : Yii::t('app', 'fursa.results.gap_no') ?>
                        </strong>
                    </div>
                </div>

                <?php if (!empty($r['local_indicators'])): ?>
                    <div class="mm-local-context mm-local-context--category">
                        <?php foreach ($r['local_indicators'] as $ind): ?>
                            <?= mm_indicator_chip($ind) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($r['properties'])): ?>
                    <div class="mm-fursa-props">
                        <strong><?= Yii::t('app', 'fursa.results.suitable_spaces') ?></strong>
                        <?php foreach ($r['properties'] as $prop): ?>
                            <a href="<?= Url::to(['/property/view', 'id' => $prop['id']]) ?>" class="mm-fursa-prop-link">
                                <span><?= Html::encode($prop['title']) ?></span>
                                <span class="mm-fursa-prop-price">TSh <?= number_format($prop['price']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($r['available_count'] > 0): ?>
                    <a class="mm-btn-primary mm-fursa-browse"
                       href="<?= Yii::$app->homeUrl ?>property/index?PropertySearch[category_id]=<?= $r['category_id'] ?>&PropertySearch[location_id]=<?= (int)$selectedLocationId ?>">
                        <i class="bi bi-search"></i> <?= Yii::t('app', 'fursa.results.browse_all', ['count' => $r['available_count'], 'ward' => Html::encode($ward)]) ?>
                    </a>
                <?php else: ?>
                    <button class="mm-btn-secondary mm-fursa-browse" disabled>
                        <?= Yii::t('app', 'fursa.results.no_spaces') ?>
                    </button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mm-fursa-demo-note">
        <span class="mm-demo-badge"><?= Yii::t('app', 'localdata.demo_badge') ?></span>
        <p><?= Yii::t('app', 'fursa.results.demo_note') ?></p>
    </div>

    <?php if ($methodology): ?>
        <p class="mm-methodology-note">
            <i class="bi bi-diagram-3"></i> <?= Html::encode($methodology) ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($dataSources)): ?>
        <div class="mm-data-sources">
            <h5 class="mm-data-sources-title"><?= Yii::t('app', 'localdata.sources_heading') ?></h5>
            <ul class="mm-data-sources-list">
                <?php foreach ($dataSources as $src): ?>
                    <li>
                        <span><?= Html::encode($src['name']) ?></span>
                        <?php if ($src['collected_at']): ?>
                            <span class="mm-data-sources-date"><?= Html::encode($src['collected_at']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($src['is_demo'])): ?>
                            <span class="mm-demo-badge mm-demo-badge--sm"><?= Yii::t('app', 'localdata.demo_badge') ?></span>
                        <?php else: ?>
                            <span class="mm-verified-badge"><?= Yii::t('app', 'localdata.verified_badge') ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
