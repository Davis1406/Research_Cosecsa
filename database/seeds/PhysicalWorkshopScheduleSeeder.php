<?php

use App\Schedule;
use App\Speaker;
use Illuminate\Database\Seeder;

/**
 * Syncs the completed Physical Workshop timetable (25–29 May 2026, Days 1–5)
 * from the reference/dev copy of the database to wherever this seeder is run
 * (e.g. production, if it's missing some or all of these rows).
 *
 * Safe to run more than once and safe to run alongside data already present:
 * speakers are matched by name (reusing whichever Speaker record already
 * exists rather than creating a duplicate) and schedule rows are matched by
 * (course_type, day_number, start_time, title) via firstOrCreate — an
 * existing row is left untouched, only genuinely missing rows are inserted.
 * It will never overwrite manual edits already made on the target database.
 */
class PhysicalWorkshopScheduleSeeder extends Seeder
{
    public function run()
    {
        // Day 1 — 2026-05-25
        $this->day(1, '2026-05-25', [
            ['08:30:00', '09:00:00', 'Registration and Pre-Course Assessment', null, 'ALL participants', null, true],
            ['09:00:00', '09:15:00', 'Introductions and Welcome Remarks', 'Dr. Barnabas Alayande', null, null, true],
            ['09:15:00', '09:45:00', 'Official Opening: COSECSA\'s Vision for Research Development', 'Dr. Michael Mwachiro', 'COSECSA Country Representatives', null, true],
            ['09:45:00', '10:00:00', 'Introduction of the Course and Expectations', 'Dr. Godfrey Sama Philipo', null, null, true],
            ['10:00:00', '10:30:00', 'Morning Tea Break', null, 'ALL', null, false],
            ['10:30:00', '11:00:00', 'Overview of Epidemiological Study Design', 'Dr. Alemayehu Amberbir', null, null, true],
            ['11:00:00', '11:30:00', 'Cross-Sectional Study Designs', 'Dr. Michael Mwachiro', null, null, true],
            ['11:30:00', '12:00:00', 'Case-Control Study Designs', 'Dr. Alemayehu Amberbir', null, null, true],
            ['12:00:00', '12:30:00', 'Cohort Study Designs', 'Dr. Alemayehu Amberbir', null, null, true],
            ['12:30:00', '13:00:00', 'Randomized Study Designs in Surgical Care', 'Dr. Gibson Kagaruki', null, null, true],
            ['13:00:00', '14:00:00', 'Lunch Break', null, 'ALL', null, false],
            ['14:00:00', '14:30:00', 'Introduction to Implementation Science Designs', 'Dr. Godfrey Sama Philipo', null, null, true],
            ['14:30:00', '15:00:00', 'Participants Proposal Presentation and Feedback', 'Dr. Godfrey Sama Philipo', 'Panel', null, true],
            ['15:00:00', '15:15:00', 'Afternoon Tea Break', null, 'ALL', null, false],
            ['15:15:00', '16:15:00', 'Participants Proposal Presentation and Feedback', 'Dr. Barnabas Alayande', 'Panel', null, true],
            ['16:15:00', '16:30:00', 'Wrap-Up of the Day', 'Dr. Michael Mwachiro', null, null, true],
        ]);

        // Day 2 — 2026-05-26
        $this->day(2, '2026-05-26', [
            ['09:00:00', '09:10:00', 'Recap of Day 1', 'Dr. Gatwiri Murithi', null, null, true],
            ['09:10:00', '09:35:00', 'Framing a Research Question and Anatomy of a Research Protocol', 'Professor Chester Kalinda (PhD)', null, null, true],
            ['09:35:00', '10:00:00', 'Sampling Techniques & Sample Size Determination', 'Dr. Gatwiri Murithi', null, null, true],
            ['10:00:00', '10:30:00', 'Morning Tea Break', null, 'ALL', null, false],
            ['10:30:00', '11:00:00', 'Random Errors, Bias and Confounding', 'Professor Chester Kalinda (PhD)', null, null, true],
            ['11:00:00', '11:30:00', 'Introduction to Data Analysis Using a Desired Software', 'Dr. Georges Bucyibaruta', null, null, true],
            ['11:30:00', '13:00:00', 'Statistical Tests Examples and Uses', 'Dr. Georges Bucyibaruta', null, null, true],
            ['13:00:00', '14:00:00', 'Lunch Break', null, 'ALL', null, false],
            ['14:00:00', '15:15:00', 'Systematic Review and Meta-Analysis', 'Dr. Paul Otiku', null, null, true],
            ['15:15:00', '16:15:00', 'Participants Proposal Presentation and Feedback', 'Dr. Ephrem Daniel', 'Panel', null, true],
            ['16:15:00', '16:30:00', 'Wrap-Up of the Day', 'Dr. Michael Mwachiro', null, null, true],
        ]);

        // Day 3 — 2026-05-27
        $this->day(3, '2026-05-27', [
            ['09:00:00', '09:30:00', 'Why Surgical Research in the ECSA Region?', 'Prof. Abebe Bekele', null, null, true],
            ['09:30:00', '10:20:00', 'Association Using Statistical Software (ANOVA, t-tests, CI)', 'Dr. Georges Bucyibaruta', null, null, true],
            ['10:20:00', '10:40:00', 'Morning Tea Break', null, 'ALL', null, false],
            ['10:40:00', '12:10:00', 'Introduction to Correlation and Regression', 'Dr. Gibson Kagaruki', null, null, true],
            ['12:10:00', '13:00:00', 'Introduction to Logistic Regression', 'Professor Chester Kalinda (PhD)', null, null, true],
            ['13:00:00', '14:00:00', 'Lunch Break', null, 'ALL', null, false],
            ['14:00:00', '15:00:00', 'Logistic Regression Analysis', 'Professor Chester Kalinda (PhD)', null, null, true],
            ['15:00:00', '15:15:00', 'Afternoon Tea Break', null, 'ALL', null, false],
            ['15:15:00', '16:15:00', 'Participants Proposal Presentation and Feedback', 'Dr. Derbew Berhe', 'Panel', null, true],
            ['16:15:00', '16:30:00', 'Wrap-Up of the Day', 'Dr. Michael Mwachiro', null, null, true],
        ]);

        // Day 4 — 2026-05-28
        $this->day(4, '2026-05-28', [
            ['09:00:00', '09:10:00', 'Recap of Previous Day', 'Dr. Tairu Fofanah', null, null, true],
            ['09:10:00', '10:15:00', 'Linear Regression Analysis', 'Dr. Paul Otiku', null, null, true],
            ['10:15:00', '10:45:00', 'Morning Tea Break', null, 'ALL', null, false],
            ['10:45:00', '12:00:00', 'Research Proposal Components', 'Dr. Barnabas Alayande', null, null, true],
            ['12:00:00', '13:00:00', 'Ethical Considerations in Research', 'Dr. Barnabas Alayande', null, null, true],
            ['13:00:00', '14:00:00', 'Lunch Break', null, 'ALL', null, false],
            ['14:00:00', '15:00:00', 'Statistical Sessions and Consultations', null, 'ALL participants', null, true],
            ['15:00:00', '15:15:00', 'Afternoon Tea Break', null, 'ALL', null, false],
            ['15:15:00', '16:15:00', 'Practical Session: Trainee Presentation of Proposals/Reports', null, null, null, true],
            ['16:15:00', '16:30:00', 'Wrap-Up of the Day', 'Dr. Michael Mwachiro', null, null, true],
        ]);

        // Day 5 — 2026-05-29
        $this->day(5, '2026-05-29', [
            ['09:00:00', '09:10:00', 'Recap of Previous Day', 'Dr. Gatwiri Murithi', null, null, true],
            ['09:10:00', '10:00:00', 'Manuscript Writing and Publication', 'Dr. Vincent Kipkorir', null, null, true],
            ['10:00:00', '10:30:00', 'Morning Tea Break', null, 'ALL', null, false],
            ['10:30:00', '11:00:00', 'Choosing the Right Journal', 'Dr. Barnabas Alayande', null, null, true],
            ['11:00:00', '11:30:00', 'What Reviewers Want – Introduction to Peer Reviewing', 'Dr. Barnabas Alayande', null, null, true],
            ['12:30:00', '13:00:00', 'Abstract Writing for Conferences', 'Dr. Sarah Warunee Derichs', null, null, true],
            ['13:00:00', '14:00:00', 'Lunch Break', null, 'ALL', null, false],
            ['14:00:00', '14:30:00', 'Final Evaluation Session', 'Dr. Godfrey Sama Philipo', null, null, true],
            ['14:30:00', '15:00:00', 'Course Closure and Certification', null, 'ALL participants', null, true],
        ]);
    }

    private function day($dayNumber, $date, array $sessions)
    {
        foreach ($sessions as [$startTime, $endTime, $title, $speakerName, $subtitle, $location, $isCompleted]) {
            $speaker = $speakerName ? $this->findOrCreateSpeaker($speakerName) : null;

            Schedule::firstOrCreate(
                [
                    'course_type' => 'physical',
                    'day_number'  => $dayNumber,
                    'start_time'  => $startTime,
                    'title'       => $title,
                ],
                [
                    'date'         => $date,
                    'end_time'     => $endTime,
                    'subtitle'     => $subtitle,
                    'location'     => $location,
                    'speaker_id'   => $speaker->id ?? null,
                    'is_completed' => $isCompleted,
                    'completed_at' => $isCompleted ? now() : null,
                ]
            );
        }
    }

    /**
     * Reuse a speaker already on file if the name matches (exactly, or after
     * stripping punctuation/whitespace to absorb minor spelling variants),
     * otherwise create a new one.
     */
    private function findOrCreateSpeaker($name)
    {
        $speaker = Speaker::where('name', $name)->first();

        if ($speaker) {
            return $speaker;
        }

        $normalized = strtolower(preg_replace('/[^a-z]/i', '', $name));
        $existing   = Speaker::all()->first(function ($s) use ($normalized) {
            return strtolower(preg_replace('/[^a-z]/i', '', $s->name)) === $normalized;
        });

        if ($existing) {
            return $existing;
        }

        return Speaker::create(['name' => $name]);
    }
}
