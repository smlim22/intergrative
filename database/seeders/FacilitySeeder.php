<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{

    public function run()
    {
        Facility::create([
            'name' => 'Basketball (Half Court only)',
            'category' => 'Rooftop Sports Arena',
            'description' => 'Modern half-court basketball facility with professional flooring and adjustable hoops. Perfect for training sessions, small games, and skill development.',
            'hourly_rate' => 80.00,
            'capacity' => 10,
            'status' => 'active', 

        ]);
        
        Facility::create([
            'name' => 'Volleyball',
            'category' => 'Rooftop Sports Arena',
            'description' => 'Full-size volleyball court with regulation net height and professional sand or indoor flooring. Ideal for competitive matches and recreational play.',
            'hourly_rate' => 80.00,
            'capacity' => 12,
            'status' => 'active',

        ]);
        
        Facility::create([
            'name' => 'Badminton(Hall 1 Court VIP)',
            'category' => 'Rooftop Sports Arena',
            'description' => 'Has a high ceiling to accommodate the shuttlecocks trajectory, and a sprung wooden floor to reduce the impact on players joints. The court dimensions are clearly marked with white or yellow lines, and there are nets positioned at the center. The lighting is non-glare and designed to iluminate the court evenly.',
            'hourly_rate' => 26.00,
            'capacity' => 4,
            'status' => 'active',

        ]);
        
        Facility::create([
            'name' => 'Badminton(Hall 2, 3 Courts)',
            'category' => 'Rooftop Sports Arena',
            'description' => 'Has a high ceiling to accommodate the shuttlecocks trajectory, and a sprung wooden floor to reduce the impact on players joints. The court dimensions are clearly marked with white or yellow lines, and there are nets positioned at the center. The lighting is non-glare and designed to iluminate the court evenly.',
            'hourly_rate' => 23.00,
            'capacity' => 12,
            'status' => 'active',

        ]);
        
        Facility::create([
            'name' => 'Futsal/Netball',
            'category' => 'Rooftop Sports Arena',
            'description' => 'The surface is usually a hard court made of synthetic material or polished concrete, whihc is durable and provides good grip. The court has a rectangular layout with marked lines for both sports, including penalty areas and goal circles. For futsal, there are two goals at opposite ends, while for netball features goal posts without backboards.',
            'hourly_rate' => 100.00,
            'capacity' => 14,
            'status' => 'active',

        ]);
        
        Facility::create([
            'name' => 'Swimming Pool (30 People Max)',
            'category' => 'Rooftop Sports Arena',
            'description' => 'Olympic-size swimming pool with crystal clear water, proper filtration system, and safety equipment. Suitable for swimming lessons, lap swimming, and pool parties.',
            'half_day_rate' => 200.00,
            'full_day_rate' => 400.00,
            'capacity' => 30,
            'status' => 'active',

        ]);
        
        Facility::create([
            'name' => 'Multipurpose Hall (980 sq metre)',
            'category' => 'Halls and Rooms',
            'description' => 'Has a large, flexible space used for a wide range of activities. It can host large events like conferences, exhibitions, banquets, and performances. The hall often has features such as a stage, sound system, and movable seating to adapt to different functions. Its large size and open layout make it suitable for community gatherings, large-scale sports, or as an examination venue.',
            'half_day_rate' => 2500.00,
            'full_day_rate' => 4000.00,
            'capacity' => 500,
            'status' => 'active',

        ]);
        
        Facility::create([
            'name' => 'Lecture Hall (128 sq metre)',
            'category' => 'Halls and Rooms',
            'description' => 'Is an academic space designed for teaching large groups of students. It can accommodate a significant number of people. The seating is typically tiered or sloped to provide a clear view of the lecturer and the projection screen or whiteboard at the front. The room is equipped with audio-visual technology, including projectors and sound systems to support presentations.',
            'half_day_rate' => 400.00,
            'full_day_rate' => 600.00,
            'capacity' => 80,
            'status' => 'active',

        ]);
        
        Facility::create([
            'name' => 'Tutorial Room (64 sq metre)',
            'category' => 'Halls and Rooms',
            'description' => 'Is a smaller academic space designed for teaching smaller groups of students. It can accommodate a limited number of people. The seating is typically arranged to facilitate discussion and interaction. The room is equipped with basic audio-visual technology to support presentations.',
            'half_day_rate' => 200.00,
            'full_day_rate' => 400.00,
            'capacity' => 25,
            'status' => 'active',

        ]);
        
        Facility::create([
            'name' => 'PA System',
            'category' => 'Halls and Rooms',
            'description' => 'Professional public address system with microphones, speakers, and amplifiers. Perfect for events, presentations, and announcements.',
            'per_use_rate' => 200.00,
            'capacity' => null,
            'status' => 'active',

        ]);
    }
}