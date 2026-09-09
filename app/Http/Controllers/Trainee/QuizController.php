<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Quiz;
use App\QuizAnswer;
use App\QuizAttempt;
use App\Services\CourseCompletionService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /** List published quizzes for the trainee's own course, with attempt status. */
    public function index()
    {
        $trainee    = auth()->user()->trainee;
        $courseType = $trainee->course_type ?? config('courses.default');

        $quizzes = Quiz::course($courseType)
            ->where('is_published', true)
            ->withCount('questions')
            ->orderBy('title')
            ->get();

        $bestAttempts = QuizAttempt::where('user_id', auth()->id())
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->whereNotNull('completed_at')
            ->get()
            ->groupBy('quiz_id')
            ->map(fn($attempts) => $attempts->sortByDesc('score')->first());

        return view('trainee.quizzes.index', compact('quizzes', 'bestAttempts', 'courseType'));
    }

    /** Take a quiz — blocked once already passed. */
    public function show(Quiz $quiz)
    {
        $this->authorizeQuiz($quiz);

        $bestAttempt = QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->where('passed', true)
            ->first();

        if ($bestAttempt) {
            return redirect()->route('trainee.quizzes.result', $quiz)
                ->with('message', 'You have already passed this quiz.');
        }

        $quiz->load('questions.options');

        return view('trainee.quizzes.show', compact('quiz'));
    }

    /** Grade the submission and store the attempt. */
    public function submit(Request $request, Quiz $quiz)
    {
        $this->authorizeQuiz($quiz);

        $quiz->load('questions.options');

        $attempt = QuizAttempt::create([
            'quiz_id'      => $quiz->id,
            'user_id'      => auth()->id(),
            'started_at'   => now(),
            'completed_at' => now(),
        ]);

        $earnedPoints = 0;
        $totalPoints  = 0;

        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            $answerInput  = $request->input('answers.' . $question->id);

            if (in_array($question->type, ['multiple_choice', 'true_false'])) {
                $selectedOption = $question->options->firstWhere('id', (int) $answerInput);
                $isCorrect = (bool) ($selectedOption->is_correct ?? false);

                QuizAnswer::create([
                    'attempt_id'          => $attempt->id,
                    'question_id'         => $question->id,
                    'selected_option_id'  => $selectedOption->id ?? null,
                    'is_correct'          => $isCorrect,
                ]);
            } else {
                // Short-answer questions aren't machine-gradable here — any
                // non-empty response earns the points. Facilitators can
                // review the free-text response in the results view.
                $isCorrect = filled($answerInput);

                QuizAnswer::create([
                    'attempt_id'   => $attempt->id,
                    'question_id'  => $question->id,
                    'text_answer'  => $answerInput,
                    'is_correct'   => $isCorrect,
                ]);
            }

            if ($isCorrect) {
                $earnedPoints += $question->points;
            }
        }

        $score  = $totalPoints > 0 ? (int) round(($earnedPoints / $totalPoints) * 100) : 0;
        $passed = $score >= $quiz->pass_score;

        $attempt->update(['score' => $score, 'passed' => $passed]);

        if ($passed) {
            $trainee = auth()->user()->trainee;
            if ($trainee) {
                app(CourseCompletionService::class)->checkAndIssue($trainee);
            }
        }

        return redirect()->route('trainee.quizzes.result', $quiz)
            ->with('message', $passed ? 'Congratulations — you passed!' : 'You did not reach the pass mark. You can try again.');
    }

    /** The trainee's own latest attempt at this quiz. */
    public function result(Quiz $quiz)
    {
        $this->authorizeQuiz($quiz);

        $attempt = QuizAttempt::with('answers.question.options', 'answers.selectedOption')
            ->where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->firstOrFail();

        return view('trainee.quizzes.result', compact('quiz', 'attempt'));
    }

    /** A quiz is only visible to a trainee if it's published and matches their course. */
    private function authorizeQuiz(Quiz $quiz): void
    {
        $trainee    = auth()->user()->trainee;
        $courseType = $trainee->course_type ?? config('courses.default');

        abort_unless($quiz->is_published && $quiz->course_type === $courseType, 403);
    }
}
