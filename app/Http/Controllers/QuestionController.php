<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Question::query();

        if ($search = $request->input('search')) {
            $query->where('content', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($institution_id = $request->input('institution_id')) {
            $query->where('institution_id', $institution_id);
        }

        $questions = $query->with('institution', 'correctOption')->paginate(10);
        $institutions = Institution::all();
        $categories = Question::select('category')->distinct()->pluck('category')->filter();

        return view('questions.index', compact('questions', 'institutions', 'categories'));
    }

    public function create()
    {
        $institutions = Institution::all();
        $categories = Question::select('category')->distinct()->pluck('category')->filter();
        return view('questions.create', compact('institutions', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
            'institution_id' => 'nullable|exists:institutions,id',
            'options' => 'required|array|min:4|max:4',
            'is_quiz' => 'boolean',
            'options.*.content' => 'required|string',
            'correct_option' => 'required|in:0,1,2,3',
            'image' => 'nullable|image|max:2048', // Validasi gambar (max 2MB)
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/questions', 'public');
        }

        $question = Question::create([
            'content' => $request->content,
            'category' => $request->category,
            'institution_id' => $request->institution_id,
            'image_path' => $imagePath,
            'is_quiz' => $request->is_quiz ?? false,
        ]);

        foreach ($request->options as $index => $option) {
            Option::create([
                'question_id' => $question->id,
                'content' => $option['content'],
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        return redirect()->route('questions.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Question $question)
    {
        $institutions = Institution::all();
        $categories = Question::select('category')->distinct()->pluck('category')->filter();
        return view('questions.edit', compact('question', 'institutions', 'categories'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
            'institution_id' => 'nullable|exists:institutions,id',
            'options' => 'required|array|min:4|max:4',
            'options.*.content' => 'required|string',
            'correct_option' => 'required|in:0,1,2,3',
            'image' => 'nullable|image|max:2048', // Validasi gambar (max 2MB)
        ]);

        $imagePath = $question->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('uploads/questions', 'public');
        }

        $question->update([
            'content' => $request->content,
            'category' => $request->category,
            'institution_id' => $request->institution_id,
            'image_path' => $imagePath,
        ]);

        $question->options()->delete();
        foreach ($request->options as $index => $option) {
            Option::create([
                'question_id' => $question->id,
                'content' => $option['content'],
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        return redirect()->route('questions.index')->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Question $question)
    {
        if ($question->image_path) {
            Storage::disk('public')->delete($question->image_path);
        }
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Soal berhasil dihapus.');
    }
}