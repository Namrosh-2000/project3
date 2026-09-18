<?php

/** @var yii\web\View $this */
/** @var array $properties */

use yii\bootstrap5\Html;
use yii\helpers\Json;
use yii\web\View;

$this->title = 'Interactive Property Map';
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['depends' => 'app\\assets\\AppAsset']);
?>

<div class="el-page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-geo-alt-fill text-warning me-2"></i><?= Html::encode($this->title) ?></h1>
            <p>Explore properties and commercial spaces geo-located across Kinondoni Municipality.</p>
        </div>
    </div>
</div>

<div class="card shadow-lg border-0 overflow-hidden mb-4">
    <div class="card-body p-0">
        <div id="map" style="height: 650px; width: 100%;"></div>
    </div>
</div>

<?php
$points = [];
foreach ($properties as $p) {
    if ($p->location && $p->location->latitude && $p->location->longitude) {
        $points[] = [
            'lat' => (float)$p->location->latitude,
            'lng' => (float)$p->location->longitude,
            'title' => $p->title,
            'price' => number_format((float)$p->price),
            'ward' => $p->location->ward ?? 'Kinondoni',
            'id' => $p->id,
        ];
    }
}
$jsPoints = Json::encode($points);
$js = <<<JS
var map = L.map('map').setView([-6.7924, 39.2083], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19, attribution: '© OpenStreetMap | EneoLink Kinondoni'
}).addTo(map);

var points = $jsPoints;
points.forEach(function(p) {
    var marker = L.marker([p.lat, p.lng]).addTo(map);
    var content = '<div class="p-1">' +
        '<strong class="d-block text-dark">' + p.title + '</strong>' +
        '<small class="text-muted d-block">📍 Ward: ' + p.ward + '</small>' +
        '<div class="text-primary font-weight-bold my-1">TSh ' + p.price + '</div>' +
        '<a href="/property/' + p.id + '" class="btn btn-sm btn-primary w-100 text-white mt-1" target="_blank">View Details</a>' +
        '</div>';
    marker.bindPopup(content);
});
JS;
$this->registerJs($js);
?>