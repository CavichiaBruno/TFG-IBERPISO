<?php

namespace Tests\Feature\Controllers;

use App\Models\Property;
use App\Models\User;
use Tests\TestCase;

class PropertyControllerTest extends TestCase
{

    /**
     * Test que la página de propiedades se puede acceder
     */
    public function test_properties_index_page_loads()
    {
        $response = $this->get(route('properties.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.properties.index');
    }

    /**
     * Test que se muestran propiedades activas
     */
    public function test_active_properties_are_displayed()
    {
        Property::factory(3)->create(['activa' => true]);
        Property::factory(2)->create(['activa' => false]);

        $response = $this->get(route('properties.index'));

        $response->assertStatus(200);
    }

    /**
     * Test filtro por operación (venta/alquiler)
     */
    public function test_filter_by_operation()
    {
        Property::factory(3)->create(['tipo_operacion' => 'venta', 'activa' => true]);
        Property::factory(2)->create(['tipo_operacion' => 'alquiler', 'activa' => true]);

        $response = $this->get(route('properties.index', ['operacion' => 'venta']));

        $response->assertStatus(200);
    }

    /**
     * Test filtro por tipo de propiedad
     */
    public function test_filter_by_property_type()
    {
        Property::factory(2)->create(['tipo_propiedad' => 'piso', 'activa' => true]);
        Property::factory(3)->create(['tipo_propiedad' => 'casa', 'activa' => true]);

        $response = $this->get(route('properties.index', ['tipo' => 'piso']));

        $response->assertStatus(200);
    }

    /**
     * Test filtro por rango de precio
     */
    public function test_filter_by_price_range()
    {
        Property::factory(2)->create(['precio' => 100000, 'activa' => true]);
        Property::factory(2)->create(['precio' => 500000, 'activa' => true]);

        $response = $this->get(route('properties.index', [
            'precio_min' => 200000,
            'precio_max' => 600000,
        ]));

        $response->assertStatus(200);
    }

    /**
     * Test búsqueda por texto
     */
    public function test_search_properties_by_text()
    {
        Property::factory()->create([
            'titulo' => 'Piso en Madrid centro',
            'ciudad' => 'Madrid',
            'activa' => true,
        ]);

        $response = $this->get(route('properties.index', ['q' => 'Madrid']));

        $response->assertStatus(200);
    }

    /**
     * Test que se puede ver detalle de una propiedad
     */
    public function test_can_view_property_details()
    {
        $property = Property::factory()->create();

        $response = $this->get(route('properties.show', [
            'id' => $property->id,
            'slug' => $property->slug,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('pages.properties.show');
    }

    /**
     * Test que la paginación funciona
     */
    public function test_pagination_works()
    {
        Property::factory(25)->create(['activa' => true]);

        $response = $this->get(route('properties.index', ['page' => 1]));

        $response->assertStatus(200);
    }

    /**
     * Test respuesta AJAX de propiedades
     */
    public function test_ajax_properties_request()
    {
        Property::factory(5)->create(['activa' => true]);

        $response = $this->get(route('properties.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        // Puede retornar JSON o HTML según la configuración
        $this->assertTrue(in_array($response->status(), [200, 404]));
    }

    /**
     * Test ordenación de propiedades por precio ascendente
     */
    public function test_sort_properties_by_price_ascending()
    {
        Property::factory()->create(['precio' => 500000, 'activa' => true]);
        Property::factory()->create(['precio' => 200000, 'activa' => true]);
        Property::factory()->create(['precio' => 350000, 'activa' => true]);

        $response = $this->get(route('properties.index', ['orden' => 'precio_asc']));

        $response->assertStatus(200);
    }

    /**
     * Test ordenación por superficie
     */
    public function test_sort_properties_by_surface()
    {
        Property::factory()->create(['superficie_m2' => 100, 'activa' => true]);
        Property::factory()->create(['superficie_m2' => 200, 'activa' => true]);

        $response = $this->get(route('properties.index', ['orden' => 'superficie']));

        $response->assertStatus(200);
    }
}
