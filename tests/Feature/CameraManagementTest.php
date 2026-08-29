<?php

namespace Tests\Feature;

use App\Models\Camera;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CameraManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_administrator_can_open_camera_creation_form(): void
    {
        [$admin, $organization] = $this->createAdministrator();
        Site::factory()->for($organization)->create(['name' => 'Centre-ville']);

        $this->actingAs($admin)
            ->get(route('cameras.create'))
            ->assertOk()
            ->assertSee('Ajouter une caméra')
            ->assertSee('Centre-ville');
    }

    public function test_user_without_admin_role_cannot_create_camera(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user)->get(route('cameras.create'))->assertForbidden();
    }

    public function test_administrator_creates_camera_with_encrypted_stream_url_and_audit_log(): void
    {
        [$admin, $organization] = $this->createAdministrator();
        $site = Site::factory()->for($organization)->create();
        $streamUrl = 'rtsp://operator:secret@192.0.2.10/live';

        $this->actingAs($admin)->post(route('cameras.store'), [
            'site_id' => $site->id,
            'name' => 'Caméra Entrée Nord',
            'code' => 'cam-nord-01',
            'stream_url' => $streamUrl,
            'protocol' => 'rtsp',
            'latitude' => -11.6647,
            'longitude' => 27.4794,
            'direction_degrees' => 90,
            'status' => 'offline',
            'capabilities' => ['person', 'vehicle', 'plate'],
        ])->assertRedirect(route('cameras.index'));

        $camera = Camera::where('code', 'CAM-NORD-01')->firstOrFail();

        $this->assertSame($streamUrl, $camera->stream_url_encrypted);
        $this->assertNotSame($streamUrl, DB::table('cameras')->where('id', $camera->id)->value('stream_url_encrypted'));
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'camera.created',
            'resource_id' => $camera->id,
        ]);
    }

    public function test_administrator_cannot_attach_camera_to_another_organization_site(): void
    {
        [$admin] = $this->createAdministrator();
        $foreignSite = Site::factory()->create();

        $this->actingAs($admin)
            ->from(route('cameras.create'))
            ->post(route('cameras.store'), [
                'site_id' => $foreignSite->id,
                'name' => 'Caméra étrangère',
                'code' => 'CAM-FOREIGN',
                'stream_url' => 'rtsp://192.0.2.10/live',
                'protocol' => 'rtsp',
                'status' => 'offline',
            ])
            ->assertRedirect(route('cameras.create'))
            ->assertSessionHasErrors('site_id');

        $this->assertDatabaseMissing('cameras', ['code' => 'CAM-FOREIGN']);
    }

    /**
     * @return array{User, Organization}
     */
    private function createAdministrator(): array
    {
        $organization = Organization::factory()->create();
        $admin = User::factory()->for($organization)->create(['status' => 'active']);
        $admin->roles()->attach(Role::create([
            'organization_id' => $organization->id,
            'name' => 'admin',
            'description' => 'Administration complète',
        ]));

        return [$admin, $organization];
    }
}
