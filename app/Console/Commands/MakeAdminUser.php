<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('admin:make {email : Email of the user} {--name= : Name, used when creating} {--password= : Password, used when creating}')]
#[Description('Create an admin user, or promote an existing closer-api user to admin')]
class MakeAdminUser extends Command
{
    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->forceFill(['is_admin' => true])->save();
            $this->info("Promoted {$email} to admin.");

            return self::SUCCESS;
        }

        $password = $this->option('password') ?: $this->secret('Password (min 8 chars)');
        if (strlen((string) $password) < 8) {
            $this->error('Password must be at least 8 characters.');

            return self::FAILURE;
        }

        User::create([
            'name' => $this->option('name') ?: 'Admin',
            'email' => $email,
            'password' => $password,
            'is_admin' => true,
        ]);
        $this->info("Created admin {$email}.");

        return self::SUCCESS;
    }
}
