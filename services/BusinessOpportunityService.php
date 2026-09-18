<?php

namespace app\services;

use app\models\Property;
use app\models\PropertyCategory;
use app\models\User;
use Yii;

/**
 * Smart Business Opportunity & Match Recommendation Engine.
 *
 * Calculates a multi-factor Match Score (%) based on:
 * 1. Affordability Score (35%) - Budget vs Median Rent Price
 * 2. User Interest Alignment (30%) - Category match with User Profile Interests
 * 3. Infrastructure Alignment (20%) - Water, Electricity, Parking, Security, Internet
 * 4. Market Supply & Demand Score (15%) - Local availability ratio
 */
class BusinessOpportunityService
{
    public function suggest($locationId, $budget, User $user = null, $selectedInterest = null)
    {
        $locationId = (int)$locationId;
        $budget = (float)$budget;

        $userInterests = [];
        $userAmenities = [];
        if ($user) {
            if (!empty($user->business_interests)) {
                $userInterests = array_map('trim', explode(',', $user->business_interests));
            }
            if (!empty($user->preferred_amenities)) {
                $userAmenities = array_map('trim', explode(',', $user->preferred_amenities));
            }
        }
        if (!empty($selectedInterest)) {
            $userInterests[] = trim($selectedInterest);
        }

        $results = [];

        $businessCategories = PropertyCategory::find()
            ->where(['type' => 'business_space', 'is_active' => true])
            ->all();

        foreach ($businessCategories as $cat) {
            $availableProps = Property::find()
                ->where([
                    'category_id' => $cat->id,
                    'location_id' => $locationId,
                    'status' => Property::STATUS_VERIFIED,
                    'is_available' => true,
                ])
                ->with(['coverImage', 'location'])
                ->all();

            $availableCount = count($availableProps);

            $medianPrice = (float)(Property::find()
                ->select('AVG(price) AS avg_price')
                ->where([
                    'category_id' => $cat->id,
                    'location_id' => $locationId,
                    'status' => Property::STATUS_VERIFIED,
                ])
                ->scalar() ?: 0);

            // 1. Affordability Score (Max 35)
            $affordabilityScore = 5;
            if ($medianPrice > 0) {
                if ($budget >= ($medianPrice * 6)) {
                    $affordabilityScore = 35;
                } elseif ($budget >= ($medianPrice * 3)) {
                    $affordabilityScore = 25;
                } elseif ($budget >= $medianPrice) {
                    $affordabilityScore = 15;
                }
            } else {
                $affordabilityScore = ($budget >= 500000) ? 30 : 15;
            }

            // 2. User Interest Alignment Score (Max 30)
            $interestScore = 10;
            foreach ($userInterests as $interest) {
                if (empty($interest)) continue;
                if (mb_stripos($cat->name, $interest) !== false || mb_stripos($interest, $cat->name) !== false) {
                    $interestScore = 30;
                    break;
                } elseif (mb_stripos($cat->description ?? '', $interest) !== false) {
                    $interestScore = 20;
                }
            }

            // 3. Infrastructure Alignment Score (Max 20)
            $infraScore = 15; // default reasonable score
            if (!empty($availableProps) && !empty($userAmenities)) {
                $matchedAmenityCount = 0;
                $totalChecked = 0;
                foreach ($availableProps as $p) {
                    foreach ($userAmenities as $amenity) {
                        $totalChecked++;
                        $field = 'has_' . strtolower($amenity);
                        if (isset($p->$field) && $p->$field) {
                            $matchedAmenityCount++;
                        }
                    }
                }
                if ($totalChecked > 0) {
                    $ratio = $matchedAmenityCount / $totalChecked;
                    $infraScore = (int)round($ratio * 20);
                }
            }

            // 4. Supply & Demand Score (Max 15)
            $demandScoreRaw = $this->estimateDemand($cat, $availableCount);
            $supplyDemandScore = (int)round(($demandScoreRaw / 100) * 15);

            // Total Calculated Match Score (%)
            $totalMatchScore = min(98, max(45, $affordabilityScore + $interestScore + $infraScore + $supplyDemandScore));

            $badgeClass = 'bg-success text-white';
            if ($totalMatchScore < 70) {
                $badgeClass = 'bg-warning text-dark';
            } elseif ($totalMatchScore < 50) {
                $badgeClass = 'bg-secondary text-white';
            }

            $affordable = ($affordabilityScore >= 25);
            $reason = $this->buildReason($cat, $availableCount, $medianPrice, $budget, $totalMatchScore, $interestScore > 10);

            $results[] = (object)[
                'category' => $cat,
                'available_count' => $availableCount,
                'avg_monthly_price' => $medianPrice,
                'match_score' => $totalMatchScore,
                'badge_class' => $badgeClass,
                'is_affordable' => $affordable,
                'recommendation' => $reason,
                'properties' => array_slice($availableProps, 0, 3),
            ];
        }

        usort($results, function ($a, $b) {
            return ($b->match_score <=> $a->match_score) ?: ($b->available_count <=> $a->available_count);
        });

        return $results;
    }

    private function estimateDemand($category, $availableInLocation)
    {
        $globalAvailable = Property::find()
            ->where([
                'category_id' => $category->id,
                'status' => Property::STATUS_VERIFIED,
                'is_available' => true,
            ])
            ->count();

        if ($globalAvailable == 0) {
            return 50;
        }
        if ($availableInLocation == 0) {
            return 90;
        }

        $ratio = $availableInLocation / max($globalAvailable, 1);
        return (int)round(100 - min(95, $ratio * 100));
    }

    private function buildReason($cat, $available, $avgPrice, $budget, $matchScore, $hasInterestMatch)
    {
        $interestText = $hasInterestMatch ? " (Inafanana na mapendekezo ya biashara kwenye profile yako)" : "";

        if ($matchScore >= 85) {
            return "Fursa Nzuri Sana! {$cat->name}{$interestText}. Bajeti yako ya TSh " . number_format($budget) . " inatosheleza vizuri kodi na mtaji. Kuna nafasi {$available} zinazopatikana kikamilifu.";
        }
        if ($matchScore >= 70) {
            return "Fursa Nzuri ({$matchScore}% Match). Bei ya wastani ni TSh " . number_format($avgPrice) . "/mwezi{$interestText}. Bajeti yako inaweza kugharamia miezi kadhaa ya mwanzo.";
        }
        if ($available == 0) {
            return "Upatikanaji ni mdogo kwa sasa kwenye kata hii. Huenda ikawa fursa mpya yenye ushindani mdogo pindi nafasi zikipatikana.";
        }
        return "Inahitaji utafiti wa ziada. Bei ya wastani (TSh " . number_format($avgPrice) . ") inaweza kuhitaji ongezeko dogo la bajeti ya mtaji.";
    }
}