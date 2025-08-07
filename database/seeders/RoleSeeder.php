<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'mahasiswa',
                'display_name' => 'Mahasiswa',
                'description' => 'Role untuk mahasiswa yang dapat mendaftar handle Codeforces dan melihat aktivitas pribadi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'pendamping',
                'display_name' => 'Pendamping',
                'description' => 'Role untuk pendamping yang dapat memantau semua mahasiswa dan mengatur sistem',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
