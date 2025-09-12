<?php

namespace App\Strategies;

interface FacilityDisplayStrategyInterface
{
    public function validateFacilityData(array $data): array;
    public function formatPricingDisplay($facility): string;
    public function getRequiredFields(): array;
    public function getFacilityType(): string;
}