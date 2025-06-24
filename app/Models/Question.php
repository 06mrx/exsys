<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'institution_id', 'category', 'image_path', 'is_quiz', 'question_type'];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function correctOption()
    {
        return $this->hasOne(Option::class)->where('is_correct', true);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_questions');
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions')
            ->withPivot('order');
    }
}