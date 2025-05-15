<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['institution_id', 'name', 'duration', 'start_time', 'is_active'];

    protected $dates = ['start_time'];


    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')
                    ->withPivot('order')
                    ->orderBy('pivot_order');
    }
}