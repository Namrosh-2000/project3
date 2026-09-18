<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\models\Booking;
use app\models\Property;
use app\models\User;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => 'MachoMtaa - Local Business Intelligence & Commercial Space Discovery for Kinondoni, Dar es Salaam']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode(($this->title ? $this->title . ' | ' : '') . 'MachoMtaa') ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => '<i class="bi bi-geo-alt-fill"></i> MachoMtaa',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-lg navbar-light el-navbar'],
    ]);

    $navItems = [
        ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
        ['label' => Yii::t('app', 'Opportunities'), 'url' => ['/business/index']],
        ['label' => Yii::t('app', 'Marketplace'), 'url' => ['/property/index']],
    ];

    if (!Yii::$app->user->isGuest) {
        $identity = Yii::$app->user->identity;

        if ($identity->role === User::ROLE_ADMIN) {
            $navItems[] = ['label' => '📅 Bookings', 'url' => ['/admin/bookings']];
        } elseif (in_array($identity->role, [User::ROLE_OWNER, User::ROLE_AGENT], true)) {
            $pendingOwnerBookings = Booking::find()->where(['owner_id' => $identity->id, 'status' => Booking::STATUS_PENDING])->count();
            $bLabel = '📅 Bookings';
            if ($pendingOwnerBookings > 0) {
                $bLabel .= ' <span class="badge bg-warning text-dark">' . $pendingOwnerBookings . '</span>';
            }
            $navItems[] = ['label' => $bLabel, 'url' => ['/booking/owner'], 'encode' => false];
        } else {
            $navItems[] = ['label' => '📅 My Bookings', 'url' => ['/booking/index']];
        }

        $myItems = [
            ['label' => '🏠 ' . Yii::t('app', 'Open Your Dashboard'), 'url' => $identity->getDashboardRoute()],
            ['label' => '📅 My Bookings', 'url' => ['/booking/index']],
            ['label' => 'Favorites', 'url' => ['/account/favorites']],
            ['label' => 'Inquiries', 'url' => ['/account/inquiries']],
        ];
        if (in_array($identity->role, [User::ROLE_OWNER, User::ROLE_AGENT], true)) {
            $pendingOwnerBookings = Booking::find()->where(['owner_id' => $identity->id, 'status' => Booking::STATUS_PENDING])->count();
            $ownerBookingsLabel = '📥 Received Bookings';
            if ($pendingOwnerBookings > 0) {
                $ownerBookingsLabel .= ' <span class="badge bg-warning text-dark">' . $pendingOwnerBookings . '</span>';
            }
            $myItems[] = ['label' => $ownerBookingsLabel, 'url' => ['/booking/owner'], 'encode' => false];
            $myItems[] = ['label' => 'My Listings', 'url' => ['/account/listings']];
            $myItems[] = ['label' => '+ Add Property', 'url' => ['/property-submission/create']];
        }
        if ($identity->role === User::ROLE_ADMIN) {
            $pendingCount = Property::find()->where(['status' => Property::STATUS_PENDING])->count();
            $adminLabel = 'Admin';
            if ($pendingCount > 0) {
                $adminLabel .= ' <span class="el-nav-badge">' . $pendingCount . '</span>';
            }
            $myItems[] = ['label' => $adminLabel, 'url' => ['/admin/index'], 'encode' => false];
            $myItems[] = ['label' => 'Manage System Bookings', 'url' => ['/admin/bookings']];
        }
        $navItems[] = ['label' => 'My Account', 'items' => $myItems];
        $navItems[] = ['label' => '👤 ' . Yii::$app->user->identity->username, 'items' => [
            ['label' => 'Profile', 'url' => ['/profile/index']],
            ['label' => 'Account Settings', 'url' => ['/profile/settings']],
            ['label' => '---'],
        ]];
    }

    // Kiswahili/English switcher — choice is remembered in a cookie
    // (SiteController::actionSetLanguage); default is Kiswahili.
    $currentLang = Yii::$app->language;
    $navItems[] = '<li class="nav-item"><span class="mm-lang-switch">'
        . Html::a('SW', ['/site/set-language', 'code' => 'sw'], ['class' => $currentLang === 'sw' ? 'active' : ''])
        . '<span>/</span>'
        . Html::a('EN', ['/site/set-language', 'code' => 'en'], ['class' => $currentLang === 'en' ? 'active' : ''])
        . '</span></li>';

    if (Yii::$app->user->isGuest) {
        $navItems[] = ['label' => Yii::t('app', 'Sign Up'), 'url' => ['/site/signup']];
        $navItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
        $navItems[] = ['label' => Yii::t('app', 'Open Your Dashboard'), 'url' => ['/site/login'], 'linkOptions' => ['class' => 'mm-nav-cta']];
    } else {
        $navItems[] = ['label' => Yii::t('app', 'Open Your Dashboard'), 'url' => $identity->getDashboardRoute(), 'linkOptions' => ['class' => 'mm-nav-cta']];
        $navItems[] = '<li class="nav-item">'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
            . Html::submitButton(
                Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'nav-link btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => $navItems,
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container py-3">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-4 el-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <strong>MachoMtaa</strong> &mdash; <?= Html::encode(Yii::t('app', 'footer.tagline')) ?>
            </div>
            <div class="col-md-6 text-md-end">
                <small><?= Html::encode(Yii::t('app', 'footer.location')) ?></small>
            </div>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>