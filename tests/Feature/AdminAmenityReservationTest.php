<?php

namespace Tests\Feature;

use App\Models\Amenity;
use App\Models\AmenityReservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAmenityReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_non_resident_reservation(): void
    {
        $this->withoutMiddleware();

        $admin = User::factory()->create([
            'role' => 'admin',
            'active' => true,
        ]);

        $amenity = Amenity::create([
            'name' => 'Clubhouse',
            'days_available' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'time_slots' => [],
            'max_capacity' => 50,
            'price' => 1500,
            'description' => 'Main clubhouse',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.amenity-reservations.store'), [
            'customer_type' => 'non_resident',
            'guest_name' => 'Jane Visitor',
            'guest_contact' => '09123456789',
            'guest_email' => 'jane@example.com',
            'amenity_id' => $amenity->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'duration' => 2,
            'guest_count' => 12,
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'notes' => 'Walk-in booking',
        ]);

        $reservation = AmenityReservation::first();

        $response->assertRedirect(route('admin.amenity-reservations.confirmation', $reservation));
        $this->assertNotNull($reservation);
        $this->assertNull($reservation->resident_id);
        $this->assertSame('non_resident', $reservation->customer_type);
        $this->assertSame('Jane Visitor', $reservation->guest_name);
        $this->assertSame('09123456789', $reservation->guest_contact);
        $this->assertSame('jane@example.com', $reservation->guest_email);
        $this->assertSame('admin_created', $reservation->booking_source);
        $this->assertSame($admin->id, $reservation->created_by_admin_id);
        $this->assertSame('approved', $reservation->status);
        $this->assertSame('pending', $reservation->payment_status);
        $this->assertSame('10:00 - 12:00', $reservation->time_slot);
        $this->assertEquals(3000.0, (float) $reservation->total_price);
    }
}
