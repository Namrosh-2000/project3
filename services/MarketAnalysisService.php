<?php

namespace app\services;

use app\models\Location;
use app\models\MarketIndicator;
use app\models\User;
use Yii;

/**
 * MarketAnalysisService — the single entry point Fursa calls to turn
 * an entrepreneur's request into a market analysis.
 *
 * This is the `analyzeMarket(request)` abstraction called for in the
 * product spec (§16): a stable interface the UI talks to regardless
 * of what powers it underneath. Today it is two real, non-fabricated
 * layers combined:
 *
 *   1. Listing-based match scoring — BusinessOpportunityService,
 *      unchanged, computed straight from live Property/Category rows
 *      (real prices, real availability).
 *   2. Local-data context — admin-entered MarketIndicator rows
 *      (Phase 4's local data architecture) for the chosen ward,
 *      each carrying its own confidence level and data source.
 *
 * There is no generative/LLM-based market analysis wired in yet. If
 * one is added later, it plugs in here as a third layer — this
 * class's public contract (analyzeMarket() returning a MarketAnalysisResult)
 * does not need to change, so BusinessController and the views never
 * have to know which layers actually ran.
 *
 * Nothing here invents a number: a ward with no MarketIndicator rows
 * simply returns an empty indicators array, and the view is expected
 * to say so plainly rather than hide the gap.
 */
class MarketAnalysisService
{
    /** @var BusinessOpportunityService */
    private $opportunityService;

    public function __construct(BusinessOpportunityService $opportunityService = null)
    {
        $this->opportunityService = $opportunityService ?: new BusinessOpportunityService();
    }

    /**
     * @param int $locationId
     * @param float $budget monthly rental budget
     * @param User|null $user
     * @param string|null $selectedInterest
     * @return array{
     *   results: object[],
     *   ward_indicators: MarketIndicator[],
     *   category_indicators: array<int, MarketIndicator[]> keyed by category_id,
     *   data_sources: array<int, array{name:string,type:string,is_demo:bool,collected_at:?string}>,
     *   methodology: string
     * }
     */
    public function analyzeMarket($locationId, $budget, User $user = null, $selectedInterest = null)
    {
        $locationId = (int)$locationId;

        // Layer 1: real listing-based match scoring (unchanged).
        $results = $this->opportunityService->suggest($locationId, $budget, $user, $selectedInterest);

        // Layer 2: admin-entered local data context for this ward.
        $allIndicators = MarketIndicator::find()
            ->where(['location_id' => $locationId])
            ->with(['dataSource', 'category'])
            ->orderBy(['confidence_level' => SORT_DESC, 'created_at' => SORT_DESC])
            ->all();

        $wardIndicators = [];
        $categoryIndicators = [];
        $dataSources = [];

        foreach ($allIndicators as $indicator) {
            if ($indicator->category_id === null) {
                $wardIndicators[] = $indicator;
            } else {
                $categoryIndicators[(int)$indicator->category_id][] = $indicator;
            }

            if ($indicator->dataSource && !isset($dataSources[$indicator->dataSource->id])) {
                $dataSources[$indicator->dataSource->id] = [
                    'name' => $indicator->dataSource->name,
                    'type' => $indicator->dataSource->type,
                    'is_demo' => (bool)$indicator->dataSource->is_demo,
                    'collected_at' => $indicator->dataSource->collected_at,
                ];
            }
        }

        return [
            'results' => $results,
            'ward_indicators' => $wardIndicators,
            'category_indicators' => $categoryIndicators,
            'data_sources' => array_values($dataSources),
            'methodology' => $this->methodologyNote(count($allIndicators) > 0),
        ];
    }

    /**
     * Plain-language explanation of what actually fed the analysis,
     * shown alongside results per §17 of the spec ("never imply
     * certainty beyond available data").
     */
    private function methodologyNote($hasLocalIndicators)
    {
        if ($hasLocalIndicators) {
            return Yii::t('app', 'localdata.methodology_with_indicators');
        }
        return Yii::t('app', 'localdata.methodology_listings_only');
    }
}
