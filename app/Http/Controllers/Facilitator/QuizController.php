<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Quiz;
use App\QuizQuestion;
use App\QuizOption;
use App\QuizAttempt;
use App\Schedule;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['schedule', 'questions', 'attempts'])
            ->withCount(['questions', 'attempts'])
            ->latest()
            ->get()
            ->groupBy(fn($q) => $q->schedule_id ?? 'general');

        return view('facilitator.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $sessions = Schedule::discussable()->orderBy('day_number')->orderBy('start_time')->get();
        return view('facilitator.quizzes.form', compact('sessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'schedule_id' => 'nullable|exists:schedules,id',
            'time_limit'  => 'nullable|integer|min:1',
            'pass_score'  => 'nullable|integer|min:0|max:100',
        ]);

        $quiz = Quiz::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'schedule_id'  => $request->schedule_id ?: null,
            'created_by'   => auth()->id(),
            'time_limit'   => $request->time_limit,
            'pass_score'   => $request->pass_score ?? 60,
            'is_published' => $request->has('is_published') ? 1 : 0,
        ]);

        $this->syncQuestions($quiz, $request->input('questions', []));

        return redirect()->route('facilitator.quizzes.index')->with('message', 'Quiz created successfully.');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['schedule', 'questions.options', 'attempts.user', 'creator']);
        return view('facilitator.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load(['questions.options']);
        $sessions = Schedule::discussable()->orderBy('day_number')->orderBy('start_time')->get();
        return view('facilitator.quizzes.form', compact('quiz', 'sessions'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'schedule_id' => 'nullable|exists:schedules,id',
            'time_limit'  => 'nullable|integer|min:1',
            'pass_score'  => 'nullable|integer|min:0|max:100',
        ]);

        $quiz->update([
            'title'        => $request->title,
            'description'  => $request->description,
            'schedule_id'  => $request->schedule_id ?: null,
            'time_limit'   => $request->time_limit,
            'pass_score'   => $request->pass_score ?? 60,
            'is_published' => $request->has('is_published') ? 1 : 0,
        ]);

        // Delete old questions and recreate
        $quiz->questions()->each(function ($q) {
            $q->options()->delete();
            $q->delete();
        });

        $this->syncQuestions($quiz, $request->input('questions', []));

        return redirect()->route('facilitator.quizzes.index')->with('message', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('facilitator.quizzes.index')->with('message', 'Quiz deleted.');
    }

    public function results(Quiz $quiz)
    {
        $quiz->load(['questions.options']);
        $attempts = QuizAttempt::with(['user', 'answers.question', 'answers.selectedOption'])
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->get();

        return view('facilitator.quizzes.results', compact('quiz', 'attempts'));
    }

    public function togglePublish(Quiz $quiz)
    {
        $quiz->update(['is_published' => !$quiz->is_published]);
        $msg = $quiz->is_published ? 'Quiz published.' : 'Quiz unpublished.';
        return back()->with('message', $msg);
    }

    private function syncQuestions(Quiz $quiz, array $questions)
    {
        foreach ($questions as $idx => $qData) {
            if (empty($qData['text'])) continue;

            $question = QuizQuestion::create([
                'quiz_id'       => $quiz->id,
                'question_text' => $qData['text'],
                'type'          => $qData['type'] ?? 'multiple_choice',
                'points'        => $qData['points'] ?? 1,
                'sort_order'    => $idx,
            ]);

            if (in_array($question->type, ['multiple_choice', 'true_false'])) {
                foreach (($qData['options'] ?? []) as $oIdx => $oData) {
                    if (empty($oData['text'])) continue;
                    QuizOption::create([
                        'question_id' => $question->id,
                        'option_text' => $oData['text'],
                        'is_correct'  => isset($oData['is_correct']) && $oData['is_correct'] ? 1 : 0,
                        'sort_order'  => $oIdx,
                    ]);
                }
            }
        }
    }
}
