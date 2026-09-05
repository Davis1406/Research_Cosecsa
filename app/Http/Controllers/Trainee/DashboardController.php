<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Schedule;
use App\TrainingMaterial;
use App\Quiz;
use App\QuizAttempt;

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

        $quizCount = Quiz::where('is_published', true)->count();
        $quizPassed = auth()->id()
            ? QuizAttempt::where('user_id', auth()->id())->where('passed', true)->count()
            : 0;

        return view('trainee.dashboard', compact(
            'trainee', 'totalSessions', 'completedSessions',
            'totalMaterials', 'myDocuments', 'quizCount', 'quizPassed', 'courseType'
        ));
    }
}
