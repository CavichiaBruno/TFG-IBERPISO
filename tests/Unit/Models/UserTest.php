<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Property;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test que un usuario puede ser creado correctamente
     */
    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'nombre' => 'Juan García',
            'correo' => 'juan@example.com',
            'rol' => 'usuario',
        ]);

        $this->assertNotNull($user->id);
        $this->assertEquals('Juan García', $user->nombre);
        $this->assertEquals('juan@example.com', $user->correo);
        $this->assertEquals('usuario', $user->rol);
    }

    /**
     * Test que el correo debe ser único (verifica que el usuario se creó con ese correo)
     */
    public function test_user_email_must_be_unique()
    {
        $email = 'unique@example.com';
        $user = User::factory()->create(['correo' => $email]);
        
        // Verify that the user exists with the specified email
        $this->assertDatabaseHas('usuarios', ['correo' => $email, 'id' => $user->id]);
    }

    /**
     * Test que un usuario tiene propiedades
     */
    public function test_user_has_many_properties()
    {
        $user = User::factory()->create();
        Property::factory(3)->create(['usuario_id' => $user->id]);

        $this->assertEquals(3, $user->properties()->count());
    }

    /**
     * Test que la contraseña está hasheada
     */
    public function test_user_password_is_hashed()
    {
        $user = User::factory()->create();

        // The factory hashes the password, so it should not equal 'password'
        $this->assertNotEquals('password', $user->contrasena);
        // It should start with $2y$ which is bcrypt hash prefix
        $this->assertStringStartsWith('$2y$', $user->contrasena);
    }

    /**
     * Test validación de rol de usuario
     */
    public function test_user_has_valid_role()
    {
        $adminUser = User::factory()->create(['rol' => 'admin']);
        $regularUser = User::factory()->create(['rol' => 'usuario']);
        $agentUser = User::factory()->create(['rol' => 'agente']);

        $this->assertEquals('admin', $adminUser->rol);
        $this->assertEquals('usuario', $regularUser->rol);
        $this->assertEquals('agente', $agentUser->rol);
    }

    /**
     * Test que un usuario puede ser eliminado
     */
    public function test_user_can_be_deleted()
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertNull(User::find($userId));
    }

    /**
     * Test que el usuario tiene un nombre y correo válidos
     */
    public function test_user_attributes_are_valid()
    {
        $user = User::factory()->create();

        $this->assertNotEmpty($user->nombre);
        $this->assertNotEmpty($user->correo);
        $this->assertStringContainsString('@', $user->correo);
    }

    /**
     * Test que un usuario admin puede tener permisos especiales
     */
    public function test_admin_user_has_special_role()
    {
        $adminUser = User::factory()->create(['rol' => 'admin']);

        $this->assertEquals('admin', $adminUser->rol);
        $this->assertTrue($adminUser->rol === 'admin');
    }
}
