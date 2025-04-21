<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10000) as $i) {
            User::create([
                'name'     => $faker->name,
                'username' => $faker->unique()->userName,
                'email'    => $faker->unique()->safeEmail,
                'status'   => $faker->randomElement(['active', 'non_active']),
                'role'     => $faker->randomElement(['admin', 'staff', 'apoteker']),
                'password' => Hash::make('password'),
            ]);
        }
    }
}
