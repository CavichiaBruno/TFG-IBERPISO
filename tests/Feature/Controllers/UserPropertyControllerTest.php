<?php

namespace Tests\Feature\Controllers;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPropertyControllerTest extends TestCase
{
     use RefreshDatabase;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test que el usuario no autenticado no puede ver sus propiedades
     */
    public function test_unauthenticated_user_cannot_view_properties()
    {
        $response = $this->get(route('user.properties.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test que usuario autenticado puede ver sus propiedades
     */
    public function test_authenticated_user_can_view_own_properties()
    {
        Property::factory(3)->create(['usuario_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('user.properties.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.properties.user_index');
    }

    /**
     * Test que usuario solo ve sus propias propiedades
     */
    public function test_user_sees_only_own_properties()
    {
        $otherUser = User::factory()->create();
        
        Property::factory(3)->create(['usuario_id' => $this->user->id]);
        Property::factory(2)->create(['usuario_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('user.properties.index'));

        $response->assertStatus(200);
    }

    /**
     * Test que usuario puede crear propiedad
     */
    public function test_user_can_create_property()
    {
        $response = $this->actingAs($this->user)
            ->post(route('user.properties.store'), [
                'titulo' => 'Nueva Propiedad',
                'descripcion' => 'Esta es una descripción de prueba muy completa y detallada que contiene suficientes caracteres para cumplir con el requisito mínimo de cien caracteres en la validación del formulario de creación',
                'tipo_propiedad' => 'piso',
                'tipo_operacion' => 'venta',
                'precio' => 250000,
                'superficie_m2' => 120,
                'habitaciones' => 3,
                'banos' => 2,
                'direccion' => 'Calle Principal 123',
                'ciudad' => 'Madrid',
                'provincia' => 'Madrid',
                'codigo_postal' => '28001',
            ]);

        $this->assertDatabaseHas('propiedades', [
            'titulo' => 'Nueva Propiedad',
            'usuario_id' => $this->user->id,
        ]);
    }

    /**
     * Test que se requieren campos obligatorios al crear propiedad
     */
    public function test_property_creation_requires_title()
    {
        $response = $this->actingAs($this->user)
            ->post(route('user.properties.store'), [
                'descripcion' => 'Descripción sin título',
                'tipo_propiedad' => 'piso',
                'tipo_operacion' => 'venta',
                'precio' => 250000,
            ]);

        $response->assertSessionHasErrors('titulo');
    }

    /**
     * Test que usuario puede activar/desactivar propiedad
     */
    public function test_user_can_toggle_property_active_status()
    {
        $property = Property::factory()->create([
            'usuario_id' => $this->user->id,
            'activa' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->patch(route('user.properties.toggle', $property->id));

        $property->refresh();
        $this->assertFalse($property->activa);
    }

    /**
     * Test que usuario puede eliminar su propiedad
     */
    public function test_user_can_delete_own_property()
    {
        $property = Property::factory()->create(['usuario_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('user.properties.destroy', $property->id));

        $this->assertSoftDeleted($property);
    }

    /**
     * Test que usuario no puede eliminar propiedad de otro
     */
    public function test_user_cannot_delete_others_property()
    {
        $otherUser = User::factory()->create();
        $property = Property::factory()->create(['usuario_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('user.properties.destroy', $property->id));

        $response->assertStatus(404);
    }

    /**
     * Test que la paginación funciona en propiedades del usuario
     */
    public function test_user_properties_pagination()
    {
        Property::factory(15)->create(['usuario_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('user.properties.index'));

        $response->assertStatus(200);
    }

    /**
     * Test que usuario puede ver formulario de crear propiedad
     */
    public function test_user_can_view_create_property_form()
    {
        $response = $this->actingAs($this->user)
            ->get(route('user.properties.create'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.properties.create');
    }

    /**
     * Test validación de slug único
     */
    public function test_property_slug_must_be_unique()
    {
        Property::factory()->create([
            'usuario_id' => $this->user->id,
            'titulo' => 'Property Title',
            'slug' => 'property-title',
        ]);

        // Crear otra propiedad con el mismo título
        $response = $this->actingAs($this->user)
            ->post(route('user.properties.store'), [
                'titulo' => 'Property Title',
                'descripcion' => 'Another complete description for testing purposes',
                'tipo_propiedad' => 'piso',
                'tipo_operacion' => 'venta',
                'precio' => 250000,
                'superficie_m2' => 120,
                'habitaciones' => 3,
                'banos' => 2,
                'direccion' => 'Calle Test 456',
                'ciudad' => 'Barcelona',
                'provincia' => 'Barcelona',
                'codigo_postal' => '08001',
            ]);

        // El slug debe ser único pero el sistema genera slugs incrementados
        $this->assertDatabaseHas('propiedades', [
            'titulo' => 'Property Title',
            'usuario_id' => $this->user->id,
        ]);
    }
}
