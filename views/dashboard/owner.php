<?php

/** @var yii\web\View $this */
/** @var app\models\User $identity */

use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'dash.owner.title');
?>

<div class="mm-dash-head">
    <h1><?= Html::encode(Yii::t('app', 'dash.owner.title')) ?></h1>
    <p><?= Html::encode(Yii::t('app', 'dash.owner.sub')) ?></p>
</div>

<div class="mm-dash-grid">
    <?= Html::a(
        '<i class="bi bi-building"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.listings')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.listings_desc')) . '</span>',
        ['/account/listings'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-plus-square"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.add_listing')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.add_listing_desc')) . '</span>',
        ['/property-submission/create'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <?= Html::a(
        '<i class="bi bi-chat-dots"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.inquiries')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.inquiries_desc')) . '</span>',
        ['/account/inquiries'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <div class="mm-dash-card mm-dash-card--soon">
        <i class="bi bi-door-open"></i>
        <strong><?= Html::encode(Yii::t('app', 'dash.owner.viewings')) ?></strong>
        <span><?= Html::encode(Yii::t('app', 'dash.owner.viewings_desc')) ?></span>
        <span class="mm-dash-soon-tag"><?= Html::encode(Yii::t('app', 'dash.soon')) ?></span>
    </div>

    <div class="mm-dash-card mm-dash-card--soon">
        <i class="bi bi-envelope"></i>
        <strong><?= Html::encode(Yii::t('app', 'dash.owner.messages')) ?></strong>
        <span><?= Html::encode(Yii::t('app', 'dash.owner.messages_desc')) ?></span>
        <span class="mm-dash-soon-tag"><?= Html::encode(Yii::t('app', 'dash.soon')) ?></span>
    </div>

    <?= Html::a(
        '<i class="bi bi-calendar-check"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.bookings')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.bookings_desc')) . '</span>',
        ['/booking/owner'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>

    <div class="mm-dash-card mm-dash-card--soon">
        <i class="bi bi-patch-check"></i>
        <strong><?= Html::encode(Yii::t('app', 'dash.owner.verification')) ?></strong>
        <span><?= Html::encode(Yii::t('app', 'dash.owner.verification_desc')) ?></span>
        <span class="mm-dash-soon-tag"><?= Html::encode(Yii::t('app', 'dash.soon')) ?></span>
    </div>

    <?= Html::a(
        '<i class="bi bi-person"></i>'
        . '<strong>' . Html::encode(Yii::t('app', 'dash.owner.profile')) . '</strong>'
        . '<span>' . Html::encode(Yii::t('app', 'dash.owner.profile_desc')) . '</span>',
        ['/profile/index'],
        ['class' => 'mm-dash-card', 'encode' => false]
    ) ?>
</div>
