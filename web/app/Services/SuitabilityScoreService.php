<?php

namespace App\Services;

use App\Helpers\FileHelper;
use App\Models\Driver;
use App\Models\Street;
use Exception;

class SuitabilityScoreService
{
    const INCREASED_PERCENT = 50;
    const SUITABILITY_MULTIPLIER_EVEN = 1.5;
    const SUITABILITY_MULTIPLIER_ODD = 1;

    /**
     * @param string $streetsFileName
     * @param string $drivesFileName
     * @return array
     */
    public function calculate(string $streetsFileName, string $drivesFileName): array
    {
        $streets = (new Street($streetsFileName))->getData();
        $drivers = (new Driver($drivesFileName))->getData();

        $total = 0;
        $distributionResult = [];
        foreach ($streets as $street) {
            $suitabilityScoreMax = 0;
            $bestDriver = [];

            foreach ($drivers as $key => $driver) {
                $suitabilityScore = $this->calculateSuitabilityScore($driver, $street);
                if ($suitabilityScore > $suitabilityScoreMax){
                    $suitabilityScoreMax = $suitabilityScore;
                    $bestDriver = [
                        'key' => $key,
                        'name'=> $driver->name,
                        'score' => $suitabilityScore
                    ];
                }

            }
            $distributionResult[] = [
                'street' => $street->name,
                'driver' => $bestDriver['name'],
                'score' => $suitabilityScoreMax
            ];
            unset($drivers[$bestDriver['key']]);
            $total += $bestDriver['score'];
        }

        return [
            'total' => $total,
            'distributionResult' => $distributionResult
        ];
    }

    /**
     * If the length of the shipment's destination street name is even, the base suitability
     * score (SS) is the number of vowels in the driver’s name multiplied by 1.5.
     * If the length of the shipment's destination street name is odd, the base SS is the
     * number of consonants in the driver’s name multiplied by 1.
     * If the length of the shipment's destination street name shares any common factors
     * (besides 1) with the length of the driver’s name, the SS is increased by 50% above the
     * base SS.
     *
     * @param object $driver
     * @param object $street
     * @return float
     */
    private function calculateSuitabilityScore(object $driver, object $street): float
    {
        $suitabilityScore = $street->type === Street::TYPE_EVEN ? self::SUITABILITY_MULTIPLIER_EVEN * $driver->vowel :
            self::SUITABILITY_MULTIPLIER_ODD * $driver->consonant;
        if ($street->length === $driver->length) {
            $suitabilityScore = $suitabilityScore + ($suitabilityScore / 100 * self::INCREASED_PERCENT);
        }
        return (float)$suitabilityScore;
    }

}
