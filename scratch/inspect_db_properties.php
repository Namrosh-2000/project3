<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
new yii\web\Application($config);

$properties = app\models\Property::find()->with(['images', 'owner', 'location', 'category'])->all();

echo "Total Properties in DB: " . count($properties) . "\n\n";

foreach ($properties as $p) {
    echo "ID: {$p->id} | Title: {$p->title} | Status: {$p->status} | Owner: " . ($p->owner->username ?? 'N/A') . " | Images Count: " . count($p->images) . "\n";
    foreach ($p->images as $img) {
        echo "   -> Image ID: {$img->id} | Path: {$img->image_path} | Exists on disk: " . (file_exists(Yii::getAlias('@webroot') . $img->image_path) ? 'YES' : 'NO') . "\n";
    }
}
