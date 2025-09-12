<?php
// filepath: app/Strategies/GenericFacilityDisplayStrategy.php

namespace App\Strategies;
class GenericFacilityDisplayStrategy implements FacilityDisplayStrategyInterface
{
    public function validateFacilityData(array $data): array
    {
        $errors = [];
        
        // 🎯 MINIMAL VALIDATION for generic facilities
        if (empty($data['name'])) {
            $errors['name'] = 'Facility name is required.';
        }
        
        if (empty($data['category'])) {
            $errors['category'] = 'Category is required.';
        }

        if (empty($data['hourly_rate']) && empty($data['half_day_rate']) && 
            empty($data['full_day_rate']) && empty($data['per_use_rate'])) {
            $errors['pricing'] = 'Generic facilities should have at least one pricing rate.';
        }
        
        return $errors;
    }

    public function formatPricingDisplay($facility): string
    {
        $prices = [];
        
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
        return ['name', 'category']; // Generic facilities are flexible
    }

    public function getFacilityType(): string
    {
        return 'General Facility';
    }
}