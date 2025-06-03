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

    protected $description = 'Create a new admin user with the specified name, email, and password.';

    public function handle()
    {
        $name = $this->argument('name') ?? $this->ask('What is the admin name?');
        $email = $this->argument('email') ?? $this->ask('What is the admin email?');
        $password = $this->argument('password') ?? $this->ask('What is the admin password?');

        if (User::where('email', $email)->exists()) {
            $this->error("El usuario con email {$email} ya existe.");
            return 1; // Código de error
        }

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
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

        $this->info("Usuario admin '{$name}' creado correctamente.");
        return 0; // Éxito
    }
}

