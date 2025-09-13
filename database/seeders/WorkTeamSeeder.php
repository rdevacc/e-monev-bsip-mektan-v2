<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_teams')->insert([
            [
                'id' => 1,
                'work_group_id' => 1,
                'name' => 'Tim Kerja Program dan Evaluasi',
                'team_leader' => 'Fero',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'work_group_id' => 1,
                'name' => 'Tim Kerja Perekayasaan Teknologi dan Modernisasi Pertanian',
                'team_leader' => 'Amiq Nurul Azmi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'work_group_id' => 2,
                'name' => 'Tim Kerja Pengelolaan Kerja Sama',
                'team_leader' => 'Daragantina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'work_group_id' => 2,
                'name' => 'Tim Kerja Pendayagunaan Hasil Perakitan',
                'team_leader' => 'Tri Saksono',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'work_group_id' => 3,
                'name' => 'Tim Kerja Layanan Pengujian',
                'team_leader' => 'Rudi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'work_group_id' => 3,
                'name' => 'Tim Kerja Layanan Sertifikasi',
                'team_leader' => 'Ivony',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'work_group_id' => 4,
                'name' => 'Tim Kerja Pengelolaan Sumber Daya Manusia',
                'team_leader' => 'Titin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'work_group_id' => 4,
                'name' => 'Tim Kerja Tata Usaha dan Rumah Tangga',
                'team_leader' => 'Karitni',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'work_group_id' => 4,
                'name' => 'Tim Kerja Keuangan dan Barang Milik Negara',
                'team_leader' => 'Sigid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
