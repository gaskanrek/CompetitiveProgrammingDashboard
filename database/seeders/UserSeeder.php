<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Role;
use App\Models\CodeforcesHandle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $pendampingRole = Role::where('name', 'pendamping')->first();

        // Create Pendamping user
        $pendamping = User::create([
            'name' => 'Admin Pendamping',
            'email' => 'pendamping@test.com',
            'password' => Hash::make('password'),
            'role_id' => $pendampingRole->id,
        ]);

        UserProfile::create([
            'user_id' => $pendamping->id,
            'full_name' => 'Admin Pendamping',
            'university' => 'Universitas Test',
            'major' => 'Teknik Informatika',
            'graduation_year' => 2025,
        ]);

        // Create sample Mahasiswa users
        $mahasiswaData = [
            [
                'name' => 'Ahmad Budi',
                'email' => 'ahmad@test.com',
                'nim' => '21120001',
                'full_name' => 'Ahmad Budi Santoso',
                'university' => 'Universitas Test',
                'major' => 'Teknik Informatika',
                'graduation_year' => 2025,
                'handle' => null, // Will be added manually later
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@test.com',
                'nim' => '21120002',
                'full_name' => 'Siti Nurhaliza',
                'university' => 'Universitas Test',
                'major' => 'Teknik Informatika',
                'graduation_year' => 2025,
                'handle' => null,
            ],
            [
                'name' => 'Doni Pratama',
                'email' => 'doni@test.com',
                'nim' => '21120003',
                'full_name' => 'Doni Pratama Wijaya',
                'university' => 'Universitas Test',
                'major' => 'Teknik Informatika',
                'graduation_year' => 2025,
                'handle' => null,
            ],
        ];

        foreach ($mahasiswaData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role_id' => $mahasiswaRole->id,
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'nim' => $data['nim'],
                'full_name' => $data['full_name'],
                'university' => $data['university'],
                'major' => $data['major'],
                'graduation_year' => $data['graduation_year'],
            ]);

            // Add Codeforces handle if specified
            if ($data['handle']) {
                CodeforcesHandle::create([
                    'user_id' => $user->id,
                    'handle' => $data['handle'],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Created sample users:');
        $this->command->info('- pendamping@test.com / password (Pendamping)');
        $this->command->info('- ahmad@test.com / password (Mahasiswa)');
        $this->command->info('- siti@test.com / password (Mahasiswa)');
        $this->command->info('- doni@test.com / password (Mahasiswa)');
    }
}
