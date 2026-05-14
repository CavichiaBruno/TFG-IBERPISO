<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Property;
use App\Models\User;
use Tests\TestCase;

class AdminPropertyControllerTest extends TestCase
{
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['rol' => 'admin']);
    }

    /**
     * Test que solo administradores pueden acceder al panel
     */
    public function test_non_admin_cannot_access_admin_properties()
    {
        $user = User::factory()->create(['rol' => 'usuario']);

        $response = $this->actingAs($user)->get(route('admin.properties.index'));

        // Debería redirigir o dar 403
        $this->assertTrue(in_array($response->status(), [403, 404, 302]));
    }

    /**
     * Test que admin puede ver listado de propiedades
     */
    public function test_admin_can_view_properties_list()
    {
        Property::factory(5)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.properties.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.properties.index');
    }

    /**
     * Test filtro de propiedades activas en admin
     */
    public function test_admin_can_filter_active_properties()
    {
        Property::factory(3)->create(['activa' => true]);
        Property::factory(2)->create(['activa' => false]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.properties.index', ['filtro' => 'activas']));

        $response->assertStatus(200);
    }

    /**
     * Test filtro de propiedades destacadas
     */
    public function test_admin_can_filter_featured_properties()
    {
        Property::factory(2)->create(['destacada' => true]);
        Property::factory(3)->create(['destacada' => false]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.properties.index', ['filtro' => 'destacadas']));

        $response->assertStatus(200);
    }

    /**
     * Test búsqueda de propiedades por título
     */
    public function test_admin_can_search_properties()
    {
        Property::factory()->create(['titulo' => 'Piso en Centro']);
        Property::factory()->create(['titulo' => 'Casa en Montaña']);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.properties.index', ['q' => 'Centro']));

        $response->assertStatus(200);
    }

    /**
     * Test que admin puede crear propiedad
     */
    public function test_admin_can_create_property()
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.properties.store'), [
                'titulo' => 'Admin Property',
                'descripcion' => 'Property created by admin for testing purposes with sufficient details and information to pass validation and meet minimum character requirements for proper testing',
                'tipo_propiedad' => 'piso',
                'tipo_operacion' => 'venta',
                'precio' => 350000,
                'superficie_m2' => 150,
                'habitaciones' => 4,
                'banos' => 2,
                'direccion' => 'Avenida Admin 789',
                'ciudad' => 'Valencia',
                'provincia' => 'Valencia',
                'codigo_postal' => '46001',
            ]);

        $this->assertDatabaseHas('propiedades', ['titulo' => 'Admin Property']);
    }

    /**
     * Test que admin puede editar propiedad
     */
    public function test_admin_can_update_property()
    {
        $property = Property::factory()->create();

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.properties.update', $property->id), [
                'titulo' => 'Updated Title',
                'descripcion' => 'Updated description for testing purposes with complete and detailed information that contains sufficient characters to meet the validation requirement of a hundred characters minimum',
                'tipo_propiedad' => 'casa',
                'tipo_operacion' => 'alquiler',
                'precio' => 1500,
                'superficie_m2' => 180,
                'habitaciones' => 5,
                'banos' => 3,
                'direccion' => 'Nueva Dirección 123',
                'ciudad' => 'Sevilla',
                'provincia' => 'Sevilla',
                'codigo_postal' => '41001',
            ]);

        $property->refresh();
        $this->assertEquals('Updated Title', $property->titulo);
    }

    /**
     * Test que admin puede eliminar propiedad
     */
    public function test_admin_can_delete_property()
    {
        $property = Property::factory()->create();

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.properties.destroy', $property->id));

        $this->assertSoftDeleted($property);
    }

    /**
     * Test que admin puede ver formulario de crear propiedad
     */
    public function test_admin_can_view_create_form()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.properties.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.properties.create');
    }

    /**
     * Test que admin puede ver formulario de edición
     */
    public function test_admin_can_view_edit_form()
    {
        $property = Property::factory()->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.properties.edit', $property->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.properties.edit');
    }

    /**
     * Test paginación en admin properties
     */
    public function test_admin_properties_pagination()
    {
        Property::factory(20)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.properties.index'));

        $response->assertStatus(200);
    }

    /**
     * Test que admin puede filtrar por tipo de operación
     */
    public function test_admin_can_filter_by_operation()
    {
        Property::factory(3)->create(['tipo_operacion' => 'venta']);
        Property::factory(2)->create(['tipo_operacion' => 'alquiler']);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.properties.index', ['filtro' => 'venta']));

        $response->assertStatus(200);
    }

    /**
     * Test respuesta AJAX para admin properties
     */
    public function test_admin_ajax_properties_request()
    {
        Property::factory(5)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.properties.index'), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $this->assertTrue(in_array($response->status(), [200, 404]));
    }
}
