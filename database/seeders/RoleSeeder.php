<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = ['admin', 'editor', 'reviewer', 'author'];
        
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Admin Jurnalku',
            'email' => 'admin@jurnalku.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('admin');

        // Create editor user
        $editor = User::create([
            'name' => 'Editor In Chief',
            'email' => 'editor@jurnalku.com',
            'password' => Hash::make('password123'),
        ]);
        $editor->assignRole('editor');

        // Create reviewer user
        $reviewer = User::create([
            'name' => 'Reviewer One',
            'email' => 'reviewer@jurnalku.com',
            'password' => Hash::make('password123'),
        ]);
        $reviewer->assignRole('reviewer');

        // Create author user
        $author = User::create([
            'name' => 'Author One',
            'email' => 'author@jurnalku.com',
            'password' => Hash::make('password123'),
        ]);
        $author->assignRole('author');

        echo "✅ Roles and users created successfully!\n";
        echo "Admin: admin@jurnalku.com / password123\n";
        echo "Editor: editor@jurnalku.com / password123\n";
        echo "Reviewer: reviewer@jurnalku.com / password123\n";
        echo "Author: author@jurnalku.com / password123\n";
    }
}