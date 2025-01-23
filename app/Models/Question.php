<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_text',
        'is_correct',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'question_score',
        'exercise_id'
    ];
    protected $table = 'questions';
}
