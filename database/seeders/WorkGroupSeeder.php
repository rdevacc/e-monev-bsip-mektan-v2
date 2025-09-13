<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('work_groups')->insert([
            [
                'id' => 1,
                'name' => 'Kelompok Program dan Perekayasaan Teknologi',
                'group_leader' => 'Harsono',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Kelompok Kerja Sama dan Pendayagunaan Hasil Perakitan',
                'group_leader' => 'Sulha Pangaribu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Kelompok Layanan Pengujian dan Penilaian Kesesuaian',
                'group_leader' => 'Muhammad Iqbal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Bagian Tata Usaha',
                'group_leader' => 'Suphendi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
