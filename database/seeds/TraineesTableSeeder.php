<?php

use App\Trainee;
use Illuminate\Database\Seeder;

class TraineesTableSeeder extends Seeder
{
    public function run()
    {
        // Sample trainees - replace with real data or leave empty
        $trainees = [
            [
                'name'                => 'Dr. Alice Kamau',
                'email'               => 'alice.kamau@example.com',
                'phone'               => '+254712345678',
                'institution'         => 'Kenyatta National Hospital',
                'registration_number' => 'COSECSA/2024/001',
                'country'             => 'Kenya',
                'specialty'           => 'General Surgery Year 2',
                'enrollment_date'     => '2024-01-15',
                'notes'               => null,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'name'                => 'Dr. Bernard Osei',
                'email'               => 'bernard.osei@example.com',
                'phone'               => '+233201234567',
                'institution'         => 'Korle Bu Teaching Hospital',
                'registration_number' => 'COSECSA/2024/002',
                'country'             => 'Ghana',
                'specialty'           => 'Orthopaedic Surgery Year 1',
                'enrollment_date'     => '2024-01-15',
                'notes'               => null,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ];

        Trainee::insert($trainees);
    }
}
