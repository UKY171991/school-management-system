<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class MasterAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('slug', 'master-admin')->first();
        
        if (!$role) {
            $this->command->error('Master Admin role not found. Please run RoleSeeder first.');
            return;
        }

        $user = User::updateOrCreate(
            ['email' => 'master@admin.com'],
            [
                'name' => 'Master Admin',
                'password' => Hash::make('password'),
                'role_id' => $role->id,
            ]
        );

        $this->command->info('Master Admin user created successfully.');
        $this->command->info('Email: master@admin.com');
        $this->command->info('Password: password');
    }
}
