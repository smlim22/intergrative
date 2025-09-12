<?php
// filepath: app/Strategies/SportsArenaDisplayStrategy.php

namespace App\Strategies;
class SportsArenaDisplayStrategy implements FacilityDisplayStrategyInterface
{
    public function validateFacilityData(array $data): array
    {
        $errors = [];
        
        // 🎯 THIS IS THE IMPORTANT PART - Category-specific validation!
        if (empty($data['capacity'])) {
            $errors['capacity'] = 'Sports facilities must specify capacity.';
        }
        
        if (empty($data['hourly_rate']) && empty($data['half_day_rate']) && 
            empty($data['full_day_rate']) && empty($data['per_use_rate'])) {
            $errors['pricing'] = 'Sports facilities should have at least one pricing rate.';
        }
        
        return $errors;
    }

    public function formatPricingDisplay($facility): string
    {
        $prices = [];
        
        // 🔄 SAME ORDER FOR ALL STRATEGIES - Show all available pricing
        if ($facility->hourly_rate) {
            $prices[] = 'RM' . number_format($facility->hourly_rate, 2) . '/hour';
        }
        if ($facility->half_day_rate) {
            $prices[] = 'RM' . number_format($facility->half_day_rate, 2) . '/half-day';
        }
        if ($facility->full_day_rate) {
            $prices[] = 'RM' . number_format($facility->full_day_rate, 2) . '/full-day';
        }
        if ($facility->per_use_rate) {
            $prices[] = 'RM' . number_format($facility->per_use_rate, 2) . '/use';
        }
        
        return implode(' | ', $prices) ?: 'Contact for pricing';
    }

    public function getRequiredFields(): array
    {
        return ['name', 'category', 'capacity']; // Sports REQUIRE capacity
    }

    public function getFacilityType(): string
    {
        return 'Sports Arena';
    }
}