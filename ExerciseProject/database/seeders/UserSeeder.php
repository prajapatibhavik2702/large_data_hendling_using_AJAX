<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

    for ($i = 1; $i <= 100; $i++) {
        // log::info('adfs');
        DB::table('users')->insert([
            'name' => $faker->name,
            'email' => rand(3000, 8000).$faker->unique()->safeEmail,
            'password' => Hash::make('123456789'),
            'notification_on_off' => rand(0, 1),

             ]);
             log::info('svcfzd');
    }
    }
}
