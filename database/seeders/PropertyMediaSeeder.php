<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Database\Seeder;

class PropertyMediaSeeder extends Seeder
{
    public function run(): void
    {
        // Placeholder: properties will show a default image since no real files are seeded.
        // In production, upload images through the admin panel.
        $this->command->info('Property media seeder skipped — upload media via admin panel.');
    }
}
