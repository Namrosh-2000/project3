<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Location;
use app\models\Property;
use app\models\PropertyCategory;
use app\models\PropertyImage;
use app\models\User;

/**
 * php yii seed/run
 */
class SeedController extends Controller
{
    public function actionRun()
    {
        $this->stdout("Seeding Kinondoni wards...\n");
        $wards = [
            'Sinza', 'Kijitonyama', 'Mikocheni', 'Kariakoo',
            'Kinondoni', 'Msasani', 'Mwananyamala', 'Magomeni', 'Tandale',
            'Kunduchi', 'Kawe', 'Buguruni', 'Mwenge', 'Ubungo',
        ];
        foreach ($wards as $ward) {
            $existing = Location::find()->where(['ward' => $ward])->one();
            if (!$existing) {
                $loc = new Location();
                $loc->region = 'Dar es Salaam';
                $loc->municipality = 'Kinondoni';
                $loc->ward = $ward;
                $loc->save();
                $this->stdout("  + ward: $ward\n");
            }
        }

        $this->stdout("Seeding property categories...\n");
        $categories = [
            ['Single Room', 'single-room', 'room'],
            ['Self-Contained Room', 'self-contained-room', 'room'],
            ['Shared Room', 'shared-room', 'room'],
            ['Furnished Room', 'furnished-room', 'room'],
            ['1-Bedroom Apartment', '1-bedroom-apartment', 'apartment'],
            ['2-Bedroom Apartment', '2-bedroom-apartment', 'apartment'],
            ['3-Bedroom Apartment', '3-bedroom-apartment', 'apartment'],
            ['Furnished Apartment', 'furnished-apartment', 'apartment'],
            ['House for Rent', 'house-rent', 'house'],
            ['House for Sale', 'house-sale', 'house'],
            ['Shop', 'shop', 'business_space'],
            ['Office', 'office', 'business_space'],
            ['Restaurant Space', 'restaurant-space', 'business_space'],
            ['Salon Space', 'salon-space', 'business_space'],
            ['Warehouse', 'warehouse', 'business_space'],
        ];
        foreach ($categories as [$name, $slug, $type]) {
            $existing = PropertyCategory::find()->where(['slug' => $slug])->one();
            if (!$existing) {
                $cat = new PropertyCategory();
                $cat->name = $name;
                $cat->slug = $slug;
                $cat->type = $type;
                $cat->is_active = true;
                $cat->save();
                $this->stdout("  + category: $name\n");
            }
        }

        $this->stdout("Seeding demo admin user...\n");
        if (!User::findByUsername('admin')) {
            $admin = new User();
            $admin->username = 'admin';
            $admin->email = 'admin@eneolink.local';
            $admin->role = User::ROLE_ADMIN;
            $admin->status = User::STATUS_ACTIVE;
            $admin->setPassword('admin123');
            $admin->generateAuthKey();
            $admin->save();
            $this->stdout("  + admin / admin123\n");
        }

        $this->stdout("Seeding demo properties...\n");
        $owner = User::find()->where(['role' => User::ROLE_OWNER])->one();
        if (!$owner) {
            $owner = new User();
            $owner->username = 'demo_owner';
            $owner->email = 'owner@demo.com';
            $owner->phone = '0712345678';
            $owner->role = User::ROLE_OWNER;
            $owner->status = User::STATUS_ACTIVE;
            $owner->setPassword('demo123');
            $owner->generateAuthKey();
            $owner->save();
            $this->stdout("  + demo_owner / demo123\n");
        }

        $this->seedDemoProperties($owner);
        $this->seedDemoImages();

        $this->stdout("Done.\n");
        return ExitCode::OK;
    }

    private function seedDemoProperties($owner)
    {
        $categoryMap = [];
        foreach (PropertyCategory::find()->all() as $cat) {
            $categoryMap[$cat->slug] = $cat;
        }
        $locationIds = Location::find()->select('id')->column();

        $properties = [
            [
                'title' => 'Self-contained Room in Sinza',
                'slug' => 'self-contained-room',
                'ward_id' => $this->findWardId('Sinza', $locationIds),
                'listing_type' => 'rent',
                'price' => 250000,
                'price_period' => 'monthly',
                'bedrooms' => 1,
                'is_furnished' => true,
                'has_parking' => false,
                'has_water' => true,
                'has_electricity' => true,
                'has_security' => true,
                'has_internet' => true,
                'description' => 'Modern self-contained room in a quiet neighborhood. Walking distance to bus stop. Suitable for working professionals.',
                'verified' => true,
            ],
            [
                'title' => '1-Bedroom Apartment in Msasani',
                'slug' => '1-bedroom-apartment',
                'ward_id' => $this->findWardId('Msasani', $locationIds),
                'listing_type' => 'rent',
                'price' => 650000,
                'price_period' => 'monthly',
                'bedrooms' => 1,
                'is_furnished' => false,
                'has_parking' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_security' => true,
                'has_internet' => false,
                'description' => 'Spacious 1-bedroom apartment on the first floor. Close to major roads and shopping areas.',
                'verified' => true,
            ],
            [
                'title' => 'Shop Space in Kariakoo',
                'slug' => 'shop',
                'ward_id' => $this->findWardId('Kariakoo', $locationIds),
                'listing_type' => 'rent',
                'price' => 800000,
                'price_period' => 'monthly',
                'bedrooms' => 0,
                'is_furnished' => false,
                'has_parking' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_security' => true,
                'has_internet' => false,
                'description' => 'Prime commercial shop space in the heart of Kariakoo market. High foot traffic area, ideal for retail or wholesale business.',
                'verified' => true,
            ],
            [
                'title' => '2-Bedroom Apartment in Mikocheni',
                'slug' => '2-bedroom-apartment',
                'ward_id' => $this->findWardId('Mikocheni', $locationIds),
                'listing_type' => 'rent',
                'price' => 900000,
                'price_period' => 'monthly',
                'bedrooms' => 2,
                'is_furnished' => false,
                'has_parking' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_security' => true,
                'has_internet' => true,
                'description' => 'Well-maintained 2BR apartment. Near University of Dar es Salaam area. Good for families.',
                'verified' => true,
            ],
            [
                'title' => 'Office Space in Kinondoni CBD',
                'slug' => 'office',
                'ward_id' => $this->findWardId('Kinondoni', $locationIds),
                'listing_type' => 'rent',
                'price' => 1500000,
                'price_period' => 'monthly',
                'bedrooms' => 0,
                'is_furnished' => false,
                'has_parking' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_security' => true,
                'has_internet' => true,
                'description' => 'Professional office space near Kinondoni bus stand. Suitable for small business, consultancy, or agency.',
                'verified' => true,
            ],
            [
                'title' => 'Furnished Room in Mwenge',
                'slug' => 'furnished-room',
                'ward_id' => $this->findWardId('Mwenge', $locationIds),
                'listing_type' => 'rent',
                'price' => 350000,
                'price_period' => 'monthly',
                'bedrooms' => 1,
                'is_furnished' => true,
                'has_parking' => false,
                'has_water' => true,
                'has_electricity' => true,
                'has_security' => true,
                'has_internet' => true,
                'description' => 'Fully furnished room with bed, wardrobe, desk, and private bathroom. Walking distance to Mwenge market.',
                'verified' => true,
            ],
            [
                'title' => '3-Bedroom House in Kunduchi',
                'slug' => 'house-rent',
                'ward_id' => $this->findWardId('Kunduchi', $locationIds),
                'listing_type' => 'rent',
                'price' => 2500000,
                'price_period' => 'monthly',
                'bedrooms' => 3,
                'is_furnished' => false,
                'has_parking' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_security' => true,
                'has_internet' => false,
                'description' => 'Standalone 3BR house with big compound. Ideal for families. Quiet residential area near the beach.',
                'verified' => true,
            ],
            [
                'title' => 'Salon Space in Magomeni',
                'slug' => 'salon-space',
                'ward_id' => $this->findWardId('Magomeni', $locationIds),
                'listing_type' => 'rent',
                'price' => 400000,
                'price_period' => 'monthly',
                'bedrooms' => 0,
                'is_furnished' => false,
                'has_parking' => false,
                'has_water' => true,
                'has_electricity' => true,
                'has_security' => false,
                'has_internet' => false,
                'description' => 'Small commercial space ideal for salon, barber shop, or beauty services. Located on a busy street.',
                'verified' => true,
            ],
        ];

        $now = time();
        foreach ($properties as $data) {
            $cat = $categoryMap[$data['slug']] ?? null;
            if (!$cat || !$data['ward_id']) continue;

            $existing = Property::find()->where(['title' => $data['title'], 'owner_id' => $owner->id])->one();
            if ($existing) continue;

            $p = new Property();
            $p->title = $data['title'];
            $p->description = $data['description'];
            $p->category_id = $cat->id;
            $p->location_id = $data['ward_id'];
            $p->owner_id = $owner->id;
            $p->listing_type = $data['listing_type'];
            $p->price = $data['price'];
            $p->price_period = $data['price_period'];
            $p->bedrooms = $data['bedrooms'];
            $p->is_furnished = $data['is_furnished'];
            $p->has_parking = $data['has_parking'];
            $p->has_water = $data['has_water'];
            $p->has_electricity = $data['has_electricity'];
            $p->has_security = $data['has_security'];
            $p->has_internet = $data['has_internet'];
            $p->status = $data['verified'] ? Property::STATUS_VERIFIED : Property::STATUS_PENDING;
            $p->is_available = true;
            $p->views_count = rand(5, 100);
            $p->created_at = $now - rand(1, 30) * 86400;
            $p->updated_at = $now;

            if ($p->save()) {
                $this->stdout("  + property: {$p->title}\n");
            }
        }
    }

    private function findWardId($wardName, $locationIds)
    {
        $loc = Location::find()->where(['ward' => $wardName])->one();
        return $loc ? $loc->id : ($locationIds[0] ?? null);
    }

    public function seedDemoImages()
    {
        $this->stdout("Seeding sample property images...\n");
        $properties = Property::find()->all();
        $now = time();

        foreach ($properties as $p) {
            PropertyImage::deleteAll(['property_id' => $p->id]);

            $uploadDir = Yii::getAlias('@app/web/uploads/properties/' . $p->id);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $catSlug = $p->category->slug ?? 'room';
            $title = htmlspecialchars($p->title, ENT_QUOTES, 'UTF-8');
            $ward = htmlspecialchars($p->location->ward ?? 'Kinondoni', ENT_QUOTES, 'UTF-8');

            // Image 1: Cover image
            $coverSvg = $this->generatePropertySvg($title, $ward, $catSlug, 'cover');
            $coverName = 'cover_' . uniqid() . '.svg';
            file_put_contents($uploadDir . '/' . $coverName, $coverSvg);

            $img1 = new PropertyImage();
            $img1->property_id = $p->id;
            $img1->image_path = '/uploads/properties/' . $p->id . '/' . $coverName;
            $img1->is_cover = 1;
            $img1->sort_order = 0;
            $img1->created_at = $now;
            $img1->save();

            // Image 2: Interior view
            $interiorSvg = $this->generatePropertySvg($title, $ward, $catSlug, 'interior');
            $interiorName = 'interior_' . uniqid() . '.svg';
            file_put_contents($uploadDir . '/' . $interiorName, $interiorSvg);

            $img2 = new PropertyImage();
            $img2->property_id = $p->id;
            $img2->image_path = '/uploads/properties/' . $p->id . '/' . $interiorName;
            $img2->is_cover = 0;
            $img2->sort_order = 1;
            $img2->created_at = $now;
            $img2->save();

            $this->stdout("  + seeded sample images for: {$p->title}\n");
        }
    }

    private function generatePropertySvg($title, $ward, $catSlug, $viewType)
    {
        $bgGradient = 'skyGrad';
        $accentColor = '#3b82f6';
        $categoryLabel = 'PROPERTY';
        $buildingColor1 = '#1e293b';
        $buildingColor2 = '#0f172a';

        if (strpos($catSlug, 'apartment') !== false) {
            $categoryLabel = 'APARTMENT BUILDING';
            $bgGradient = 'aptGrad';
            $accentColor = '#0284c7';
        } elseif (strpos($catSlug, 'house') !== false) {
            $categoryLabel = 'STANDALONE HOUSE';
            $bgGradient = 'houseGrad';
            $accentColor = '#16a34a';
        } elseif (strpos($catSlug, 'office') !== false) {
            $categoryLabel = 'COMMERCIAL OFFICE';
            $bgGradient = 'officeGrad';
            $accentColor = '#6366f1';
        } elseif (in_array($catSlug, ['shop', 'salon-space', 'restaurant-space', 'warehouse'])) {
            $categoryLabel = 'BUSINESS & RETAIL SPACE';
            $bgGradient = 'bizGrad';
            $accentColor = '#f59e0b';
        } else {
            $categoryLabel = 'RESIDENTIAL ROOM';
            $bgGradient = 'roomGrad';
            $accentColor = '#0d9488';
        }

        $viewTitle = ($viewType === 'cover') ? 'MAIN EXTERIOR & ENTRANCE' : 'INTERIOR LIVING SPACE';

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 530" width="100%" height="100%">
  <defs>
    <linearGradient id="aptGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0f172a"/>
      <stop offset="50%" stop-color="#1e293b"/>
      <stop offset="100%" stop-color="#0f2942"/>
    </linearGradient>
    <linearGradient id="houseGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#064e3b"/>
      <stop offset="50%" stop-color="#0f5132"/>
      <stop offset="100%" stop-color="#022c22"/>
    </linearGradient>
    <linearGradient id="officeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#1e1b4b"/>
      <stop offset="50%" stop-color="#312e81"/>
      <stop offset="100%" stop-color="#0f172a"/>
    </linearGradient>
    <linearGradient id="bizGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#451a03"/>
      <stop offset="50%" stop-color="#78350f"/>
      <stop offset="100%" stop-color="#271104"/>
    </linearGradient>
    <linearGradient id="roomGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#134e4a"/>
      <stop offset="50%" stop-color="#0f766e"/>
      <stop offset="100%" stop-color="#042f2e"/>
    </linearGradient>
    <linearGradient id="cardOverlay" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#000000" stop-opacity="0.2"/>
      <stop offset="100%" stop-color="#000000" stop-opacity="0.85"/>
    </linearGradient>
    <linearGradient id="goldBadge" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#d97706"/>
      <stop offset="100%" stop-color="#b45309"/>
    </linearGradient>
  </defs>

  <!-- Sky & Canvas Background -->
  <rect width="800" height="530" fill="url(#{$bgGradient})"/>

  <!-- Architectural Background Elements -->
  <g opacity="0.25">
    <line x1="0" y1="100" x2="800" y2="100" stroke="#ffffff" stroke-width="1"/>
    <line x1="0" y1="200" x2="800" y2="200" stroke="#ffffff" stroke-width="1"/>
    <line x1="0" y1="300" x2="800" y2="300" stroke="#ffffff" stroke-width="1"/>
    <line x1="0" y1="400" x2="800" y2="400" stroke="#ffffff" stroke-width="1"/>
  </g>

  <!-- Main Building / Room Vector Silhouette -->
  <g transform="translate(180, 70)">
    <!-- Main Structure block -->
    <rect x="0" y="40" width="440" height="280" rx="16" fill="#ffffff" opacity="0.08" stroke="#ffffff" stroke-width="2"/>

    <!-- Roof / Ceiling Arch -->
    <path d="M-20,40 L220,-20 L460,40 Z" fill="#ffffff" opacity="0.12"/>

    <!-- Windows / Rooms Grid -->
    <rect x="40" y="80" width="90" height="80" rx="6" fill="#fde047" opacity="0.3"/>
    <rect x="175" y="80" width="90" height="80" rx="6" fill="#38bdf8" opacity="0.3"/>
    <rect x="310" y="80" width="90" height="80" rx="6" fill="#fde047" opacity="0.3"/>

    <rect x="40" y="190" width="90" height="80" rx="6" fill="#38bdf8" opacity="0.3"/>
    <!-- Entrance door -->
    <rect x="185" y="180" width="70" height="140" rx="6" fill="{$accentColor}" opacity="0.7"/>
    <circle cx="240" cy="250" r="5" fill="#ffffff"/>
    <rect x="310" y="190" width="90" height="80" rx="6" fill="#38bdf8" opacity="0.3"/>
  </g>

  <!-- Gradient Overlay for Contrast -->
  <rect width="800" height="530" fill="url(#cardOverlay)"/>

  <!-- Top Left: Verified EneoLink Badge -->
  <g transform="translate(30, 30)">
    <rect width="210" height="34" rx="17" fill="url(#goldBadge)"/>
    <circle cx="20" cy="17" r="9" fill="#ffffff"/>
    <path d="M16 17 l3 3 l5 -5" stroke="#b45309" stroke-width="2.5" fill="none" stroke-linecap="round"/>
    <text x="36" y="22" font-family="system-ui, -apple-system, sans-serif" font-size="12" font-weight="700" fill="#ffffff" letter-spacing="0.5">VERIFIED LISTING</text>
  </g>

  <!-- Top Right: View Type Badge -->
  <g transform="translate(570, 30)">
    <rect width="200" height="34" rx="8" fill="#000000" opacity="0.6" stroke="#ffffff" stroke-opacity="0.3" stroke-width="1"/>
    <text x="100" y="22" font-family="system-ui, -apple-system, sans-serif" font-size="11" font-weight="600" fill="#e2e8f0" text-anchor="middle">{$viewTitle}</text>
  </g>

  <!-- Bottom Details Overlay Card -->
  <g transform="translate(30, 370)">
    <rect width="740" height="130" rx="12" fill="#0f172a" opacity="0.88" stroke="#ffffff" stroke-opacity="0.15" stroke-width="1"/>

    <!-- Category Pill -->
    <rect x="24" y="20" width="180" height="26" rx="6" fill="{$accentColor}"/>
    <text x="114" y="37" font-family="system-ui, -apple-system, sans-serif" font-size="11" font-weight="700" fill="#ffffff" text-anchor="middle" letter-spacing="0.5">{$categoryLabel}</text>

    <!-- Property Title -->
    <text x="24" y="78" font-family="system-ui, -apple-system, sans-serif" font-size="22" font-weight="800" fill="#ffffff">{$title}</text>

    <!-- Location & Ward -->
    <text x="24" y="106" font-family="system-ui, -apple-system, sans-serif" font-size="14" font-weight="500" fill="#94a3b8">📍 {$ward}, Kinondoni &middot; Dar es Salaam, Tanzania</text>

    <!-- Brand stamp -->
    <text x="716" y="106" font-family="system-ui, -apple-system, sans-serif" font-size="14" font-weight="700" fill="{$accentColor}" text-anchor="end">EneoLink</text>
  </g>
</svg>
SVG;
    }
}