<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;


class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Facility::create([
        'name' => 'Basketball (Half Court only)',
        'category' => 'Rooftop Sports Arena',
        'description' => 'Modern half-court basketball facility with professional flooring and adjustable hoops. Perfect for training sessions, small games, and skill development.',
        'hourly_rate' => 80.00,
    ]);
    Facility::create([
        'name' => 'Volleyball',
        'category' => 'Rooftop Sports Arena',
        'description' => 'Full-size volleyball court with regulation net height and professional sand or indoor flooring. Ideal for competitive matches and recreational play.',
        'hourly_rate' => 80.00,
    ]);
    Facility::create([
        'name' => 'Swimming Pool',
        'category' => 'Rooftop Sports Arena',
        'description' => 'Olympic-size swimming pool with crystal clear water, proper filtration system, and safety equipment. Suitable for swimming lessons, lap swimming, and pool parties.',
        'half_day_rate' => 200.00,
        'full_day_rate' => 400.00,
    ]);
    Facility::create([
        'name' => 'PA System',
        'category' => 'Audio Equipment',
        'description' => 'Professional public address system with microphones, speakers, and amplifiers. Perfect for events, presentations, and announcements.',
        'per_use_rate' => 200.00,
    ]);
    // Add more as needed
}
}
