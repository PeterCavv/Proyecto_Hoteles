<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'create:admin
                            {name? : Nombre del usuario}
                            {email? : Email del usuario}
                            {password? : Contraseña}';

    protected $description;

    public function __construct()
    {
        parent::__construct();

        $this->description = __('commands.create_admin.description');
    }

    public function handle(): int
    {
        $name = $this->argument('name') ?? $this->ask(__('commands.create_admin.ask.name'));
        $email = $this->argument('email') ?? $this->ask(__('commands.create_admin.ask.email'));
        $password = $this->argument('password') ?? $this->ask(__('commands.create_admin.ask.password'));

        if (User::where('email', $email)->exists()) {
            $this->error(__('commands.create_admin.user_exists', ['email' => $email]));
            return 1;
        }

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole('admin');

        $this->info(__('commands.create_admin.created', ['name' => $name]));
        return 0;
    }
}

