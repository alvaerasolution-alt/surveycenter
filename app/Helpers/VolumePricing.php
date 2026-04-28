<?php

namespace App\Helpers;

use App\Models\Setting;

class VolumePricing
{
    /**
     * Default tiers (used when no DB settings exist).
     * Format: JSON array of {max, price} objects, sorted ascending.
     * max = null means unlimited (highest tier).
     */
    public const DEFAULT_TIERS_JSON = '[{"max":99,"price":500},{"max":499,"price":450},{"max":999,"price":400},{"max":null,"price":350}]';

    public const DEFAULT_MIN_ORDER = 50000;

    /**
     * Get the tiers array from DB settings (cached) or defaults.
     * Returns: [ ['max' => 99, 'price' => 500], ... ]
     */
    public static function getTiers(): array
    {
        $json = Setting::get('pricing_tiers', self::DEFAULT_TIERS_JSON);
        $tiers = json_decode($json, true);

        return is_array($tiers) ? $tiers : json_decode(self::DEFAULT_TIERS_JSON, true);
    }

    /**
     * Get the minimum order amount from settings.
     */
    public static function getMinOrder(): int
    {
        return (int) Setting::get('pricing_min_order', self::DEFAULT_MIN_ORDER);
    }

    /**
     * Get price per question per respondent based on respondent count.
     */
    public static function pricePerUnit(int $respondentCount): int
    {
        foreach (self::getTiers() as $tier) {
            if ($tier['max'] === null || $respondentCount <= $tier['max']) {
                return (int) $tier['price'];
            }
        }

        $tiers = self::getTiers();
        return (int) ($tiers[0]['price'] ?? 500); // fallback to first tier
    }

    /**
     * Calculate total cost: questions × respondents × tier price.
     */
    public static function calculateTotal(int $questionCount, int $respondentCount): int
    {
        $unitPrice = self::pricePerUnit($respondentCount);
        return $questionCount * $respondentCount * $unitPrice;
    }

    /**
     * Get tier label for display.
     */
    public static function tierLabel(int $respondentCount): string
    {
        $tiers = self::getTiers();
        $prev = 1;

        foreach ($tiers as $tier) {
            if ($tier['max'] === null) {
                return '≥ ' . number_format($prev, 0, ',', '.') . ' responden';
            }
            if ($respondentCount <= $tier['max']) {
                return $prev . '–' . number_format($tier['max'], 0, ',', '.') . ' responden';
            }
            $prev = $tier['max'] + 1;
        }

        return '1–99 responden';
    }

    /**
     * Check if user gets a special price (below base tier).
     */
    public static function isSpecialPrice(int $respondentCount): bool
    {
        $tiers = self::getTiers();
        if (empty($tiers)) return false;

        // Special price = anything beyond the first (base) tier
        $firstMax = $tiers[0]['max'] ?? 99;
        return $respondentCount > $firstMax;
    }

    /**
     * Return the tiers array formatted for frontend JS consumption.
     * Used to inject into Blade templates so JS can do client-side calculation.
     */
    public static function tiersForJs(): string
    {
        return json_encode(self::getTiers());
    }
}
