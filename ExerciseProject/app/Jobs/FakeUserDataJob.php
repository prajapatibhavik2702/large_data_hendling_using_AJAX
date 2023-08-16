<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class FakeUserDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 100; $i++) {

            DB::table('users')->insert([
                // 'id' => $i,
                'name' => $faker->name,
                'email' => rand(3000, 8000).$faker->unique()->safeEmail,
                'password' => Hash::make('123456789'),
                'notification_on_off' => rand(0, 1),
            ]);

        }
    }
}
