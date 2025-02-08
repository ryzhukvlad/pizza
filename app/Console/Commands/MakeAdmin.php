<?php

namespace App\Console\Commands;

use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-admin {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $attributes = [
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => $this->argument('password'),
            'role' => UserRole::ADMIN
        ];
        try {
            User::factory()->create($attributes);
        } catch (QueryException $exception) {
            if ($exception->getCode() == 23000) {
                $this->error("User email {$attributes['email']} already exists!");
                return;
            }
            $this->error($exception->getMessage());
            return;
        }
        $this->info("Admin {$attributes['name']} has been created");
    }
}
