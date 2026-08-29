<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\Camera;
use App\Models\User;
use App\Models\VideoRecording;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SurveillanceDashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_operational_dashboard(): void
    {
        $user = User::factory()->create();
        Camera::factory()->create(['status' => 'online']);
        Alert::factory()->create(['status' => 'new']);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('Centre de commandement')
            ->assertSee('Alertes actives')
            ->assertSee('Caméras en ligne');
    }

    public function test_camera_page_exposes_geolocated_cameras_to_interactive_map(): void
    {
        $user = User::factory()->create();
        $camera = Camera::factory()->create([
            'name' => 'Caméra Carte Test',
            'latitude' => -11.6647,
            'longitude' => 27.4794,
        ]);
        VideoRecording::factory()->for($camera)->create([
            'storage_key' => 'https://media.example.test/camera-loop.mp4',
        ]);

        $this->actingAs($user)->get(route('cameras.index'))
            ->assertOk()
            ->assertSee('data-camera-map', false)
            ->assertSee('Caméra Carte Test')
            ->assertSee('camera-loop.mp4');
    }

    public function test_camera_without_recording_uses_the_demo_video(): void
    {
        $user = User::factory()->create();
        Camera::factory()->create([
            'latitude' => -11.6647,
            'longitude' => 27.4794,
        ]);

        $this->actingAs($user)->get(route('cameras.index'))
            ->assertOk()
            ->assertSee('flower.mp4')
            ->assertSee('&quot;preview_is_demo&quot;:true', false);
    }
}
