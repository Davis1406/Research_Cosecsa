<?php

namespace App\Services;

use App\Certificate;
use App\Quiz;
use App\QuizAttempt;
use App\Trainee;
use App\TrainingMaterial;
use App\TrainingMaterialView;

/**
 * Checks whether an online-course trainee has finished everything required
 * to graduate (every online-course material viewed + every published
 * online-course quiz passed) and, the moment that becomes true, auto-issues
 * their certificate. Physical-course trainees are untouched — certificates
 * for that course remain fully manual, issued by staff via the existing
 * Admin/Facilitator certificate CRUD.
 */
class CourseCompletionService
{
    const ONLINE = 'online';

    /**
     * Run the completion check for a trainee and issue a certificate if
     * they've just finished. Safe to call repeatedly — issues at most one
     * auto-generated certificate per trainee for the online course.
     */
    public function checkAndIssue(Trainee $trainee): ?Certificate
    {
        if ($trainee->course_type !== self::ONLINE || !$trainee->user_id) {
            return null;
        }

        if ($this->alreadyIssued($trainee)) {
            return null;
        }

        if (!$this->materialsComplete($trainee) || !$this->quizzesComplete($trainee)) {
            return null;
        }

        return $this->issue($trainee);
    }

    public function alreadyIssued(Trainee $trainee): bool
    {
        return Certificate::where('trainee_id', $trainee->id)
            ->where('course_type', self::ONLINE)
            ->where('auto_generated', true)
            ->exists();
    }

    public function materialsComplete(Trainee $trainee): bool
    {
        $materialIds = TrainingMaterial::course(self::ONLINE)->pluck('id');

        if ($materialIds->isEmpty()) {
            return false;
        }

        $viewedCount = TrainingMaterialView::where('user_id', $trainee->user_id)
            ->whereIn('training_material_id', $materialIds)
            ->count();

        return $viewedCount >= $materialIds->count();
    }

    public function quizzesComplete(Trainee $trainee): bool
    {
        $quizIds = Quiz::course(self::ONLINE)->where('is_published', true)->pluck('id');

        if ($quizIds->isEmpty()) {
            return false;
        }

        $passedCount = QuizAttempt::where('user_id', $trainee->user_id)
            ->whereIn('quiz_id', $quizIds)
            ->where('passed', true)
            ->distinct('quiz_id')
            ->count('quiz_id');

        return $passedCount >= $quizIds->count();
    }

    /** Materials-viewed / quizzes-passed counts for a progress display. */
    public function progress(Trainee $trainee): array
    {
        $totalMaterials  = TrainingMaterial::course(self::ONLINE)->count();
        $viewedMaterials = $trainee->user_id
            ? TrainingMaterialView::where('user_id', $trainee->user_id)
                ->whereIn('training_material_id', TrainingMaterial::course(self::ONLINE)->pluck('id'))
                ->count()
            : 0;

        $totalQuizzes  = Quiz::course(self::ONLINE)->where('is_published', true)->count();
        $passedQuizzes = $trainee->user_id
            ? QuizAttempt::where('user_id', $trainee->user_id)
                ->whereIn('quiz_id', Quiz::course(self::ONLINE)->where('is_published', true)->pluck('id'))
                ->where('passed', true)
                ->distinct('quiz_id')
                ->count('quiz_id')
            : 0;

        return compact('totalMaterials', 'viewedMaterials', 'totalQuizzes', 'passedQuizzes');
    }

    private function issue(Trainee $trainee): Certificate
    {
        return Certificate::create([
            'trainee_id'     => $trainee->id,
            'course_type'    => self::ONLINE,
            'event_name'     => config('courses.types.online.label'),
            'venue'          => null,
            'event_date'     => now()->format('F Y'),
            'issued_by'      => null,
            'course_name'    => config('courses.types.online.label'),
            'org_name'       => 'College of Surgeons of East, Central & Southern Africa',
            'generated_at'   => now(),
            'auto_generated' => true,
        ]);
    }
}
