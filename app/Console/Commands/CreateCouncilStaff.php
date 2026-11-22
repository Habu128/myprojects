<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:create-council-staff')]
#[Description('Create or promote a council staff account for report management')]
class CreateCouncilStaff extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->ask('Staff member name');
        $email = $this->ask('Staff member email');
        $password = $this->secret('Password (at least 12 characters)');
        $passwordConfirmation = $this->secret('Confirm password');

        if (! is_string($name) || trim($name) === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Enter a name and a valid email address.');

            return self::FAILURE;
        }

        if (! is_string($password) || strlen($password) < 12 || $password !== $passwordConfirmation) {
            $this->error('Passwords must match and contain at least 12 characters.');

            return self::FAILURE;
        }

        $staffMember = User::query()->firstOrNew(['email' => $email]);
        $staffMember->name = $name;
        $staffMember->password = $password;
        $staffMember->is_council_staff = true;
        $staffMember->save();

        $this->info("Council staff access is ready for {$staffMember->email}.");

        return self::SUCCESS;
    }
}
