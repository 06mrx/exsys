<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\Question;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Routing\Controller;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['admin'])->except(['start', 'submit', 'indexStudent', 'saveAnswer']);
    }

    public function index()
    {
        $quizzes = Quiz::withCount('questions')->paginate(10);
        return view('quizzes.index', compact('quizzes'));
    }

    public function indexStudent()
    {
        $institutionId = Auth::user()->institution_id;
        $userId = Auth::id();

        $quizzes = Quiz::where('institution_id', $institutionId)
            ->where('is_active', true)
            ->whereNotIn('id', function ($query) use ($userId) {
                $query->select('quiz_id')
                    ->from('quiz_results')
                    ->where('user_id', $userId)
                    ->groupBy('quiz_id');
            })
            ->withCount('questions')
            ->paginate(10);

        return view('quizzes.index-student', compact('quizzes'));
    }


    public function create()
    {
        $questions = Question::where('is_quiz', true)->get();
        $institutions = Institution::all();
        return view('quizzes.create', compact('questions', 'institutions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'is_active' => 'nullable|in:on,off',
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id',
            'start_time' => 'required|date',
        ]);

        $quiz = Quiz::create([
            'institution_id' => $request->institution_id,
            'name' => $request->name,
            'duration' => $request->duration,
            'is_active' => $request->is_active === 'on',
            'start_time' => Carbon::parse($request->start_time, 'Asia/Makassar'),
        ]);

        foreach ($request->questions as $index => $questionId) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_id' => $questionId,
                'order' => $index,
            ]);
        }

        return redirect()->route('quizzes.index')->with('success', 'Quiz berhasil dibuat.');
    }

    public function show(Quiz $quiz)
    {
        $questions = $quiz->questions()->with('options')->get();
        return view('quizzes.show', compact('quiz', 'questions'));
    }

    public function edit(Quiz $quiz)
    {
        $questions = Question::all();
        $selectedQuestions = $quiz->quizQuestions->pluck('question_id')->toArray();
        $institutions = Institution::all();
        return view('quizzes.edit', compact('quiz', 'questions', 'selectedQuestions', 'institutions'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'is_active' => 'nullable|in:on,off',
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id',
            'start_time' => 'required|date',
        ]);

        $quiz->update([
            'institution_id' => $request->institution_id,
            'name' => $request->name,
            'duration' => $request->duration,
            'is_active' => $request->is_active === 'on',
            'start_time' => Carbon::parse($request->start_time, 'Asia/Makassar'),
        ]);

        $quiz->quizQuestions()->delete();
        foreach ($request->questions as $index => $questionId) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_id' => $questionId,
                'order' => $index,
            ]);
        }

        return redirect()->route('quizzes.index')->with('success', 'Quiz berhasil diperbarui.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz berhasil dihapus.');
    }



    public function saveAnswer(Request $request)
    {
        $quizId = $request->input('quiz_id');
        $questionId = $request->input('question_id');
        $optionId = $request->input('option_id');

        $userAnswers = session()->get('quiz_answers_' . $quizId, []);
        $userAnswers[$questionId] = $optionId;
        session()->put('quiz_answers_' . $quizId, $userAnswers);

        // Cek apakah jawaban benar
        $question = Question::find($questionId);
        $selectedOption = $question->options->firstWhere('id', $optionId);
        $isCorrect = $selectedOption && $selectedOption->is_correct;

        return response()->json(['success' => true, 'is_correct' => $isCorrect]);
    }

    public function start(Quiz $quiz)
    {
        $currentTime = Carbon::now('Asia/Makassar');
        $hasTaken = QuizResult::where('quiz_id', $quiz->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($hasTaken) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah mengikuti quiz ini.');
        }

        if (!$quiz->is_active) {
            return redirect()->route('dashboard')->with('error', 'Quiz ini tidak aktif.');
        }

        $questions = $quiz->questions()->with('options')->get();
        if ($questions->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada soal untuk quiz ini.');
        }

        $userAnswers = session()->get('quiz_answers_' . $quiz->id, []);
        $endTime = Carbon::now('Asia/Makassar')->addMinutes($quiz->duration);

        // Simpan waktu mulai di session
        session()->put('quiz_start_time_' . $quiz->id, $currentTime);

        return view('quizzes.start', compact('quiz', 'questions', 'userAnswers', 'endTime'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $currentTime = Carbon::now('Asia/Makassar');
        $startTime = session()->get('quiz_start_time_' . $quiz->id);
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'exists:options,id',
        ]);

        $questions = $quiz->questions()->with('options')->get();
        $score = 0;

        foreach ($request->answers as $questionId => $optionId) {
            $question = $questions->firstWhere('id', $questionId);
            $selectedOption = $question->options->firstWhere('id', $optionId);
            $isCorrect = $selectedOption && $selectedOption->is_correct;

            QuizResult::create([
                'quiz_id' => $quiz->id,
                'user_id' => Auth::id(),
                'question_id' => $questionId,
                'option_id' => $optionId,
                'is_correct' => $isCorrect,
                'completed_at' => $currentTime,
                'created_at' => $startTime, // Simpan waktu mulai
            ]);

            if ($isCorrect) {
                $score++;
            }
        }

        session()->forget('quiz_answers_' . $quiz->id);
        session()->forget('quiz_start_time_' . $quiz->id);

        $totalQuestions = $questions->count();
        $percentage = ($score / $totalQuestions) * 100;

        return redirect()->route('dashboard')->with('success', "Quiz selesai! Skor Anda: {$score}/{$totalQuestions} ({$percentage}%)");
    }

    public function results(Request $request)
    {
        $this->middleware('admin');
        $quizzes = Quiz::all();
        $institutions = Institution::all();

        $query = QuizResult::query()
            ->select(
                'quiz_results.user_id',
                'quiz_results.quiz_id',
                'quizzes.start_time',
                \DB::raw('MAX(quiz_results.completed_at) as completed_at'),
                \DB::raw('COUNT(quiz_results.id) as total_attempts'),
                \DB::raw('SUM(CASE WHEN quiz_results.is_correct = 1 THEN 1 ELSE 0 END) as correct_answers')
            )
            ->with(['quiz', 'user'])
            ->join('users', 'quiz_results.user_id', '=', 'users.id')
            ->join('quizzes', 'quiz_results.quiz_id', '=', 'quizzes.id')
            ->groupBy('quiz_results.user_id', 'quiz_results.quiz_id', 'quizzes.start_time');

        if ($request->filled('quiz_id')) {
            $query->where('quiz_results.quiz_id', $request->quiz_id);
        }

        if ($request->filled('institution_id')) {
            $query->where('users.institution_id', $request->institution_id);
        }

        $results = $query->get()
            ->map(function ($group) {
                $totalQuestions = QuizResult::where('user_id', $group->user_id)
                    ->where('quiz_id', $group->quiz_id)
                    ->count();
                $completionTime = $group->completed_at && $group->start_time
                    ? Carbon::parse($group->start_time, 'Asia/Makassar')->diffInSeconds(Carbon::parse($group->completed_at, 'Asia/Makassar'))
                    : 0;
                // $completionTime *= -1;
                // dump($completionTime);
                return [
                    'user' => $group->user,
                    'quiz' => $group->quiz,
                    'score' => $group->correct_answers,
                    'total_questions' => $totalQuestions,
                    'start_time' => $group->start_time,
                    'completion_time' => $completionTime,
                    'completed_at' => $group->completed_at,
                ];
            })
            ->sortBy([
                ['completion_time', 'asc'], // Urutkan berdasarkan durasi tercepat
                ['score', 'desc'],        // Urutkan berdasarkan skor tertinggi
            ])
            ->values();

        return view('quizzes.results', compact('results', 'quizzes', 'institutions'));
    }
}