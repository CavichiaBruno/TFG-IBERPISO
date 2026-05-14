<?php

namespace Tests\Unit\Models;

use App\Models\Property;
use App\Models\User;
use Tests\TestCase;

class PropertyTest extends TestCase
{

    /**
     * Test que la propiedad puede ser creada correctamente
     */
    public function test_property_can_be_created()
    {
        $user = User::factory()->create();
        
        $property = Property::create([
            'usuario_id' => $user->id,
            'titulo' => 'Test Property',
            'slug' => 'test-property',
            'descripcion' => 'A test property description',
            'tipo_propiedad' => 'piso',
            'tipo_operacion' => 'venta',
            'precio' => 250000,
            'superficie_m2' => 120,
            'habitaciones' => 3,
            'banos' => 2,
            'direccion' => 'Calle Test 123',
            'ciudad' => 'Madrid',
            'provincia' => 'Madrid',
            'codigo_postal' => '28001',
            'activa' => true,
        ]);

        $this->assertNotNull($property->id);
        $this->assertEquals('Test Property', $property->titulo);
        $this->assertEquals('piso', $property->tipo_propiedad);
        $this->assertEquals(250000, $property->precio);
    }

    /**
     * Test que una propiedad tiene un usuario propietario
     */
    public function test_property_belongs_to_user()
    {
        $user = User::factory()->create();
        $property = Property::factory()->create(['usuario_id' => $user->id]);

        $this->assertInstanceOf(User::class, $property->usuario);
        $this->assertEquals($user->id, $property->usuario->id);
    }

    /**
     * Test scope de propiedades activas
     */
    public function test_active_scope_filters_properties()
    {
        Property::factory()->create(['activa' => true]);
        Property::factory()->create(['activa' => true]);
        Property::factory()->create(['activa' => false]);

        $activeProperties = Property::active()->count();

        $this->assertEquals(2, $activeProperties);
    }

    /**
     * Test scope de propiedades por operación
     */
    public function test_filter_by_operation_scope()
    {
        Property::factory()->create(['tipo_operacion' => 'venta']);
        Property::factory()->create(['tipo_operacion' => 'venta']);
        Property::factory()->create(['tipo_operacion' => 'alquiler']);

        $saleProperties = Property::active()->filterByOperation('venta')->count();
        $rentalProperties = Property::active()->filterByOperation('alquiler')->count();

        $this->assertEquals(2, $saleProperties);
        $this->assertEquals(1, $rentalProperties);
    }

    /**
     * Test validación de campos requeridos
     */
    public function test_property_requires_title()
    {
        $this->expectException(\Exception::class);

        Property::create([
            'usuario_id' => 1,
            'slug' => 'test',
            'tipo_propiedad' => 'piso',
            'tipo_operacion' => 'venta',
            'precio' => 100000,
        ]);
    }

    /**
     * Test soft delete de propiedades
     */
    public function test_property_can_be_soft_deleted()
    {
        $property = Property::factory()->create();
        $propertyId = $property->id;

        $property->delete();

        $this->assertSoftDeleted($property);
        $this->assertNull(Property::find($propertyId));
        $this->assertNotNull(Property::withTrashed()->find($propertyId));
    }

    /**
     * Test formatos de precio
     */
    public function test_property_price_formatting()
    {
        $property = Property::factory()->create(['precio' => 250000.50]);

        $this->assertEquals(250000.50, $property->precio);
    }

    /**
     * Test relación con medios (fotos)
     */
    public function test_property_has_many_media()
    {
        $property = Property::factory()->create();
        
        // Simulamos la relación con medios
        $this->assertTrue(method_exists($property, 'medios'));
    }
}
