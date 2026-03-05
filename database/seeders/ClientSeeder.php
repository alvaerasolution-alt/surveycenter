<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::insert([
            ['name' => 'Andika P.', 'company' => 'PT Bank X', 'package' => 'Menengah', 'progress' => '450/500', 'invoice' => 'Lunas'],
            ['name' => 'Sinta D.', 'company' => 'Startup A', 'package' => 'Kecil', 'progress' => '200/200', 'invoice' => 'Lunas'],
            ['name' => 'Budi S.', 'company' => 'Pemda Y', 'package' => 'Premium', 'progress' => '800/2000', 'invoice' => 'Pending'],
            ['name' => 'Maya P.', 'company' => 'Brand Z', 'package' => 'Menengah', 'progress' => '100/500', 'invoice' => 'Lunas'],
        ]);
    }
}
