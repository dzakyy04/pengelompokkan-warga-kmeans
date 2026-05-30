<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@gmail.com'], ['name' => 'Administrator', 'password' => Hash::make('password'), 'role' => 'admin']);
        User::firstOrCreate(['email' => 'kades@gmail.com'], ['name' => 'Kepala Desa', 'password' => Hash::make('password'), 'role' => 'kepala_desa']);
    }
}
