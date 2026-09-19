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
        'brandLabel' => '<i class="bi bi-geo-alt-fill text-warning"></i> MachoMtaa',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-lg navbar-light el-navbar shadow-sm'],
    ]);

    // 1. Primary navigation tabs: Nyumbani, Fursa, Fremu Soko
    $navLeftItems = [
        ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
        ['label' => Yii::t('app', 'Opportunities'), 'url' => ['/business/index']],
        ['label' => Yii::t('app', 'Marketplace'), 'url' => ['/property/index']],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-lg-0 mm-main-nav'],
        'items' => $navLeftItems,
    ]);

    // 2. Right side controls: Badili Lugha, Auth / User menu, and Fungua Dashibodi Yako
    $navRightItems = [];

    // Language switcher dropdown (Kiswahili default / English optional)
    $currentLang = Yii::$app->language;
    $navRightItems[] = [
        'label' => '🌐 ' . Yii::t('app', 'Change Language'),
        'items' => [
            [
                'label' => Yii::t('app', 'Kiswahili') . ($currentLang === 'sw' ? '  ✓' : ''),
                'url' => ['/site/set-language', 'code' => 'sw'],
                'linkOptions' => ['class' => $currentLang === 'sw' ? 'fw-bold active' : ''],
            ],
            [
                'label' => Yii::t('app', 'English') . ($currentLang === 'en' ? '  ✓' : ''),
                'url' => ['/site/set-language', 'code' => 'en'],
                'linkOptions' => ['class' => $currentLang === 'en' ? 'fw-bold active' : ''],
            ],
        ],
        'options' => ['class' => 'mm-lang-item'],
    ];

    if (Yii::$app->user->isGuest) {
        $navRightItems[] = ['label' => Yii::t('app', 'Sign Up'), 'url' => ['/site/signup']];
        $navRightItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
        $navRightItems[] = [
            'label' => Yii::t('app', 'Open Your Dashboard'),
            'url' => ['/site/login'],
            'linkOptions' => ['class' => 'btn mm-nav-cta ms-lg-2'],
        ];
    } else {
        $identity = Yii::$app->user->identity;

        $userSubItems = [
            ['label' => '🏠 ' . Yii::t('app', 'Dashboard'), 'url' => $identity->getDashboardRoute()],
            ['label' => '👤 ' . Yii::t('app', 'Profile'), 'url' => ['/profile/index']],
            ['label' => '⚙️ ' . Yii::t('app', 'Settings'), 'url' => ['/profile/settings']],
        ];

        if (in_array($identity->role, [User::ROLE_OWNER, User::ROLE_AGENT], true)) {
            $userSubItems[] = '<div class="dropdown-divider"></div>';
            $userSubItems[] = ['label' => '📋 ' . Yii::t('app', 'My Listings'), 'url' => ['/account/listings']];
            $userSubItems[] = ['label' => '➕ ' . Yii::t('app', 'Add Listing'), 'url' => ['/property-submission/create']];
            $userSubItems[] = ['label' => '📅 ' . Yii::t('app', 'Booking'), 'url' => ['/booking/owner']];
        } else {
            $userSubItems[] = '<div class="dropdown-divider"></div>';
            $userSubItems[] = ['label' => '❤️ ' . Yii::t('app', 'Save'), 'url' => ['/account/favorites']];
            $userSubItems[] = ['label' => '📅 ' . Yii::t('app', 'Booking'), 'url' => ['/booking/index']];
            $userSubItems[] = ['label' => '📊 ' . Yii::t('app', 'Analysis'), 'url' => ['/business/history']];
        }

        if ($identity->role === User::ROLE_ADMIN) {
            $userSubItems[] = '<div class="dropdown-divider"></div>';
            $userSubItems[] = ['label' => '🛡️ Admin Panel', 'url' => ['/admin/index']];
        }

        $userSubItems[] = '<div class="dropdown-divider"></div>';
        $userSubItems[] = '<li>' . Html::beginForm(['/site/logout'], 'post', ['class' => 'px-3 py-1'])
            . Html::submitButton(
                '<i class="bi bi-box-arrow-right"></i> ' . Yii::t('app', 'Logout'),
                ['class' => 'btn btn-sm btn-outline-danger w-100 text-start']
            )
            . Html::endForm() . '</li>';

        $navRightItems[] = [
            'label' => '👤 ' . Html::encode($identity->username),
            'items' => $userSubItems,
        ];

        $navRightItems[] = [
            'label' => Yii::t('app', 'Open Your Dashboard'),
            'url' => $identity->getDashboardRoute(),
            'linkOptions' => ['class' => 'btn mm-nav-cta ms-lg-2'],
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto align-items-lg-center'],
        'items' => $navRightItems,
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