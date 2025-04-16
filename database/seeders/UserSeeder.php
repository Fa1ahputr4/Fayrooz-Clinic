<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['name'=>'admin', 'username'=>'admin', 'email'=>'admin@example.com', 'status'=>'active', 'role'=>'admin', 'password'=>'admin']);
        User::create(['name'=>'staff', 'username'=>'staff', 'email'=>'staff@example.com', 'status'=>'active', 'role'=>'staff', 'password'=>'staff']);
        User::create(['name'=>'apoteker', 'username'=>'apoteker', 'email'=>'apoteker@example.com', 'status'=>'active', 'role'=>'apoteker', 'password'=>'apoteker']);
    }
}
