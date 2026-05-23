<?php

use App\Role;
use App\Speaker;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * FacilitatorsResyncSeeder
 *
 * Replaces the facilitator roster with the confirmed list for the
 * COSECSA Research Methodology Course (May 2026).
 *
 * PRESERVED (never touched):
 *   - info@cosecsa.org  (system admin)
 *   - rpo.coordinator@cosecsa.org  (Godfrey – keeps existing record)
 *   - managing_editor@cosecsa.org  (Vincent – keeps existing record)
 */
class FacilitatorsResyncSeeder extends Seeder
{
    /** Emails that must never be deleted or have their roles removed */
    const PROTECTED_EMAILS = [
        'info@cosecsa.org',
        'rpo.coordinator@cosecsa.org',
        'managing_editor@cosecsa.org',
    ];

    /** Confirmed facilitator list */
    const FACILITATORS = [
        ['name' => 'Prof. Abebe Bekele',         'institution' => 'UGHE / COSECSA',       'email' => 'abebesurg@gmail.com'],
        ['name' => 'Dr. Michael Mwachiro',        'institution' => 'COSECSA',               'email' => 'drdechemwach@gmail.com'],
        ['name' => 'Dr. Godfrey Sama Philipo',    'institution' => 'COSECSA',               'email' => 'rpo.coordinator@cosecsa.org'],
        ['name' => 'Dr. Vincent Kipkorir',        'institution' => 'COSECSA / Tenwek',      'email' => 'managing_editor@cosecsa.org'],
        ['name' => 'Dr. Barnabas Alayande',       'institution' => 'UGHE / COSECSA',        'email' => 'balayande@ughe.org'],
        ['name' => 'Dr. Gatwiri Murithi',         'institution' => 'UGHE',                  'email' => 'gmurithi@ughe.org'],
        ['name' => 'Dr. Sarah Warunee Derichs',   'institution' => 'UGHE',                  'email' => 'sderichs@ughe.org'],
        ['name' => 'Dr. Tairu Fofanah',           'institution' => 'UGHE',                  'email' => 'tfofanah@ughe.org'],
        ['name' => 'Dr. Gibson Kagaruki',         'institution' => 'NIMR',                  'email' => 'gkagaruki@gmail.com'],
        ['name' => 'Dr. Georges Bucyibaruta',     'institution' => 'UGHE',                  'email' => 'gbucyibaruta@ughe.org'],
        ['name' => 'Dr. Alemayehu Amberbir',      'institution' => 'UGHE',                  'email' => 'aamberbir@ughe.org'],
        ['name' => 'Dr. Paul Otiku',              'institution' => 'UGHE',                  'email' => 'potiku@ughe.org'],
        ['name' => 'Dr. Ephrem Daniel',           'institution' => 'UGHE',                  'email' => 'edaniel@ughe.org'],
        ['name' => 'Dr. Chester Kalinda',         'institution' => 'UGHE',                  'email' => 'ckalinda@ughe.org'],
        ['name' => 'Dr. Derbew Berhe',            'institution' => 'UGHE',                  'email' => 'dfikadu@ughe.org'],
    ];

    public function run()
    {
        $facilitatorRole    = Role::where('slug', 'facilitator')->orWhere('title', 'Facilitator')->firstOrFail();
        $leadFacilitatorRole = Role::where('slug', 'lead-facilitator')->orWhere('title', 'Lead Facilitator')->first();

        $confirmedEmails = array_column(self::FACILITATORS, 'email');

        // ── 1. Remove speakers NOT in confirmed list ──────────────────────────
        Speaker::withTrashed()->get()->each(function (Speaker $speaker) use ($confirmedEmails) {
            // Find if this speaker is linked to a protected user
            if ($speaker->user_id) {
                $userEmail = User::find($speaker->user_id)?->email ?? '';
                if (in_array($userEmail, self::PROTECTED_EMAILS)) {
                    return; // skip protected
                }
            }

            // Check by speaker name against confirmed list names
            $confirmedNames = array_column(self::FACILITATORS, 'name');
            // Normalise comparison: strip dots/spaces
            $normalize = fn($s) => strtolower(preg_replace('/[\s.]+/', '', $s));
            $normalizedConfirmed = array_map($normalize, $confirmedNames);

            if (!in_array($normalize($speaker->name), $normalizedConfirmed)) {
                // Delete any linked user (that isn't protected)
                if ($speaker->user_id) {
                    $user = User::find($speaker->user_id);
                    if ($user && !in_array($user->email, self::PROTECTED_EMAILS)) {
                        $user->roles()->detach();
                        $user->forceDelete();
                    }
                }
                $this->command->line("  Removing speaker: {$speaker->name}");
                $speaker->forceDelete();
            }
        });

        // ── 2. Remove facilitator users NOT in confirmed email list ───────────
        $allFacilitatorRoleIds = collect([$facilitatorRole->id, $leadFacilitatorRole?->id])->filter()->toArray();

        User::whereHas('roles', function ($q) use ($allFacilitatorRoleIds) {
            $q->whereIn('id', $allFacilitatorRoleIds);
        })->whereNotIn('email', self::PROTECTED_EMAILS)
          ->whereNotIn('email', $confirmedEmails)
          ->get()
          ->each(function (User $user) {
              $this->command->line("  Removing facilitator user: {$user->email}");
              $user->roles()->detach();
              $user->forceDelete();
          });

        // ── 3. Upsert confirmed facilitators ─────────────────────────────────
        foreach (self::FACILITATORS as $data) {
            $isProtected = in_array($data['email'], self::PROTECTED_EMAILS);

            // Upsert user
            $user = User::withTrashed()->where('email', $data['email'])->first();
            if ($user) {
                $user->restore();
                $user->name = $data['name'];
                if (!$isProtected) {
                    $user->password = Hash::make('Cosecsa@2026');
                }
                $user->email_verified_at = $user->email_verified_at ?? now();
                $user->save();
            } else {
                $user = User::create([
                    'name'               => $data['name'],
                    'email'              => $data['email'],
                    'password'           => Hash::make('Cosecsa@2026'),
                    'email_verified_at'  => now(),
                ]);
            }

            // Ensure facilitator role (don't strip lead-facilitator from Godfrey/Vincent)
            if (!$user->roles->contains($facilitatorRole->id)) {
                if (!$isProtected || $user->roles->isEmpty()) {
                    $user->roles()->syncWithoutDetaching([$facilitatorRole->id]);
                }
            }

            // Upsert speaker record
            $normalize = fn($s) => strtolower(preg_replace('/[\s.]+/', '', $s));
            $speaker = Speaker::withTrashed()
                ->where('user_id', $user->id)
                ->orWhere(function ($q) use ($data, $normalize) {
                    // Match by normalised name
                    $q->whereRaw("LOWER(REPLACE(REPLACE(name,' ',''),'.','')) = ?",
                        [$normalize($data['name'])]);
                })
                ->first();

            if ($speaker) {
                $speaker->restore();
                $speaker->name        = $data['name'];
                $speaker->description = $data['institution'];
                $speaker->user_id     = $user->id;
                $speaker->save();
            } else {
                $speaker = Speaker::create([
                    'name'        => $data['name'],
                    'description' => $data['institution'],
                    'user_id'     => $user->id,
                ]);
            }

            $this->command->line("  ✓ {$data['name']} <{$data['email']}>");
        }

        $this->command->info('Facilitator resync complete.');
    }
}
