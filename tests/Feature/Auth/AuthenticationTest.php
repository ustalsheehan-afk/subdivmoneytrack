<?php

namespace Tests\Feature\Auth;

use App\Models\Resident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_screen_can_be_rendered()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_resident_login_screen_can_be_rendered()
    {
        $response = $this->get('/resident/login');

        $response->assertStatus(200);
    }

    public function test_residents_can_authenticate_using_the_login_screen()
    {
        $resident = Resident::factory()->create([
            'password' => 'password',
        ]);

        $response = $this->post('/resident/login', [
            'email' => $resident->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($resident, 'resident');
        $response->assertRedirect();
    }

    public function test_residents_can_not_authenticate_with_invalid_password()
    {
        $resident = Resident::factory()->create([
            'password' => 'password',
        ]);

        $this->post('/resident/login', [
            'email' => $resident->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('resident');
    }
}
