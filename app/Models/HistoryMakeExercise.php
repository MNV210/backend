<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Exercise;

class HistoryMakeExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'score',
        'total_question',
        'correct_answer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
