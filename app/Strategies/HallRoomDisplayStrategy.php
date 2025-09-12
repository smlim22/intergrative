<?php
// filepath: app/Strategies/HallRoomDisplayStrategy.php

namespace App\Strategies;

class HallRoomDisplayStrategy implements FacilityDisplayStrategyInterface
{
    public function validateFacilityData(array $data): array
    {
        $errors = [];
        
        // 🎯 THIS IS THE IMPORTANT PART - Different validation for halls!
        if (empty($data['capacity'])) {
            $errors['capacity'] = 'Meeting spaces must specify seating capacity.';
        }
        
        if (empty($data['description'])) {
            $errors['description'] = 'Meeting spaces should describe amenities and facilities.';
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
        return ['name', 'category', 'capacity', 'description']; // Halls REQUIRE description
    }

    public function getFacilityType(): string
    {
        return 'Hall and Room';
    }
}