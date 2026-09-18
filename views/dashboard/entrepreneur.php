<?php

/** @var yii\web\View $this */
/** @var app\models\User $identity */

use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'dash.entrepreneur.title');
?>

<div class="mm-dash-head">
    <h1><?= Html::encode(Yii::t('app', 'dash.entrepreneur.title')) ?></h1>
    <p><?= Html::encode(Yii::t('app', 'dash.entrepreneur.sub')) ?></p>
</div>

<div class="mm-dash-grid">
    <?= Html::a(
        '<i class="bi bi-graph-up-arrow"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.opportunities')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.opportunities_desc')) . '</span>',
        ['/business/index'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-clock-history"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.history')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.history_desc')) . '</span>',
        ['/business/history'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-heart"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.saved')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.saved_desc')) . '</span>',
        ['/account/favorites'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-calendar-check"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.bookings')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.bookings_desc')) . '</span>',
        ['/booking/index'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <div class="mm-dash-card mm-dash-card--soon">
        <i class="bi bi-bell"></i>
        <strong><?= Html::encode(Yii::t('app', 'dash.entrepreneur.notifications')) ?></strong>
        <span><?= Html::encode(Yii::t('app', 'dash.entrepreneur.notifications_desc')) ?></span>
        <span class="mm-dash-soon-tag"><?= Html::encode(Yii::t('app', 'dash.soon')) ?></span>
    </div>

    <?= Html::a(
        '<i class="bi bi-person"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.entrepreneur.profile')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.entrepreneur.profile_desc')) . '</span>',
        ['/profile/index'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>
</div>
