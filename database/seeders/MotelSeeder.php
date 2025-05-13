<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $motels = [];

        for ($i = 1; $i <= 20; $i++) {
            $motels[] = [
                'name' => 'Motel ' . $i,
                'address' => '123 Street, City ' . $i,
                'website' => 'https://motel' . $i . '.com',
                'phone' => '+123456789' . $i,
                'google_place_id' => Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('motels')->insert($motels);
    }
}
