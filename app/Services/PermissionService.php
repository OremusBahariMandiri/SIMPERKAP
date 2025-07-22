<?php
namespace App\Services;

use App\Models\A02DmUserAccess;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionService
{
    /**
     * Sync the A02DmUserAccess with Spatie Permissions system.
     *
     * @param A02DmUserAccess $userAccess
     * @return void
     */
    public static function syncUserAccessWithPermissions(A02DmUserAccess $userAccess)
    {
        $user = $userAccess->user;

        // Clear existing permissions
        $user->syncPermissions([]);

        // Add permissions based on user access settings
        $permissions = [];

        // Menu Access
        if ($userAccess->menu_acs) {
            $permissions[] = 'menu.access';
        }

        // Create Access
        if ($userAccess->tambah_acs) {
            $permissions[] = 'create.access';
        }

        // Edit Access
        if ($userAccess->ubah_acs) {
            $permissions[] = 'edit.access';
        }

        // Delete Access
        if ($userAccess->hapus_acs) {
            $permissions[] = 'delete.access';
        }

        // Download Access
        if ($userAccess->download_acs) {
            $permissions[] = 'download.access';
        }

        // Detail Access
        if ($userAccess->detail_acs) {
            $permissions[] = 'detail.access';
        }

        // Monitoring Access
        if ($userAccess->monitoring_acs) {
            $permissions[] = 'monitoring.access';
        }

        // Assign permissions to user
        $user->syncPermissions($permissions);
    }

    /**
     * Create default permissions in the system
     *
     * @return void
     */
    public static function createDefaultPermissions()
    {
        $permissions = [
            'menu.access',
            'create.access',
            'edit.access',
            'delete.access',
            'download.access',
            'detail.access',
            'monitoring.access',
            // Module specific permissions
            'user.manage',
            'perusahaan.manage',
            'kapal.manage',
            'dokumen.manage',
            'jenis-kapal.manage',
            'kategori-dokumen.manage',
            'nama-dokumen.manage'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }

    /**
     * Create default roles and assign permissions
     *
     * @return void
     */
    public static function createDefaultRoles()
    {
        // Admin role
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->syncPermissions(Permission::all());

        // Operator role
        $operatorRole = Role::findOrCreate('operator', 'web');
        $operatorRole->syncPermissions([
            'menu.access',
            'create.access',
            'edit.access',
            'detail.access',
            'download.access',
            'perusahaan.manage',
            'kapal.manage',
            'dokumen.manage'
        ]);

        // Viewer role
        $viewerRole = Role::findOrCreate('viewer', 'web');
        $viewerRole->syncPermissions([
            'menu.access',
            'detail.access',
            'download.access'
        ]);
    }

    /**
     * Update user roles based on is_admin field
     *
     * @param A01DmUser $user
     * @return void
     */
    public static function assignDefaultRoleToUser($user)
    {
        if ($user->is_admin) {
            $user->assignRole('admin');
        } else {
            $user->assignRole('operator');
        }
    }
}