<?php

use App\Schedule;
use App\Speaker;
use Illuminate\Database\Seeder;

/**
 * Seeds the timetable for the Online Research Methodology Course (RMC) — the
 * 9-week live-webinar series that runs alongside the original physical
 * workshop, per "2026_Final Program RMC.pdf".
 *
 * Safe to run more than once: speakers are matched by name (reusing existing
 * ones already on file from the physical workshop) and schedule rows are
 * matched by (course_type, day_number, start_time, title) before creating.
 */
class OnlineResearchMethodologyCourseSeeder extends Seeder
{
    public function run()
    {
        // Week 1 — 28th August 2026
        $this->week(1, '2026-08-28', [
            ['15:00:00', '15:10:00', 'Introductions, welcome remarks and course expectations', 'Dr Michael Mwachiro'],
            ['15:10:00', '16:05:00', 'Why surgical research in the ECSA region?', 'Dr John Tarpley'],
            ['16:05:00', '17:00:00', 'Formulating a Research Question and Overview of Epidemiological Study Designs', 'Dr Robert Parker'],
        ]);

        // Week 2 — 4th September 2026
        $this->week(2, '2026-09-04', [
            ['15:00:00', '16:00:00', 'Cross-sectional study designs', 'Dr Michael Mbambiko'],
            ['16:00:00', '17:00:00', 'Case-control study designs', 'Dr Alemayehu Amberbir'],
        ]);

        // Week 3 — 11th September 2026
        $this->week(3, '2026-09-11', [
            ['15:00:00', '15:40:00', 'Cohort Study designs', 'Dr. Jana McLeod'],
            ['15:40:00', '16:20:00', 'Randomized Study Designs in Surgical Care', 'Dr Akutu Munyika'],
            ['16:20:00', '17:00:00', 'Introduction to Implementation Science designs', 'Dr Godfrey Sama Philipo'],
        ]);

        // Week 4 — 18th September 2026
        $this->week(4, '2026-09-18', [
            ['15:00:00', '15:40:00', 'Introduction to data analysis using a desired software', 'Dr. Rhondi Khauffmann'],
            ['15:40:00', '16:20:00', 'Sampling techniques & sample size determination', 'Dr Robert Riviello'],
            ['16:20:00', '17:00:00', 'Random errors, bias and confounding', 'Dr. Olivia Kituuka'],
        ]);

        // Week 5 — 25th September 2026
        $this->week(5, '2026-09-25', [
            ['15:00:00', '16:00:00', 'Introduction to Correlation and Regression', 'Dr Gibson Kagaruki'],
            ['16:00:00', '17:00:00', 'Statistical Tests Examples and Uses', 'Dr. Georges Bucyibaruta'],
        ]);

        // Week 6 — 2nd October 2026
        $this->week(6, '2026-10-02', [
            ['15:00:00', '16:00:00', 'Introduction to logistic regression', 'Dr. Dennis Mazingi'],
            ['16:00:00', '17:00:00', 'Linear Regression Analysis', 'Dr. Isaac Cheruiyot'],
        ]);

        // Week 7 — 9th October 2026
        $this->week(7, '2026-10-09', [
            ['15:00:00', '16:00:00', 'Logistic Regression Analysis', 'Dr Chester Kalinda'],
            ['16:00:00', '17:00:00', 'Statistical Tests Examples and Uses', 'Dr. Isaac Cheruiyot'],
        ]);

        // Week 8 — 16th October 2026
        $this->week(8, '2026-10-16', [
            ['15:00:00', '16:00:00', 'Basics of Research Ethics and IRB application', 'Dr. Barnabas Alayande'],
            ['16:00:00', '17:00:00', 'Research Proposal components', 'Dr Catherine Mohr'],
        ]);

        // Week 9 — 23rd October 2026
        $this->week(9, '2026-10-23', [
            ['15:00:00', '16:00:00', 'Manuscript Writing and Publication (Choosing the right journal)', 'Dr Vincent Kipkorir'],
            ['16:00:00', '17:00:00', 'What reviewers want – Introduction to Peer Reviewing', 'Dr. Mumba Chalwe'],
        ]);
    }

    private function week($weekNumber, $date, array $sessions)
    {
        foreach ($sessions as [$startTime, $endTime, $title, $trainerName]) {
            $speaker = $this->findOrCreateSpeaker($trainerName);

            Schedule::firstOrCreate(
                [
                    'course_type' => 'online',
                    'day_number'  => $weekNumber,
                    'start_time'  => $startTime,
                    'title'       => $title,
                ],
                [
                    'date'       => $date,
                    'end_time'   => $endTime,
                    'speaker_id' => $speaker->id,
                ]
            );
        }
    }

    /**
     * Reuse a speaker already on file (e.g. from the physical workshop) if the
     * name matches, otherwise create a new one.
     */
    private function findOrCreateSpeaker($name)
    {
        $speaker = Speaker::where('name', $name)->first();

        if ($speaker) {
            return $speaker;
        }

        // Loose match against minor spelling variants between the two programmes
        // (e.g. "Dr. Georges Bucyibaruta" vs "Dr. Georges Bucybaruta").
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
