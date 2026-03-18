<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class Register extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:register {name} {surname} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Регистрирует пользователя';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user =  User::create([
            'name' => $this->argument('name'),
            'surname' => $this->argument('surname'),
            'email' => $this->argument('email'),
            'password' => $this->argument('password'),
            'role_id' => 2,
            'type_id' => 1,
        ]);

        echo $user->id . PHP_EOL;
    }
}
