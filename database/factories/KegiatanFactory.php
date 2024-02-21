<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kegiatan>
 */
class KegiatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $current_date =  Carbon::now();
        $start_date = $current_date->subMonth(3);
        $end_date = $current_date;

        return [
            'nama' => fake()->words(rand(3, 7), true),
            'user_id' => rand(1, 7),
            'kelompok_id' => rand(1, 4),
            'subkelompok_id' => rand(1, 9),
            'status_id' => rand(1,2),
            'anggaran_kegiatan' => 0,
            'target_keuangan' => 0,
            'realisasi_keuangan' => 0,
            'target_fisik' => 0,
            'realisasi_fisik' => 0,
            'dones' => null,
            'problems' => null,
            'follow_up' => null,
            'todos' => null,
            'created_at' => Carbon::now()->subDays(rand(0, 40)),
            'updated_at' => Carbon::now(),
        ];
    }
}
