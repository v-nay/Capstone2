<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rankings = [];
        $motels = DB::table('motels')->pluck('id'); // Fetch all motel IDs

        foreach ($motels as $index => $motel_id) {
            $rankings[] = [
                'motel_id' => $motel_id,
                'rating' => rand(30, 50) / 10, // Generates a rating between 3.0 and 5.0
                'calculated_score' => rand(60, 100), // Generates a score between 60 and 100
                'rank' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('rankings')->insert($rankings);
    }
}
