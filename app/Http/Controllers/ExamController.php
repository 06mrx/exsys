<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\Question;
use App\Models\User;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Routing\Controller; // Pastikan ini diimpor

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['admin'])->except(['start', 'submit', 'indexStudent', 'saveAnswer']);
    }

    public function index()
    {
        $exams = Exam::withCount('questions')->paginate(10);
        return view('exams.index', compact('exams'));
    }

    public function indexStudent()
    {
        $institutionId = Auth::user()->institution_id;
        $userId = Auth::id();

        $exams = Exam::where('institution_id', $institutionId)
            ->where('is_active', true)
            ->whereNotIn('id', function ($query) use ($userId) {
                $query->select('exam_id')
                    ->from('exam_results')
                    ->where('user_id', $userId)
                    ->groupBy('exam_id');
            })
            ->withCount('questions')
            ->paginate(10);

        return view('exams.index-student', compact('exams'));
    }


    public function create()
    {
        $questions = Question::where('is_quiz', false)->get();
        $institutions = Institution::all();
        return view('exams.create', compact('questions', 'institutions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'shuffle_questions' => 'nullable|in:on,off',
            'shuffle_options' => 'nullable|in:on,off',
            'start_time' => 'nullable|date',
            'is_active' => 'nullable|in:on,off',
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id',
        ]);

        $exam = Exam::create([
            'institution_id' => $request->institution_id,
            'name' => $request->name,
            'duration' => $request->duration,
            'shuffle_questions' => $request->shuffle_questions === 'on',
            'shuffle_options' => $request->shuffle_options === 'on',
            'start_time' => $request->start_time,
            'is_active' => $request->is_active === 'on',
        ]);

        foreach ($request->questions as $index => $questionId) {
            ExamQuestion::create([
                'exam_id' => $exam->id,
                'question_id' => $questionId,
                'order' => $index,
            ]);
        }

        return redirect()->route('exams.index')->with('success', 'Ujian berhasil dibuat.');
    }

    public function show(Exam $exam)
    {
        $questions = $exam->questions()->with('options')->get();
        return view('exams.show', compact('exam', 'questions'));
    }

    public function edit(Exam $exam)
    {
        $questions = Question::all();
        $selectedQuestions = $exam->examQuestions->pluck('question_id')->toArray();
        $institutions = Institution::all();
        return view('exams.edit', compact('exam', 'questions', 'selectedQuestions', 'institutions'));
    }

    public function update(Request $request, Exam $exam)
    {
        // dd($request->all());
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'shuffle_questions' => 'nullable|in:on,off',
            'shuffle_options' => 'nullable|in:on,off',
            'start_time' => 'nullable|date',
            'is_active' => 'nullable|in:on,off',
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id',
        ]);

        $exam->update([
            'institution_id' => $request->institution_id,
            'name' => $request->name,
            'duration' => $request->duration,
            'shuffle_questions' => $request->shuffle_questions === 'on',
            'shuffle_options' => $request->shuffle_options === 'on',
            'start_time' => $request->start_time,
            'is_active' => $request->is_active === 'on',
        ]);

        $exam->examQuestions()->delete();
        foreach ($request->questions as $index => $questionId) {
            ExamQuestion::create([
                'exam_id' => $exam->id,
                'question_id' => $questionId,
                'order' => $index,
            ]);
        }

        return redirect()->route('exams.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('exams.index')->with('success', 'Ujian berhasil dihapus.');
    }

    public function start(Exam $exam)
    {
        $currentTime = Carbon::now('Asia/Makassar');
        $hasTaken = ExamResult::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($hasTaken) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah mengikuti ujian ini.');
        }

        if (!$exam->is_active) {
            return redirect()->route('dashboard')->with('error', 'Ujian ini tidak aktif.');
        }

        if ($exam->start_time && $currentTime->lt($exam->start_time)) {
            $remainingTime = $exam->start_time->diffForHumans($currentTime, true);
            return redirect()->route('dashboard')->with('error', "Ujian belum dimulai. Silakan tunggu {$remainingTime}.");
        }

        $questions = $exam->questions()->with('options')->get();
        $userAnswers = session()->get('exam_answers_' . $exam->id, []); // Ambil jawaban dari session

        if ($exam->shuffle_questions) {
            $questions = $questions->shuffle();
        }

        if ($exam->shuffle_options) {
            $questions->each(function ($question) {
                $question->options = $question->options->shuffle();
            });
        }

        // Hitung waktu akhir berdasarkan durasi
        // dd(intval($exam->duration));
        $endTime = Carbon::now('Asia/Makassar')->addMinutes(intval($exam->duration));

        return view('exams.start', compact('exam', 'questions', 'userAnswers', 'endTime'));
    }

    public function saveAnswer(Request $request)
    {
        $examId = $request->input('exam_id');
        $questionId = $request->input('question_id');
        $optionId = $request->input('option_id');

        $userAnswers = session()->get('exam_answers_' . $examId, []);
        $userAnswers[$questionId] = $optionId;
        session()->put('exam_answers_' . $examId, $userAnswers);

        return response()->json(['success' => true]);
    }

    public function submit(Request $request, Exam $exam)
    {
        $currentTime = Carbon::now('Asia/Makassar');
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'exists:options,id',
        ]);

        $questions = $exam->questions()->with('options')->get();
        $score = 0;

        foreach ($request->answers as $questionId => $optionId) {
            $question = $questions->firstWhere('id', $questionId);
            $selectedOption = $question->options->firstWhere('id', $optionId);
            $isCorrect = $selectedOption && $selectedOption->is_correct;

            ExamResult::create([
                'exam_id' => $exam->id,
                'user_id' => Auth::id(),
                'question_id' => $questionId,
                'option_id' => $optionId,
                'is_correct' => $isCorrect,
                'completed_at' => $currentTime,
            ]);

            if ($isCorrect) {
                $score++;
            }
        }

        // Hapus session jawaban setelah submit
        session()->forget('exam_answers_' . $exam->id);

        $totalQuestions = $questions->count();
        $percentage = ($score / $totalQuestions) * 100;

        return redirect()->route('dashboard')->with('success', "Ujian selesai! Skor Anda: {$score}/{$totalQuestions} ({$percentage}%)");
    }

    public function results(Request $request)
    {
        $institutions = Institution::all();
        $exams = Exam::all();
        $institutionId = $request->input('institution_id');
        $examId = $request->input('exam_id');
        $search = $request->input('search');

        $query = ExamResult::with(['exam', 'user', 'question', 'option'])
            ->select('exam_id', 'user_id')
            ->groupBy('exam_id', 'user_id');

        if ($institutionId) {
            $query->whereHas('user', function ($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            });
        }

        if ($examId) {
            $query->where('exam_id', $examId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('exam', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        $results = $query->get()->map(function ($result) {
            $exam = $result->exam;
            $user = $result->user;
            $correctAnswers = ExamResult::where('exam_id', $exam->id)
                ->where('user_id', $user->id)
                ->where('is_correct', true)
                ->count();
            $totalQuestions = $exam->questions()->count();
            $percentage = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

            return [
                'exam_id' => $exam->id,
                'user_id' => $user->id,
                'exam_name' => $exam->name,
                'user_name' => $user->name,
                'score' => "{$correctAnswers}/{$totalQuestions}",
                'percentage' => number_format($percentage, 2) . '%',
                'completed_at' => $result->created_at,
            ];
        });

        return view('exams.results', compact('results', 'institutions', 'exams'));
    }

    public function resultDetail(Exam $exam, User $user)
    {
        $results = ExamResult::with(['question', 'option'])
            ->where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->get();

        return view('exams.result-detail', compact('exam', 'user', 'results'));
    }
}