<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Certificate;
use App\Schedule;
use App\TrainingMaterial;
use App\Quiz;
use App\QuizAttempt;
use App\Services\CourseCompletionService;

class DashboardController extends Controller
{
    public function index()
    {
        $trainee    = auth()->user()->trainee;
        $courseType = $trainee->course_type ?? config('courses.default');

        $noBreaks = Schedule::course($courseType)
                             ->where('title', 'not like', '%break%')
                             ->where('title', 'not like', '%lunch%')
                             ->where('title', 'not like', '%tea%');
        $totalSessions     = (clone $noBreaks)->count();
        $completedSessions = (clone $noBreaks)->where('is_completed', true)->count();
        $totalMaterials = TrainingMaterial::course($courseType)->count();
        $myDocuments = $trainee ? $trainee->documents()->count() : 0;

        $quizCount = Quiz::course($courseType)->where('is_published', true)->count();
        $quizPassed = auth()->id()
            ? QuizAttempt::where('user_id', auth()->id())
                ->whereIn('quiz_id', Quiz::course($courseType)->pluck('id'))
                ->where('passed', true)
                ->distinct('quiz_id')
                ->count('quiz_id')
            : 0;

        // Online-course-only: study progress + auto-issued certificate
        $onlineProgress   = null;
        $onlineCertificate = null;
        if ($trainee && $courseType === 'online') {
            $onlineProgress    = app(CourseCompletionService::class)->progress($trainee);
            $onlineCertificate = Certificate::where('trainee_id', $trainee->id)
                ->where('course_type', 'online')
                ->where('auto_generated', true)
                ->first();
        }

        return view('trainee.dashboard', compact(
            'trainee', 'totalSessions', 'completedSessions',
            'totalMaterials', 'myDocuments', 'quizCount', 'quizPassed', 'courseType',
            'onlineProgress', 'onlineCertificate'
        ));
    }
}
