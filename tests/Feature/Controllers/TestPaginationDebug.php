<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestPaginationDebug extends TestCase
{
    use RefreshDatabase;

    public function test_pagination_debug()
    {
        $user = User::factory()->create();
        $properties = Property::factory(5)->create(['usuario_id' => $user->id]);

        // Check what was created
        echo "\nCreated user with ID: " . $user->id . PHP_EOL;
        echo "Created " . $properties->count() . " properties" . PHP_EOL;

        // Check the database
        $dbProps = Property::where('usuario_id', $user->id)->get();
        echo "Database has " . $dbProps->count() . " properties" . PHP_EOL;
        foreach ($dbProps as $prop) {
            echo "  - Property ID: " . $prop->id . ", Slug: " . $prop->slug . PHP_EOL;
        }

        // Try the HTTP request
        $response = $this->actingAs($user)->get(route('user.properties.index'));

        $response->assertStatus(200);
    }
}
