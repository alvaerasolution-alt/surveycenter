<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['lead', 'prospect', 'customer'];
        $sources = ['Website', 'Referral', 'Ads', 'Social Media', 'Event'];

        for ($i = 1; $i <= 20; $i++) {
            DB::table('customers')->insert([
                'full_name' => 'Customer ' . $i,
                'email' => 'customer' . $i . '@example.com',
                'phone' => '08' . rand(100000000, 999999999),
                'status' => $statuses[array_rand($statuses)],
                'source' => $sources[array_rand($sources)],
                'notes' => 'Catatan untuk customer ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
