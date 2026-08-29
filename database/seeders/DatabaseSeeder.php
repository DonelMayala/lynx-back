<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $organization = Organization::factory()->create([
            'name' => 'Lynx Vision Command',
            'code' => 'LYNX-COMMAND',
        ]);

        $admin = User::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Administrateur Lynx',
            'email' => 'test@example.com',
            'status' => 'active',
        ]);

        $admin->roles()->attach(Role::create([
            'organization_id' => $organization->id,
            'name' => 'admin',
            'description' => 'Administration complète du système',
        ]));
    }
}
