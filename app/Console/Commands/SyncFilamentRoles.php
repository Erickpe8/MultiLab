<?php

namespace App\Console\Commands;

use App\Helpers\RoleHelper;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class SyncFilamentRoles extends Command
{
    protected $signature = 'multilab:sync-roles {--superadmin-email=}';

    protected $description = 'Ensure the roles required by MultiLab exist (guard=web) and optionally assign one user as superadmin.';

    public function handle(): int
    {
        $this->info('Syncing roles for Filament access...');

        foreach (RoleHelper::getAllRoles() as $roleName) {
            $role = Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );

            if ($role->guard_name !== 'web') {
                $role->guard_name = 'web';
                $role->save();
            }
        }

        if ($email = $this->option('superadmin-email') ?? env('MULTILAB_SUPERADMIN_EMAIL')) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->assignRole('superadmin');
                $this->info("Assigned superadmin role to {$email}.");
            } else {
                $this->warn("User with email {$email} was not found. Please create it before using this command.");
            }
        } else {
            $this->comment('No --superadmin-email provided and MULTILAB_SUPERADMIN_EMAIL is not set. Skipping assignment.');
        }

        $this->info('Roles synchronized.');

        return self::SUCCESS;
    }
}
