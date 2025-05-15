<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'name',
        'duration',
        'shuffle_questions',
        'shuffle_options',
        'start_time', // Ditambahkan
        'is_active',  // Ditambahkan
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
            ->withPivot('order')
            ->orderBy('exam_questions.order');
    }
}