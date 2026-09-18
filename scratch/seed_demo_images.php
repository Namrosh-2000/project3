<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
new yii\web\Application($config);

$webroot = Yii::getAlias('@webroot');
$targetDir = $webroot . '/uploads/demo';

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0775, true);
}

$artifactsDir = 'C:\Users\AMOS\.gemini\antigravity\brain\aaa5c5f8-28a4-44b6-9495-b36a38e2718b';
$imageMap = [
    3 => 'demo_apartment_kinondoni_1788857396742.jpg',
    4 => 'demo_office_space_1788857418476.jpg',
    5 => 'demo_apartment_kinondoni_1788857396742.jpg',
    6 => 'demo_office_space_1788857418476.jpg',
    7 => 'demo_furnished_room_1788857457503.jpg',
    8 => 'demo_house_kunduchi_1788857436532.jpg',
    9 => 'demo_office_space_1788857418476.jpg',
];

$now = time();

foreach ($imageMap as $propertyId => $filename) {
    $src = $artifactsDir . '\\' . $filename;
    $destFilename = 'demo_prop_' . $propertyId . '.jpg';
    $destPath = $targetDir . '/' . $destFilename;
    $webPath = '/uploads/demo/' . $destFilename;

    if (file_exists($src)) {
        copy($src, $destPath);
        echo "Copied {$filename} to {$destPath}\n";

        // Check if image record already exists for this property
        $exists = app\models\PropertyImage::find()
            ->where(['property_id' => $propertyId])
            ->exists();

        if (!$exists) {
            $img = new app\models\PropertyImage();
            $img->property_id = $propertyId;
            $img->image_path = $webPath;
            $img->is_cover = 1;
            $img->sort_order = 0;
            $img->created_at = $now;
            if ($img->save()) {
                echo "Attached image to Property #{$propertyId}\n";
            } else {
                echo "Failed to save image for Property #{$propertyId}: " . print_r($img->getErrors(), true) . "\n";
            }
        } else {
            echo "Property #{$propertyId} already has image.\n";
        }
    } else {
        echo "Source file {$src} not found.\n";
    }
}

echo "Demo images attached successfully!\n";
