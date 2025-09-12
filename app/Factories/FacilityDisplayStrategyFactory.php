<?php
// filepath: app/Factories/FacilityDisplayStrategyFactory.php

namespace App\Factories;

use App\Strategies\SportsArenaDisplayStrategy;
use App\Strategies\HallRoomDisplayStrategy;
use App\Strategies\GenericFacilityDisplayStrategy;

class FacilityDisplayStrategyFactory
{
    public static function createFromCategory($category)
    {
        $categoryLower = strtolower($category);
        
        // Sports-related facilities (expanded keywords from Solution 2)
        if (str_contains($categoryLower, 'sports') || 
            str_contains($categoryLower, 'arena') ||
            str_contains($categoryLower, 'court') ||
            str_contains($categoryLower, 'pool') ||
            str_contains($categoryLower, 'gym') ||
            str_contains($categoryLower, 'field') ||
            str_contains($categoryLower, 'track') ||
            str_contains($categoryLower, 'basketball') ||
            str_contains($categoryLower, 'volleyball') ||
            str_contains($categoryLower, 'badminton') ||
            str_contains($categoryLower, 'tennis') ||
            str_contains($categoryLower, 'football') ||
            str_contains($categoryLower, 'swimming')) {
            return new SportsArenaDisplayStrategy();
        }
        
        // Hall and room facilities (expanded keywords from Solution 2)
        if (str_contains($categoryLower, 'hall') || 
            str_contains($categoryLower, 'room') ||
            str_contains($categoryLower, 'auditorium') ||
            str_contains($categoryLower, 'conference') ||
            str_contains($categoryLower, 'meeting') ||
            str_contains($categoryLower, 'multipurpose') ||
            str_contains($categoryLower, 'seminar') ||
            str_contains($categoryLower, 'lecture') ||
            str_contains($categoryLower, 'ballroom') ||
            str_contains($categoryLower, 'event')) {
            return new HallRoomDisplayStrategy();
        }
        
        // Default to generic strategy for any other category (Solution 1)
        return new GenericFacilityDisplayStrategy();
    }

    public static function getAllStrategies()
    {
        return [
            'sports' => new SportsArenaDisplayStrategy(),
            'halls' => new HallRoomDisplayStrategy(),
            'generic' => new GenericFacilityDisplayStrategy(),
        ];
    }
    
    /**
     * Get strategy suggestions for admin reference (Simple version for Solution 1+2)
     */
    public static function getCategoryStrategySuggestions()
    {
        return [
            'Sports Facilities' => [
                'keywords' => ['sports', 'arena', 'court', 'pool', 'gym', 'field', 'track', 'basketball', 'volleyball', 'badminton', 'tennis', 'football', 'swimming'],
                'strategy' => 'SportsArenaDisplayStrategy',
                'description' => 'For athletic and recreational facilities',
                'requirements' => ['name', 'category', 'capacity', 'description'],
                'pricing_notes' => 'Usually hourly or half/full day rates'
            ],
            'Meeting Spaces' => [
                'keywords' => ['hall', 'room', 'auditorium', 'conference', 'meeting', 'multipurpose', 'seminar', 'lecture', 'ballroom', 'event'],
                'strategy' => 'HallRoomDisplayStrategy', 
                'description' => 'For event and meeting spaces',
                'requirements' => ['name', 'category', 'capacity', 'description'],
                'pricing_notes' => 'Usually half-day or full-day rates'
            ],
            'Other Facilities' => [
                'keywords' => ['library', 'lab', 'studio', 'workshop', 'clinic', 'office', 'parking', 'equipment'],
                'strategy' => 'GenericFacilityDisplayStrategy',
                'description' => 'For general purpose facilities',
                'requirements' => ['name', 'category'],
                'pricing_notes' => 'Flexible pricing options available'
            ]
        ];
    }
}