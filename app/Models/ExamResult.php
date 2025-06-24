<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'user_id', 'question_id', 'option_id', 'is_correct'];
    
    protected $casts = [
        'option_id' => 'array', // Mengonversi kolom option_id dari JSON ke array
    ];
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        // dd(88);
        return $this->belongsTo(Question::class);
    }

    // public function option()
    // {
    //     return $this->belongsTo(Option::class);
    // }

    public function getOption()
    {
        // dd($this->option_id);
        if (empty($this->option_id) || !is_array($this->option_id)) {
            // dd('Option IDs are not set or not an array.');
            return null;
        }
        // dd(111);
        // dd(Option::whereIn('id', $this->option_id)->get());
        return Option::whereIn('id', $this->option_id)->get();
    }
}