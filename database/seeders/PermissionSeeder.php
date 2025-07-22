<?php
namespace Database\Seeders;

use App\Services\PermissionService;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default permissions
        PermissionService::createDefaultPermissions();

        // Create default roles
        PermissionService::createDefaultRoles();
    }
}