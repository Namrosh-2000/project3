<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'About EneoLink';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-info-circle-fill text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Empowering digital property discovery &amp; smart business opportunities across Kinondoni, Dar es Salaam.</p>
        </div>
        <div>
            <a href="<?= Url::to(['/property/index']) ?>" class="btn btn-warning fw-bold shadow-sm">
                <i class="bi bi-search me-1"></i> Explore Properties
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0 p-4">
            <div class="card-body">
                <span class="badge bg-primary-subtle text-primary border border-primary mb-3 px-3 py-2">Our Core Mission</span>
                <h3 class="fw-bold text-dark mb-3">Connecting Property Owners and Seekers Digitally First</h3>
                <p class="text-secondary leading-relaxed mb-4">
                    EneoLink is designed to bridge the gap between property owners, listing agents, and room/business seekers in Kinondoni Municipality. Before spending days physically walking around Sinza, Kawe, Mwenge, or Mikocheni, users can browse verified rooms, apartments, and commercial spaces online with transparent pricing and map locations.
                </p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <i class="bi bi-shield-check text-success display-6 d-block mb-1"></i>
                            <strong class="d-block text-dark">Verified Listings</strong>
                            <small class="text-muted">Admin-moderated safety</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <i class="bi bi-geo-alt-fill text-danger display-6 d-block mb-1"></i>
                            <strong class="d-block text-dark">Kinondoni Focus</strong>
                            <small class="text-muted">Ward level accuracy</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0 p-4">
            <div class="card-body">
                <span class="badge bg-success-subtle text-success border border-success mb-3 px-3 py-2">Commercial Insight</span>
                <h3 class="fw-bold text-dark mb-3">Smart Business Opportunity Advisory</h3>
                <p class="text-secondary leading-relaxed mb-4">
                    Finding a commercial space is only half the battle. EneoLink provides an integrated Business Opportunity Advisor tool. By entering your monthly budget and chosen ward, our system calculates commercial demand scores, available spaces, and average rent, giving entrepreneurs confidence in where to establish shops and offices.
                </p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <i class="bi bi-lightbulb-fill text-warning display-6 d-block mb-1"></i>
                            <strong class="d-block text-dark">Decision Support</strong>
                            <small class="text-muted">Budget heuristic tools</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <i class="bi bi-chat-left-dots-fill text-primary display-6 d-block mb-1"></i>
                            <strong class="d-block text-dark">Direct Inquiry</strong>
                            <small class="text-muted">Direct contact with owners</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card bg-gradient bg-dark text-white p-5 rounded-4 shadow-lg mb-5 border-0">
    <div class="row align-items-center g-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-white mb-2">Ready to List Your Property or Start a Business?</h2>
            <p class="text-light-50 mb-0">Join property owners and business seekers utilizing EneoLink across Kinondoni Municipality today.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?= Url::to(['/site/signup']) ?>" class="btn btn-warning btn-lg px-4 fw-bold shadow">
                Get Started Now
            </a>
        </div>
    </div>
</div>
