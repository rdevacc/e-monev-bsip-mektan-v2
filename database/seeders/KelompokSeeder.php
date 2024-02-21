<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelompoks')->insert([
            [
                'id' => 1,
                'nama' => 'Kelompok Program dan Evaluasi',
                'nama_kakel' => 'Harsono',
                'anggaran_kelompok' => 700000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama' => 'Kelompok Standardisasi dan Pengujian',
                'nama_kakel' => 'Lilik TM',
                'anggaran_kelompok' => 600000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama' => 'Kelompok PPHSPI',
                'nama_kakel' => 'Elita',
                'anggaran_kelompok' => 500000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama' => 'Bagian Tata Usaha',
                'nama_kakel' => 'Suphendi',
                'anggaran_kelompok' => 400000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
