<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\BusinessAnalysis;
use app\models\Location;
use app\services\MarketAnalysisService;

/**
 * Fursa — the business/market-analysis interface (Phase 3), now
 * backed by MarketAnalysisService (Phase 4): listing-based match
 * scoring plus any admin-entered local-data context for the chosen
 * ward, each carrying its own confidence level and data source.
 *
 * actionIndex runs a fresh "Chambua Soko Langu" request and, for
 * logged-in entrepreneurs, saves it to Historia ya Uchambuzi.
 * actionHistory lists past analyses; actionView reopens one exactly
 * as it was computed, via its stored results_snapshot.
 */
class BusinessController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['history', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $service = new MarketAnalysisService();

        $wards = Location::find()
            ->select(['ward'])
            ->distinct()
            ->orderBy(['ward' => SORT_ASC])
            ->column();

        $results = [];
        $budget = null;
        $ward = null;
        $selectedInterest = null;
        $selectedLocationId = null;
        $saved = null;

        // Extra Fursa-form fields (§15 of the product spec). These are
        // captured and saved with the analysis so the entrepreneur's
        // full request is on record, even though only budget + ward
        // feed the actual match-score computation below — we don't
        // have granular enough data (customer segments, exact plot
        // sizes) to score against the rest without fabricating it.
        $businessType = null;
        $businessVision = null;
        $targetCustomers = null;
        $startingBudget = null;
        $spaceSizeNeeded = null;
        $specialRequirements = null;

        $user = !Yii::$app->user->isGuest ? Yii::$app->user->identity : null;

        $request = Yii::$app->request;
        if ($request->isPost || $request->isGet) {
            $budget = (float)($request->post('budget') ?: $request->get('budget'));
            $ward = trim((string)($request->post('ward') ?: $request->get('ward')));
            $selectedInterest = trim((string)($request->post('interest') ?: $request->get('interest')));

            $businessType = trim((string)$request->post('business_type', $selectedInterest));
            $businessVision = trim((string)$request->post('business_vision', ''));
            $targetCustomers = trim((string)$request->post('target_customers', ''));
            $startingBudget = (float)$request->post('starting_budget', 0);
            $spaceSizeNeeded = trim((string)$request->post('space_size_needed', ''));
            $specialRequirements = trim((string)$request->post('special_requirements', ''));

            if ($selectedInterest === '' && $businessType !== '') {
                $selectedInterest = $businessType;
            }

            // Bado hajatuma form: tumia mapendekezo aliyoyaweka kwenye profile yake.
            if (!$request->isPost && $budget <= 0 && $ward === '' && $user) {
                if (!empty($user->capital_budget)) {
                    $budget = (float)$user->capital_budget;
                }
                if (!empty($user->preferred_location)) {
                    $match = current(array_filter($wards, function ($w) use ($user) {
                        return mb_stripos($user->preferred_location, $w) !== false;
                    }));
                    if ($match) {
                        $ward = $match;
                    }
                }
                if (!empty($user->business_interests) && $businessType === '') {
                    $businessType = $user->business_interests;
                    $selectedInterest = $selectedInterest !== '' ? $selectedInterest : $businessType;
                }
            }

            if ($budget > 0 && $ward !== '') {
                $loc = Location::find()
                    ->where(['ward' => $ward])
                    ->orderBy(['id' => SORT_ASC])
                    ->one();
                if ($loc) {
                    $selectedLocationId = $loc->id;
                    $marketAnalysis = $service->analyzeMarket($loc->id, $budget, $user, $selectedInterest);
                    $displayResults = self::serializeAnalysis($marketAnalysis);

                    if ($request->isPost && $user && $businessType !== '') {
                        $analysis = new BusinessAnalysis([
                            'user_id' => $user->id,
                            'business_type' => $businessType,
                            'business_vision' => $businessVision ?: null,
                            'target_customers' => $targetCustomers ?: null,
                            'ward' => $ward,
                            'location_id' => $selectedLocationId,
                            'starting_budget' => $startingBudget > 0 ? (int)$startingBudget : null,
                            'rental_budget' => (int)$budget,
                            'space_size_needed' => $spaceSizeNeeded ?: null,
                            'special_requirements' => $specialRequirements ?: null,
                            'status' => BusinessAnalysis::STATUS_COMPLETED,
                            'results_snapshot' => json_encode($displayResults, JSON_UNESCAPED_UNICODE),
                        ]);
                        if ($analysis->save()) {
                            $saved = $analysis;
                        }
                    }
                }
            }
        }

        return $this->render('index', [
            'wards' => $wards,
            'results' => $displayResults ?? [],
            'budget' => $budget,
            'ward' => $ward,
            'selectedInterest' => $selectedInterest,
            'selectedLocationId' => $selectedLocationId,
            'user' => $user,
            'businessType' => $businessType,
            'businessVision' => $businessVision,
            'targetCustomers' => $targetCustomers,
            'startingBudget' => $startingBudget,
            'spaceSizeNeeded' => $spaceSizeNeeded,
            'specialRequirements' => $specialRequirements,
            'saved' => $saved,
        ]);
    }

    /**
     * Historia ya Uchambuzi — list of the current entrepreneur's past
     * analyses, newest first.
     */
    public function actionHistory()
    {
        $items = BusinessAnalysis::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('history', [
            'items' => $items,
        ]);
    }

    /**
     * Fungua Uchambuzi — reopen a saved analysis exactly as it was
     * computed (from its results_snapshot), not a fresh live query.
     */
    public function actionView($id)
    {
        $analysis = BusinessAnalysis::findOne($id);
        if (!$analysis || (int)$analysis->user_id !== (int)Yii::$app->user->id) {
            throw new NotFoundHttpException(Yii::t('app', 'fursa.history.not_found'));
        }

        return $this->render('view', [
            'analysis' => $analysis,
            'results' => $analysis->getSnapshot() ?: [],
        ]);
    }

    /**
     * Reduce a MarketAnalysisService::analyzeMarket() result (which
     * embeds ActiveRecord models) to plain arrays safe to
     * json_encode and to redisplay later without re-querying listings
     * or local-data rows that may have since changed. This is the
     * exact structure both the fresh results view and a reopened
     * history entry render from.
     */
    public static function serializeAnalysis(array $analysis)
    {
        $categories = [];
        foreach ($analysis['results'] as $r) {
            $categoryId = (int)$r->category->id;
            $categories[] = [
                'category_name' => $r->category->name,
                'category_id' => $categoryId,
                'available_count' => $r->available_count,
                'avg_monthly_price' => (float)$r->avg_monthly_price,
                'match_score' => $r->match_score,
                'badge_class' => $r->badge_class,
                'is_affordable' => $r->is_affordable,
                'recommendation' => $r->recommendation,
                'properties' => array_map(function ($p) {
                    return [
                        'id' => $p->id,
                        'title' => $p->title,
                        'price' => (float)$p->price,
                    ];
                }, $r->properties),
                'local_indicators' => array_map([self::class, 'serializeIndicator'], $analysis['category_indicators'][$categoryId] ?? []),
            ];
        }

        return [
            'categories' => $categories,
            'ward_indicators' => array_map([self::class, 'serializeIndicator'], $analysis['ward_indicators']),
            'data_sources' => $analysis['data_sources'],
            'methodology' => $analysis['methodology'],
        ];
    }

    private static function serializeIndicator($indicator)
    {
        return [
            'type' => $indicator->indicator_type,
            'type_label' => $indicator->typeLabel,
            'display_value' => $indicator->displayValue,
            'confidence_level' => $indicator->confidence_level,
            'confidence_label' => $indicator->confidenceLabel,
            'notes' => $indicator->notes,
            'is_demo' => (bool)$indicator->is_demo,
            'source_name' => $indicator->dataSource->name ?? null,
        ];
    }
}
