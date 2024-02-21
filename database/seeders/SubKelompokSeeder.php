<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubKelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sub_kelompoks')->insert([
            [
                'id' => 1,
                'kelompok_id' => 1,
                'nama' => 'Tim Kerja Program',
                'nama_katim' => 'Fero',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'kelompok_id' => 1,
                'nama' => 'Tim Kerja Evaluasi',
                'nama_katim' => 'Sri Utami',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'kelompok_id' => 2,
                'nama' => 'Tim Kerja Standardisasi',
                'nama_katim' => 'Yusi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'kelompok_id' => 2,
                'nama' => 'Tim Kerja Pengujian',
                'nama_katim' => 'Rudi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'kelompok_id' => 3,
                'nama' => 'Tim Kerja Pengelolaan Hasil Standardisasi',
                'nama_katim' => 'Daragantina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'kelompok_id' => 3,
                'nama' => 'Tim Kerja Penyebarluasan Hasil Standardisasi',
                'nama_katim' => 'Tri S',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'kelompok_id' => 4,
                'nama' => 'Tim Kerja Tata Usaha dan Rumah Tangga',
                'nama_katim' => 'Kartini',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'kelompok_id' => 4,
                'nama' => 'Tim Kerja Kepegawaian',
                'nama_katim' => 'Sulha',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'kelompok_id' => 4,
                'nama' => 'Tim Kerja Keuangan',
                'nama_katim' => 'Sigid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
