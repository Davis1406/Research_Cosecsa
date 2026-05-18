<?php

use App\Role;
use App\User;
use App\Speaker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PortalRolesSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $leadFacilitator = Role::firstOrCreate(
            ['title' => 'Lead Facilitator'],
            ['title' => 'Lead Facilitator', 'slug' => 'lead-facilitator']
        );
        $facilitator = Role::firstOrCreate(
            ['title' => 'Facilitator'],
            ['title' => 'Facilitator', 'slug' => 'facilitator']
        );
        $trainee = Role::firstOrCreate(
            ['title' => 'Trainee'],
            ['title' => 'Trainee', 'slug' => 'trainee']
        );

        // Godfrey Sama — lead facilitator user
        $user = User::firstOrCreate(
            ['email' => 'godfrey.sama@cosecsa.org'],
            [
                'name'               => 'Godfrey Sama',
                'password'           => Hash::make('Cosecsa@2024'),
                'email_verified_at'  => now(),
            ]
        );
        $user->roles()->syncWithoutDetaching([$leadFacilitator->id]);

        // Link to existing Speaker record if exists
        $speaker = Speaker::where('name', 'like', '%Godfrey%Sama%')
            ->orWhere('name', 'like', '%Sama%')
            ->first();
        if ($speaker && !$speaker->user_id) {
            $speaker->update(['user_id' => $user->id]);
        }
    }
}
