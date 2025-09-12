<?php
// filepath: app/Models/Facility.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Factories\FacilityDisplayStrategyFactory;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category', 
        'description',
        'hourly_rate',
        'half_day_rate',
        'full_day_rate',
        'per_use_rate',
        'capacity',
        'status'
    ];

    // Strategy Pattern Implementation
    public function getDisplayStrategy()
    {
        return FacilityDisplayStrategyFactory::createFromCategory($this->category);
    }

    public function getFormattedPricing()
    {
        return $this->getDisplayStrategy()->formatPricingDisplay($this);
    }

    public function getFacilityType()
    {
        return $this->getDisplayStrategy()->getFacilityType();
    }

    public function getRequiredFields()
    {
        return $this->getDisplayStrategy()->getRequiredFields();
    }

    // Status management using status column
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isDisabled()
    {
        return $this->status === 'disabled';
    }

    public function disable()
    {
        $this->status = 'disabled';
        $this->save();
    }

    public function enable()
    {
        $this->status = 'active';
        $this->save();
    }

    // Search scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDisabled($query)
    {
        return $query->where('status', 'disabled');
    }

    public function scopeSearchByName($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Display helpers
    public function getCapacityDisplay()
    {
        return $this->capacity ? $this->capacity . ' people' : '<em class="text-muted">Not specified</em>';
    }

    public function getStatusBadge()
    {
        return $this->status === 'active' 
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Disabled</span>';
    }
}