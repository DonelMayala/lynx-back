<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SiteManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_administrator_sees_site_management_page(): void
    {
        [$admin] = $this->createAdministrator();

        $this->actingAs($admin)
            ->get(route('sites.index'))
            ->assertOk()
            ->assertSee('Gérer les sites')
            ->assertSee('Ajouter un emplacement');
    }

    public function test_user_without_admin_role_cannot_manage_sites(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user)->get(route('sites.index'))->assertForbidden();
    }

    public function test_administrator_creates_site_for_own_organization(): void
    {
        [$admin, $organization] = $this->createAdministrator();

        $this->actingAs($admin)->post(route('sites.store'), [
            'name' => 'Carrefour Centre-ville',
            'address' => 'Avenue Lumumba, Lubumbashi',
            'timezone' => 'Africa/Lubumbashi',
            'latitude' => -11.6647,
            'longitude' => 27.4794,
            'active' => true,
        ])->assertRedirect(route('sites.index'));

        $this->assertDatabaseHas('sites', [
            'organization_id' => $organization->id,
            'name' => 'Carrefour Centre-ville',
            'active' => true,
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'site.created',
        ]);
    }

    public function test_site_rejects_invalid_coordinates(): void
    {
        [$admin] = $this->createAdministrator();

        $this->actingAs($admin)
            ->from(route('sites.index'))
            ->post(route('sites.store'), [
                'name' => 'Position invalide',
                'timezone' => 'Africa/Lubumbashi',
                'latitude' => 100,
                'longitude' => 200,
                'active' => true,
            ])
            ->assertRedirect(route('sites.index'))
            ->assertSessionHasErrors(['latitude', 'longitude']);

        $this->assertDatabaseMissing('sites', ['name' => 'Position invalide']);
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
