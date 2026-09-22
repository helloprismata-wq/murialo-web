<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateLowonganUser extends Command
{
    protected $signature = 'lowongan:user';

    protected $description = 'Buat akun lokal untuk mencoba modul lowongan sebelum fitur auth digabung';

    public function handle(): int
    {
        if (! app()->environment('local')) {
            $this->error('Perintah ini hanya tersedia pada APP_ENV=local.');

            return self::FAILURE;
        }

        $data = ['name' => $this->ask('Nama'), 'email' => $this->ask('Email'), 'password' => $this->secret('Password (minimal 8 karakter)')];
        $validator = Validator::make($data, ['name' => 'required|string|max:255', 'email' => 'required|email|max:255|unique:users,email', 'password' => 'required|string|min:8']);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::create($validator->validated());
        $this->info('Akun dibuat. Buka /lowongan dan gunakan email serta password yang baru Anda isi.');

        return self::SUCCESS;
    }
}
