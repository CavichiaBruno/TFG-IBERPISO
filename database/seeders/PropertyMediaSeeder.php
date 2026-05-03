<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Database\Seeder;

class PropertyMediaSeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::all();
        $imageCount = 8; // User provided 8 images now

        foreach ($properties as $index => $property) {
            $imageNumber = ($index % $imageCount) + 1;
            $imagePath = 'images/properties/prop' . $imageNumber . '.jpg';

            PropertyMedia::create([
                'property_id'   => $property->id,
                'file_path'     => $imagePath,
                'file_type'     => 'image',
                'mime_type'     => 'image/jpeg',
                'file_size_kb'  => 500,
                'original_name' => 'prop' . $imageNumber . '.jpg',
                'is_cover'      => true,
                'sort_order'    => 0,
            ]);
        }
    }
}
