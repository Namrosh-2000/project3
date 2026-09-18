<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Property;
use app\models\PropertyImage;

class SeedImagesController extends Controller
{
    // API key ya Pexels
    private $pexelsApiKey = '8OVToFM2u7YmAonTXpmFLwUoLBxJjH2WTFyhDJ1oESmbHScVrZjv0HxU';

    private $searchTerms = [
        'room' => 'furnished bedroom interior',
        'apartment' => 'modern apartment building exterior',
        'house' => 'residential house exterior Africa',
        'business_space' => 'small shop storefront',
    ];

    public function actionRun()
    {
        $properties = Property::find()->with('category')->all();
        $this->stdout("Jumla ya properties: " . count($properties) . "\n\n");

        $updated = 0;
        $created = 0;

        foreach ($properties as $property) {
            $type = $property->category->type ?? 'apartment';
            $term = $this->searchTerms[$type] ?? 'modern apartment';

            $imageUrl = $this->fetchPexelsImage($term);

            if (!$imageUrl) {
                $this->stdout("✘ Pexels haikurudisha picha kwa: {$property->title} (term: {$term})\n");
                continue;
            }

            // Tafuta cover image iliyopo, kama ipo IBADILISHE, kama haipo TENGENEZA
            $existing = PropertyImage::find()
                ->where(['property_id' => $property->id, 'is_cover' => true])
                ->one();

            if ($existing) {
                $existing->image_path = $imageUrl;
                $existing->save(false);
                $this->stdout("↻ Imebadilishwa: {$property->title}\n");
                $updated++;
            } else {
                $img = new PropertyImage();
                $img->property_id = $property->id;
                $img->image_path = $imageUrl;
                $img->is_cover = true;
                $img->sort_order = 0;
                $img->created_at = time();
                $img->save();
                $this->stdout("✔ Imetengenezwa: {$property->title}\n");
                $created++;
            }

            usleep(300000);
        }

        $this->stdout("\nJumla: {$updated} zimebadilishwa, {$created} zimetengenezwa mpya.\n");
    }

    private function fetchPexelsImage($query)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.pexels.com/v1/search?query=' . urlencode($query) . '&per_page=1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: ' . $this->pexelsApiKey]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            $this->stdout("  HTTP {$httpCode}: " . substr($response, 0, 150) . "\n");
            return null;
        }

        $data = json_decode($response, true);
        return $data['photos'][0]['src']['large'] ?? null;
    }
}