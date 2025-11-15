<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email} {--role=admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote a user to admin or superadmin role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->option('role');

        // Validate role
        if (!in_array($role, ['admin', 'superadmin'])) {
            $this->error('Invalid role. Use "admin" or "superadmin".');
            return 1;
        }

        // Find user
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        // Update role
        $user->update(['role' => $role]);

        $this->info("✓ User {$user->name} ({$user->email}) has been promoted to {$role}.");
        $this->info("They can now access the admin panel at /admin");

        return 0;
    }
}
